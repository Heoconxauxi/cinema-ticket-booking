<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function contact()
    {
        return view('pages.contact');
    }

    public function sendContact(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'fullname.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'subject.required' => 'Vui lòng nhập tiêu đề.',
            'message.required' => 'Vui lòng nhập tin nhắn.',
        ]);

        // Gửi email hoặc lưu DB tùy bạn (demo)
        // Mail::to('admin@cinema.local')->send(new ContactMail($validated));

        return back()->with('success', 'Tin nhắn của bạn đã được gửi thành công!');
    }
}
