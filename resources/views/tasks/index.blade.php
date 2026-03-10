@extends('layouts.dashboard')

@section('content')
    <div x-data="{ showModal: false }" class="space-y-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">Technical Task Ledger</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium">Daily organized technical implementation tasks.</p>
            </div>
            <button @click="showModal = true"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                New Entry
            </button>
        </div>

        @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6">
                <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Engineer</label>
                        <select name="user_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                            <option value="">All Engineers</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Status</label>
                        <select name="status" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-2.5 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                            <option value="">All States</option>
                            @foreach(['pending', 'started', 'closed'] as $s)
                                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ strtoupper($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Date</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-2 text-sm focus:ring-2 focus:ring-indigo-500 font-bold">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-slate-900 dark:bg-indigo-600 text-white rounded-xl py-2.5 text-[10px] font-black uppercase tracking-widest hover:opacity-90 transition-all">Filter</button>
                        <a href="{{ route('tasks.index') }}" class="bg-slate-100 dark:bg-slate-800 text-slate-500 p-2.5 rounded-xl hover:bg-slate-200 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </a>
                    </div>
                </form>
            </div>
        @endif

        <div class="space-y-10">
            @php $groupedTasks = $tasks->groupBy(fn($t) => $t->task_date->format('Y-m-d')); @endphp
            @forelse($groupedTasks as $date => $dayTasks)
                <div class="bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden shadow-indigo-500/5">
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-4 flex items-center justify-between border-b border-slate-200 dark:border-slate-800">
                        <div class="flex items-center gap-4">
                            <div class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-xs font-black uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($date)->format('l') }}
                            </div>
                            <span class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                            </span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            {{ count($dayTasks) }} Tasks
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                            <thead class="bg-slate-50/50 dark:bg-slate-900/50">
                                <tr>
                                    <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Engineer</th>
                                    <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Task Details</th>
                                    <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Operations</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                                @foreach($dayTasks as $task)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-[10px] font-black text-indigo-600 dark:text-indigo-400">
                                                    {{ substr($task->user->name, 0, 1) }}
                                                </div>
                                                <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $task->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-5 min-w-[300px]">
                                            <div class="mb-1 flex items-center gap-2">
                                                <span class="text-sm font-black text-slate-900 dark:text-white">{{ $task->title }}</span>
                                                @if($task->url)
                                                    <a href="{{ $task->url }}" target="_blank" class="text-indigo-500 hover:text-indigo-700 transition-all">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                                    </a>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-500 line-clamp-1 font-medium">{{ $task->description ?? 'No description.' }}</p>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                                {{ $task->status === 'closed' ? 'bg-emerald-100 text-emerald-800' :
                                                   ($task->status === 'started' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800') }}">
                                                {{ $task->status }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 whitespace-nowrap text-right">
                                            <div class="flex justify-end gap-2">
                                                @if($task->status !== 'started' && $task->status !== 'closed')
                                                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="started">
                                                        <button type="submit" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Start Task">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($task->status !== 'closed')
                                                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="closed">
                                                        <button type="submit" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Close Task">
                                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('tasks.show', $task) }}" class="p-2 bg-slate-50 text-slate-500 rounded-lg hover:bg-slate-200 transition-all shadow-sm" title="View Logs">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-100 dark:border-slate-800">
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No tasks logged for the selected period.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $tasks->links() }}
        </div>

        <!-- Create Task Modal -->
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div @click="showModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
                    <div class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('tasks.store') }}" method="POST" class="p-10">
                            @csrf
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-8 uppercase tracking-tight">New Task Entry</h3>
                            <div class="space-y-6">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Title</label>
                                    <input type="text" name="title" required placeholder="Main task objective..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 font-bold">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Task Date</label>
                                    <input type="date" name="task_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 font-bold">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                                    <textarea name="description" rows="3" placeholder="Context or specifics..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 font-bold"></textarea>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reference URL</label>
                                    <input type="url" name="url" placeholder="https://..." class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 font-bold">
                                </div>
                                @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assignee</label>
                                    <select name="user_id" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 font-bold transition-all">
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ $u->id == Auth::id() ? 'selected' : '' }}>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                            <div class="mt-8 flex justify-end gap-3">
                                <button type="button" @click="showModal = false" class="px-6 py-3 text-xs font-black text-slate-500 uppercase tracking-widest rounded-xl hover:bg-slate-100 transition-all">Cancel</button>
                                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-500/20 uppercase tracking-widest hover:bg-indigo-700 transition-all">Commit Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="mt-10">
        {{ $tasks->links() }}
    </div>
@endsection