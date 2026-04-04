@extends('layouts.dashboard')

@section('content')
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">
                Activity Report
            </h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Tracking output and efficiency across teams.</p>
        </div>
        <div class="flex gap-4">
            <form action="{{ route('performance.index') }}" method="GET" class="flex gap-2">
                <select name="month"
                    class="bg-white dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 shadow-sm font-bold p-3">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year"
                    class="bg-white dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 shadow-sm font-bold p-3">
                    @for ($y = 2024; $y <= 2026; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit"
                    class="bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    @if ($isAdmin)
        <div x-data="{ activeTab: 'inside' }" class="space-y-8">
            <!-- Team Selector Tabs -->
            <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl w-full max-w-xl">
                <button @click="activeTab = 'inside'"
                    :class="activeTab === 'inside' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' :
                        'text-slate-500 hover:text-slate-700'"
                    class="flex-1 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl transition-all">Inside
                    Sales</button>
                <button @click="activeTab = 'field'"
                    :class="activeTab === 'field' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' :
                        'text-slate-500 hover:text-slate-700'"
                    class="flex-1 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl transition-all">Field
                    Sales</button>
                <button @click="activeTab = 'technical'"
                    :class="activeTab === 'technical' ? 'bg-white dark:bg-slate-700 text-indigo-600 shadow-sm' :
                        'text-slate-500 hover:text-slate-700'"
                    class="flex-1 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl transition-all">Technical
                    Team</button>
            </div>

            <!-- Inside Sales Team Performance -->
            <div x-show="activeTab === 'inside'" class="space-y-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @forelse($insideSalesStats as $stat)
                        <div
                            class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl p-8 overflow-hidden hover:border-indigo-500 transition-all group">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="h-14 w-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xl shadow-inner group-hover:scale-110 transition-transform">
                                    {{ substr($stat['user']->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                        {{ $stat['user']->name }}</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $stat['total'] }} Total Leads Processed &nbsp;|&nbsp;
                                        <a href="{{ route('leads.index', ['assigned_to' => $stat['user']->id, 'untouched' => 1]) }}"
                                            class="bg-rose-100 text-rose-700 hover:bg-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 px-2 py-0.5 rounded-lg transition-colors cursor-pointer inline-flex items-center">{{ $stat['untouched'] }}
                                            Untouched</a>
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                        Pipeline
                                        Distribution</h4>
                                    <div class="space-y-3">
                                        @foreach ($stat['by_status'] as $status => $count)
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $status }}</span>
                                                <span
                                                    class="px-2.5 py-1 bg-slate-50 dark:bg-slate-800 rounded-lg text-xs font-black text-indigo-600 dark:text-indigo-400">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                        Engagement
                                        Status</h4>
                                    <div class="space-y-3">
                                        @foreach ($stat['by_prospect'] as $pStatus => $count)
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $pStatus == 'None' ? 'New Contact' : $pStatus }}</span>
                                                <span
                                                    class="px-2.5 py-1 {{ $pStatus == 'Order Won' ? 'bg-emerald-50 text-emerald-600' : ($pStatus == 'Order Lost' ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-600') }} dark:bg-slate-800 rounded-lg text-xs font-black">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @php
                                $uData = $stat['user'];
                                $kras = \App\Models\Kra::where('user_id', $uData->id)->get();
                                $target = $uData->sales_target_amount ?? 0;
                                $achieved = \App\Models\Lead::where('assigned_to', $uData->id)->sum('converted_amount');
                            @endphp

                            <!-- Admin KRA View -->
                            @if ($kras->count() > 0)
                                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Key
                                        Result Areas</h4>
                                    <div class="space-y-3">
                                        @foreach ($kras as $kra)
                                            <div
                                                class="flex items-center justify-between bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                                        {{ $kra->title }}</p>
                                                    <p class="text-[9px] text-slate-500 uppercase tracking-widest mt-0.5">
                                                        {{ $kra->description ?? 'No desc' }}</p>
                                                </div>
                                                <span
                                                    class="px-2 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 rounded text-[10px] font-black tracking-widest">{{ intval($kra->target_value) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Target Achievement view -->
                            @if ($target > 0)
                                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                        Monthly Target Overview</h4>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-indigo-50 dark:bg-indigo-900/10 p-4 rounded-xl">
                                            <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">
                                                Target</p>
                                            <p class="text-xl font-black text-indigo-900 dark:text-indigo-300">
                                                ₹{{ number_format($target) }}</p>
                                        </div>
                                        <div class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-xl">
                                            <p
                                                class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">
                                                Achieved</p>
                                            <p class="text-xl font-black text-emerald-900 dark:text-emerald-300">
                                                ₹{{ number_format($achieved) }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-end mb-2">
                                            <span
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Progress</span>
                                            <span
                                                class="text-xs font-black text-slate-900 dark:text-white">{{ min(round(($achieved / $target) * 100), 100) }}%</span>
                                        </div>
                                        <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-indigo-500 rounded-full"
                                                style="width: {{ min(($achieved / $target) * 100, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-slate-500">No data available for Inside Sales this
                            month.</div>
                    @endforelse
                </div>
            </div>

            <!-- Field Sales Team Performance -->
            <div x-show="activeTab === 'field'" class="space-y-8" style="display: none;">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @forelse($fieldSalesStats as $stat)
                        <div
                            class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl p-8 overflow-hidden hover:border-emerald-500 transition-all group">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="h-14 w-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black text-xl shadow-inner group-hover:scale-110 transition-transform">
                                    {{ substr($stat['user']->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                        {{ $stat['user']->name }}</h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                        {{ $stat['total'] }} Total Leads Processed &nbsp;|&nbsp;
                                        <a href="{{ route('leads.index', ['assigned_to' => $stat['user']->id, 'untouched' => 1]) }}"
                                            class="bg-rose-100 text-rose-700 hover:bg-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 px-2 py-0.5 rounded-lg transition-colors cursor-pointer inline-flex items-center">{{ $stat['untouched'] }}
                                            Untouched</a>
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                        Pipeline
                                        Distribution</h4>
                                    <div class="space-y-3">
                                        @foreach ($stat['by_status'] as $status => $count)
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $status }}</span>
                                                <span
                                                    class="px-2.5 py-1 bg-slate-50 dark:bg-slate-800 rounded-lg text-xs font-black text-emerald-600 dark:text-emerald-400">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                        Engagement
                                        Status</h4>
                                    <div class="space-y-3">
                                        @foreach ($stat['by_prospect'] as $pStatus => $count)
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ $pStatus == 'None' ? 'New Contact' : $pStatus }}</span>
                                                <span
                                                    class="px-2.5 py-1 {{ $pStatus == 'Order Won' ? 'bg-indigo-50 text-indigo-600' : ($pStatus == 'Order Lost' ? 'bg-rose-50 text-rose-600' : 'bg-slate-50 text-slate-600') }} dark:bg-slate-800 rounded-lg text-xs font-black">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @php
                                $uData = $stat['user'];
                                $kras = \App\Models\Kra::where('user_id', $uData->id)->get();
                                $target = $uData->sales_target_amount ?? 0;
                                $achieved = \App\Models\Lead::where('assigned_to', $uData->id)->sum('converted_amount');
                            @endphp

                            <!-- Admin KRA View -->
                            @if ($kras->count() > 0)
                                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Key
                                        Result Areas</h4>
                                    <div class="space-y-3">
                                        @foreach ($kras as $kra)
                                            <div
                                                class="flex items-center justify-between bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                                                <div>
                                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                                        {{ $kra->title }}</p>
                                                    <p class="text-[9px] text-slate-500 uppercase tracking-widest mt-0.5">
                                                        {{ $kra->description ?? 'No desc' }}</p>
                                                </div>
                                                <span
                                                    class="px-2 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 rounded text-[10px] font-black tracking-widest">{{ intval($kra->target_value) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Target Achievement view -->
                            @if ($target > 0)
                                <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">
                                        Monthly Target Overview</h4>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-indigo-50 dark:bg-indigo-900/10 p-4 rounded-xl">
                                            <p
                                                class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">
                                                Target</p>
                                            <p class="text-xl font-black text-indigo-900 dark:text-indigo-300">
                                                ₹{{ number_format($target) }}</p>
                                        </div>
                                        <div class="bg-emerald-50 dark:bg-emerald-900/10 p-4 rounded-xl">
                                            <p
                                                class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">
                                                Achieved</p>
                                            <p class="text-xl font-black text-emerald-900 dark:text-emerald-300">
                                                ₹{{ number_format($achieved) }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-end mb-2">
                                            <span
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Progress</span>
                                            <span
                                                class="text-xs font-black text-slate-900 dark:text-white">{{ min(round(($achieved / $target) * 100), 100) }}%</span>
                                        </div>
                                        <div
                                            class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full"
                                                style="width: {{ min(($achieved / $target) * 100, 100) }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-slate-500">No data available for Field Sales this
                            month.</div>
                    @endforelse
                </div>
            </div>

            <!-- Technical Team Performance -->
            <div x-show="activeTab === 'technical'" class="space-y-8" style="display: none;">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @forelse($techStats as $stat)
                        <div
                            class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl p-8 overflow-hidden hover:border-indigo-500 transition-all group">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-14 w-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black text-xl shadow-inner group-hover:scale-110 transition-transform">
                                        {{ substr($stat['user']->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3
                                            class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                            {{ $stat['user']->name }}</h3>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                            {{ $stat['total'] }}
                                            Tasks Managed</p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    @foreach (['pending' => 'bg-slate-100', 'started' => 'bg-indigo-100', 'closed' => 'bg-emerald-100'] as $key => $color)
                                        <div class="flex-1 p-4 rounded-2xl {{ $color }} dark:bg-slate-800/50">
                                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1">
                                                {{ $key }}
                                            </p>
                                            <p class="text-xl font-black text-slate-900 dark:text-white">
                                                {{ $stat['by_status'][$key] ?? 0 }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div>
                                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Daily
                                        Activity
                                        Grid</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($stat['daily'] as $date => $count)
                                            <div
                                                class="px-3 py-2 bg-slate-50 dark:bg-slate-800 rounded-xl text-center border border-slate-100 dark:border-slate-700">
                                                <p
                                                    class="text-[9px] font-black text-slate-400 uppercase leading-none mb-1">
                                                    {{ \Carbon\Carbon::parse($date)->format('D') }}</p>
                                                <p class="text-[10px] font-black text-indigo-600 leading-none mb-2">
                                                    {{ \Carbon\Carbon::parse($date)->format('d') }}</p>
                                                <p class="text-xs font-black text-slate-900 dark:text-white">
                                                    {{ $count }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-slate-500">No data available for Technical Team
                            this month.</div>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <!-- Individual User View -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            @if (Auth::user()->hasRole(['sales', 'inside_sales', 'field_sales']))
                @php $stat = $insideSalesStats[0] ?? (isset($fieldSalesStats[0]) ? $fieldSalesStats[0] : null); @endphp
                @if ($stat)
                    <div
                        class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 shadow-2xl border border-slate-100 dark:border-slate-800">
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8">Sales
                            Pipeline
                            Performance</h2>
                        <div class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="p-6 bg-indigo-50 dark:bg-indigo-900/20 rounded-[2rem]">
                                    <p class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-1">Total
                                        Leads</p>
                                    <p class="text-4xl font-black text-indigo-900 dark:text-indigo-300">
                                        {{ $stat['total'] }}</p>
                                </div>
                                <div class="p-6 bg-emerald-50 dark:bg-emerald-900/20 rounded-[2rem]">
                                    <p class="text-xs font-black text-emerald-500 uppercase tracking-widest mb-1">Success
                                        Rate</p>
                                    <p class="text-4xl font-black text-emerald-900 dark:text-emerald-300">
                                        {{ $stat['total'] > 0 ? round((($stat['by_prospect']['Order Won'] ?? 0) / $stat['total']) * 100) : 0 }}%
                                    </p>
                                </div>
                                <a href="{{ route('leads.index', ['assigned_to' => $stat['user']->id, 'untouched' => 1]) }}"
                                    class="block hover:scale-105 transition-transform">
                                    <div
                                        class="p-6 bg-rose-50 dark:bg-rose-900/20 rounded-[2rem] border border-rose-100 dark:border-rose-800 h-full flex flex-col justify-center">
                                        <p class="text-xs font-black text-rose-500 uppercase tracking-widest mb-1">
                                            Untouched Leads</p>
                                        <p class="text-4xl font-black text-rose-900 dark:text-rose-300">
                                            {{ $stat['untouched'] }}</p>
                                    </div>
                                </a>
                            </div>

                            <div class="space-y-4">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em]">Lifecycle
                                    Breakdown</h4>
                                @foreach (['Pending', 'Prospect', 'Approach', 'Negotiable', 'Order Won', 'Order Lost', 'Drop'] as $st)
                                    <div class="flex items-center gap-4">
                                        <span class="w-24 text-xs font-bold text-slate-500">{{ $st }}</span>
                                        <div
                                            class="flex-1 h-3 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                            @php $count = (in_array($st, ['Pending', 'New Lead', 'Existing', 'Drop']) ? $stat['by_status'][$st] ?? 0 : $stat['by_prospect'][$st] ?? 0); @endphp
                                            <div class="h-full bg-indigo-500 transition-all duration-1000"
                                                style="width: {{ $stat['total'] > 0 ? ($count / $stat['total']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs font-black text-slate-900 dark:text-white">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if (Auth::user()->hasRole('technical') && isset($techStats[0]))
                @php $stat = $techStats[0]; @endphp
                <div
                    class="bg-white dark:bg-slate-900 rounded-[3rem] p-10 shadow-2xl border border-slate-100 dark:border-slate-800">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8">Technical
                        Output
                        Metrics</h2>
                    <div class="space-y-8">
                        <div class="grid grid-cols-3 gap-4">
                            @foreach (['pending' => 'bg-slate-50', 'started' => 'bg-indigo-50', 'closed' => 'bg-emerald-50'] as $key => $color)
                                <div class="p-6 {{ $color }} dark:bg-slate-800 rounded-[2rem] text-center">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-500 mb-1">
                                        {{ $key }}</p>
                                    <p class="text-3xl font-black text-slate-900 dark:text-white">
                                        {{ $stat['by_status'][$key] ?? 0 }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        <div class="space-y-6">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em]">Daily Activity Volume
                            </h4>
                            <div class="grid grid-cols-7 gap-3">
                                @foreach ($stat['daily'] as $date => $count)
                                    <div
                                        class="aspect-square flex flex-col items-center justify-center p-2 rounded-2xl border-2 {{ $count > 5 ? 'border-emerald-500 bg-emerald-50' : 'border-slate-100 bg-white' }} dark:bg-slate-800 dark:border-slate-800">
                                        <span
                                            class="text-[8px] font-black text-slate-400 uppercase">{{ \Carbon\Carbon::parse($date)->format('D') }}</span>
                                        <span
                                            class="text-lg font-black text-slate-900 dark:text-white">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
    {{-- @endif --}}
@endsection
