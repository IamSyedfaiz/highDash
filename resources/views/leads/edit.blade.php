@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('leads.index') }}"
                class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 transition shadow-sm text-slate-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Edit Lead</h1>
        </div>

        <form action="{{ route('leads.update', $lead->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-4">
                    Company Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Company Name *</label>
                        <input type="text" name="company_name" required
                            value="{{ old('company_name', $lead->company_name) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Contact Name</label>
                        <input type="text" name="contact_name" value="{{ old('contact_name', $lead->contact_name) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $lead->name) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation', $lead->designation) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Add Distribution</label>
                        <input type="text" name="add_distribution"
                            value="{{ old('add_distribution', $lead->add_distribution) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Keywords</label>
                        <textarea name="keywords" rows="2"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('keywords', $lead->keywords) }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $lead->email) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Business Type *</label>
                        <select name="business_type" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            @foreach (['Manufacturer', 'Supplier', 'Trader', 'Wholesaler', 'Importer', 'Exporter', 'Service Provider'] as $type)
                                <option value="{{ $type }}" {{ $lead->business_type == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Lead Source</label>
                        <input type="text" name="lead_source" value="{{ old('lead_source', $lead->lead_source) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- System & Status -->
            <div
                class="bg-indigo-50/50 dark:bg-indigo-900/10 rounded-3xl border border-indigo-100 dark:border-indigo-800 shadow-sm p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-indigo-900 dark:text-indigo-200 border-b border-indigo-100 dark:border-indigo-800 pb-4">
                    Internal Workflow</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-indigo-500 uppercase ml-1">Overall Status</label>
                        <select name="status"
                            class="w-full bg-white dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 shadow-sm">
                            <option value="Pending" {{ $lead->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="New Lead" {{ $lead->status == 'New Lead' ? 'selected' : '' }}>New Lead</option>
                            <option value="Existing" {{ $lead->status == 'Existing' ? 'selected' : '' }}>Existing</option>
                            <option value="Drop" {{ $lead->status == 'Drop' ? 'selected' : '' }}>Drop (Unassign)</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-indigo-500 uppercase ml-1">Prospect</label>
                        <select name="prospect_status"
                            class="w-full bg-white dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 shadow-sm">
                            <option value="None" {{ $lead->prospect_status == 'None' ? 'selected' : '' }}>None</option>
                            <option value="Approach" {{ $lead->prospect_status == 'Approach' ? 'selected' : '' }}>Approach
                            </option>
                            <option value="Negotiable" {{ $lead->prospect_status == 'Negotiable' ? 'selected' : '' }}>
                                Negotiable</option>
                            <option value="Order Won" {{ $lead->prospect_status == 'Order Won' ? 'selected' : '' }}>Order
                                Won</option>
                            <option value="Order Lost" {{ $lead->prospect_status == 'Order Lost' ? 'selected' : '' }}>Order
                                Lost</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-indigo-500 uppercase ml-1">Secondary Status</label>
                        <select name="calling_status"
                            class="w-full bg-white dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 shadow-sm">
                            <option value="">None</option>
                            <option value="Call Answered" {{ $lead->calling_status == 'Call Answered' ? 'selected' : '' }}>
                                Call Answered</option>
                            <option value="Busy / Callback" {{ $lead->calling_status == 'Busy / Callback' ? 'selected' : '' }}>Busy / Callback</option>
                            <option value="Not Answered" {{ $lead->calling_status == 'Not Answered' ? 'selected' : '' }}>
                                Not
                                Answered</option>
                            <option value="Interested" {{ $lead->calling_status == 'Interested' ? 'selected' : '' }}>
                                Interested</option>
                            <option value="Not Interested" {{ $lead->calling_status == 'Not Interested' ? 'selected' : '' }}>
                                Not Interested</option>
                            <option value="Switched Off" {{ $lead->calling_status == 'Switched Off' ? 'selected' : '' }}>
                                Switched Off</option>
                            <option value="Wrong Number" {{ $lead->calling_status == 'Wrong Number' ? 'selected' : '' }}>
                                Wrong
                                Number</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Additional Contacts -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-4">
                    Location & Media</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Primary Phone *</label>
                        <input type="text" name="phone" required value="{{ old('phone', $lead->phone) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $lead->city) }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">Feedback / Notes</label>
                    <textarea name="feedback" rows="4"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all"
                        placeholder="Enter session feedback...">{{ old('feedback', $lead->feedback) }}</textarea>
                </div>
            </div>

            <div
                class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <div>
                    @if (Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                        <button type="button" onclick="confirmDelete()"
                            class="text-rose-600 font-bold text-sm hover:text-rose-700 transition px-4 py-2 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl">Delete
                            Lead Permanently</button>
                    @endif
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('leads.index') }}"
                        class="px-8 py-3 text-slate-600 dark:text-slate-400 font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">Discard</a>
                    <button type="submit"
                        class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
                        Update Lead Info
                    </button>
                </div>
            </div>
        </form>

        <form id="deleteForm" action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this lead? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endsection