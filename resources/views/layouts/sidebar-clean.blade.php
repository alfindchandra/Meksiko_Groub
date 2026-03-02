<div class="pt-4 mt-auto border-t border-gray-200">
            <div x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" 
                 x-transition:enter="transition ease-out duration-200"
                 class="px-3 mb-2">
                <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Meksiko Clean</p>
            </div>
            
            <div class="space-y-1">
                @can('manage-clean')
                <a href="{{ route('meksikoclean.dashboard') }}" 
                   class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-xl group
                          {{ request()->routeIs('meksikoclean.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('meksikoclean.dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}" 
                         :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"/>
                    </svg>
                    <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Dashboard Clean</span>
                </a>
                @endcan

                @php
                    $cleanLinks = [
                        ['route' => 'meksikoclean.services.index', 'label' => 'Daftar Layanan', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ['route' => 'meksikoclean.partners.index', 'label' => 'Mitra Offline', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                        ['route' => 'meksikoclean.transactions.index', 'label' => 'Transaksi', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ];
                @endphp

                @foreach($cleanLinks as $link)
                    <a href="{{ route($link['route']) }}" 
                       class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-xl group
                              {{ request()->routeIs($link['route'] . '*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-100' }}">
                        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs($link['route'] . '*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-blue-600' }}" 
                             :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"></path>
                        </svg>
                        <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
    </div>