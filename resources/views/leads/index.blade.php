@extends('layouts.dashboard')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Lead Management</h1>
            <p class="text-slate-500 dark:text-slate-400">View and manage all your business leads.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-emerald-500/20 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import Excel
                </button>
                <a href="{{ route('leads.export', request()->all()) }}"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-amber-500/20 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Excel
                </a>
            @endif
            @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                <a href="{{ route('leads.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/20 transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Lead Manually
                </a>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div
        class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm mb-8 transition-all">
        <form action="{{ route('leads.index') }}" method="GET"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 items-end">
            <div class="space-y-1">
                <label
                    class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Company</label>
                <input type="text" name="company" value="{{ request('company') }}" placeholder="Search company..."
                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>
            <div class="space-y-1">
                <label
                    class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Status</label>
                <select name="status"
                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\Lead::STATUSES as $statusOption)
                        <option value="{{ $statusOption }}" {{ request('status') == $statusOption ? 'selected' : '' }}>
                            {{ $statusOption }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Business
                    Type</label>
                <select name="business_type"
                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="">All Types</option>
                    @foreach(['Manufacturer', 'Supplier', 'Trader', 'Wholesaler', 'Importer', 'Exporter', 'Service Provider'] as $type)
                        <option value="{{ $type }}" {{ request('business_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label
                    class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">City</label>
                <input type="text" name="city" value="{{ request('city') }}" placeholder="Search city..."
                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Assigned
                        To</label>
                    <select name="assigned_to"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">All Agents</option>
                        <option value="none" {{ request('assigned_to') == 'none' ? 'selected' : '' }}>Not Assigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-slate-900 dark:bg-indigo-600 text-white rounded-xl py-2.5 text-sm font-bold hover:bg-slate-800 dark:hover:bg-indigo-700 transition-all">
                    Filter
                </button>
                <a href="{{ route('leads.index') }}"
                    class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-xl hover:bg-slate-200 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div
        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden transition-all">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/50">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Company & Contact</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Contact Details</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Business Type</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Assigned To</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold shrink-0">
                                        {{ substr($lead->company_name, 0, 1) }}
                                    </div>
                                    <div class="ml-4 truncate max-w-[150px]">
                                        <div class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                            {{ $lead->company_name }}
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                            {{ $lead->contact_name ?? 'No Contact' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-slate-200 font-medium">{{ $lead->phone }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $lead->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span
                                    class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg text-xs font-semibold">
                                    {{ $lead->business_type }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                                                                                            @if($lead->status === 'Pending') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                                                                                            @elseif($lead->status === 'Prospect') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                                                                            @elseif($lead->status === 'Approach') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                                                                                            @elseif($lead->status === 'Negotiable') bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-400
                                                                                                            @elseif($lead->status === 'Order won' || $lead->status === 'New Lead') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                                                                                                            @elseif($lead->status === 'Existing') bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-400
                                                                                                            @else bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400 @endif">
                                    <span
                                        class="h-1.5 w-1.5 rounded-full mr-2 
                                                                                                                @if($lead->status === 'Pending') bg-amber-400
                                                                                                                @elseif($lead->status === 'Prospect') bg-blue-400
                                                                                                                @elseif($lead->status === 'Approach') bg-indigo-400
                                                                                                                @elseif($lead->status === 'Negotiable') bg-violet-400
                                                                                                                @elseif($lead->status === 'Order won' || $lead->status === 'New Lead') bg-emerald-400
                                                                                                                @elseif($lead->status === 'Existing') bg-slate-400
                                                                                                                @else bg-rose-400 @endif"></span>
                                    {{ $lead->status }}
                                </span>
                            </td>

                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($lead->assignedUser)
                                    <div class="flex items-center">
                                        <img class="h-6 w-6 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode($lead->assignedUser->name) }}&color=7F9CF5&background=EBF4FF"
                                            alt="">
                                        <span
                                            class="ml-2 text-sm text-slate-700 dark:text-slate-300">{{ $lead->assignedUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('leads.show', $lead->id) }}"
                                        class="p-2 text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all"
                                        title="View Details">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('leads.edit', $lead->id) }}"
                                        class="p-2 text-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition-all"
                                        title="Edit Lead">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="h-16 w-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center text-slate-400 mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1">No Leads Found</h3>
                                    <p class="text-slate-500 dark:text-slate-400 max-w-xs">Try adjusting your filters or start
                                        by adding a new lead.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                {{ $leads->links() }}
            </div>
        @endif
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="document.getElementById('importModal').classList.add('hidden')"></div>
            <div
                class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
                <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 pt-5 pb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Import Leads from Excel</h3>
                            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                                class="text-slate-400 hover:text-slate-500 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Download the template and upload your
                                Excel file here. Ensure columns match the lead fields.</p>
                            <div
                                class="mt-2 flex justify-center px-6 pt-10 pb-12 border-2 border-slate-200 dark:border-slate-800 border-dashed rounded-3xl hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-300 group">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors"
                                        stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 dark:text-slate-400">
                                        <label for="file-upload"
                                            class="relative cursor-pointer rounded-md font-bold text-indigo-600 hover:text-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="file-upload" name="file" type="file" class="sr-only" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">XLSX, XLS, CSV up
                                        to 10MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3 border-t border-slate-200 dark:border-slate-800">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all">Start
                            Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection