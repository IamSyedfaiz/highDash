<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $today = Attendance::where('user_id', Auth::id())
            ->where('date', Carbon::today())
            ->first();

        return view('attendance.index', compact('today'));
    }

    public function history()
    {
        $user = Auth::user();
        $history = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('attendance.history', compact('history'));
    }
}
