<div x-show="sidebarOpen" class="fixed inset-0 z-40 flex lg:hidden">
    <div class="relative flex flex-col flex-1 w-full max-w-xs bg-white focus:outline-none">
        <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            @include('layouts.sidebar-content', ['isMobile' => true]) 
        </div>
    </div>
</div>

<aside 
    class="hidden lg:flex lg:flex-col border-r border-gray-200 bg-white transition-all duration-300 ease-in-out"
    :class="sidebarMinimized ? 'w-20' : 'w-64'">
    <div class="flex-1 pt-5 pb-4 overflow-y-auto overflow-x-hidden">
        @include('layouts.sidebar-content', ['isMobile' => false])
    </div>
</aside>