@extends('layouts.dashboard')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Role Management</h1>
        <p class="text-slate-500 dark:text-slate-400">Define system access levels and permissions.</p>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
        Create New Role
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($roles as $role)
    <div class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-2xl transition-all group relative overflow-hidden">
        <div class="absolute -right-4 -top-4 text-slate-50 dark:text-slate-800 font-black text-8xl transition-colors group-hover:text-indigo-50/50 dark:group-hover:text-indigo-900/10">{{ substr($role->name, 0, 1) }}</div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-6">
                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $role->slug }}</span>
                <a href="{{ route('admin.roles.edit', $role->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </a>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">{{ $role->name }}</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 line-clamp-2 h-10">{{ $role->description ?? 'Standard system role with predefined permissions.' }}</p>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between text-xs font-bold text-slate-400 uppercase tracking-widest">
                    <span>Permissions</span>
                    <span class="text-indigo-600">{{ $role->permissions->count() }} active</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($role->permissions->take(3) as $permission)
                    <span class="px-2 py-1 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-md text-[10px] whitespace-nowrap">{{ str_replace('-', ' ', $permission->slug) }}</span>
                    @endforeach
                    @if($role->permissions->count() > 3)
                    <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-md text-[10px]">+{{ $role->permissions->count() - 3 }} more</span>
                    @endif
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex -space-x-2">
                    @foreach($role->users->take(4) as $u)
                    <img class="h-8 w-8 rounded-full ring-2 ring-white dark:ring-slate-900" src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                    @endforeach
                    @if($role->users->count() > 4)
                    <div class="h-8 w-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold text-slate-500 ring-2 ring-white dark:ring-slate-900">+{{ $role->users->count() - 4 }}</div>
                    @endif
                </div>
                <span class="text-xs font-bold text-slate-400">{{ $role->users->count() }} Users</span>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection