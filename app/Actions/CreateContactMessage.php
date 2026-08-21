<?php

namespace App\Actions;

use App\Mail\AdminContactMessageReceived;
use App\Models\ContactMessage;
use App\Support\Settings;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CreateContactMessage
{
    public function __construct(private readonly Settings $settings) {}

    public function handle(array $data): ContactMessage
    {
        $message = ContactMessage::create(collect($data)->except('website')->all());

        try {
            $email = $this->settings->get('notifications.contact_admin_email');
            if ($this->settings->get('notifications.contact_admin_enabled', false) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($email)->queue(new AdminContactMessageReceived($message));
            }
        } catch (Throwable $exception) {
            report($exception);
        }

        return $message;
    }
}
