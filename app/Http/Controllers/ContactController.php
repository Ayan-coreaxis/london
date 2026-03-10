<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:2000',
        ]);

        // Send email to admin
        try {
            Mail::send('emails.contact', [
                'contactName'    => $request->name,
                'contactEmail'   => $request->email,
                'contactSubject' => $request->subject,
                'contactMessage' => $request->message,
                'contactPhone'   => $request->phone ?? null,
            ], function ($m) use ($request) {
                $m->to(config('mail.admin_address', 'info@londoninstantprint.co.uk'))
                  ->subject('Contact Form: ' . $request->subject)
                  ->replyTo($request->email, $request->name);
            });
        } catch (\Exception $e) {
            // Log error silently, still show success to user
            \Illuminate\Support\Facades\Log::error('Contact mail error: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you! Your message has been sent. We\'ll get back to you within 24 hours.');
    }
}
