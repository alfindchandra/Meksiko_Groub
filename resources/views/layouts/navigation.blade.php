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
        

        <!-- Notifications -->
      

        <!-- User Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 mr-4 focus:outline-none">
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                    <span class="text-xs font-semibold text-gray-600">
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