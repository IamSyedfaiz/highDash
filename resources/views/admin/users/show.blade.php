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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach($user->attendances()->latest('date')->take(10)->get() as $att)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                        {{ $att->date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $att->login_at?->format('H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        {{ $att->logout_at?->format('H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $att->status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        <button onclick="openLeadActivityModal('{{ $att->date->format('Y-m-d') }}', '{{ $user->id }}')" class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100 font-medium text-xs transition">View Leads</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Logs -->
            <div class="bg-white rounded-lg border border-slate-100 shadow-sm overflow-hidden flex flex-col" style="max-height: 500px;">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 sticky top-0 z-10">
                    <h3 class="text-lg font-semibold flex items-center justify-between">
                        <span>Browser & Activity Logs</span>
                        <span class="text-xs font-normal text-slate-500 bg-slate-200 px-2 py-1 rounded-full">Last 50 entries</span>
                    </h3>
                </div>
                <div class="px-6 py-4 overflow-y-auto flex-1">
                    <ul class="space-y-5">
                        @forelse($user->activityLogs()->latest()->take(50)->get() as $log)
                            <li class="flex items-start">
                                <div class="mt-1.5 h-2.5 w-2.5 rounded-full {{ isset($log->properties['browser_activity']) ? 'bg-sky-400' : 'bg-indigo-500' }} mr-3 flex-shrink-0"></div>
                                <div class="flex-1">
                                    <p class="text-sm text-slate-800 font-medium">{{ $log->description }}</p>
                                    <p class="text-xs text-slate-500 mt-1 flex flex-wrap gap-x-2">
                                        <span>{{ $log->created_at->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span>IP: {{ $log->properties['ip'] ?? 'N/A' }}</span>
                                        @if(isset($log->properties['url']))
                                            <span>•</span>
                                            <a href="{{ $log->properties['url'] }}" target="_blank" class="text-sky-600 hover:text-sky-800 font-medium hover:underline truncate max-w-xs block" title="{{ $log->properties['url'] }}">Link</a>
                                        @endif
                                    </p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-slate-500 text-center py-4">No activity logged yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Lead Activity Modal -->
    <div id="leadActivityModal" class="fixed inset-0 z-[100] hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-2xl w-full max-w-2xl overflow-hidden shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Lead Activity: <span id="modalActivityDate"></span></h3>
                <button onclick="closeLeadActivityModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <!-- Loader -->
                <div id="modalLoader" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto"></div>
                </div>
                <!-- Content -->
                <div id="modalContent" class="hidden space-y-6">
                    <table class="min-w-full divide-y divide-slate-200 border rounded-lg overflow-hidden">
                        <thead class="bg-slate-50" id="statsThead">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-bold text-slate-500 uppercase">Count</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100" id="statsTbody">
                        </tbody>
                    </table>
                    <div class="bg-rose-50 border border-rose-100 p-4 rounded-xl flex justify-between items-center">
                        <span class="text-sm font-bold text-rose-800">Not Open Leads (Untouched)</span>
                        <span id="notOpenLeadsCount" class="text-lg font-bold text-rose-900">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openLeadActivityModal(date, userId) {
            document.getElementById('leadActivityModal').classList.remove('hidden');
            document.getElementById('modalActivityDate').textContent = date;
            document.getElementById('modalLoader').classList.remove('hidden');
            document.getElementById('modalContent').classList.add('hidden');
            
            fetch(`/admin/users/${userId}/attendance/${date}/lead-stats`)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('statsTbody');
                    tbody.innerHTML = '';
                    if (data.statuses && Object.keys(data.statuses).length > 0) {
                        for (const [status, count] of Object.entries(data.statuses)) {
                            tbody.innerHTML += `
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-slate-700">${status || 'No Status'}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-indigo-600 font-bold">${count}</td>
                                </tr>
                            `;
                        }
                    } else {
                        tbody.innerHTML = `<tr><td colspan="2" class="px-4 py-3 text-center text-sm text-slate-500">No lead activity for this date.</td></tr>`;
                    }
                    
                    document.getElementById('notOpenLeadsCount').textContent = data.not_open || 0;
                    
                    document.getElementById('modalLoader').classList.add('hidden');
                    document.getElementById('modalContent').classList.remove('hidden');
                })
                .catch(err => {
                    console.error('Failed to load lead stats', err);
                    document.getElementById('modalLoader').classList.add('hidden');
                    document.getElementById('modalContent').classList.remove('hidden');
                    document.getElementById('statsTbody').innerHTML = `<tr><td colspan="2" class="px-4 py-3 text-center text-sm text-red-500">Failed to load stats.</td></tr>`;
                });
        }
        function closeLeadActivityModal() {
            document.getElementById('leadActivityModal').classList.add('hidden');
        }
    </script>
@endsection