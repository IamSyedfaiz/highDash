@extends('layouts.dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">
            {{ __('My Profile') }}
        </h1>
    </div>

    <div class="space-y-8">
        @php
            $user = auth()->user();
            $achieved = \App\Models\Lead::where('assigned_to', $user->id)->sum('converted_amount');
            $target = $user->sales_target_amount ?? 0;
            $balance = $target - $achieved;
            $kras = \App\Models\Kra::where('user_id', $user->id)->get();
        @endphp

        @if($user->hasRole(['sales', 'inside_sales', 'field_sales', 'calling']))
            <div
                class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800 shadow-xl rounded-3xl p-8 mb-8">
                <h3 class="text-xl font-black text-indigo-900 dark:text-indigo-200 mb-6 uppercase tracking-tight">My Direct
                    Sales Targets
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div
                        class="p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-sm text-center border border-slate-100 dark:border-slate-800">
                        <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest">Assigned Target
                            Amount</span>
                        <span
                            class="block text-4xl font-black text-slate-900 dark:text-white mt-3">₹{{ number_format($target, 2) }}</span>
                    </div>
                    <div
                        class="p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-sm text-center border border-slate-100 dark:border-slate-800">
                        <span class="block text-xs font-bold text-emerald-500 uppercase tracking-widest">Total Target
                            Achieved</span>
                        <span class="block text-4xl font-black text-emerald-600 mt-3">₹{{ number_format($achieved, 2) }}</span>
                    </div>
                    <div
                        class="p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-sm text-center border border-slate-100 dark:border-slate-800">
                        <span class="block text-xs font-bold text-rose-500 uppercase tracking-widest">Target Remaining</span>
                        <span
                            class="block text-4xl font-black text-rose-600 mt-3">₹{{ number_format($balance > 0 ? $balance : 0, 2) }}</span>
                    </div>
                </div>

                @if($kras->count() > 0)
                    <h4 class="text-xs font-black text-indigo-900 dark:text-indigo-300 uppercase mt-10 mb-6 tracking-widest">My Key
                        Result Areas (KRAs)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($kras as $kra)
                            <div
                                class="p-6 bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex justify-between items-center group hover:border-indigo-300 transition-colors">
                                <div>
                                    <p class="font-bold text-slate-800 dark:text-white text-lg">{{ $kra->title }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $kra->description }}</p>
                                </div>
                                <div class="text-right ml-6 bg-slate-50 dark:bg-slate-800 px-4 py-3 rounded-2xl">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Goal</span>
                                    <span
                                        class="text-2xl font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ $kra->target_value }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div
                class="p-8 bg-white dark:bg-slate-900 shadow-xl rounded-3xl border border-slate-200 dark:border-slate-800 w-full mb-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div
                class="p-8 bg-white dark:bg-slate-900 shadow-xl rounded-3xl border border-slate-200 dark:border-slate-800 w-full mb-8">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div
            class="p-8 bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/30 shadow-xl rounded-3xl w-full max-w-2xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection