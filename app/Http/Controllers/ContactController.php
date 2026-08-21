<?php

namespace App\Http\Controllers;

use App\Actions\CreateContactMessage;
use App\Http\Requests\StoreContactMessageRequest;
use Throwable;

class ContactController extends Controller
{
    public function create()
    {
        return view('public.contact');
    }

    public function store(StoreContactMessageRequest $request, CreateContactMessage $action)
    {
        try {
            $action->handle($request->validated());
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->with('error', 'Pesan belum dapat dikirim. Silakan coba lagi atau hubungi kami melalui WhatsApp.');
        }

        return back()->with('success', 'Pesan Anda telah diterima. Terima kasih telah menghubungi kami.');
    }
}
