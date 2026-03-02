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
        $query = Notification::query();
        if (!auth()->user()->isAdminPusat()) {
            $query->where('user_id', auth()->id());
        }

        $this->notifications = $query->latest()->take(5)->get();

        $countQuery = Notification::where('is_read', false);
        if (!auth()->user()->isAdminPusat()) {
            $countQuery->where('user_id', auth()->id());
        }
        $this->unreadCount = $countQuery->count();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && (auth()->user()->isAdminPusat() || $notification->user_id === auth()->id())) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        $query = Notification::where('is_read', false);
        if (!auth()->user()->isAdminPusat()) {
            $query->where('user_id', auth()->id());
        }
        
        $query->update([
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