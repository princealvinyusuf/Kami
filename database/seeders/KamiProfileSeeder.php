<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class KamiProfileSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'branding.site_name', 'value' => 'KAMI Kompeten', 'type' => 'string', 'group' => 'branding', 'is_public' => true],
            ['key' => 'company.legal_name', 'value' => 'Kompeten Akademi Muda Indonesia', 'type' => 'string', 'group' => 'company', 'is_public' => true],
            [
                'key' => 'company.description',
                'value' => 'KAMI Kompeten adalah lembaga pendidikan dan pelatihan vokasi yang menyiapkan talenta agar kompeten, profesional, dan siap bersaing di dunia industri melalui pelatihan berbasis kompetensi, sertifikasi nasional, serta pendampingan karier.',
                'type' => 'text',
                'group' => 'company',
                'is_public' => true,
            ],
            ['key' => 'contact.email', 'value' => 'kamikompeten@gmail.com', 'type' => 'string', 'group' => 'contact', 'is_public' => true],
            ['key' => 'contact.phone', 'value' => '085920449308 / 082345034242', 'type' => 'string', 'group' => 'contact', 'is_public' => true],
            ['key' => 'contact.whatsapp', 'value' => '085920449308', 'type' => 'string', 'group' => 'contact', 'is_public' => true],
            [
                'key' => 'contact.address',
                'value' => 'Jl. Merdeka Raya Karang Pule, Kel. Karang Pule, Kecamatan Sekarbela, Kota Mataram, NTB, Kode Pos 83116',
                'type' => 'text',
                'group' => 'contact',
                'is_public' => true,
            ],
            ['key' => 'social.instagram', 'value' => 'https://www.instagram.com/kamikompeten.id', 'type' => 'string', 'group' => 'contact', 'is_public' => true],
            ['key' => 'hero.eyebrow', 'value' => 'KAMI LATIH - KAMI KOMPETEN', 'type' => 'string', 'group' => 'presentation', 'is_public' => true],
            [
                'key' => 'hero.title',
                'value' => 'Solusi Meningkatkan Kompetensi & Sertifikasi Anda',
                'type' => 'string',
                'group' => 'presentation',
                'is_public' => true,
            ],
            [
                'key' => 'hero.description',
                'value' => 'Kami menghadirkan program pelatihan berbasis kebutuhan pasar kerja, penguatan soft skills dan hard skills, serta fasilitasi sertifikasi kompetensi untuk menyiapkan SDM yang siap kerja dan siap bersaing.',
                'type' => 'text',
                'group' => 'presentation',
                'is_public' => true,
            ],
            [
                'key' => 'footer.description',
                'value' => 'KAMI LATIH - KAMI KOMPETEN · Your Competent Career Partner.',
                'type' => 'text',
                'group' => 'presentation',
                'is_public' => true,
            ],
            ['key' => 'seo.default_title', 'value' => 'KAMI Kompeten | Pelatihan dan Sertifikasi Kompetensi', 'type' => 'string', 'group' => 'seo', 'is_public' => true],
            [
                'key' => 'seo.default_description',
                'value' => 'Kompeten Akademi Muda Indonesia menyediakan pelatihan vokasi, sertifikasi kompetensi melalui mitra LSP, konsultasi, serta pemagangan dan penempatan kerja.',
                'type' => 'text',
                'group' => 'seo',
                'is_public' => true,
            ],
            [
                'key' => 'legal.terms_summary',
                'value' => 'Sertifikasi kompetensi dilaksanakan sesuai ketentuan peraturan perundang-undangan melalui mekanisme dan mitra yang berlaku.',
                'type' => 'text',
                'group' => 'legal',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::query()->updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
