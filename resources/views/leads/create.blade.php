@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ Auth::user()->isAdmin() || Auth::user()->hasRole('manager') || Auth::user()->hasRole(['sales', 'inside_sales', 'field_sales']) ? route('leads.index') : route('dashboard') }}"
                class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 transition shadow-sm text-slate-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Add New Lead</h1>
        </div>

        <form action="{{ route('leads.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Company Information -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-4">
                    Company Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Company Name *</label>
                        <input type="text" name="company_name" required value="{{ old('company_name') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Contact Name</label>
                        <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Designation</label>
                        <input type="text" name="designation" value="{{ old('designation') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Add Distribution</label>
                        <input type="text" name="add_distribution" value="{{ old('add_distribution') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Keywords</label>
                        <textarea name="keywords" rows="2"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('keywords') }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Business Type *</label>
                        <select name="business_type" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                            @foreach(['Manufacturer', 'Supplier', 'Trader', 'Wholesaler', 'Importer', 'Exporter', 'Service Provider'] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Lead Source</label>
                        <input type="text" name="lead_source" value="{{ old('lead_source') }}"
                            placeholder="e.g. Website, Reference"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-4">
                    Contact Numbers</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Primary Phone *</label>
                        <input type="text" name="phone" required value="{{ old('phone') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Phone 1</label>
                        <input type="text" name="phone_1" value="{{ old('phone_1') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Phone 2</label>
                        <input type="text" name="phone_2" value="{{ old('phone_2') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-4">
                    Location</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">City</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">State</label>
                        <input type="text" name="state" value="{{ old('state') }}"
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase ml-1">Full Address</label>
                    <textarea name="address" rows="3"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ Auth::user()->isAdmin() || Auth::user()->hasRole('manager') || Auth::user()->hasRole(['sales', 'inside_sales', 'field_sales']) ? route('leads.index') : route('dashboard') }}"
                    class="px-8 py-3 text-slate-600 dark:text-slate-400 font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">Cancel</a>
                <button type="submit"
                    class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
                    Save New Lead
                </button>
            </div>
        </form>
    </div>
@endsection