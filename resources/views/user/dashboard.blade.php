@extends('layouts.dashboard')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">
                {{ Auth::user()->hasRole('technical') ? 'Technical Suite' : 'Caller Dashboard' }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Welcome back, {{ Auth::user()->name }}. Track your progress today.
            </p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-[10px] font-black uppercase text-slate-400 tracking-[0.2em] mb-1">Session Active</p>
            <div class="flex items-center justify-end gap-2">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tighter">Monitoring
                    System</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    @if(Auth::user()->hasRole('technical'))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 text-center">
            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Queue Pool</p>
                <div class="text-4xl font-black text-slate-900 dark:text-white mb-1">{{ $taskStats['pending'] }}</div>
                <p class="text-[8px] text-slate-400 font-black uppercase">Awaiting Action</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all border-b-4 border-b-indigo-500">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Live Dev</p>
                <div class="text-4xl font-black text-indigo-500 mb-1">{{ $taskStats['started'] }}</div>
                <p class="text-[8px] text-slate-400 font-black uppercase italic">In implementation</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all border-b-4 border-b-emerald-500">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Production</p>
                <div class="text-4xl font-black text-emerald-500 mb-1">{{ $taskStats['closed'] }}</div>
                <p class="text-[8px] text-slate-400 font-black uppercase font-black uppercase">Shipped</p>
            </div>
            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Buffer Total</p>
                <div class="text-4xl font-black text-slate-500 mb-1">{{ $taskStats['total'] }}</div>
                <p class="text-[8px] text-slate-400 font-black uppercase">Global Record</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">My Total Leads</p>
                <div class="text-4xl font-black text-slate-900 dark:text-white mb-1">{{ $assignedLeadsCount }}</div>
                <p class="text-xs text-slate-500">Global assignments</p>
            </div>

            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Pending Calls</p>
                <div class="text-4xl font-black text-amber-500 mb-1">{{ $newLeadsCount }}</div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <p class="text-xs text-slate-500 font-bold">Priority Follow-ups</p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-xl transition-all">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Lead Conversions</p>
                <div class="text-4xl font-black text-emerald-500 mb-1">{{ $convertedLeadsCount }}</div>
                <p class="text-xs text-slate-500 font-bold">Month Achievement</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Quick Access -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Operations Center</h3>
            <div class="grid grid-cols-2 gap-4">
                @if(Auth::user()->hasRole('technical'))
                    <a href="{{ route('tasks.index') }}"
                        class="p-6 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-[2rem] transition-all border border-transparent hover:border-indigo-100 group">
                        <div
                            class="p-3 bg-white dark:bg-slate-700 rounded-2xl mb-4 shadow-sm w-fit text-indigo-600 transition-transform group-hover:scale-110">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <p class="font-black text-slate-900 dark:text-white">Process Queue</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Active Buffer</p>
                    </a>
                @else
                    <a href="{{ route('leads.index', ['status' => 'New Lead']) }}"
                        class="p-6 bg-slate-50 dark:bg-slate-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-[2rem] transition-all border border-transparent hover:border-indigo-100 group">
                        <div
                            class="p-3 bg-white dark:bg-slate-700 rounded-2xl mb-4 shadow-sm w-fit text-indigo-600 transition-transform group-hover:scale-110">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <p class="font-black text-slate-900 dark:text-white">Start Calling</p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Access Lead Pool</p>
                    </a>
                @endif
                <a href="{{ route('attendance.index') }}"
                    class="p-6 bg-slate-50 dark:bg-slate-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-[2rem] transition-all border border-transparent hover:border-emerald-100 group">
                    <div
                        class="p-3 bg-white dark:bg-slate-700 rounded-2xl mb-4 shadow-sm w-fit text-emerald-600 transition-transform group-hover:scale-110">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
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
                    <p
                        class="text-[10px] font-black text-slate-400 border-t border-slate-100 pt-2 uppercase tracking-widest">
                        Present</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-rose-500">{{ $stats['absent'] }}</div>
                    <p
                        class="text-[10px] font-black text-slate-400 border-t border-slate-100 pt-2 uppercase tracking-widest">
                        Absent</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-black text-amber-500">{{ $stats['leave'] }}</div>
                    <p
                        class="text-[10px] font-black text-slate-400 border-t border-slate-100 pt-2 uppercase tracking-widest">
                        Leave</p>
                </div>
            </div>
            <div
                class="mt-12 p-6 bg-slate-50 dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between mb-3 text-[10px] font-black uppercase tracking-widest">
                    <span class="text-slate-500">Monthly Target Progress</span>
                    <span class="text-indigo-600">65% Target</span>
                </div>
                <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-full" style="width: 65%">
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 mt-3 italic text-center">Maintain consistent hours to meet your
                    performance KPIs.</p>
            </div>
        </div>
    </div>

    @if(!Auth::user()->hasRole('technical'))
        <!-- Today's Follow-ups -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm mt-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white uppercase tracking-tight">Today's Scheduled
                        Follow-ups</h3>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-[0.2em] mt-1">Priority Pipeline Activities
                    </p>
                </div>
                <div
                    class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                    <span
                        class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $todayFollowUps->count() }}
                        DUETODAY</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($todayFollowUps as $followUp)
                    <div
                        class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-[2rem] border border-slate-100 dark:border-slate-800 group hover:border-indigo-300 transition-all">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="h-12 w-12 rounded-2xl bg-white dark:bg-slate-700 border border-slate-100 dark:border-slate-600 flex items-center justify-center text-indigo-600 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all transform group-hover:rotate-6">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-black text-slate-900 dark:text-white leading-tight truncate group-hover:text-indigo-600">
                                    {{ $followUp->lead->company_name }}</p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter truncate">
                                    {{ $followUp->lead->phone }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</span>
                                <span class="text-[9px] font-bold text-slate-700 dark:text-slate-300">{{ $followUp->status }}</span>
                            </div>
                            <a href="{{ route('leads.show', $followUp->lead) }}"
                                class="inline-flex items-center px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black rounded-xl uppercase tracking-widest hover:scale-105 transition-all">View
                                Details</a>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-16 text-center bg-slate-50/50 dark:bg-slate-800/30 rounded-[2.5rem] border border-dashed border-slate-200 dark:border-slate-800">
                        <div
                            class="h-16 w-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Clear Pipeline for Today</p>
                        <p class="text-[9px] text-slate-500 font-bold mt-1">Excellent work! No missing follow-ups.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
@endsection