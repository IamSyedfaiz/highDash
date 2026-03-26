@extends('layouts.dashboard')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-6">
            <div class="h-20 w-20 rounded-[2rem] bg-indigo-600 flex items-center justify-center text-white text-3xl font-black shadow-2xl shadow-indigo-500/40">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-2">{{ $user->name }}</h1>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full text-[10px] font-black uppercase tracking-widest text-slate-500">{{ $user->roles->first()->name ?? 'Specialist' }}</span>
                    <span class="h-1.5 w-1.5 rounded-full bg-slate-300"></span>
                    <p class="text-slate-400 font-bold text-sm">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('admin.reports.user.performance', $user) }}" method="GET" class="flex gap-2">
                <select name="month" class="bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold shadow-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ ($month ?? now()->month) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="p-3 bg-slate-900 dark:bg-indigo-600 text-white rounded-xl hover:opacity-90 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Work Capacity</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                {{ floor($attendances->sum('work_duration_minutes') / 60) }}h {{ $attendances->sum('work_duration_minutes') % 60 }}m
            </h3>
        </div>
        @if($user->hasRole(['sales', 'inside_sales', 'field_sales']))
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl border-l-4 border-l-emerald-500">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Leads Captured</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">{{ $leads->count() }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl border-l-4 border-l-indigo-500">
            <p class="text-[10px) font-black text-slate-400 uppercase tracking-widest mb-2">Conversion Rate</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                {{ $leads->count() > 0 ? round(($leads->where('status', 'Existing')->count() / $leads->count()) * 100, 1) : 0 }}%
            </h3>
        </div>
        @else
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl border-l-4 border-l-indigo-500">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tasks Completed</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">{{ $tasks->where('status', 'closed')->count() }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl border-l-4 border-l-amber-500">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Efficiency Index</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                {{ $tasks->count() > 0 ? round(($tasks->where('status', 'closed')->count() / $tasks->count()) * 100) : 0 }}%
            </h3>
        </div>
        @endif
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Days Present</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">{{ $attendances->count() }}</h3>
        </div>
    </div>

    <!-- Chart & Logs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Work Distribution Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-10 rounded-[3rem] border border-slate-200 dark:border-slate-800 shadow-xl">
            <div class="flex justify-between items-center mb-10">
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Work Efficiency Chart</h3>
                <div class="flex gap-4">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-indigo-500"></div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Minutes Worked</span>
                    </div>
                </div>
            </div>
            <div class="h-80">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
            <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8">Live Feed</h3>
            <div class="space-y-6 relative before:absolute before:left-2 before:top-2 before:bottom-2 before:w-px before:bg-slate-100 dark:before:bg-slate-800">
                @php $logs = $user->activityLogs()->latest()->take(6)->get(); @endphp
                @forelse($logs as $log)
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-1.5 h-4 w-4 rounded-full bg-white dark:bg-slate-900 border-4 border-indigo-500"></div>
                        <p class="text-xs font-black text-slate-900 dark:text-white mb-0.5">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</p>
                        <p class="text-xs text-slate-500 font-medium mb-1 line-clamp-1">{{ $log->description }}</p>
                        <p class="text-[8px] text-slate-400 font-black uppercase tracking-widest">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-slate-400 italic text-sm py-10 text-center uppercase font-black tracking-widest">Silence in the logs</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Detailed List (Leads or Tasks) -->
    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
        <div class="p-10 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/30">
            <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">
                {{ $user->hasRole(['sales', 'inside_sales', 'field_sales']) ? 'Lead Pipeline Record' : 'Technical Task History' }}
            </h3>
            <div class="flex gap-2">
                @if($user->hasRole(['sales', 'inside_sales', 'field_sales']))
                    <a href="{{ route('admin.reports.export.leads', ['user_id' => $user->id]) }}" class="px-6 py-3 bg-emerald-600 text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:scale-105 transition-all">Export Leads</a>
                @else
                    <a href="{{ route('admin.reports.export.tasks', ['user_id' => $user->id]) }}" class="px-6 py-3 bg-indigo-600 text-white text-[10px] font-black rounded-xl uppercase tracking-widest shadow-lg shadow-indigo-500/20 hover:scale-105 transition-all">Export Tasks</a>
                @endif
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50">
                    <tr>
                        @if($user->hasRole(['sales', 'inside_sales', 'field_sales']))
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Company</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        @else
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Task Title</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Progress</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Completed</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @if($user->hasRole(['sales', 'inside_sales', 'field_sales']))
                        @foreach($leads as $lead)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-indigo-900/5 transition-colors">
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900 dark:text-white leading-tight">{{ $lead->company_name }}</p>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-tighter">{{ $lead->contact_name }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $lead->status === 'New Lead' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $lead->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-xs text-slate-400 font-bold">{{ $lead->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    @else
                        @foreach($tasks as $task)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-indigo-900/5 transition-colors">
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900 dark:text-white leading-tight">{{ $task->title }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">Ref: {{ $task->url ?? 'None' }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $task->status === 'closed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ strtoupper($task->status) }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-xs text-slate-400 font-bold">{{ $task->completed_at ? $task->completed_at->format('M d, Y') : '---' }}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            
            // Generate labels and data based on monthly attendance
            @php
                $labels = [];
                $workData = [];
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                for($i=1; $i<=$daysInMonth; $i++) {
                    $dateStr = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $att = $attendances->where('date', $dateStr)->first();
                    $labels[] = date('d M', strtotime($dateStr));
                    $workData[] = $att ? $att->work_duration_minutes : 0;
                }
            @endphp

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Minutes Worked',
                        data: {!! json_encode($workData) !!},
                        borderColor: '#6366f1',
                        backgroundColor: (context) => {
                            const chart = context.chart;
                            const {ctx, chartArea} = chart;
                            if (!chartArea) return null;
                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                            gradient.addColorStop(0, 'rgba(99, 102, 241, 0)');
                            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.2)');
                            return gradient;
                        },
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366f1',
                        pointRadius: 4,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Outfit', size: 10, weight: '900' },
                            bodyFont: { family: 'Outfit', size: 12, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    const h = Math.floor(context.parsed.y / 60);
                                    const m = context.parsed.y % 60;
                                    return h + 'h ' + m + 'm logged';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.02)', drawBorder: false },
                            ticks: { 
                                color: '#94a3b8', 
                                font: { size: 10, weight: '900' },
                                callback: value => value + 'm'
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 9, weight: '900' } }
                        }
                    }
                }
            });
        });
    </script>
@endsection
