@extends('layouts.dashboard')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.roles.index') }}"
                class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 transition shadow-sm text-slate-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Edit Role: {{ $role->name }}</h1>
        </div>

        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Basic Info -->
                <div class="lg:col-span-1 space-y-8">
                    <div
                        class="bg-white dark:bg-slate-900 p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Role Details</h3>
                        <div class="space-y-6">
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-500 uppercase ml-1">Role Name</label>
                                <input type="text" name="name" value="{{ old('name', $role->name) }}" required
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-500 uppercase ml-1">Description</label>
                                <textarea name="description" rows="4"
                                    class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all italic placeholder:text-slate-400"
                                    placeholder="Briefly describe what users with this role can do...">{{ old('description', $role->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-600 rounded-3xl p-8 shadow-2xl text-white">
                        <h4 class="text-lg font-bold mb-2">Important!</h4>
                        <p class="text-indigo-100 text-sm leading-relaxed mb-4">Editing this role will immediately affect
                            the permissions of all users currently assigned to it.</p>
                        <div class="text-[10px] font-mono bg-white/20 p-2 rounded-lg">Slug: {{ $role->slug }}</div>
                    </div>
                </div>

                <!-- Permissions Selection -->
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
                        <div
                            class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Active Permissions</h3>
                                <p class="text-xs text-slate-500">Check the boxes to enable specific capabilities for this
                                    role.</p>
                            </div>
                            <button type="button"
                                @click="document.querySelectorAll('.perm-check').forEach(c => c.checked = true)"
                                class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Select All</button>
                        </div>

                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($permissions as $permission)
                                    <label
                                        class="relative flex items-center p-4 rounded-2xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all cursor-pointer group">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                @if(in_array($permission->id, $rolePermissions)) checked @endif
                                                class="perm-check h-5 w-5 rounded-lg border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 transition-all bg-white dark:bg-slate-800">
                                        </div>
                                        <div class="ml-4 text-sm">
                                            <p
                                                class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-indigo-600 transition-colors uppercase tracking-tight">
                                                {{ str_replace('-', ' ', $permission->name) }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">{{ $permission->slug }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div
                            class="p-8 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-4">
                            <a href="{{ route('admin.roles.index') }}"
                                class="px-8 py-3 text-slate-600 dark:text-slate-400 font-bold rounded-xl hover:bg-slate-100 transition">Discard</a>
                            <button type="submit"
                                class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection