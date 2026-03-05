@extends('layouts.dashboard')

@section('title', 'Apply for Leave')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Apply for Leave</h1>
            <p class="text-slate-500">Please provide details for your leave request.</p>
        </div>

        <div class="bg-white shadow sm:rounded-lg border border-slate-100 p-6 sm:p-10">
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-700">Leave Type</label>
                        <select id="type" name="type"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="casual">Casual Leave</option>
                            <option value="sick">Sick Leave</option>
                            <option value="annual">Annual Leave</option>
                            <option value="maternity/paternity">Maternity / Paternity</option>
                            <option value="unpaid">Unpaid Leave</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="from_date" class="block text-sm font-medium text-slate-700">From Date</label>
                            <input type="date" name="from_date" id="from_date" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-md">
                        </div>
                        <div>
                            <label for="to_date" class="block text-sm font-medium text-slate-700">To Date</label>
                            <input type="date" name="to_date" id="to_date" required
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-md">
                        </div>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-slate-700">Reason</label>
                        <textarea id="reason" name="reason" rows="4" required
                            class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-slate-300 rounded-md"
                            placeholder="Briefly describe your reason for leave..."></textarea>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-x-4">
                        <a href="{{ route('leaves.index') }}"
                            class="text-sm font-semibold leading-6 text-slate-900">Cancel</a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection