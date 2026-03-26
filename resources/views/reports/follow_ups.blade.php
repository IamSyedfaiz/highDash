@extends('layouts.dashboard')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">Follow Ups
                Analytics</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Tracking all scheduled follow-ups by user and status.
            </p>
        </div>
    </div>

    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($groupedFollowUps as $userId => $userFollowUps)
                @php
                    $assignedUser = $userFollowUps->first()->lead->assignedUser ?? null;
                    $userName = $assignedUser ? $assignedUser->name : 'Unassigned Leads';
                    $hasRole = $assignedUser ? $assignedUser->roles->pluck('name')->join(', ') : 'None';
                    $initial = substr($userName, 0, 1);
                    $totalCount = $userFollowUps->count();

                    // Group by status
                    $statusCounts = $userFollowUps->groupBy('status')->map->count();
                @endphp
                <div
                    class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl p-8 overflow-hidden hover:border-indigo-500 transition-all group flex flex-col h-full">
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="h-14 w-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xl shadow-inner group-hover:scale-110 transition-transform flex-shrink-0">
                            {{ $initial }}
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight leading-tight">
                                {{ $userName }}</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $totalCount }} Upcoming Follow-Ups</p>
                        </div>
                    </div>

                    <div class="flex-1 mt-6 border-t border-slate-100 dark:border-slate-800 pt-6">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Follow Up Breakdown</h4>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($statusCounts as $status => $count)
                                <a href="{{ route('leads.index', ['assigned_to' => $assignedUser->id ?? 'none', 'follow_up_status' => $status]) }}" 
                                   class="flex items-center justify-between p-2 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-lg transition overflow-hidden group/link">
                                    <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400 group-hover/link:text-indigo-600 dark:group-hover/link:text-indigo-400 block truncate" title="{{ $status }}">{{ $status }}</span>
                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 group-hover/link:bg-indigo-100 dark:group-hover/link:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 rounded text-[10px] font-black shadow-sm transition-colors ml-2">
                                        {{ $count }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="max-w-xs mx-auto text-slate-400">
                        <svg class="h-12 w-12 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="font-bold text-sm uppercase tracking-widest mb-1">No Upcoming Follow-Ups</p>
                        <p class="text-[10px]">Your schedule is clear right now. Any active leads with set call-back times
                            will appear here.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection