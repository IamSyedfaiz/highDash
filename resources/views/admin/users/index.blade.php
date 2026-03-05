@extends('layouts.dashboard')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-whiteTracking-tight">Team Management</h1>
            <p class="text-slate-500 dark:text-slate-400">Manage your workforce, roles, and system access.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Add New Member
        </a>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                <thead class="bg-slate-50/50 dark:bg-slate-800/30">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Employee
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Role &
                            Primary Email</th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Status
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Joined
                            Date</th>
                        <th class="px-8 py-5 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Manage
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @foreach($users as $user)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-indigo-900/5 transition-all">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black shadow-lg shadow-indigo-500/20 group-hover:scale-110 transition-transform">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">EID:
                                            #{{ 1000 + $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="space-y-1">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span
                                                class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">{{ $role->name }}</span>
                                        @endforeach
                                    </div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                                    <span class="h-1 w-1 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-sm text-slate-500 font-medium">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap text-right text-sm">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 transition shadow-sm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-400 hover:text-slate-900 dark:hover:text-white hover:border-slate-400 transition shadow-sm">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-8 py-5 bg-slate-50/50 dark:bg-slate-800/20 border-t border-slate-100 dark:border-slate-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection