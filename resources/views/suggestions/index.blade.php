@extends('layouts.dashboard')

@section('content')
<div x-data="{ showPostModal: false }" class="max-w-4xl mx-auto space-y-8 pb-20">
    <!-- Header & Tabs -->
    <div class="glass-header rounded-3xl p-6 bg-white/40 dark:bg-slate-900/40 backdrop-blur-xl border border-white/50 dark:border-slate-800 shadow-xl sticky top-4 z-40">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 uppercase tracking-tighter">
                    Feedback Feed
                </h1>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Anonymous Suggestions & Polls</p>
            </div>
            <button @click="showPostModal = true" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all transform hover:-translate-y-1 font-black text-xs uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Post Suggestion
            </button>
        </div>

        <div class="mt-6 flex space-x-2 overflow-x-auto hide-scrollbar">
            @php
                $tabs = [
                    'all' => ['label' => 'Latest Feed', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                    'trending' => ['label' => 'Trending', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                    'polls' => ['label' => 'Active Polls', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    'my_targeted' => ['label' => 'Mentions', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z']
                ];
            @endphp
            @foreach($tabs as $key => $tabData)
                <a href="{{ route('suggestions.index', ['tab' => $key]) }}"
                   class="flex items-center px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest whitespace-nowrap transition-all {{ $tab === $key ? 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300' : 'text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800/50' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabData['icon'] }}" /></svg>
                    {{ $tabData['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Feed -->
    <div class="space-y-6">
        @forelse($suggestions as $suggestion)
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 transition-all hover:shadow-lg relative overflow-hidden group">
                <!-- Background decorative element -->
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/10 dark:to-purple-900/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                <div class="flex items-start justify-between relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 font-bold shadow-inner">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                Anonymous Colleague
                                @if($suggestion->targetUser)
                                    <span class="text-indigo-500 text-xs bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                        {{ $suggestion->targetUser->name }}
                                    </span>
                                @endif
                            </h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">
                                {{ $suggestion->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 relative z-10">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $suggestion->title }}</h2>
                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed whitespace-pre-wrap">{{ $suggestion->description }}</p>
                    
                    @if($suggestion->image)
                        <div class="mt-4 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700">
                            <img src="{{ Storage::url($suggestion->image) }}" class="w-full h-auto object-cover max-h-96" alt="Suggestion Image">
                        </div>
                    @endif

                    <!-- Poll Section -->
                    @if($suggestion->poll)
                        <div class="mt-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-100 dark:border-slate-800">
                            <h4 class="text-sm font-black text-slate-900 dark:text-white mb-4 flex items-center gap-2 uppercase tracking-wide">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                {{ $suggestion->poll->question }}
                            </h4>
                            
                            @php
                                $totalVotes = $suggestion->poll->options->sum(fn($opt) => $opt->votes->count());
                                $userVoted = $suggestion->poll->options->contains(function($opt) {
                                    return $opt->votes->where('user_id', Auth::id())->count() > 0;
                                });
                            @endphp

                            <div class="space-y-3">
                                @foreach($suggestion->poll->options as $option)
                                    @php
                                        $votesCount = $option->votes->count();
                                        $percentage = $totalVotes > 0 ? round(($votesCount / $totalVotes) * 100) : 0;
                                        $isUserVote = $option->votes->where('user_id', Auth::id())->count() > 0;
                                    @endphp
                                    <div class="relative overflow-hidden rounded-xl bg-white dark:bg-slate-900 border {{ $isUserVote ? 'border-indigo-500' : 'border-slate-200 dark:border-slate-700' }} transition-all">
                                        <div class="absolute left-0 top-0 bottom-0 bg-indigo-100/50 dark:bg-indigo-900/20 transition-all duration-1000 ease-out" style="width: {{ $percentage }}%"></div>
                                        <div class="relative z-10 flex items-center justify-between p-3">
                                            <div class="flex items-center gap-3 w-full">
                                                <form action="{{ route('suggestions.polls.vote', $suggestion->poll) }}" method="POST" class="shrink-0">
                                                    @csrf
                                                    <input type="hidden" name="option_id" value="{{ $option->id }}">
                                                    <button type="submit" class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all
                                                        {{ $isUserVote ? 'border-indigo-500 bg-indigo-500 text-white' : 'border-slate-300 dark:border-slate-600 hover:border-indigo-400' }}">
                                                        @if($isUserVote)
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        @endif
                                                    </button>
                                                </form>
                                                <span class="text-sm font-medium text-slate-800 dark:text-slate-200 flex-1">{{ $option->option_text }}</span>
                                                <span class="text-xs font-bold text-slate-500">{{ $percentage }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-right">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $totalVotes }} Total Votes</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Comments Section -->
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 relative z-10" x-data="{ showComments: false }">
                    <button @click="showComments = !showComments" class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-slate-500 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        {{ $suggestion->comments->count() }} Comments
                    </button>

                    <div x-show="showComments" x-collapse x-cloak class="mt-4 space-y-4">
                        <form action="{{ route('suggestions.comments.store', $suggestion) }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="text" name="comment" required placeholder="Add an anonymous comment..." class="flex-1 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all">
                            <button type="submit" class="bg-slate-900 dark:bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:opacity-90 transition-opacity">Post</button>
                        </form>

                        <div class="space-y-4 max-h-60 overflow-y-auto hide-scrollbar pr-2">
                            @foreach($suggestion->comments->whereNull('parent_id') as $comment)
                                <div class="bg-slate-50/50 dark:bg-slate-800/30 rounded-2xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                            <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                            </div>
                                            Anonymous
                                        </span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $comment->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm rounded-3xl border border-dashed border-slate-300 dark:border-slate-700">
                <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No feedback found</h3>
                <p class="text-slate-500 text-sm">Be the first to share your thoughts!</p>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $suggestions->links() }}
        </div>
    </div>

    <!-- Post Suggestion Modal -->
    <div x-show="showPostModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto py-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="showPostModal" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showPostModal = false"></div>

        <div x-show="showPostModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="relative bg-white dark:bg-slate-900 rounded-3xl p-8 w-full max-w-2xl shadow-2xl border border-slate-200 dark:border-slate-800 m-4 transform transition-all">
            
            <div class="absolute top-4 right-4">
                <button @click="showPostModal = false" class="text-slate-400 hover:text-slate-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-6 uppercase tracking-tight">New Anonymous Post</h2>

            <form action="{{ route('suggestions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ hasPoll: false, optionsCount: 2 }">
                @csrf
                
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Subject</label>
                    <input type="text" name="title" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="What's on your mind?">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Details</label>
                    <textarea name="description" required rows="4" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all resize-none" placeholder="Provide more context..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Mention Someone (Optional)</label>
                        <select name="target_user_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                            <option value="">No one specific</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Attach Image (Optional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>

                <!-- Poll Toggle -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="has_poll" value="0">
                        <div class="relative">
                            <input type="checkbox" name="has_poll" value="1" x-model="hasPoll" class="sr-only">
                            <div class="block bg-slate-200 dark:bg-slate-700 w-10 h-6 rounded-full transition-colors" :class="hasPoll ? 'bg-indigo-500' : ''"></div>
                            <div class="dot absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform" :class="hasPoll ? 'transform translate-x-4' : ''"></div>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Add a Poll</span>
                    </label>
                </div>

                <div x-show="hasPoll" x-collapse class="bg-indigo-50/50 dark:bg-indigo-900/10 rounded-2xl p-5 border border-indigo-100 dark:border-indigo-900/30 space-y-4">
                    <div>
                        <label class="block text-xs font-black text-indigo-800 dark:text-indigo-300 uppercase tracking-widest mb-2">Poll Question</label>
                        <input type="text" name="poll_question" :required="hasPoll" class="w-full bg-white dark:bg-slate-800 border border-indigo-200 dark:border-indigo-800 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" placeholder="Ask something...">
                    </div>
                    
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-indigo-800 dark:text-indigo-300 uppercase tracking-widest mb-2">Options</label>
                        <template x-for="i in optionsCount" :key="i">
                            <input type="text" :name="'poll_options[]'" :required="hasPoll && i <= 2" class="w-full bg-white dark:bg-slate-800 border border-indigo-200 dark:border-indigo-800 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :placeholder="'Option ' + i">
                        </template>
                    </div>
                    
                    <button type="button" @click="if(optionsCount < 5) optionsCount++" x-show="optionsCount < 5" class="text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add Option
                    </button>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black text-xs uppercase tracking-widest py-4 rounded-xl shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                        Post Anonymously
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
