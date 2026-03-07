@extends('layouts.dashboard')

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8"
        x-data="{ showModal: false }">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">Daily Task Queue
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Manage and track your technical implementation tasks.</p>
        </div>
        <div class="flex gap-3">
            <button @click="showModal = true"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                Create New Task
            </button>
        </div>

        <!-- Create Task Modal -->
        <template x-teleport="body">
            <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div @click="showModal = false"
                        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
                    <div
                        class="inline-block align-bottom bg-white dark:bg-slate-900 rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-800">
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <div class="p-10">
                                <h3
                                    class="text-2xl font-black text-slate-900 dark:text-white mb-8 uppercase tracking-tight">
                                    Initialize Task</h3>
                                <div class="space-y-6">
                                    <div class="space-y-1">
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Task
                                            Title</label>
                                        <input type="text" name="title" required placeholder="What needs to be done?"
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description</label>
                                        <textarea name="description" rows="3"
                                            placeholder="Provide context or technical details..."
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold"></textarea>
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reference
                                            URL</label>
                                        <input type="url" name="url" placeholder="https://github.com/..."
                                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                                    </div>
                                    @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                                        <div class="space-y-1">
                                            <label
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Assign
                                                To</label>
                                            <select name="user_id"
                                                class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                                                @foreach($users as $u)
                                                    <option value="{{ $u->id }}" {{ $u->id == Auth::id() ? 'selected' : '' }}>
                                                        {{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div
                                class="px-10 py-8 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                                <button type="button" @click="showModal = false"
                                    class="px-6 py-3 text-xs font-black text-slate-500 uppercase tracking-widest hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">Abort</button>
                                <button type="submit"
                                    class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-500/20 transition-all uppercase tracking-widest">Push
                                    Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>

    @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
        <!-- Admin Filters -->
        <div
            class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl mb-10">
            <form action="{{ route('tasks.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Team Member</label>
                    <select name="user_id"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                        <option value="">All Engineers</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current State</label>
                    <select name="status"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                        <option value="">All Tasks</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="started" {{ request('status') == 'started' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-slate-900 dark:bg-indigo-600 text-white rounded-2xl py-3 text-xs font-black uppercase tracking-widest hover:opacity-90 transition-all shadow-lg">Scan
                        Queue</button>
                    <a href="{{ route('tasks.index') }}"
                        class="bg-slate-100 dark:bg-slate-800 text-slate-500 p-3 rounded-2xl hover:bg-slate-200 transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($tasks as $task)
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden group hover:border-indigo-500 transition-all duration-300">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <span
                            class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm
                                    {{ $task->status === 'closed' ? 'bg-emerald-100 text-emerald-800' :
                ($task->status === 'started' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800') }}">
                            {{ $task->status }}
                        </span>
                        @if($task->url)
                            <a href="{{ $task->url }}" target="_blank"
                                class="text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        @endif
                        <a href="{{ route('tasks.show', $task) }}"
                            class="p-2 text-slate-400 hover:text-indigo-600 transition-colors" title="Open Details">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </div>

                    <h3
                        class="text-xl font-black text-slate-900 dark:text-white mb-2 leading-tight group-hover:text-indigo-600 transition-colors">
                        {{ $task->title }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 line-clamp-3 font-medium">
                        {{ $task->description ?? 'No additional description provided.' }}</p>

                    <div class="flex items-center gap-4 pt-6 border-t border-slate-50 dark:border-slate-800 mb-8">
                        <div
                            class="h-10 w-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold shadow-inner">
                            {{ substr($task->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-900 dark:text-white leading-none mb-1">
                                {{ $task->user->id == Auth::id() ? 'Self Assigned' : $task->user->name }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">
                                {{ $task->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        @if($task->status !== 'started')
                            <form action="{{ route('tasks.update', $task) }}" method="POST" class="contents">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="started">
                                <button type="submit"
                                    class="col-span-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">Start</button>
                            </form>
                        @endif

                        @if($task->status !== 'closed')
                            <form action="{{ route('tasks.update', $task) }}" method="POST" class="contents">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="closed">
                                <button type="submit"
                                    class="col-span-1 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all">Close</button>
                            </form>
                        @endif

                        @if(Auth::user()->isAdmin() || $task->created_by == Auth::id())
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="contents"
                                onsubmit="return confirm('Abort this task?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all">Purge</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div
                    class="h-20 w-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-6 shadow-inner">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">Queue is Empty</h3>
                <p class="text-slate-500 font-bold">No active tasks found in the implementation buffer.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $tasks->links() }}
    </div>
@endsection