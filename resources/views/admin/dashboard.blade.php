@extends('layouts.dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Admin Dashboard</h1>
        <p class="text-slate-500 dark:text-slate-400">System overview and performance analytics.</p>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Users</span>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ $totalUsers }}</div>
            <p class="text-xs text-slate-500">Active employees</p>
        </div>

        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Attendance</span>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ $todayAttendance }}</div>
            <p class="text-xs text-slate-500">Present today</p>
        </div>

        <div
            class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-2xl group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Leads</span>
            </div>
            <div class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ $totalLeads }}</div>
            <p class="text-xs text-slate-500">{{ $unassignedLeads }} unassignedpool</p>
        </div>

        <div class="bg-rose-600 p-6 rounded-3xl shadow-xl shadow-rose-500/20 text-white group cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-2xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.268 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest text-white/70">Dropped Leads</span>
            </div>
            <div class="text-3xl font-black mb-1">{{ $droppedLeads }}</div>
            <p class="text-xs text-white/70">Need review</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lead Results Chart -->
        <div
            class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Lead Pipeline Distribution</h3>
            <div class="h-[300px]">
                <canvas id="leadStatsChart"></canvas>
            </div>
        </div>

        <!-- Top Agents -->
        <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Top Calling Agents</h3>
            <div class="space-y-6">
                @foreach($topAgents as $agent)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <img class="h-10 w-10 rounded-xl"
                                src="https://ui-avatars.com/api/?name={{ urlencode($agent->name) }}&color=7F9CF5&background=EBF4FF"
                                alt="">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $agent->name }}</p>
                                <p class="text-xs text-slate-500">Conversions: {{ $agent->leads_count }}</p>
                            </div>
                        </div>
                        <div
                            class="text-xs font-bold text-emerald-500 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded-full">
                            +{{ rand(5, 15) }}%</div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.users.index') }}"
                class="block w-full text-center mt-8 py-3 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-sm font-bold rounded-xl hover:bg-slate-100 transition">View
                All Agents</a>
        </div>

        <!-- Recent System Logs -->
        <div
            class="lg:col-span-3 bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Live Activity Stream</h3>
            <div class="space-y-4">
                @foreach($recentActivities as $activity)
                    <div
                        class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl group hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-all">
                        <div class="flex items-center gap-4">
                            <div
                                class="h-10 w-10 rounded-xl bg-white dark:bg-slate-700 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-sm">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-slate-700 dark:text-slate-300"><span
                                        class="font-bold text-slate-900 dark:text-white">{{ $activity->user->name }}</span>
                                    {{ $activity->description }}</p>
                                <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest text-slate-400 bg-slate-200 dark:bg-slate-700 px-2 py-1 rounded-lg">{{ $activity->action }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('leadStatsChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($leadStats->pluck('status')) !!},
                datasets: [{
                    data: {!! json_encode($leadStats->pluck('count')) !!},
                    backgroundColor: ['#4f46e5', '#10b981', '#f43f5e', '#f59e0b', '#8b5cf6'],
                    hoverOffset: 20,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, weight: 'bold' },
                            color: localStorage.getItem('theme') === 'dark' ? '#fff' : '#000'
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
@endsection