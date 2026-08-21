<?php

namespace Database\Seeders;

use App\Models\LegalDocument;
use Illuminate\Database\Seeder;

class KamiLegalSeeder extends Seeder
{
    public function run(): void
    {
        $documents = [
            [
                'title' => 'Dasar Hukum Sertifikasi Kompetensi',
                'type' => 'regulasi',
                'body' => "Ringkasan dasar hukum yang dicantumkan dalam profil lembaga:\n\n1. Undang-Undang Nomor 13 Tahun 2003 tentang Ketenagakerjaan (Pasal 18 ayat 2).\n2. Peraturan Pemerintah Nomor 10 Tahun 2018 tentang BNSP.\n3. Undang-Undang Nomor 12 Tahun 2012 tentang Pendidikan Tinggi (Pasal 44 ayat 1-3).",
            ],
            [
                'title' => 'Peran KAMI Kompeten dalam Sertifikasi',
                'type' => 'kebijakan',
                'body' => "KAMI Kompeten berperan sebagai penyelenggara pelatihan dan fasilitator asesmen/sertifikasi melalui kerja sama dengan LSP mitra sesuai skema yang berlaku.\n\nCatatan: penerbitan sertifikat kompetensi mengacu pada ketentuan dan otoritas lembaga terkait sesuai peraturan perundang-undangan.",
            ],
        ];

        foreach ($documents as $document) {
            LegalDocument::query()->updateOrCreate(
                ['slug' => str($document['title'])->slug()],
                [
                    ...$document,
                    'status' => 'published',
                    'published_at' => now(),
                    'verification_status' => 'verified',
                    'verification_notes' => 'Diringkas dari konten legal pada PROFIL KAMI KOMPETEN.',
                ],
            );
        }
    }
}
