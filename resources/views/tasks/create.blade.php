@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex justify-between items-end border-b border-slate-200 pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">Create Tasks
                </h1>
                <p class="text-slate-500 font-medium text-sm mt-1">Add multiple task entries for a specific day</p>
            </div>
            <a href="{{ route('tasks.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-700">Cancel & Go
                Back</a>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST"
            class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-100"
            x-data="{ tasks: [{ title: '', description: '', urls: [''] }] }">
            @csrf

            <!-- Common Date & User settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Task Date</label>
                    <input type="date" name="task_date" value="{{ date('Y-m-d') }}"
                        class="w-full rounded-xl border-slate-200 bg-white font-bold">
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Assignee</label>
                        <select name="user_id" class="w-full rounded-xl border-slate-200 bg-white font-bold">
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ $u->id == Auth::id() ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <!-- Dynamic Tasks List -->
            <div class="space-y-8">
                <template x-for="(task, taskIndex) in tasks" :key="taskIndex">
                    <div
                        class="bg-white border-2 border-slate-100 p-6 rounded-2xl space-y-5 relative shadow-sm hover:border-indigo-100 transition-colors">
                        <button type="button" @click="tasks.splice(taskIndex, 1)" x-show="tasks.length > 1"
                            class="absolute top-4 right-4 bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white rounded-full w-8 h-8 flex items-center justify-center font-bold transition-all shadow-sm"
                            title="Remove Entry">
                            &times;
                        </button>

                        <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-4 bg-indigo-50 inline-block px-3 py-1 rounded-lg"
                            x-text="'Task Entry #' + (taskIndex + 1)"></div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold">Title <span class="text-rose-500">*</span></label>
                            <input type="text" x-bind:name="'tasks[' + taskIndex + '][title]'" required
                                placeholder="E.g., Client meeting, Website deployment..."
                                class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500 font-medium">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold">Description</label>
                            <textarea x-bind:name="'tasks[' + taskIndex + '][description]'" rows="2"
                                placeholder="Provide extra context here..."
                                class="w-full rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm"></textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">Reference URLs</label>
                            <div class="space-y-3">
                                <template x-for="(url, urlIndex) in task.urls" :key="urlIndex">
                                    <div class="flex gap-2 relative group">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                        </div>
                                        <input type="url" x-bind:name="'tasks[' + taskIndex + '][urls][' + urlIndex + ']'"
                                            x-model="task.urls[urlIndex]" placeholder="https://example.com"
                                            class="w-full pl-9 rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500 text-sm">

                                        <button type="button" @click="task.urls.splice(urlIndex, 1)"
                                            x-show="task.urls.length > 1"
                                            class="px-3 bg-slate-100 text-slate-400 rounded-xl hover:bg-rose-500 hover:text-white font-bold transition-all shadow-sm">&times;</button>
                                    </div>
                                </template>
                            </div>
                            <button type="button" @click="task.urls.push('')"
                                class="mt-3 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 flex items-center gap-1 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 4v16m8-8H4" />
                                </svg> Add URL Space
                            </button>
                        </div>
                    </div>
                </template>

                <button type="button" @click="tasks.push({ title: '', description: '', urls: [''] })"
                    class="w-full py-5 border-2 border-dashed border-slate-300 rounded-2xl text-slate-500 hover:text-indigo-600 hover:border-indigo-400 hover:bg-indigo-50 transition-all font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg> Append Another Task Entry
                </button>
            </div>

            <div class="mt-8 flex justify-end gap-4 border-t border-slate-100 pt-6">
                <a href="{{ route('tasks.index') }}"
                    class="px-6 py-3 font-semibold text-slate-500 hover:text-slate-700">Cancel</a>
                <button type="submit"
                    class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition">Create
                    Task</button>
            </div>
        </form>
    </div>
@endsection