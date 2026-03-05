@extends('layouts.dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Caller Dashboard</h1>
        <p class="text-slate-500 dark:text-slate-400">Welcome back, {{ Auth::user()->name }}. Track your progress today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-indigo-600 p-8 rounded-3xl shadow-xl shadow-indigo-500/20 text-white relative overflow-hidden group">
            <svg class="absolute -right-6 -bottom-6 h-32 w-32 text-white/10 transform rotate-12 transition-transform group-hover:rotate-0"
                fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
            </svg>
            <p class="text-xs font-bold uppercase tracking-widest text-indigo-100 mb-2">My Total Leads</p>
            <div class="text-4xl font-black mb-1">{{ $assignedLeadsCount }}</div>
            <p class="text-xs text-indigo-200">Total assigned to you</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Pending Calls</p>
            <div class="text-4xl font-black text-slate-900 dark:text-white mb-1">{{ $newLeadsCount }}</div>
            <div class="flex items-center gap-2 mt-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <p class="text-xs text-slate-500">Need attention</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Converted</p>
            <div class="text-4xl font-black text-slate-900 dark:text-white mb-1">{{ $convertedLeadsCount }}</div>
            <p class="text-xs text-emerald-500 font-bold">Great job!</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Quick Access -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Quick Links</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('leads.index', ['status' => 'New Lead']) }}"
                    class="p-6 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-2xl transition-all border border-transparent hover:border-indigo-100 group">
                    <div class="p-3 bg-white dark:bg-slate-700 rounded-xl mb-4 shadow-sm w-fit text-indigo-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <p class="font-bold text-slate-900 dark:text-white">Start Calling</p>
                    <p class="text-xs text-slate-500">View new leads</p>
                </a>
                <a href="{{ route('attendance.index') }}"
                    class="p-6 bg-slate-50 dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-2xl transition-all border border-transparent hover:border-emerald-100 group">
                    <div class="p-3 bg-white dark:bg-slate-700 rounded-xl mb-4 shadow-sm w-fit text-emerald-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="font-bold text-slate-900 dark:text-white">Attendance</p>
                    <p class="text-xs text-slate-500">View history</p>
                </a>
            </div>
        </div>

        <!-- Attendance Card -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Attendance Overview (IST)</h3>
            <div class="flex items-center justify-around">
                <div class="text-center">
                    <div class="text-2xl font-black text-emerald-500">{{ $stats['present'] }}</div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Present</p>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-rose-500">{{ $stats['absent'] }}</div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Absent</p>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-black text-amber-500">{{ $stats['leave'] }}</div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Leave</p>
                </div>
            </div>
            <div class="mt-10 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500 uppercase">Work Hours Progress</span>
                    <span class="text-xs font-bold text-indigo-600">65% Target</span>
                </div>
                <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-600 rounded-full" style="width: 65%"></div>
                </div>
            </div>
        </div>
    </div>
@endsection