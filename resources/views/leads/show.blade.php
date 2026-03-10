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
                <p class="text-slate-500 dark:text-slate-400">Lead ID: #{{ $lead->id }} • Updated
                    {{ $lead->updated_at->diffForHumans() }}
                </p>
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
        <!-- Lead Details & Statistics -->
        <div class="lg:col-span-1 space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden p-8 transition-all">
                <div class="mb-8">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Business profile</h3>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="p-2.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-xl">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Contact
                                    Executive</p>
                                <p class="text-sm font-black text-slate-900 dark:text-white">
                                    {{ $lead->contact_name ?? 'Not Provided' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="p-2.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Email Endpoint
                                </p>
                                <p class="text-sm font-black text-slate-900 dark:text-white">{{ $lead->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="p-2.5 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 rounded-xl">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Primary
                                    Communication</p>
                                <p class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $lead->phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 dark:border-slate-800">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Metadata</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter mb-1">City</p>
                            <p class="text-xs font-black text-slate-900 dark:text-white">{{ $lead->city ?? '-' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter mb-1">State</p>
                            <p class="text-xs font-black text-slate-900 dark:text-white">{{ $lead->state ?? '-' }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl col-span-2">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter mb-1">Lead Source</p>
                            <p class="text-xs font-black text-slate-900 dark:text-white">{{ $lead->lead_source ?? 'N/A' }}
                            </p>
                        </div>
                        <div
                            class="p-4 bg-indigo-50 dark:bg-indigo-900/10 rounded-2xl col-span-2 border border-indigo-100 dark:border-indigo-900/20">
                            <p class="text-[10px] text-indigo-400 font-black uppercase tracking-tighter mb-1">Assigned To
                            </p>
                            <p class="text-xs font-black text-indigo-600 dark:text-indigo-300">
                                {{ $lead->assignedUser->name ?? 'Unassigned' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div
                class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-500/20">
                <p class="text-xs font-black uppercase tracking-widest opacity-60 mb-8">Follow-up Velocity</p>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-4xl font-black mb-1">{{ $lead->followUps->count() }}</p>
                        <p class="text-[10px] font-bold opacity-80 uppercase tracking-tighter">Total Interactions</p>
                    </div>
                    <div
                        class="h-16 w-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/20">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Follow-up Activity & New Entry -->
        <div class="lg:col-span-2 space-y-8">
            <!-- New Followup Form -->
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
                <div
                    class="px-8 py-6 bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight">New
                            Interaction</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Capture details of your
                            latest call</p>
                    </div>
                    <span
                        class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $lead->status === 'Drop' ? 'bg-rose-100 text-rose-800' : 'bg-indigo-100 text-indigo-800' }}">
                        Current Status: {{ $lead->status }}
                    </span>
                </div>

                <div class="p-8">
                    <form action="{{ route('leads.followups.store', $lead->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Call
                                    Result / Status</label>
                                <select name="status" required
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm font-black focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                                    <option value="">Select Outcome...</option>
                                    <option value="Call Answered">Call Answered ✅</option>
                                    <option value="Busy / Callback">Busy / Callback 📵</option>
                                    <option value="Not Answered">Not Answered ❌</option>
                                    <option value="Interested">Interested ⭐</option>
                                    <option value="Not Interested">Not Interested 👎</option>
                                    <option value="Switched Off">Switched Off 🔋</option>
                                    <option value="Wrong Number">Wrong Number 🚫</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Prospect
                                    Pipeline</label>
                                <select name="prospect_status"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm font-black focus:ring-2 focus:ring-indigo-500 transition-all cursor-pointer">
                                    <option value="None" {{ $lead->prospect_status == 'None' ? 'selected' : '' }}>None
                                    </option>
                                    <option value="Approach" {{ $lead->prospect_status == 'Approach' ? 'selected' : '' }}>
                                        Approach</option>
                                    <option value="Negotiable" {{ $lead->prospect_status == 'Negotiable' ? 'selected' : '' }}>
                                        Negotiable</option>
                                    <option value="Order Won" {{ $lead->prospect_status == 'Order Won' ? 'selected' : '' }}>
                                        Order Won</option>
                                    <option value="Order Lost" {{ $lead->prospect_status == 'Order Lost' ? 'selected' : '' }}>
                                        Order Lost</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Next
                                    Follow-up Date</label>
                                <input type="date" name="next_follow_up_date"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm font-black focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Discussion
                                Message / Notes</label>
                            <textarea name="message" rows="4" required
                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-3xl p-6 text-sm font-medium placeholder:text-slate-400 focus:ring-2 focus:ring-indigo-500 transition-all"
                                placeholder="What did the customer say? What are the next steps?"></textarea>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <div class="flex items-center gap-3">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="update_lead_status" value="drop"
                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5 transition-all">
                                    <span
                                        class="text-xs font-black text-slate-400 group-hover:text-rose-500 transition-colors uppercase tracking-widest">Mark
                                        as "Drop" Lead</span>
                                </label>
                            </div>
                            <button type="submit"
                                class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 flex items-center gap-3 uppercase tracking-widest text-[10px]">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Log interaction
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Follow-up Timeline -->
            <div class="space-y-6">
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight ml-4">Communication
                    Timeline</h3>

                <div class="space-y-4">
                    @forelse($lead->followUps->sortByDesc('created_at') as $followUp)
                        <div
                            class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group hover:shadow-xl transition-all">
                            <!-- Indicator -->
                            <div
                                class="absolute top-0 left-0 bottom-0 w-1.5 {{ str_contains(strtolower($followUp->status), 'interested') ? 'bg-emerald-500' : (str_contains(strtolower($followUp->status), 'not answered') ? 'bg-rose-500' : 'bg-indigo-500') }}">
                            </div>

                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="space-y-3 flex-1">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-[9px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-widest">
                                            {{ $followUp->status }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                            {{ $followUp->created_at->format('M d, Y • h:i A') }}
                                        </span>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 italic leading-relaxed">
                                        "{{ $followUp->message }}"
                                    </p>
                                    @if($followUp->next_follow_up_date)
                                        <div class="flex items-center gap-2 text-indigo-500">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Next Call:
                                                {{ $followUp->next_follow_up_date->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center md:items-start gap-3">
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Handled By
                                        </p>
                                        <p class="text-xs font-black text-slate-900 dark:text-white">{{ $followUp->user->name }}
                                        </p>
                                    </div>
                                    <div
                                        class="h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-black text-xs">
                                        {{ substr($followUp->user->name, 0, 1) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="py-20 text-center bg-slate-50 dark:bg-slate-800/10 rounded-[3rem] border-4 border-dashed border-slate-100 dark:border-slate-800/50">
                            <p class="text-slate-400 font-black uppercase tracking-widest text-sm">No interaction history
                                documented yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection