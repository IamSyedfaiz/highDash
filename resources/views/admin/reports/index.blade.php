@extends('layouts.dashboard')

@section('title', 'Attendance Reports')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Attendance Reports</h1>
        <p class="text-slate-500">Filter and export attendance records.</p>
    </div>

    <div class="bg-white p-6 rounded-lg border border-slate-100 shadow-sm mb-8">
        <form action="{{ route('admin.reports.index') }}" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700">User</label>
                <select name="user_id" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm text-sm">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="mt-1 block w-full border-slate-300 rounded-md shadow-sm text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="mt-1 block w-full border-slate-300 rounded-md shadow-sm text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-indigo-600 text-white rounded-md py-2 text-sm font-medium hover:bg-indigo-700 transition">Filter</button>
                <a href="{{ route('admin.reports.export', request()->all()) }}"
                    class="flex-1 bg-emerald-600 text-white rounded-md py-2 text-sm font-medium hover:bg-emerald-700 text-center transition">Export
                    CSV</a>
            </div>
        </form>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Clock In</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Clock Out</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($attendances as $record)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $record->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $record->date->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $record->login_at?->format('H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $record->logout_at?->format('H:i') ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $record->work_duration_minutes ?? 0 }} min</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $record->status === 'present' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500 italic">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $attendances->appends(request()->all())->links() }}
        </div>
    </div>
@endsection