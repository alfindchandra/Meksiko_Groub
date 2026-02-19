<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4">
    <div class="flex items-center">
        <button @click="window.innerWidth < 1024 ? sidebarOpen = true : sidebarMinimized = !sidebarMinimized" 
                class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>
    <!-- Page Title -->
    <div class="hidden lg:block">
        <h2 class="text-xl font-semibold text-gray-800">
            @yield('page-title', 'Dashboard')
        </h2>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center space-x-4">
        <!-- Search (Global) -->
        <div class="relative hidden md:block">
            <input type="text" 
                   placeholder="Cari produk, transfer..." 
                   class="w-64 px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
            <svg class="absolute w-5 h-5 text-gray-400 top-2.5 right-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <!-- Notifications -->
        @livewire('components.NotificationBell')

        <!-- User Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-xs font-semibold text-primary-600">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </span>
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 class="absolute right-0 z-50 w-48 mt-2 bg-white rounded-lg shadow-lg">
                <div class="px-4 py-3 border-b">
                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>
                <div class="py-1">
                 
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        @method('GET')
                        <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-red-50">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>