<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        \Log::info('Contact form submission attempt', $validated);

        $msg = new ContactMessage();
        $msg->name = $validated['name'];
        $msg->email = $validated['email'];
        $msg->subject = $validated['subject'];
        $msg->message = $validated['message'];
        $msg->save();

        return back()->with('success', 'Pesan Anda telah berhasil dikirim. Terima kasih!');
    }
}
