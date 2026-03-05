@extends('layouts.dashboard')

@section('title', 'Attendance History')

@section('content')
    <div class="mb-8 flex justify-between items-center text-slate-900 font-bold text-2xl">
        Attendance History
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Clock
                            In</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Clock
                            Out</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Duration</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($history as $record)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                {{ $record->date->format('l, F d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $record->login_at?->format('h:i A') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $record->logout_at?->format('h:i A') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                @if($record->work_duration_minutes)
                                    {{ floor($record->work_duration_minutes / 60) }}h {{ $record->work_duration_minutes % 60 }}m
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold leading-5 
                                    {{ $record->status === 'present' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 italic">No attendance records
                                found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $history->links() }}
        </div>
    </div>
@endsection