<?php

namespace App\Support;

class WhatsApp
{
    public function __construct(private readonly Settings $settings) {}

    public function url(?string $context = null, ?string $pageUrl = null): ?string
    {
        $number = preg_replace('/\D+/', '', (string) $this->settings->get('contact.whatsapp'));

        if (! $number) {
            return null;
        }

        if (str_starts_with($number, '0')) {
            $number = '62'.substr($number, 1);
        }
        if (! preg_match('/^[1-9][0-9]{7,14}$/', $number)) {
            return null;
        }

        $template = (string) $this->settings->get(
            'contact.whatsapp_message',
            'Halo, saya ingin mendapatkan informasi tentang {context}. {url}'
        );

        $message = trim(strtr($template, [
            '{context}' => $context ?: 'layanan yang tersedia',
            '{url}' => $pageUrl ?: '',
        ]));

        return 'https://wa.me/'.$number.'?text='.rawurlencode($message);
    }
}
