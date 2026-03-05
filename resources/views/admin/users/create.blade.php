@extends('layouts.dashboard')

@section('title', 'Add New User')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Add New User</h1>
            <p class="text-slate-500">Create a new system user and assign their roles.</p>
        </div>

        <div class="bg-white shadow sm:rounded-lg border border-slate-100 p-8">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('name') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('email') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-slate-700 mb-2">Assign Roles</span>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($roles as $role)
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="role-{{ $role->id }}" name="roles[]" value="{{ $role->id }}" type="checkbox"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-slate-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="role-{{ $role->id }}"
                                            class="font-medium text-slate-700">{{ $role->name }}</label>
                                        <p class="text-slate-500 text-xs">{{ $role->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('roles') <p class="mt-1 text-sm text-rose-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end gap-x-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="text-sm font-semibold leading-6 text-slate-900 px-4 py-2">Cancel</a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection