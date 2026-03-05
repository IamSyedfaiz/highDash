@extends('layouts.dashboard')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Caller Dashboard</h1>
        <p class="text-slate-500 dark:text-slate-400">Welcome back, {{ Auth::user()->name }}. Track your progress today.</p>
    </div>
    <div class="bg-indigo-600 px-6 py-4 rounded-3xl shadow-xl shadow-indigo-500/20 text-white flex items-center gap-4">
        <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-100">Live Status (IST)</p>
            @if($user->currentSession)
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-sm font-black text-white">Logged In since {{ $user->currentSession->login_at->format('h:i A') }}</span>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                    <span class="text-sm font-black text-indigo-200">System Offline</span>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">My Total Leads</p>
        <div class="text-4xl font-black text-slate-900 dark:text-white mb-1">{{ $assignedLeadsCount }}</div>
        <p class="text-xs text-slate-500">Global assignments</p>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Pending Calls</p>
        <div class="text-4xl font-black text-amber-500 mb-1">{{ $newLeadsCount }}</div>
        <div class="flex items-center gap-2 mt-2">
            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            <p class="text-xs text-slate-500 font-bold">Priority Follow-ups</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Lead Conversions</p>
        <div class="text-4xl font-black text-emerald-500 mb-1">{{ $convertedLeadsCount }}</div>
        <p class="text-xs text-slate-500 font-bold">Month Achievement</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Quick Access -->
    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Operations Center</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('leads.index', ['status' => 'New Lead']) }}" class="p-6 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-[2rem] transition-all border border-transparent hover:border-indigo-100 group">
                <div class="p-3 bg-white dark:bg-slate-700 rounded-2xl mb-4 shadow-sm w-fit text-indigo-600 transition-transform group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                </div>
                <p class="font-black text-slate-900 dark:text-white">Start Calling</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Access Lead Pool</p>
            </a>
            <a href="{{ route('attendance.index') }}" class="p-6 bg-slate-50 dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-[2rem] transition-all border border-transparent hover:border-emerald-100 group">
                <div class="p-3 bg-white dark:bg-slate-700 rounded-2xl mb-4 shadow-sm w-fit text-emerald-600 transition-transform group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <p class="font-black text-slate-900 dark:text-white">Attendance</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Logs & History</p>
            </a>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Work Intensity (Monthly)</h3>
        <div class="flex items-center justify-around">
            <div class="text-center">
                <div class="text-3xl font-black text-emerald-500">{{ $stats['present'] }}</div>
                <p class="text-[10px] font-black text-slate-400 border-t border-slate-100 pt-2 uppercase tracking-widest">Present</p>
            </div>
             <div class="text-center">
                <div class="text-3xl font-black text-rose-500">{{ $stats['absent'] }}</div>
                <p class="text-[10px] font-black text-slate-400 border-t border-slate-100 pt-2 uppercase tracking-widest">Absent</p>
            </div>
             <div class="text-center">
                <div class="text-3xl font-black text-amber-500">{{ $stats['leave'] }}</div>
                <p class="text-[10px] font-black text-slate-400 border-t border-slate-100 pt-2 uppercase tracking-widest">Leave</p>
            </div>
        </div>
        <div class="mt-12 p-6 bg-slate-50 dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-3 text-[10px] font-black uppercase tracking-widest">
                <span class="text-slate-500">Monthly Target Progress</span>
                <span class="text-indigo-600">65% Target</span>
            </div>
            <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-full" style="width: 65%"></div>
            </div>
            <p class="text-[10px] text-slate-400 mt-3 italic text-center">Maintain consistent hours to meet your performance KPIs.</p>
        </div>
    </div>
</div>
@endsection