<x-guest-layout>
    <div class="h-full flex items-center justify-center p-4">
        <div
            class="w-full bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <!-- Decorative blur -->
            <div class="absolute -top-24 -left-24 h-48 w-48 bg-indigo-500/10 blur-[100px] rounded-full"></div>
            <div class="absolute -bottom-24 -right-24 h-48 w-48 bg-purple-500/10 blur-[100px] rounded-full"></div>

            <div class="relative z-10">
                <div class="text-center mb-10">
                    <div
                        class="h-28 w-60 mx-auto flex items-center justify-center transform hover:scale-110 transition-transform">
                        <x-application-logo class="w-full h-full fill-current" />
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">System Login</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 font-medium italic italic italic">Securely
                        enter the lead management platform.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 mb-2">Username
                            / Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white"
                            placeholder="your@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Secret
                                Access Key</label>
                            @if (Route::has('password.request'))
                                <a class="text-[9px] font-bold text-indigo-500 hover:underline uppercase tracking-widest"
                                    href="{{ route('password.request') }}">Recover Access</a>
                            @endif
                        </div>
                        <input type="password" name="password" required
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-indigo-500 transition-all font-mono"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <label for="remember_me" class="relative inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-5 w-5 rounded-lg border-slate-200 dark:border-slate-800 text-indigo-600 focus:ring-indigo-500 transition-all">
                            <span class="ms-3 text-xs font-bold text-slate-500 dark:text-slate-400">Maintain session
                                active</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all transform hover:-translate-y-1 group">
                        <span class="flex items-center justify-center gap-2">
                            Enter Workspace
                            <svg class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
