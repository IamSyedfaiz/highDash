@extends('layouts.dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">
            {{ __('Welcome back, ') }} {{ auth()->user()->name }}
        </h1>
        <p class="text-slate-500 mt-1">Here is your performance overview and dashboard.</p>
    </div>

    @php
        $user = auth()->user();
        $achieved = \App\Models\Lead::where('assigned_to', $user->id)->sum('converted_amount');
        $target = $user->sales_target_amount ?? 0;
        $balance = $target - $achieved;
        $kras = \App\Models\Kra::where('user_id', $user->id)->get();
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- left column -->
        <div class="lg:col-span-2 space-y-8">
            @if($user->hasRole(['sales', 'inside_sales', 'field_sales', 'calling', 'admin', 'manager']))
                <div
                    class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800 shadow-xl rounded-3xl p-8">
                    <h3 class="text-xl font-black text-indigo-900 dark:text-indigo-200 mb-6 uppercase tracking-tight">My Monthly
                        Sales Targets</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            class="p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex flex-col items-center justify-center">
                            <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest text-center">Set
                                Target</span>
                            <span
                                class="block text-3xl font-black text-slate-900 dark:text-white mt-2">₹{{ number_format($target) }}</span>
                        </div>
                        <div
                            class="p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-emerald-100 dark:border-emerald-900/30 flex flex-col items-center justify-center relative overflow-hidden group">
                            <div
                                class="absolute inset-0 bg-emerald-50 dark:bg-emerald-900/10 opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <span
                                class="block text-[10px] font-bold text-emerald-500 uppercase tracking-widest relative z-10 text-center">Amount
                                Achieved</span>
                            <span
                                class="block text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2 relative z-10">₹{{ number_format($achieved) }}</span>
                        </div>
                        <div
                            class="p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-rose-100 dark:border-rose-900/30 flex flex-col items-center justify-center">
                            <span
                                class="block text-[10px] font-bold text-rose-500 uppercase tracking-widest text-center">Remaining</span>
                            <span
                                class="block text-3xl font-black text-rose-600 mt-2">₹{{ number_format($balance > 0 ? $balance : 0) }}</span>
                        </div>
                    </div>

                    <!-- Visual Progress Bar -->
                    @if($target > 0)
                        <div class="mt-8">
                            <div class="flex justify-between items-end mb-2">
                                <span
                                    class="text-xs font-black text-indigo-900 dark:text-indigo-300 uppercase tracking-widest">Target
                                    Progress</span>
                                <span
                                    class="text-sm font-black text-indigo-600 dark:text-indigo-400">{{ min(round(($achieved / $target) * 100), 100) }}%</span>
                            </div>
                            <div
                                class="w-full h-3 bg-white dark:bg-slate-800 rounded-full overflow-hidden shadow-inner border border-slate-100 dark:border-slate-700">
                                <div class="h-full bg-gradient-to-r from-indigo-500 to-emerald-400 rounded-full transition-all duration-1000"
                                    style="width: {{ min(($achieved / $target) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 shadow-xl rounded-3xl border border-slate-200 dark:border-slate-800 p-8">
                <h3 class="text-xl font-black text-slate-800 dark:text-white mb-4">Quick Links</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('leads.create') }}"
                        class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl flex flex-col items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <span class="text-2xl mb-2">➕</span>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase">New Lead</span>
                    </a>
                    <a href="{{ route('leads.index') }}"
                        class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl flex flex-col items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <span class="text-2xl mb-2">📋</span>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase">My Leads</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- right column -->
        <div class="space-y-8">
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xl rounded-3xl flex flex-col h-full overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h4
                        class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        My Key Result Areas
                    </h4>
                </div>
                <div class="p-6 flex-1 bg-slate-50 dark:bg-slate-900/50">
                    @forelse($kras as $kra)
                        <div
                            class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-4 rounded-2xl shadow-sm mb-4 last:mb-0 hover:border-indigo-300 transition-colors group relative overflow-hidden">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 scale-y-0 group-hover:scale-y-100 transition-transform origin-top">
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h5 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-tight">
                                        {{ $kra->title }}</h5>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                        {{ $kra->description }}</p>
                                </div>
                                <div
                                    class="ml-4 text-right bg-indigo-50 dark:bg-indigo-900/20 px-3 py-2 rounded-xl border border-indigo-100 dark:border-indigo-800/50 flex-shrink-0">
                                    <span
                                        class="block text-[8px] font-black uppercase text-indigo-400 tracking-widest">Goal</span>
                                    <span
                                        class="block text-lg font-black text-indigo-700 dark:text-indigo-400 leading-none mt-0.5">{{ intval($kra->target_value) }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <span class="block text-3xl mb-2 grayscale opacity-50">🎯</span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">No KRAs
                                Assigned</span>
                            <p class="text-[10px] text-slate-400 mt-1">Your administrator has not assigned you any key result
                                areas yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection