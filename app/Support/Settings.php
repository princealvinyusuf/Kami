<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Settings
{
    public const DEFINITIONS = [
        'branding' => [
            'label' => 'Branding',
            'fields' => [
                'branding.logo' => ['label' => 'Logo', 'type' => 'image'],
                'branding.favicon' => ['label' => 'Favicon', 'type' => 'image'],
                'branding.site_name' => ['label' => 'Nama situs', 'type' => 'string', 'verify' => true],
            ],
        ],
        'company' => [
            'label' => 'Informasi Perusahaan',
            'fields' => [
                'company.legal_name' => ['label' => 'Nama badan hukum', 'type' => 'string', 'verify' => true],
                'company.registration_number' => ['label' => 'Nomor registrasi/NIB', 'type' => 'string', 'verify' => true],
                'company.description' => ['label' => 'Deskripsi perusahaan', 'type' => 'text', 'verify' => true],
            ],
        ],
        'contact' => [
            'label' => 'Kontak, Sosial, dan Lokasi',
            'fields' => [
                'contact.email' => ['label' => 'Email publik', 'type' => 'email', 'verify' => true],
                'contact.phone' => ['label' => 'Telepon', 'type' => 'string', 'verify' => true],
                'contact.whatsapp' => ['label' => 'WhatsApp', 'type' => 'string', 'verify' => true],
                'contact.whatsapp_message' => ['label' => 'Templat pesan WhatsApp', 'type' => 'text'],
                'contact.address' => ['label' => 'Alamat', 'type' => 'text', 'verify' => true],
                'contact.maps_url' => ['label' => 'URL peta', 'type' => 'url', 'verify' => true],
                'social.instagram' => ['label' => 'Instagram', 'type' => 'url', 'verify' => true],
                'social.linkedin' => ['label' => 'LinkedIn', 'type' => 'url', 'verify' => true],
                'social.youtube' => ['label' => 'YouTube', 'type' => 'url', 'verify' => true],
            ],
        ],
        'presentation' => [
            'label' => 'Tajuk dan Kaki Halaman',
            'fields' => [
                'hero.eyebrow' => ['label' => 'Teks kecil tajuk', 'type' => 'string'],
                'hero.title' => ['label' => 'Judul tajuk', 'type' => 'string'],
                'hero.description' => ['label' => 'Deskripsi tajuk', 'type' => 'text'],
                'footer.description' => ['label' => 'Deskripsi kaki halaman', 'type' => 'text'],
                'footer.copyright' => ['label' => 'Teks hak cipta', 'type' => 'string', 'verify' => true],
            ],
        ],
        'seo' => [
            'label' => 'SEO dan Analitik',
            'fields' => [
                'seo.default_title' => ['label' => 'Judul SEO bawaan', 'type' => 'string'],
                'seo.default_description' => ['label' => 'Deskripsi SEO bawaan', 'type' => 'text'],
                'analytics.google_id' => ['label' => 'Google Analytics ID', 'type' => 'analytics'],
                'analytics.meta_pixel_id' => ['label' => 'Meta Pixel ID', 'type' => 'analytics'],
            ],
        ],
        'notifications' => [
            'label' => 'Notifikasi Email',
            'fields' => [
                'notifications.registration_admin_email' => ['label' => 'Email penerima pendaftaran', 'type' => 'email', 'verify' => true],
                'notifications.registration_confirmation_enabled' => ['label' => 'Kirim konfirmasi pendaftaran', 'type' => 'boolean'],
                'notifications.contact_admin_email' => ['label' => 'Email penerima pesan kontak', 'type' => 'email', 'verify' => true],
                'notifications.contact_admin_enabled' => ['label' => 'Notifikasi pesan kontak', 'type' => 'boolean'],
            ],
        ],
        'registration' => [
            'label' => 'Konfigurasi Pendaftaran',
            'fields' => [
                'registration.collect_sensitive_fields' => ['label' => 'Kumpulkan data sensitif', 'type' => 'boolean'],
                'registration.require_sensitive_fields' => ['label' => 'Wajibkan data sensitif', 'type' => 'boolean'],
                'registration.document_max_kb' => ['label' => 'Batas dokumen (KB)', 'type' => 'integer'],
            ],
        ],
        'legal' => [
            'label' => 'Informasi Legal',
            'fields' => [
                'legal.privacy_summary' => ['label' => 'Ringkasan privasi', 'type' => 'text', 'verify' => true],
                'legal.terms_summary' => ['label' => 'Ringkasan ketentuan', 'type' => 'text', 'verify' => true],
                'legal.responsible_person' => ['label' => 'Penanggung jawab', 'type' => 'string', 'verify' => true],
            ],
        ],
    ];

    public function get(string $key, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable('settings')) {
                return $default;
            }

            return Cache::remember("setting.{$key}", now()->addHour(), function () use ($key, $default) {
                $setting = Setting::query()->where('key', $key)->first();
                if (! $setting) {
                    return $default;
                }

                return match ($setting->type) {
                    'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOL),
                    'integer' => (int) $setting->value,
                    'json' => json_decode($setting->value, true) ?? $default,
                    default => $setting->value,
                };
            });
        } catch (Throwable) {
            return $default;
        }
    }

    public function put(string $key, mixed $value, string $type, string $group, bool $public = false): void
    {
        $stored = $type === 'boolean' ? ($value ? 'true' : 'false') : ($type === 'json' ? json_encode($value) : $value);
        Setting::query()->updateOrCreate(['key' => $key], [
            'value' => $stored,
            'type' => $type,
            'group' => $group,
            'is_public' => $public,
        ]);
        Cache::forget("setting.{$key}");
    }
}
