<?php

namespace App\Livewire;

use App\Models\NotificationLogs;
use Carbon\Carbon;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationListAll extends Component
{
    use WithoutUrlPagination, WithPagination;
    public $notificationCount = 0;

    public function markAsRead($id)
    {
        NotificationLogs::where('id', $id)->update([
            'read_by' => Auth::user()->name,
            'read_at' => Carbon::now(),
        ]);

        // Notification marked as read and updated in the database
        $this->loadNotifications();

        // Optionally, you can use a flash message to notify the user
        flash()->success('Notification marked as read');
    }

    public function render()
    {
       $notifications = NotificationLogs::latest()->paginate(10); // same as orderBy('created_at', 'desc')

        return view('livewire.notification-list-all', ['notifications' => $notifications])->layout('layouts.app');
    }
}