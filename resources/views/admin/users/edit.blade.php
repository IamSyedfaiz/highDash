@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}"
                class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl hover:bg-slate-50 transition shadow-sm text-slate-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Edit User</h1>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-4">
                    Personal Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase ml-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-4">
                    Assign Roles</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                        <label
                            class="relative flex flex-col items-center justify-center p-4 rounded-2xl border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition shadow-sm cursor-pointer group">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" @if(in_array($role->id, $userRoles))
                            checked @endif
                                class="h-5 w-5 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 transition-all">
                            <span
                                class="mt-2 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-tighter">{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div
                class="bg-amber-50 dark:bg-amber-900/10 rounded-3xl border border-amber-100 dark:border-amber-800 p-8 space-y-6">
                <h3
                    class="text-lg font-bold text-amber-900 dark:text-amber-200 border-b border-amber-100 dark:border-amber-800 pb-4">
                    Password (Optional)</h3>
                <p class="text-xs text-amber-700 dark:text-amber-500 italic">Leave blank if you don't want to change the
                    password.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-amber-600 uppercase ml-1">New Password</label>
                        <input type="password" name="password"
                            class="w-full bg-white dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-amber-500 transition-all shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-amber-600 uppercase ml-1">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full bg-white dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-amber-500 transition-all shadow-sm">
                    </div>
                </div>
            </div>

            <div
                class="flex justify-end gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <a href="{{ route('admin.users.index') }}"
                    class="px-8 py-3 text-slate-600 dark:text-slate-400 font-bold rounded-xl hover:bg-slate-100 transition">Discard</a>
                <button type="submit"
                    class="px-12 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
                    Save User Updates
                </button>
            </div>
        </form>
    </div>
@endsection