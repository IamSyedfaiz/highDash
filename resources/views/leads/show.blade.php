@extends('layouts.dashboard')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('leads.index') }}"
                class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 transition shadow-sm">
                <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $lead->company_name }}</h1>
                <p class="text-slate-500 dark:text-slate-400">Manage interaction and update calling status.</p>
            </div>
        </div>
        <div class="flex gap-3">
            @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                <a href="{{ route('leads.edit', $lead->id) }}"
                    class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 transition">Edit
                    Lead</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Lead Details -->
        <div class="lg:col-span-1 space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden p-8 transition-all">
                <div class="mb-8">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Contact Info</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div
                                class="p-2 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Contact Name</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $lead->contact_name ?? 'Not Provided' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Email Address</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $lead->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="p-2 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Primary Phone</p>
                                <p class="text-xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $lead->phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Location & Source</h3>
                    <div class="space-y-3">
                        <p class="text-sm text-slate-600 dark:text-slate-400"><span class="font-bold">City:</span>
                            {{ $lead->city ?? '-' }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400"><span class="font-bold">State:</span>
                            {{ $lead->state ?? '-' }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400"><span class="font-bold">Source:</span>
                            {{ $lead->lead_source ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calling Workspace -->
        <div class="lg:col-span-2">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden transition-all h-full">
                <div
                    class="px-8 py-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Interaction Log</h3>
                    <p class="text-sm text-slate-500">Update the current session result below.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('leads.quickUpdate', $lead->id) }}" method="POST" class="space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Lead
                                    Status</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach(['New Lead', 'Existing', 'Drop'] as $st)
                                        <label
                                            class="relative flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 dark:border-slate-800 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm group">
                                            <input type="radio" name="status" value="{{ $st }}" class="absolute opacity-0" {{ $lead->status == $st ? 'checked' : '' }}>
                                            <span
                                                class="text-xs font-bold text-slate-700 dark:text-slate-300 status-label">{{ $st }}</span>
                                            <div
                                                class="mt-1 h-1.5 w-1.5 rounded-full opacity-0 status-indicator {{ $st == 'Drop' ? 'bg-rose-500' : 'bg-indigo-500' }}">
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Calling
                                    Result</label>
                                <select name="calling_status"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all">
                                    <option value="">Select Result...</option>
                                    <option value="Call Answered" {{ $lead->calling_status == 'Call Answered' ? 'selected' : '' }}>Call Answered ✅</option>
                                    <option value="Not Answered" {{ $lead->calling_status == 'Not Answered' ? 'selected' : '' }}>Not Answered ❌</option>
                                    <option value="Busy" {{ $lead->calling_status == 'Busy' ? 'selected' : '' }}>Busy 📵
                                    </option>
                                    <option value="Switched Off" {{ $lead->calling_status == 'Switched Off' ? 'selected' : '' }}>Switched Off 🔋</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Feedback
                                Notes</label>
                            <textarea name="feedback" rows="6"
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-6 text-sm placeholder:text-slate-400 focus:ring-4 focus:ring-indigo-500/10 transition-all"
                                placeholder="Enter details about the conversation, customer requirements, or next steps...">{{ $lead->feedback }}</textarea>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <div id="dropWarning" class="hidden text-rose-500 text-xs font-semibold animate-pulse">
                                ⚠️ Lead will be unassigned and returned to pool.
                            </div>
                            <div class="flex-1"></div>
                            <button type="submit"
                                class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold rounded-2xl shadow-2xl shadow-indigo-500/30 transition-all transform hover:scale-105 active:scale-95 flex items-center gap-3">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Update & Save Result
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        input[type="radio"]:checked+.status-label {
            color: #4f46e5;
        }

        input[type="radio"]:checked~.status-indicator {
            opacity: 1;
        }

        input[type="radio"]:checked+.status-label::before {
            content: '';
            position: absolute;
            inset: -1px;
            border: 2px solid #4f46e5;
            border-radius: 0.75rem;
        }
    </style>

    <script>
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const warning = document.getElementById('dropWarning');
                if (this.value === 'Drop') {
                    warning.classList.remove('hidden');
                } else {
                    warning.classList.add('hidden');
                }
            });
        });
    </script>
@endsection