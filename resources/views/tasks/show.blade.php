@extends('layouts.dashboard')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('tasks.index') }}" class="p-2 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-500 hover:bg-slate-50 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <span class="px-4 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full text-[10px] font-black uppercase tracking-widest">{{ $task->status }}</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $task->title }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] border border-slate-200 dark:border-slate-800 shadow-xl">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Task Description</h3>
                <div class="text-slate-700 dark:text-slate-300 font-medium leading-relaxed">
                    {!! nl2br(e($task->description)) !!}
                </div>
                
                @if($task->url)
                    <div class="mt-8 pt-8 border-t border-slate-50 dark:border-slate-800">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Technical Reference</p>
                        <a href="{{ $task->url }}" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-black hover:underline uppercase tracking-tight text-sm">
                            Open Implementation Site
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Update Status Form -->
            <div class="bg-white dark:bg-slate-900 p-10 rounded-[3rem] border border-slate-200 dark:border-slate-800 shadow-xl">
                <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8">Push Update</h3>
                <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-6">
                    @csrf @method('PATCH')
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Work Status</label>
                        <div class="grid grid-cols-3 gap-4 mt-2">
                            @foreach(['pending', 'started', 'closed'] as $status)
                                <label class="relative flex items-center justify-center p-4 border-2 rounded-2xl cursor-pointer transition-all {{ $task->status === $status ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-slate-100 dark:border-slate-800 hover:border-slate-200' }}">
                                    <input type="radio" name="status" value="{{ $status }}" class="sr-only" {{ $task->status === $status ? 'checked' : '' }}>
                                    <span class="text-xs font-black uppercase tracking-widest {{ $task->status === $status ? 'text-indigo-600' : 'text-slate-400' }}">{{ $status }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Progress Log (Public Detail)</label>
                        <textarea name="comment" rows="4" placeholder="Describe the work done or challenges encountered..."
                            class="w-full mt-2 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-medium"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-slate-900 dark:bg-indigo-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:opacity-90 transition-all shadow-xl shadow-indigo-500/10">Commit Status Update</button>
                </form>
            </div>
        </div>

        <!-- Sidebar / Meta -->
        <div class="space-y-8">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 dark:border-slate-800 pb-4">Personnel</h3>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 font-black shadow-inner">
                            {{ substr($task->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest leading-none mb-1">Assigned To</p>
                            <p class="text-sm font-black text-slate-900 dark:text-white tracking-tight">{{ $task->user->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 font-black shadow-inner">
                            {{ substr($task->creator->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest leading-none mb-1">Created By</p>
                            <p class="text-sm font-black text-slate-900 dark:text-white tracking-tight">{{ $task->creator->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-50 dark:border-slate-800 pb-4">Activity Stream</h3>
                <div class="space-y-6 relative before:absolute before:left-2 before:top-2 before:bottom-2 before:w-px before:bg-slate-100 dark:before:bg-slate-800">
                    @forelse($activities as $activity)
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-1.5 h-4 w-4 rounded-full bg-white dark:bg-slate-900 border-4 border-indigo-500"></div>
                            <p class="text-[10px] text-slate-500 font-bold mb-1 leading-relaxed">{{ $activity->description }}</p>
                            <p class="text-[8px] text-slate-400 font-black uppercase tracking-widest">{{ $activity->created_at->format('M d, H:i') }}</p>
                        </div>
                    @empty
                        <p class="text-slate-400 italic text-[10px] font-black uppercase tracking-widest text-center py-4">Buffer clean</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
