@extends('layouts.dashboard')

@section('title', 'Attendance')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow sm:rounded-lg border border-slate-100 overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-slate-50 border-b border-slate-200">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Today's Attendance</h3>
                <p class="mt-1 max-w-2xl text-sm text-slate-500">Your attendance is marked automatically when you log in.
                </p>
            </div>
            <div class="px-4 py-8 sm:p-10 text-center">
                @if($today)
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center p-4 bg-emerald-100 rounded-full mb-4">
                            <svg class="h-12 w-12 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">Marked as Present</h2>
                        <p class="text-slate-500 mt-2">Date: {{ $today->date->format('F d, Y') }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 max-w-sm mx-auto">
                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                            <span class="block text-xs text-slate-500 uppercase font-semibold">Clock In</span>
                            <span class="text-lg font-bold text-slate-900">{{ $today->login_at->format('h:i A') }}</span>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                            <span class="block text-xs text-slate-500 uppercase font-semibold">Clock Out</span>
                            <span
                                class="text-lg font-bold text-slate-900">{{ $today->logout_at ? $today->logout_at->format('h:i A') : '--:--' }}</span>
                        </div>
                    </div>

                    @if(!$today->logout_at)
                        <p class="mt-6 text-sm text-amber-600 font-medium italic">You are currently clocked in. Logout to record
                            your session end.</p>
                    @else
                        <div class="mt-6 p-4 bg-indigo-50 rounded-lg text-indigo-700 text-sm">
                            Total work duration: <strong>{{ floor($today->work_duration_minutes / 60) }}h
                                {{ $today->work_duration_minutes % 60 }}m</strong>
                        </div>
                    @endif
                @else
                    <div class="py-10">
                        <div class="inline-flex items-center justify-center p-4 bg-slate-100 rounded-full mb-4">
                            <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900">No Record for Today</h2>
                        <p class="text-slate-500 mt-2">Attendance will be marked upon your next login.</p>
                    </div>
                @endif
            </div>
            <div class="px-4 py-4 sm:px-6 bg-slate-50 border-t border-slate-200 text-right">
                <a href="{{ route('attendance.history') }}"
                    class="text-indigo-600 hover:text-indigo-500 font-semibold text-sm">View full history &rarr;</a>
            </div>
        </div>
    </div>
@endsection