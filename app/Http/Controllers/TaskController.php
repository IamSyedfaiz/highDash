<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $query = Task::with(['user', 'creator']);

            if (!$user->isAdmin() && !$user->hasRole('manager')) {
                $query->where('user_id', $user->id);
            } else {
                if ($request->user_id) {
                    $query->where('user_id', $request->user_id);
                }
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $tasks = $query->latest()->paginate(15)->withQueryString();
            $users = User::all();

            return view('tasks.index', compact('tasks', 'users'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to load tasks.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'url' => 'nullable|url',
                'user_id' => 'nullable|exists:users,id',
            ]);

            $task = Task::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'url' => $validated['url'],
                'user_id' => $validated['user_id'] ?? Auth::id(),
                'created_by' => Auth::id(),
                'status' => 'pending',
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'task_created',
                'description' => 'Created task: ' . $task->title,
                'model_type' => Task::class,
                'model_id' => $task->id,
            ]);

            return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create task.');
        }
    }

    public function show(Task $task)
    {
        try {
            $task->load(['user', 'creator']);
            $activities = ActivityLog::where('model_type', Task::class)
                ->where('model_id', $task->id)
                ->latest()
                ->get();
            return view('tasks.show', compact('task', 'activities'));
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to load task details.');
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,started,closed',
                'comment' => 'nullable|string|max:500',
            ]);

            $now = now('Asia/Kolkata');
            $data = ['status' => $validated['status']];

            if ($validated['status'] === 'started' && !$task->started_at) {
                $data['started_at'] = $now;
            }

            if ($validated['status'] === 'closed') {
                $data['completed_at'] = $now;
            }

            $task->update($data);

            $description = 'Updated task "' . $task->title . '" status to ' . strtoupper($validated['status']);
            if ($validated['comment']) {
                $description .= ". Progress Log: " . $validated['comment'];
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'task_status_updated',
                'description' => $description,
                'model_type' => Task::class,
                'model_id' => $task->id,
            ]);

            return redirect()->route('tasks.index')->with('success', 'Task status updated to ' . $validated['status']);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update task status.');
        }
    }

    public function destroy(Task $task)
    {
        try {
            if (!Auth::user()->isAdmin() && $task->created_by !== Auth::id()) {
                return back()->with('error', 'Unauthorized to delete this task.');
            }

            $task->delete();
            return back()->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete task.');
        }
    }
}
