@extends('layouts.dashboard')

@section('title', 'Manage Holidays')

@section('content')
    <div class="mb-8 font-bold text-2xl text-slate-900">Manage Upcoming Holidays</div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg mb-4">Add Holiday</h3>
                <form action="{{ route('admin.holidays.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Holiday Title</label>
                        <input type="text" name="title" class="w-full rounded-xl border-slate-200" required
                            placeholder="e.g. Diwali">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Date</label>
                        <input type="date" name="date" class="w-full rounded-xl border-slate-200" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Location/Region</label>
                        <input type="text" name="locations" class="w-full rounded-xl border-slate-200" required
                            placeholder="e.g. Delhi NCR, Mumbai or All">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-2">Description (Optional)</label>
                        <textarea name="description" class="w-full rounded-xl border-slate-200" rows="3"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition">Save
                        Holiday</button>
                </form>
            </div>
        </div>
        <div class="col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="p-4 font-semibold text-sm text-slate-500 uppercase tracking-widest">Date</th>
                            <th class="p-4 font-semibold text-sm text-slate-500 uppercase tracking-widest">Holiday</th>
                            <th class="p-4 font-semibold text-sm text-slate-500 uppercase tracking-widest">Location</th>
                            <th class="p-4 text-right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holidays as $holiday)
                            <tr class="border-b border-slate-50 hover:bg-slate-50">
                                <td class="p-4 text-sm font-semibold">
                                    {{ \Carbon\Carbon::parse($holiday->date)->format('M d, Y') }}</td>
                                <td class="p-4 font-bold text-slate-900">{{ $holiday->title }}<br><span
                                        class="text-xs text-slate-400 font-normal">{{ $holiday->description }}</span></td>
                                <td class="p-4"><span
                                        class="px-2 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md uppercase">{{ $holiday->locations }}</span>
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.holidays.destroy', $holiday->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this holiday?');">
                                        @csrf @method('DELETE')
                                        <button class="text-rose-500 hover:text-rose-700 text-sm font-black">X</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-slate-500">No holidays recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($holidays->hasPages())
                    <div class="p-4 border-t border-slate-100">{{ $holidays->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection