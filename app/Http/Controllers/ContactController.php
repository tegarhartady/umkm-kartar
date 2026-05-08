<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Change Honeypot to a more unique name to avoid browser autofill
        if ($request->filled('_hp_name')) {
            \Log::warning('Honeypot filled by bot', ['ip' => $request->ip()]);
            return back()->with('success', 'Pesan Anda telah berhasil dikirim. Terima kasih!');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            $message = ContactMessage::create($validated);
            return back()->with('success', 'Pesan Anda telah berhasil dikirim (ID: '.$message->id.'). Terima kasih!');
        } catch (\Exception $e) {
            \Log::error('Contact save error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }
}
