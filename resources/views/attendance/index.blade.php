@extends('layouts.dashboard')

@section('content')
    <div x-data="{ tab: 'overview' }" class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Attendance Workspace</h1>
                <p class="text-slate-500 dark:text-slate-400">Manage your work hours and monthly presence.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('attendance.export', ['type' => 'current']) }}"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-black rounded-xl shadow-lg shadow-emerald-500/20 transition-all uppercase tracking-widest text-[10px]">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Monthly Total
                </a>
                <a href="{{ route('attendance.export', ['type' => 'current', 'mode' => 'detailed']) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black rounded-xl shadow-lg shadow-indigo-500/20 transition-all uppercase tracking-widest text-[10px]">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Detailed Entry Log
                </a>
                <a href="{{ route('attendance.export', ['type' => 'last']) }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-800 dark:bg-slate-700 text-white text-[10px] font-black rounded-xl shadow-lg transition-all uppercase tracking-widest">
                    Last Month
                </a>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div
            class="flex gap-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl w-fit border border-slate-200 dark:border-slate-700">
            <button @click="tab = 'overview'"
                :class="tab === 'overview' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all">Overview</button>
            <button @click="tab = 'calendar'"
                :class="tab === 'calendar' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all">Sessions History</button>
            <button @click="tab = 'leaves'"
                :class="tab === 'leaves' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-6 py-2 rounded-xl text-sm font-bold transition-all">
                {{ (Auth::user()->isAdmin() || Auth::user()->hasRole('manager')) ? 'Leaves Management' : 'My Leaves' }}
            </button>
        </div>

        <!-- Tab Contents -->
        <div x-show="tab === 'overview'" x-transition:enter="duration-300 transition-opacity" class="space-y-8">
            <!-- Today's Status Card -->
            <div
                class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden group">
                <svg class="absolute -right-10 -top-10 h-64 w-64 text-white/10 transform -rotate-12" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest mb-1">Today's Presence</p>
                        <h3 class="text-4xl font-black mb-2">{{ now('Asia/Kolkata')->format('M d, Y') }}</h3>
                        <p class="text-indigo-100 flex items-center justify-center md:justify-start gap-2">
                            @php $currentSess = Auth::user()->currentSession; @endphp
                            @if($currentSess)
                                <span class="h-3 w-3 rounded-full bg-emerald-400 animate-pulse border-2 border-white/20"></span>
                                Status: <span class="font-black">Active In System</span>
                            @else
                                <span class="h-3 w-3 rounded-full bg-slate-400 border-2 border-white/20"></span>
                                Status: <span class="font-bold opacity-75">Work Session Ended</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex gap-10">
                        <div class="text-center">
                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-2">First Login</p>
                            <p class="text-3xl font-black">
                                {{ $today && $today->login_at ? $today->login_at->format('h:i A') : '--:--' }}</p>
                            <p class="text-[10px] text-indigo-300 mt-1 uppercase font-bold tracking-tighter">Start of work
                            </p>
                        </div>
                        <div class="h-12 w-px bg-white/20 self-center"></div>
                        <div class="text-center">
                            <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-2">Last Activity
                            </p>
                            <p class="text-3xl font-black">
                                {{ $today && $today->logout_at ? $today->logout_at->format('h:i A') : '--:--' }}</p>
                            <p class="text-[10px] text-indigo-300 mt-1 uppercase font-bold tracking-tighter">
                                {{ $currentSess ? 'Active Now' : 'End of day' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div
                    class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Login Intensity</p>
                    <div class="text-4xl font-black text-slate-900 dark:text-white mb-2">{{ floor($totalMinutes / 60) }}h
                        {{ $totalMinutes % 60 }}m</div>
                    <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full" style="width: 70%"></div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Full Work Days</p>
                    <div class="text-4xl font-black text-emerald-500">{{ $fullDays }}</div>
                    <p class="text-[10px] text-slate-500 font-bold mt-1 tracking-tighter uppercase">Approved (>8H)</p>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Half Work Days</p>
                    <div class="text-4xl font-black text-amber-500">{{ $halfDays }}</div>
                    <p class="text-[10px] text-slate-500 font-bold mt-1 tracking-tighter uppercase">Standard (4H-8H)</p>
                </div>
                <div
                    class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Approved Vacations</p>
                    <div class="text-4xl font-black text-purple-500">{{ $leaves->where('status', 'approved')->count() }}
                    </div>
                    <p class="text-[10px] text-slate-500 font-bold mt-1 tracking-tighter uppercase">This Month</p>
                </div>
            </div>
        </div>

        <!-- Sessions History Tab -->
        <div x-show="tab === 'calendar'" x-cloak x-transition:enter="duration-300 transition-opacity">
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
                <div
                    class="px-10 py-6 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="font-black text-slate-900 dark:text-white uppercase tracking-wider">Historical Presence Log -
                        {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h3>
                    <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Calculated by
                        sessions</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-50 dark:divide-slate-800">
                        <thead class="bg-slate-50/30 dark:bg-slate-800/20">
                            <tr>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Calendar Date</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Login & Logout Points (IST)</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Net Work Hours</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                            @forelse($attendances as $att)
                                <tr class="group hover:bg-slate-50 dark:hover:bg-indigo-900/5 transition-all">
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        <div class="text-sm font-black text-slate-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($att->date)->format('D, M d') }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold">Ref: #{{ 5000 + $att->id }}</div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex flex-wrap gap-3">
                                            @php $dailySessions = $att->loginSessions()->orderBy('login_at', 'asc')->get(); @endphp
                                            @foreach($dailySessions as $session)
                                                <div
                                                    class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-4 py-2 rounded-[1.25rem] flex items-center gap-4 shadow-sm group-hover:border-indigo-200 transition-all">
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Login</span>
                                                        <span
                                                            class="text-xs font-black text-slate-700 dark:text-slate-200">{{ $session->login_at->format('h:i A') }}</span>
                                                    </div>
                                                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
                                                    <div class="flex flex-col">
                                                        <span
                                                            class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Logout</span>
                                                        <span
                                                            class="text-xs font-black {{ $session->logout_at ? 'text-slate-700 dark:text-slate-200' : 'text-emerald-500' }}">
                                                            {{ $session->logout_at ? $session->logout_at->format('h:i A') : 'Active' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        @php $h = floor($att->work_duration_minutes / 60);
                                        $m = $att->work_duration_minutes % 60; @endphp
                                        <div class="flex flex-col">
                                            <span class="text-base font-black text-slate-900 dark:text-white">
                                                {{ $h }}h {{ $m }}m
                                            </span>
                                            <div
                                                class="w-20 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full mt-2 overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-full"
                                                    style="width: {{ min(($att->work_duration_minutes / 480) * 100, 100) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6 whitespace-nowrap">
                                        @if($att->work_duration_minutes >= 480)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Full Day
                                            </span>
                                        @elseif($att->work_duration_minutes >= 240)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Half Day
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                Regular
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-10 py-20 text-center text-slate-400 italic font-medium">No system
                                        access records documented for this month.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Leaves Tab -->
        <div x-show="tab === 'leaves'" x-cloak x-transition:enter="duration-300 transition-opacity" class="space-y-8">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Time Off Log</h3>
                <a href="{{ route('leaves.create') }}"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                    File New Request
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($leaves as $leave)
                    <div
                        class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm group hover:shadow-2xl transition-all">
                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-14 w-14 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform shadow-sm">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $leave->type }}</p>
                                    <p class="text-xs text-slate-500 font-bold uppercase tracking-tighter">
                                        {{ $leave->from_date->format('M d') }} — {{ $leave->to_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <span
                                class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest
                                {{ $leave->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($leave->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $leave->status }}
                            </span>
                        </div>
                        <div
                            class="bg-slate-50/50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-50 dark:border-slate-700">
                            <p class="text-sm text-slate-600 dark:text-slate-400 italic">"{{ $leave->reason }}"</p>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-2 py-32 text-center bg-slate-50 dark:bg-slate-800/20 rounded-[3rem] border-4 border-dashed border-slate-100 dark:border-slate-900">
                        <p class="text-slate-400 font-black uppercase tracking-widest text-sm">No vacation records found for
                            this period.</p>
                    </div>
                @endforelse
            </div>
        </div>
        </div>
    </div>
@endsection