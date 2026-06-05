<nav x-data="{ open: false }" class="bg-white/60 backdrop-blur-xl shadow-sm border border-white/40 rounded-3xl flex-shrink-0 sm:w-56 w-full sm:min-h-[calc(100vh-3rem)]">
    <!-- Desktop Sidebar -->
    <div class="hidden sm:flex sm:flex-col h-full">
        <!-- Logo -->
        <div class="h-20 flex items-center px-6">
            <a href="{{ route('dashboard') }}" class="text-xl font-extrabold text-gray-800 tracking-tight flex items-center">
                <span>Gudang</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 px-3 py-2 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-xs font-semibold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-teal-600 to-teal-800 text-white shadow-md shadow-teal-500/30 rounded-xl' : 'text-teal-900/60 hover:bg-white/50 hover:text-teal-900 rounded-xl' }}">
                <svg class="mr-3 h-4 w-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-teal-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-2.5 text-xs font-semibold transition-all duration-300 {{ request()->routeIs('categories.*') ? 'bg-gradient-to-r from-teal-600 to-teal-800 text-white shadow-md shadow-teal-500/30 rounded-xl' : 'text-teal-900/60 hover:bg-white/50 hover:text-teal-900 rounded-xl' }}">
                <svg class="mr-3 h-4 w-4 {{ request()->routeIs('categories.*') ? 'text-white' : 'text-teal-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a2 2 0 012-2z"/>
                </svg>
                Kategori
            </a>

            <a href="{{ route('suppliers.index') }}" class="flex items-center px-4 py-2.5 text-xs font-semibold transition-all duration-300 {{ request()->routeIs('suppliers.*') ? 'bg-gradient-to-r from-teal-600 to-teal-800 text-white shadow-md shadow-teal-500/30 rounded-xl' : 'text-teal-900/60 hover:bg-white/50 hover:text-teal-900 rounded-xl' }}">
                <svg class="mr-3 h-4 w-4 {{ request()->routeIs('suppliers.*') ? 'text-white' : 'text-teal-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
                Supplier
            </a>

            <a href="{{ route('items.index') }}" class="flex items-center px-4 py-2.5 text-xs font-semibold transition-all duration-300 {{ request()->routeIs('items.*') ? 'bg-gradient-to-r from-teal-600 to-teal-800 text-white shadow-md shadow-teal-500/30 rounded-xl' : 'text-teal-900/60 hover:bg-white/50 hover:text-teal-900 rounded-xl' }}">
                <svg class="mr-3 h-4 w-4 {{ request()->routeIs('items.*') ? 'text-white' : 'text-teal-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Data Barang
            </a>

            <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-2.5 text-xs font-semibold transition-all duration-300 {{ request()->routeIs('transactions.*') ? 'bg-gradient-to-r from-teal-600 to-teal-800 text-white shadow-md shadow-teal-500/30 rounded-xl' : 'text-teal-900/60 hover:bg-white/50 hover:text-teal-900 rounded-xl' }}">
                <svg class="mr-3 h-4 w-4 {{ request()->routeIs('transactions.*') ? 'text-white' : 'text-teal-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Transaksi
            </a>
        </div>

        <!-- User Settings (Bottom of Sidebar) -->
        <div class="p-4 relative" x-data="{ userOpen: false }" @click.outside="userOpen = false">
            <button @click="userOpen = !userOpen" class="flex items-center justify-between w-full p-2 text-xs font-medium text-teal-900 hover:bg-white/50 rounded-xl transition ease-in-out duration-150 relative z-10">
                <div class="flex items-center">
                    <div class="h-6 w-6 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold mr-2 text-[10px]">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 text-left truncate">{{ Auth::user()->name }}</div>
                </div>
                <div class="ml-1">
                    <svg class="fill-current h-3 w-3 text-teal-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <!-- Dropdown Menu (Pops UP) -->
            <div x-show="userOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="absolute bottom-full left-0 w-full mb-2 px-4 z-50"
                 style="display: none;">
                <div class="bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-1 overflow-hidden">
                    <x-dropdown-link :href="route('profile.edit')" class="text-xs hover:bg-teal-50">
                        {{ __('Profile') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" class="text-xs text-red-600 hover:bg-red-50"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation (Top Bar & Hamburger) -->
    <div class="sm:hidden flex justify-between h-14 px-4">
        <div class="shrink-0 flex items-center">
            <a href="{{ route('dashboard') }}" class="font-bold text-gray-800 flex items-center text-sm">
                Gudang
            </a>
        </div>
        <div class="-mr-2 flex items-center">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-white/50 focus:outline-none transition duration-150 ease-in-out">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Responsive Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden absolute z-50 w-full bg-white/90 backdrop-blur-xl border-b border-white/40 shadow-lg">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-xs">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-xs">
                {{ __('Kategori') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')" class="text-xs">
                {{ __('Supplier') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')" class="text-xs">
                {{ __('Data Barang') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')" class="text-xs">
                {{ __('Transaksi') }}
            </x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4 flex items-center">
                <div class="h-8 w-8 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold text-sm mr-3">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-xs">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="text-xs text-red-600"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
