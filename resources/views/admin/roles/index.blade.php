@extends('layouts.dashboard')

@section('title', 'Manage Roles')

@section('content')
    <div class="mb-8 flex justify-between items-center text-slate-900 font-bold text-2xl">
        Role Management
        <a href="{{ route('admin.roles.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Add
            New Role</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($roles as $role)
            <div class="bg-white p-6 rounded-lg border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">{{ $role->name }}</h3>
                    <span
                        class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs font-mono uppercase">{{ $role->slug }}</span>
                </div>
                <p class="text-sm text-slate-500 mb-6">{{ $role->description ?? 'No description provided.' }}</p>
                <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                    <span class="text-xs text-slate-400">{{ $role->users()->count() }} users assigned</span>
                    <div class="flex gap-2">
                        <button class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection