<?php

namespace App\Livewire\Components;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showDropdown = false;

    protected $listeners = ['notificationCreated' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $this->unreadCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === auth()->id()) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.components.notification-bell');
    }
}