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
        $trainingCategories = [
            'Digital Marketing',
            'MSDM & Instruktur',
            'Komunikasi & Public Speaking',
            'Teknologi Informatika',
            'Desain Grafis & Multimedia',
            'Administrasi Perkantoran',
            'Pariwisata & Hospitality',
            'UMKM & Kewirausahaan',
            'K3 & Lingkungan Hidup',
            'Pertanian & Tanaman Organik',
            'Pengembangan Profesional',
        ];

        foreach ($trainingCategories as $i => $name) {
            TrainingCategory::query()->updateOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name, 'sort_order' => $i, 'is_active' => true],
            );
        }

        $certificationCategories = [
            'Marketing',
            'MSDM & Instruktur',
            'Domestic Worker',
            'Pariwisata',
            'Komunikasi & Broadcasting',
            'UMKM',
            'Desain & Informatika',
            'Administrasi Perkantoran',
            'Public Relation & K3',
            'Teknologi Informatika',
            'Lingkungan Hidup',
            'Pertanian & Tanaman Organik',
        ];

        foreach ($certificationCategories as $i => $name) {
            CertificationCategory::query()->updateOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name, 'sort_order' => $i, 'is_active' => true],
            );
        }

        foreach (['Tips Karier', 'Pelatihan', 'Sertifikasi', 'Dunia Kerja', 'Kompetensi', 'Internship', 'Digital Skills'] as $name) {
            ArticleCategory::query()->updateOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name, 'is_active' => true],
            );
        }

        foreach ([
            ['name' => 'Provider Training', 'type' => 'training'],
            ['name' => 'Sertifikasi Profesi', 'type' => 'certification'],
            ['name' => 'Konsultan', 'type' => 'konsultan'],
            ['name' => 'Pemagangan & Internship', 'type' => 'pemagangan'],
            ['name' => 'Placement & Job Matching', 'type' => 'pemagangan'],
        ] as $i => $service) {
            Service::query()->updateOrCreate(
                ['slug' => str($service['name'])->slug()],
                [
                    'name' => $service['name'],
                    'type' => $service['type'],
                    'sort_order' => $i,
                    'status' => 'published',
                    'published_at' => now(),
                    'verification_status' => 'verified',
                ],
            );
        }
    }
}
