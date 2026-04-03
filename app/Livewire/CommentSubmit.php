<?php

namespace App\Livewire;

use App\Models\CommentList;
use Livewire\Component;

class CommentSubmit extends Component
{
    public $name;

    public $mobile;

    public $email;

    public $comment;

    public $allComments;

    public function render()
    {
        return view('livewire.comment-submit')->layout('layouts.app');
    }

    public function submitComment()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required_if:email,null|string|max:20|min:5',
            'email' => 'required_if:mobile,null|email|max:255',
            'comment' => 'required|string',
        ]);

        // Save the comment to the database
        CommentList::create([
            'name' => $this->name,
            'phone' => $this->mobile,
            'email' => $this->email,
            'comment' => $this->comment,
            'ip_address' => request()->ip(),
        ]);

        // Reset the form fields
        $this->reset();

        // Optionally, you can emit an event or show a success message
        session()->flash('message', 'Comment submitted successfully!');
    }

    // public function showComments()
    // {
    //     $this->allComments = CommentList::latest()->take(25)->get();
    // }
}
