@extends('layouts.dashboard')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">System Access Analytics</h1>
            <p class="text-slate-500 dark:text-slate-400">Comprehensive audit of user sessions and net work hours.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.export', request()->all()) }}"
                class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-black rounded-2xl shadow-xl shadow-emerald-500/20 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Audit Data
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div
        class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl mb-10">
        <form action="{{ route('admin.reports.index') }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Team Member</label>
                <select name="user_id"
                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                    <option value="">All Employees</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Range Start</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Range End</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 rounded-2xl shadow-lg shadow-indigo-500/20 transition-all uppercase tracking-widest text-xs">Filter Audit</button>
                <a href="{{ route('admin.reports.index') }}"
                    class="bg-slate-100 dark:bg-slate-800 text-slate-500 p-3 rounded-2xl hover:bg-slate-200 transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Log Table -->
    <div
        class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                <thead class="bg-slate-50/50 dark:bg-slate-800/30">
                    <tr>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Employee & Date</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Work Session Breakdown (IST)</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Duration</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($attendances as $record)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-indigo-900/5 transition-all">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-300 font-black shadow-sm">
                                        {{ substr($record->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                    <a href="{{ route('admin.reports.user.performance', $record->user) }}" class="group/user">
                                        <div class="text-sm font-black text-slate-900 dark:text-white group-hover/user:text-indigo-600 transition-colors">{{ $record->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">
                                            {{ \Carbon\Carbon::parse($record->date)->format('D, M d Y') }}
                                        </div>
                                    </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-2">
                                    @php $sessions = $record->loginSessions()->orderBy('login_at', 'asc')->get(); @endphp
                                    @foreach($sessions as $session)
                                        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-1.5 rounded-xl flex items-center gap-2 shadow-sm">
                                            <div class="flex flex-col">
                                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter leading-none">In</span>
                                                <span class="text-[10px] font-black text-slate-700 dark:text-slate-200">{{ $session->login_at?->format('h:i A') ?? '--' }}</span>
                                            </div>
                                            <div class="h-4 w-px bg-slate-200 dark:bg-slate-700"></div>
                                            <div class="flex flex-col">
                                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-tighter leading-none">Out</span>
                                                <span class="text-[10px] font-black {{ $session->logout_at ? 'text-slate-700 dark:text-slate-200' : 'text-emerald-500 animate-pulse font-extrabold' }}">
                                                    {{ $session->logout_at ? $session->logout_at->format('h:i A') : 'Active' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($sessions->isEmpty())
                                        <span class="text-[10px] text-slate-400 italic">No session data captured.</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="text-sm font-black text-slate-900 dark:text-white">
                                    {{ floor($record->work_duration_minutes / 60) }}h {{ $record->work_duration_minutes % 60 }}m
                                </div>
                                <div class="w-24 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full mt-2 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-700 rounded-full"
                                        style="width: {{ min(($record->work_duration_minutes / 480) * 100, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                @if($record->work_duration_minutes >= 480)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800 shadow-sm shadow-emerald-500/5">
                                        Full Day
                                    </span>
                                @elseif($record->work_duration_minutes >= 240)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-800 shadow-sm shadow-amber-500/5">
                                        Half Day
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-100 text-slate-800 shadow-sm">
                                        Short Leave
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400 font-extrabold uppercase tracking-widest text-sm">No audit records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())
            <div class="px-8 py-6 bg-slate-50/50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
                {{ $attendances->appends(request()->all())->links() }}
            </div>
        @endif
    </div>
@endsection