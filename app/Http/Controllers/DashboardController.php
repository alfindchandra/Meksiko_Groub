<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();

        return view('components.notifications', compact('notifications'));
    }
}
