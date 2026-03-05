@extends('layouts.dashboard')

@section('title', 'My Leaves')

@section('content')
    <div class="mb-8 flex justify-between items-center text-slate-900 font-bold text-2xl">
        My Leave Requests
        <a href="{{ route('leaves.create') }}"
            class="btn btn-primary inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">Apply
            New</a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-slate-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                {{ ucfirst($leave->type) }}</td>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500 italic">You haven't applied for
                                any leaves yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection