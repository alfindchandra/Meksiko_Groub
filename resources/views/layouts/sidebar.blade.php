<div x-show="sidebarOpen" class="fixed inset-0 z-40 flex lg:hidden" role="dialog" aria-modal="true" style="display: none;">
    <!-- Backdrop overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true" @click="sidebarOpen = false"></div>

    <!-- Sidebar wrapper -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="relative flex flex-col flex-1 w-full max-w-xs bg-white focus:outline-none pt-5 pb-4">
        
        <!-- Close button -->
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button type="button" @click="sidebarOpen = false" class="ml-1 flex items-center justify-center p-2 rounded-full ring-2 ring-white bg-gray-500/20 hover:bg-gray-500/50 focus:outline-none focus:ring-2 focus:ring-white focus:bg-gray-500 transition-colors">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 h-0 overflow-y-auto">
            @include('layouts.sidebar-content', ['isMobile' => true]) 
        </div>
    </div>
    
    <!-- Dummy element to force the sidebar to shrink to fit close icon -->
    <div class="flex-shrink-0 w-14" aria-hidden="true"></div>
</div>

<aside 
    class="hidden lg:flex lg:flex-col border-r border-gray-200 bg-white transition-all duration-300 ease-in-out"
    :class="sidebarMinimized ? 'w-20' : 'w-64'">
    <div class="flex-1 pt-5 pb-4 overflow-y-auto overflow-x-hidden">
        @include('layouts.sidebar-content', ['isMobile' => false])
    </div>
</aside>