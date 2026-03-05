@extends('layouts.dashboard')

@section('title', 'User Details')

@section('content')
    <div class="mb-8 flex justify-between items-center text-slate-900 font-bold text-2xl">
        User Details: {{ $user->name }}
        <a href="{{ route('admin.users.edit', $user->id) }}"
            class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition">Edit
            User</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-lg border border-slate-100 shadow-sm text-center">
                <div
                    class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-3xl mx-auto mb-4">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-sm text-slate-500 mb-4">{{ $user->email }}</p>
                <div class="flex flex-wrap justify-center gap-2">
                    @foreach($user->roles as $role)
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">{{ $role->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg border border-slate-100 shadow-sm">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Total Days Present</span>
                        <span
                            class="text-sm font-bold text-slate-900">{{ $user->attendances->where('status', 'present')->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500">Leaves Taken</span>
                        <span
                            class="text-sm font-bold text-slate-900">{{ $user->leaveRequests->where('status', 'approved')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <!-- Attendance History -->
            <div class="bg-white rounded-lg border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-lg font-semibold">Recent Attendance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Clock In</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Clock Out
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach($user->attendances->take(5) as $att)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        {{ $att->date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $att->login_at?->format('H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $att->logout_at?->format('H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $att->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="bg-white rounded-lg border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-lg font-semibold">Activity Logs</h3>
                </div>
                <div class="px-6 py-4">
                    <ul class="space-y-4">
                        @foreach($user->activityLogs->take(10) as $log)
                            <li class="flex items-start">
                                <div class="mt-1 h-2 w-2 rounded-full bg-indigo-400 mr-3"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-slate-800">{{ $log->description }}</p>
                                    <p class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }} • IP:
                                        {{ $log->properties['ip'] ?? 'N/A' }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection