<li>
    <a href="{{ route('dashboard') }}"
        class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>
        Dashboard
    </a>
</li>

<li>
    <a href="{{ route('attendance.index') }}"
        class="{{ request()->routeIs('attendance.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-black leading-6 transition-all duration-200 uppercase tracking-tight">
        <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Attendance & Logs
    </a>
</li>

@if (!Auth::user()->hasRole('technical') || Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
    <li>
        <a href="{{ route('leads.index') }}"
            class="{{ request()->routeIs('leads.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            Lead Management
        </a>
    </li>

    <li>
        <a href="{{ route('leads.create') }}"
            class="{{ request()->routeIs('leads.create') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200 tracking-tight">
            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Lead
        </a>
    </li>
@endif
@if (!Auth::user()->hasRole('calling') || Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
    <li>
        <a href="{{ route('tasks.index') }}"
            class="{{ request()->routeIs('tasks.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 18 4.5H6a2.25 2.25 0 0 0-2.25 2.25v12.75A2.25 2.25 0 0 0 6 21h12" />
            </svg>
            Daily Tasks
        </a>
    </li>
@endif

@if (
        (Auth::user()->hasRole('calling') || Auth::user()->hasRole('technical')) &&
        !Auth::user()->isAdmin() &&
        !Auth::user()->hasRole('manager')
    )
    <li>
        <a href="{{ route('performance.index') }}"
            class="{{ request()->routeIs('performance.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
            <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
            </svg>
            My Performance
        </a>
    </li>
@endif

@if (Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
    <li>
        <div class="text-xs font-semibold leading-6 text-slate-400 px-3 mt-4 mb-2 uppercase tracking-wider">
            Administration
        </div>
        <ul role="list" class="-mx-2 space-y-1">
            <li>
                <a href="{{ route('admin.leads.allocation') }}"
                    class="{{ request()->routeIs('admin.leads.allocation') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a5.97 5.97 0 0 0-.942 3.197M12 10.5a3.375 3.375 0 1 0 0-6.75 3.375 3.375 0 0 0 0 6.75ZM20.125 10.5a2.625 2.625 0 1 0 0-5.25 2.625 2.625 0 0 0 0 5.25ZM6.75 10.5a2.625 2.625 0 1 0 0-5.25 2.625 2.625 0 0 0 0 5.25Z" />
                    </svg>
                    Lead Allocation
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}"
                    class="{{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    User Management
                </a>
            </li>
            <li>
                <a href="{{ route('admin.roles.index') }}"
                    class="{{ request()->routeIs('admin.roles.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                    Roles & Permissions
                </a>
            </li>
            <li>
                <a href="{{ route('admin.leaves.index') }}"
                    class="{{ request()->routeIs('admin.leaves.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M12 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    Leave Management
                </a>
            </li>
        </ul>
    </li>
@endif

@if (Auth::user()->isAdmin() || Auth::user()->hasRole('manager'))
    <li>
        <div class="text-xs font-semibold leading-6 text-slate-400 px-3 mt-4 mb-2 uppercase tracking-wider">Reports
        </div>
        <ul role="list" class="-mx-2 space-y-1">
            <li>
                <a href="{{ route('admin.reports.index') }}"
                    class="{{ request()->routeIs('admin.reports.index') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 18 4.5H6a2.25 2.25 0 0 0-2.25 2.25v12.75A2.25 2.25 0 0 0 6 21h12" />
                    </svg>
                    Attendance Audit
                </a>
            </li>
            <li>
                <a href="{{ route('follow_ups.index') }}"
                    class="{{ request()->routeIs('follow_ups.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Follow Ups
                </a>
            </li>
            <li>
                <a href="{{ route('performance.index') }}"
                    class="{{ request()->routeIs('performance.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                    </svg>
                    Activity Report
                </a>
            </li>
            <li>
                <a href="{{ route('admin.holidays.index') }}"
                    class="{{ request()->routeIs('admin.holidays.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400' : 'text-slate-700 hover:text-indigo-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }} group flex gap-x-3 rounded-xl p-2.5 text-sm font-semibold leading-6 transition-all duration-200">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    Holidays
                </a>
            </li>
        </ul>
    </li>
@endif

<li class="mt-8">
    <div
        class="text-[10px] font-black leading-6 text-slate-400 px-3 uppercase tracking-widest mb-2 flex items-center justify-between">
        <span>Upcoming Holidays</span>
        <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
    </div>
    @php
        $upcomingHolidays = \App\Models\Holiday::where('date', '>=', now())->orderBy('date')->take(5)->get();
    @endphp
    @if ($upcomingHolidays->count() > 0)
        <ul role="list" class="-mx-2 space-y-2 px-2">
            @foreach ($upcomingHolidays as $hol)
                <li
                    class="bg-indigo-50/50 dark:bg-indigo-900/10 rounded-xl p-3 border border-indigo-100 dark:border-indigo-800 backdrop-blur-sm">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-indigo-900 dark:text-indigo-100">{{ $hol->title }}</span>
                        <span
                            class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400">{{ \Carbon\Carbon::parse($hol->date)->format('M d, Y') }}
                            &middot; {{ $hol->locations }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="px-3 text-xs text-slate-400 font-medium italic">No upcoming holidays.</div>
    @endif
</li>