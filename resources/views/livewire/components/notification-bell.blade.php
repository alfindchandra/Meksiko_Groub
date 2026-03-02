<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <!-- Bell Icon -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        @if($unreadCount > 0)
        <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full">
            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
        </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 z-50 w-80 mt-2 bg-white rounded-lg shadow-xl"
         style="display: none;">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
            @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="text-xs text-primary-600 hover:text-primary-700">
                Tandai semua dibaca
            </button>
            @endif
        </div>

        <!-- Notification List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
            <div wire:click="markAsRead({{ $notification->id }})"
                 class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors
                        {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        @if($notification->type === 'low_stock')
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        @elseif(str_contains($notification->type, 'transfer'))
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        @else
                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                        <p class="text-xs text-gray-600 mt-1">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notification->is_read)
                    <div class="flex-shrink-0">
                        <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="mt-2 text-sm text-gray-500">Tidak ada notifikasi</p>
            </div>
            @endforelse
        </div>
        <!-- <div class="px-4 py-3 border-t border-gray-100">
            <a href="{{ route('notifications.all') }}" class="text-sm text-primary-600 hover:text-primary-700">
                Lihat semua notifikasi
            </a>
        </div> -->
        
    </div>
</div>