<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class LeadSettingsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['key' => 'contact.whatsapp', 'value' => null, 'type' => 'string', 'group' => 'contact'],
            ['key' => 'contact.whatsapp_message', 'value' => 'Halo, saya ingin mendapatkan informasi tentang {context}. {url}', 'type' => 'string', 'group' => 'contact'],
            ['key' => 'registration.collect_sensitive_fields', 'value' => 'false', 'type' => 'boolean', 'group' => 'registration'],
            ['key' => 'registration.require_sensitive_fields', 'value' => 'false', 'type' => 'boolean', 'group' => 'registration'],
            ['key' => 'registration.document_max_kb', 'value' => '5120', 'type' => 'integer', 'group' => 'registration'],
            ['key' => 'notifications.registration_admin_email', 'value' => null, 'type' => 'string', 'group' => 'notifications'],
            ['key' => 'notifications.registration_confirmation_enabled', 'value' => 'false', 'type' => 'boolean', 'group' => 'notifications'],
        ] as $setting) {
            Setting::query()->firstOrCreate(['key' => $setting['key']], [...$setting, 'is_public' => false]);
        }
    }
}
