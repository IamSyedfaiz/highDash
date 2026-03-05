@extends('layouts.dashboard')

@section('title', 'Manage Users')

@section('content')
    <div class="mb-8 flex justify-between items-center text-slate-900 font-bold text-2xl">
        User Management
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Add
            New User</a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Roles</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($user->roles as $role)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 mr-1">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="text-slate-600 hover:text-slate-900">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
@endsection