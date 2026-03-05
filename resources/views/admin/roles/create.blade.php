@extends('layouts.dashboard')

@section('title', 'Create Role')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 font-bold text-2xl text-slate-900">
            Create New Role
        </div>

        <div class="bg-white shadow sm:rounded-lg border border-slate-100 p-8">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Role Name</label>
                        <input type="text" name="name" id="name" required placeholder="e.g. Moderator"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('name') <p class="mt-1 text-sm text-pink-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end gap-x-4">
                        <a href="{{ route('admin.roles.index') }}"
                            class="text-sm font-semibold leading-6 text-slate-900 px-4 py-2">Cancel</a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            Create Role
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection