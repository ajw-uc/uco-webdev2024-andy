<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    function send_email(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Preview isi email
        return new NewsletterMailable();

        // Kirim email
        // Mail::to($request->email)->send(new NewsletterMailable());
        // return redirect()->route('home');
    }
}
