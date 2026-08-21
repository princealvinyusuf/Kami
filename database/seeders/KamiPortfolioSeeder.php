<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;

class KamiPortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Portofolio Kegiatan 2024 - 2025',
                'category' => 'Pelatihan',
                'summary' => 'Dokumentasi kegiatan pelatihan dan pengembangan kompetensi periode 2024 - 2025.',
            ],
            [
                'title' => 'Portofolio Kegiatan Sertifikasi 2024 - 2025',
                'category' => 'Sertifikasi',
                'summary' => 'Dokumentasi fasilitasi asesmen dan sertifikasi kompetensi pada periode 2024 - 2025.',
            ],
            [
                'title' => 'Portofolio Kegiatan Kolaborasi 2024 - 2025',
                'category' => 'Kerja Sama',
                'summary' => 'Dokumentasi kegiatan kolaborasi dan pendampingan kelembagaan pada periode 2024 - 2025.',
            ],
        ];

        foreach ($items as $item) {
            Portfolio::query()->updateOrCreate(
                ['slug' => str($item['title'])->slug()],
                [
                    ...$item,
                    'event_date' => now()->toDateString(),
                    'description' => 'Konten portofolio awal bersumber dari company profile. Tambahkan foto dan detail acara melalui CMS.',
                    'status' => 'published',
                    'published_at' => now(),
                    'verification_status' => 'verified',
                    'verification_notes' => 'Judul portofolio mengacu pada slide portofolio kegiatan 2024 - 2025.',
                ],
            );
        }
    }
}
