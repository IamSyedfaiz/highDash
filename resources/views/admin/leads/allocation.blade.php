@extends('layouts.dashboard')

@section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Lead Allocation</h1>
        <p class="text-slate-500 dark:text-slate-400">Assign unassigned leads to your calling agents.</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Allocation Form -->
        <div class="xl:col-span-2">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden transition-all">
                <div
                    class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                        <h3 class="font-bold text-slate-900 dark:text-white flex items-center">
                            <span
                                class="p-2 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg mr-3">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            Unassigned Leads
                        </h3>
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <div id="selectionCounter" class="text-xs font-bold text-slate-500 dark:text-slate-400 hidden">
                                <span id="selectedCount">0</span> selected
                            </div>
                            <button type="button" onclick="toggleSelectAll()" id="selectAllBtn"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">Select All</button>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <form action="{{ route('admin.leads.allocation') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company or city..." 
                                class="w-full pl-10 bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                        <div class="w-full md:w-48">
                            <select name="status" onchange="this.form.submit()" 
                                class="w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">All Statuses</option>
                                @foreach(['Pending', 'Prospect', 'Approach', 'Negotiable', 'Order won'] as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="hidden">Search</button>
                    </form>
                </div>

                <form action="{{ route('admin.leads.allocate') }}" method="POST" id="allocationForm">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead class="bg-slate-50 dark:bg-slate-900/20">
                                <tr>
                                    <th class="px-6 py-3 text-left w-10"></th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Company</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        City</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Source</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($unassignedLeads as $lead)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors cursor-pointer"
                                        onclick="toggleLead({{ $lead->id }})">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="lead_ids[]" value="{{ $lead->id }}"
                                                id="check-{{ $lead->id }}"
                                                class="lead-checkbox rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800 transition-all pointer-events-none"
                                                onclick="event.stopPropagation()">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-900 dark:text-white">
                                                {{ $lead->company_name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $lead->business_type }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">
                                            {{ $lead->city ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700 dark:text-slate-300">
                                            {{ $lead->lead_source ?? 'Unknown' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $lead->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-20 text-center text-slate-400 dark:text-slate-600 italic">All active
                                            leads are currently assigned.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                        {{ $unassignedLeads->links() }}
                    </div>

                    @if($unassignedLeads->count() > 0)
                        <div
                            class="p-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row gap-6 items-end justify-between">
                            <div class="w-full md:max-w-xs">
                                <label
                                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-widest">Select
                                    Agent</label>
                                <select name="user_id" required
                                    class="w-full bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select an Agent...</option>
                                    @foreach($callingUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->leads_count ?? '0' }} assigned)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all transform hover:-translate-y-0.5">
                                Confirm Allocation
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Right Sidebar Info -->
        <div class="space-y-8">
            <!-- Recently Allocated -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden p-6 transition-all">
                <h3 class="font-bold text-slate-900 dark:text-white mb-6 flex items-center">
                    <span
                        class="p-2 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-lg mr-3">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Recently Allocated
                </h3>
                <ul class="space-y-4">
                    @foreach($recentlyAssigned as $lead)
                        <li class="flex items-center justify-between group">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div
                                    class="h-8 w-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ substr($lead->company_name, 0, 1) }}</div>
                                <div class="truncate">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                        {{ $lead->company_name }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-tighter">Assigned to: <span
                                            class="text-indigo-500 dark:text-indigo-400">{{ $lead->assignedUser->name }}</span>
                                    </p>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 shrink-0">{{ $lead->updated_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Help Card -->
            <div
                class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-6 shadow-2xl text-white relative overflow-hidden group">
                <svg class="absolute -right-10 -bottom-10 h-64 w-64 text-white/10 transform rotate-12 transition-transform duration-500 group-hover:rotate-0"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>
                <h4 class="text-lg font-bold mb-2">Pro Tip!</h4>
                <p class="text-white/80 text-sm mb-4 leading-relaxed">Selecting multiple leads and assigning them to an
                    agent resets their status to 'New Lead', making them top priority.</p>
                <div class="inline-flex items-center text-xs font-bold py-1 px-3 bg-white/20 rounded-full backdrop-blur-sm">
                    Learn Workflow
                    <svg class="h-3 w-3 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLead(id) {
            const check = document.getElementById('check-' + id);
            check.checked = !check.checked;
            updateCounter();
        }

        function updateCounter() {
            const count = document.querySelectorAll('.lead-checkbox:checked').length;
            const counter = document.getElementById('selectionCounter');
            const text = document.getElementById('selectedCount');

            if (count > 0) {
                counter.classList.remove('hidden');
                text.innerText = count;
            } else {
                counter.classList.add('hidden');
            }
        }

        function toggleSelectAll() {
            const allChecked = document.querySelectorAll('.lead-checkbox:checked').length === document.querySelectorAll('.lead-checkbox').length;
            document.querySelectorAll('.lead-checkbox').forEach(cb => {
                cb.checked = !allChecked;
            });
            const btn = document.getElementById('selectAllBtn');
            btn.innerText = !allChecked ? 'Deselect All' : 'Select All';
            updateCounter();
        }
    </script>
@endsection