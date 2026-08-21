<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use App\Models\CertificationCategory;
use App\Models\Service;
use App\Models\TrainingCategory;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Pelatihan Umum', 'Keterampilan Teknis', 'Pengembangan Profesional'] as $i => $name) {
            TrainingCategory::query()->firstOrCreate(['slug' => str($name)->slug()], ['name' => $name, 'sort_order' => $i]);
        }
        foreach (['Sertifikasi Umum', 'Sertifikasi Profesi'] as $i => $name) {
            CertificationCategory::query()->firstOrCreate(['slug' => str($name)->slug()], ['name' => $name, 'sort_order' => $i]);
        }
        foreach (['Berita', 'Wawasan', 'Pengumuman'] as $name) {
            ArticleCategory::query()->firstOrCreate(['slug' => str($name)->slug()], ['name' => $name]);
        }
        foreach (['Pelatihan' => 'training', 'Sertifikasi' => 'certification', 'Konsultasi' => 'konsultan'] as $name => $type) {
            Service::query()->firstOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name, 'type' => $type, 'sort_order' => array_search($name, ['Pelatihan', 'Sertifikasi', 'Konsultasi'], true)]
            );
        }
    }
}
