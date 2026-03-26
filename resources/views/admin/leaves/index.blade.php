@extends('layouts.dashboard')

@section('title', 'Manage Leaves')

@section('content')
    <div class="mb-10 flex justify-between items-end border-b border-slate-100 pb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tight">MANAGE LEAVE REQUESTS</h1>
            <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Review and update employee leave
                applications</p>
        </div>
    </div>

    <div class="mb-6">
        <form method="GET" action="{{ route('admin.leaves.index') }}" class="flex gap-3 text-sm font-normal items-center">
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-xl border-slate-200">
            <select name="role_id" class="rounded-xl border-slate-200">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}
                    </option>
                @endforeach
            </select>
            <select name="user_id" class="rounded-xl border-slate-200">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-lg hover:bg-indigo-700">Filter</button>
            @if(request()->hasAny(['date', 'role_id', 'user_id']))
                <a href="{{ route('admin.leaves.index') }}"
                    class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center text-slate-500 font-bold text-xs">
                                        {{ substr($leave->user->name, 0, 2) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $leave->user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ ucfirst($leave->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                {{ $leave->from_date->format('M d') }} - {{ $leave->to_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 truncate max-w-xs">{{ $leave->reason }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-semibold 
                                                            {{ $leave->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($leave->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($leave->status === 'pending')
                                    <div class="flex justify-end gap-x-2">
                                        <form action="{{ route('admin.leaves.updateStatus', $leave->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-900">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.leaves.updateStatus', $leave->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-rose-600 hover:text-rose-900">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">No actions available</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500 italic">No leave requests to
                                display.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection