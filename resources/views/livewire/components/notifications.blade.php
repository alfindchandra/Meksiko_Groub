<div x-data="notificationManager()" 
     @notify.window="addNotification($event.detail)"
     class="fixed top-4 right-4 z-50 space-y-3"
     style="max-width: 24rem;">
    
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="flex items-center p-4 rounded-lg shadow-lg"
             :class="{
                 'bg-green-50 border border-green-200': notification.type === 'success',
                 'bg-red-50 border border-red-200': notification.type === 'error',
                 'bg-blue-50 border border-blue-200': notification.type === 'info',
                 'bg-yellow-50 border border-yellow-200': notification.type === 'warning'
             }">
            
            <!-- Icon -->
            <div class="flex-shrink-0">
                <template x-if="notification.type === 'success'">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="notification.type === 'error'">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="notification.type === 'info'">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="notification.type === 'warning'">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </template>
            </div>
            
            <!-- Message -->
            <div class="ml-3 flex-1">
                <p class="text-sm font-medium"
                   :class="{
                       'text-green-800': notification.type === 'success',
                       'text-red-800': notification.type === 'error',
                       'text-blue-800': notification.type === 'info',
                       'text-yellow-800': notification.type === 'warning'
                   }"
                   x-text="notification.message"></p>
            </div>
            
            <!-- Close Button -->
            <button @click="removeNotification(notification.id)"
                    class="ml-4 flex-shrink-0"
                    :class="{
                        'text-green-600 hover:text-green-800': notification.type === 'success',
                        'text-red-600 hover:text-red-800': notification.type === 'error',
                        'text-blue-600 hover:text-blue-800': notification.type === 'info',
                        'text-yellow-600 hover:text-yellow-800': notification.type === 'warning'
                    }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>

<script>
function notificationManager() {
    return {
        notifications: [],
        nextId: 1,
        
        addNotification(detail) {
            const id = this.nextId++;
            const notification = {
                id,
                type: detail.type || 'info',
                message: detail.message || 'Notification',
                show: true
            };
            
            this.notifications.push(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                this.removeNotification(id);
            }, 5000);
        },
        
        removeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index !== -1) {
                this.notifications[index].show = false;
                setTimeout(() => {
                    this.notifications.splice(index, 1);
                }, 300);
            }
        }
    }
}
</script>