@extends('layouts.dashboard')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ tab: 'kra' }">
        <div class="mb-4 flex gap-4">
            <button @click="tab = 'kra'"
                :class="tab === 'kra' ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-500/20' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'"
                class="px-8 py-3 rounded-2xl font-black uppercase tracking-widest text-xs transition-all">
                Key Result Areas (KRAs)
            </button>
            <button @click="tab = 'targets'"
                :class="tab === 'targets' ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-500/20' : 'bg-white text-slate-500 hover:bg-slate-50 border border-slate-200'"
                class="px-8 py-3 rounded-2xl font-black uppercase tracking-widest text-xs transition-all">
                Monthly Set Targets
            </button>
        </div>

        <!-- KRA TAB -->
        <div x-show="tab === 'kra'" class="space-y-6">
            <div
                class="mb-8 flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">User KRAs <span
                            class="text-indigo-600 text-lg ml-2 border-l-2 border-slate-200 dark:border-slate-700 pl-2">Key
                            Result Areas</span></h1>
                    <p class="text-slate-500 mt-1">Manage performance requirements and metrics for each user.</p>
                </div>
                <button onclick="document.getElementById('addKRAModal').classList.remove('hidden')"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
                    + Assign KRA
                </button>
            </div>

            <!-- Filter by User -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-800">
                <form action="{{ route('admin.kras.index') }}" method="GET" class="flex gap-4">
                    <select name="user_id"
                        class="w-1/3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                                ({{ count($user->roles) ? $user->roles[0]->name : 'No Role' }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition">
                        Filter
                    </button>
                    @if(request('user_id'))
                        <a href="{{ route('admin.kras.index') }}"
                            class="px-6 py-3 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition">Clear</a>
                    @endif
                </form>
            </div>

            <!-- KRAs List -->
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800 relative z-10 w-full mb-12">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse whitespace-nowrap table-auto">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Assigned User</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">KRA Title</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Description</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Target Value</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($kras as $kra)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition duration-150">
                                    <td class="p-4 font-bold text-slate-800 dark:text-slate-200">
                                        {{ $kra->user->name ?? 'Unknown User' }}
                                    </td>
                                    <td class="p-4 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $kra->title }}
                                    </td>
                                    <td class="p-4 text-sm text-slate-500 w-1/3 truncate max-w-xs">
                                        {{ $kra->description ?? 'No description' }}
                                    </td>
                                    <td class="p-4 font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ intval($kra->target_value) }}
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                onclick="openEditModal({{ $kra->id }}, '{{ addslashes($kra->title) }}', '{{ addslashes($kra->description) }}', {{ intval($kra->target_value) }})"
                                                class="p-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg transition-colors group">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.kras.destroy', $kra->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this KRA completely?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-rose-600 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-lg transition-colors group">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500 font-medium">No KRAs found. Use the
                                        button
                                        above to assign one.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TARGETS TAB -->
        <div x-show="tab === 'targets'" class="space-y-6" style="display: none;">
            <div
                class="mb-8 flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Monthly Targets</h1>
                    <p class="text-slate-500 mt-1">Assign strict revenue goals for each sales pipeline member.</p>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Employee</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Target
                                Amount (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                <td class="p-4 font-bold text-slate-800 dark:text-slate-200 flex items-center gap-3">
                                    <div
                                        class="h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    {{ $user->name }}
                                </td>
                                <td class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest">
                                    {{ count($user->roles) ? $user->roles[0]->name : 'None' }}
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.kras.update_target', $user->id) }}" method="POST"
                                        class="flex items-center justify-end gap-2">
                                        @csrf
                                        <input type="number" min="0" name="sales_target_amount"
                                            value="{{ intval($user->sales_target_amount) }}"
                                            class="w-32 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-right"
                                            placeholder="0">
                                        <button type="submit"
                                            class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg font-bold text-xs uppercase tracking-widest transition">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addKRAModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Assign KRA</h3>
                <button onclick="document.getElementById('addKRAModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('admin.kras.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">User *</label>
                    <select name="user_id" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">KRA Title *</label>
                    <input type="text" name="title" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Target Value *</label>
                    <input type="number" min="0" name="target_value" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('addKRAModal').classList.add('hidden')"
                        class="px-5 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/20 transition">Save
                        KRA</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editKRAModal"
        class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800 dark:text-white">Edit KRA</h3>
                <button onclick="document.getElementById('editKRAModal').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_kra_id">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">KRA Title *</label>
                    <input type="text" id="edit_title" name="title" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Target Value *</label>
                    <input type="number" min="0" id="edit_target_value" name="target_value" required
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Description</label>
                    <textarea id="edit_description" name="description" rows="3"
                        class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('editKRAModal').classList.add('hidden')"
                        class="px-5 py-2 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition">Cancel</button>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-xl shadow-indigo-500/20 transition">Update
                        KRA</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, title, description, target) {
            document.getElementById('edit_kra_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_target_value').value = target;
            document.getElementById('editForm').action = '/admin/kras/' + id;

            document.getElementById('editKRAModal').classList.remove('hidden');
        }
    </script>
@endsection