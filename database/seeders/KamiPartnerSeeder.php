<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class KamiPartnerSeeder extends Seeder
{
    public function run(): void
    {
        $institutionPartners = [
            'LKP BERTUMBUH SUKSES GEMILANG',
            'LPK BERSAMA SUKSES GEMILANG',
            'BICARA OFFICIAL INDONESIA',
            'LPK LOMBOK GLOBAL EDUCATION',
            'LPK KARYA JAYA ABADI',
            'LPK LOMBOK INTERNATIONAL SOLUTIONS',
            'LPK CENTRAL CREATIVE GLOBAL',
            'HIKMAH INSPIRASI NUSANTARA',
            'LPK NISKALA ANNIYAH ACASHA',
            'LKP NISKALA ANNIYAH ACASHA',
            'LPK ACARYA INDONESIA',
            'BICARA KOMPETEN INDONESIA',
            'KAMI KOMPETEN MEDAN',
            'SPEAKMUDA ACADEMY',
            'KAMI KOMPETEN GORONTALO',
            'LKP PAZ GENERASI UNGGUL',
            'LPK PAZ GENERASI UNGGUL',
        ];

        foreach ($institutionPartners as $index => $name) {
            Partner::query()->updateOrCreate(
                ['slug' => str($name)->slug()],
                [
                    'name' => $name,
                    'type' => 'mitra-lembaga',
                    'sort_order' => $index,
                    'description' => 'Lembaga yang disebut dalam profil sebagai institusi dampingan pendirian.',
                    'is_active' => true,
                    'status' => 'published',
                    'published_at' => now(),
                    'verification_status' => 'verified',
                    'verification_notes' => 'Bersumber dari daftar lembaga dampingan pada PROFIL KAMI KOMPETEN.',
                ],
            );
        }

        Partner::query()->updateOrCreate(
            ['slug' => 'lsp-edutrain'],
            [
                'name' => 'LSP EDUTRAIN',
                'type' => 'mitra-lsp',
                'sort_order' => 0,
                'description' => 'Mitra LSP yang tercantum secara eksplisit pada profil lembaga.',
                'is_active' => true,
                'status' => 'published',
                'published_at' => now(),
                'verification_status' => 'verified',
                'verification_notes' => 'Disebutkan secara eksplisit pada slide mitra LSP.',
            ],
        );
    }
}
