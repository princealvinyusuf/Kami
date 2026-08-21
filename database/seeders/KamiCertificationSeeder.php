<?php

namespace Database\Seeders;

use App\Models\CertificationCategory;
use App\Models\CertificationScheme;
use App\Models\CompetencyUnit;
use Illuminate\Database\Seeder;

class KamiCertificationSeeder extends Seeder
{
    public function run(): void
    {
        $schemes = [
            ['category' => 'marketing', 'title' => 'Pemasaran Digital (Digital Marketing)'],
            ['category' => 'marketing', 'title' => 'Penyelia Media Sosial (Social Media Supervisor)'],
            ['category' => 'marketing', 'title' => 'Penulis Konten/Informasi (Copy Writer)'],
            ['category' => 'marketing', 'title' => 'Manajer Pemasaran Digital (Digital Marketing Manager)'],
            ['category' => 'marketing', 'title' => 'Tenaga Ahli Strategi Pemasaran (Marketing Strategist)'],

            ['category' => 'msdm-instruktur', 'title' => 'Staff SDM'],
            ['category' => 'msdm-instruktur', 'title' => 'Supervisor SDM'],
            ['category' => 'msdm-instruktur', 'title' => 'Manajer SDM'],
            ['category' => 'msdm-instruktur', 'title' => 'Instruktur Junior'],
            ['category' => 'msdm-instruktur', 'title' => 'Instruktur (Level 4)'],

            ['category' => 'domestic-worker', 'title' => 'Housekeeper'],
            ['category' => 'domestic-worker', 'title' => 'Babysitter'],
            ['category' => 'domestic-worker', 'title' => 'Family Cook'],
            ['category' => 'domestic-worker', 'title' => 'Caretaker'],

            ['category' => 'pariwisata', 'title' => 'Front Desk Attendant'],
            ['category' => 'pariwisata', 'title' => 'Hotel Room Preparation'],
            ['category' => 'pariwisata', 'title' => 'Tour Guide'],
            ['category' => 'pariwisata', 'title' => 'Barista'],
            ['category' => 'pariwisata', 'title' => 'Bartender'],

            ['category' => 'komunikasi-broadcasting', 'title' => 'Public Relations Officer'],
            ['category' => 'komunikasi-broadcasting', 'title' => 'Reporter'],
            ['category' => 'komunikasi-broadcasting', 'title' => 'Producer'],
            ['category' => 'komunikasi-broadcasting', 'title' => 'TV Director / Sutradara'],
            ['category' => 'komunikasi-broadcasting', 'title' => 'Anchorperson'],

            ['category' => 'umkm', 'title' => 'Penjualan Ritel melalui Platform Digital'],
            ['category' => 'umkm', 'title' => 'Pendamping UMKM Muda'],
            ['category' => 'umkm', 'title' => 'Kurator UMKM'],
            ['category' => 'umkm', 'title' => 'Fasilitator Pendidikan dan Pelatihan UMKM'],

            ['category' => 'desain-informatika', 'title' => 'Junior Graphic Designer'],
            ['category' => 'desain-informatika', 'title' => 'Junior Web Programmer'],
            ['category' => 'desain-informatika', 'title' => 'Junior Mobile Programmer'],
            ['category' => 'desain-informatika', 'title' => 'Programmer'],
            ['category' => 'desain-informatika', 'title' => 'System Administrator'],

            ['category' => 'administrasi-perkantoran', 'title' => 'Pengelolaan Perkantoran Digital'],
            ['category' => 'administrasi-perkantoran', 'title' => 'Staff Administrasi'],
            ['category' => 'administrasi-perkantoran', 'title' => 'Sekretaris'],
            ['category' => 'administrasi-perkantoran', 'title' => 'Administrasi Proyek'],

            ['category' => 'public-relation-k3', 'title' => 'Public Speaking'],
            ['category' => 'public-relation-k3', 'title' => 'Pelayanan Pelanggan (Service Excellent)'],
            ['category' => 'public-relation-k3', 'title' => 'Operator Keselamatan & Kesehatan Kerja Umum'],
            ['category' => 'public-relation-k3', 'title' => 'Ahli K3 Listrik'],

            ['category' => 'teknologi-informatika', 'title' => 'IT Project Manager'],
            ['category' => 'teknologi-informatika', 'title' => 'IT Auditor'],
            ['category' => 'teknologi-informatika', 'title' => 'Network Security Analyst'],
            ['category' => 'teknologi-informatika', 'title' => 'Full Stack Developer'],
            ['category' => 'teknologi-informatika', 'title' => 'UI/UX Designer'],

            ['category' => 'lingkungan-hidup', 'title' => 'Penanggung Jawab Pengendalian Pencemaran Air (PPPA)'],
            ['category' => 'lingkungan-hidup', 'title' => 'Penanggung Jawab Pengendalian Pencemaran Udara (PPPU)'],
            ['category' => 'lingkungan-hidup', 'title' => 'Pemantauan Pengelolaan Limbah B3 (PPLB3)'],
            ['category' => 'lingkungan-hidup', 'title' => 'Penanggung Jawab Operasional Pengolahan Air Limbah (POPAL)'],

            ['category' => 'pertanian-tanaman-organik', 'title' => 'Penyuluh Pertanian Fasilitator'],
            ['category' => 'pertanian-tanaman-organik', 'title' => 'Fasilitator Organik Tanaman'],
            ['category' => 'pertanian-tanaman-organik', 'title' => 'Manajer Produksi'],
            ['category' => 'pertanian-tanaman-organik', 'title' => 'Operator Benih Tanaman Organik'],
        ];

        foreach ($schemes as $item) {
            $category = CertificationCategory::query()->where('slug', $item['category'])->first();
            if (! $category) {
                continue;
            }

            $scheme = CertificationScheme::query()->updateOrCreate(
                ['slug' => str($item['title'])->slug()],
                [
                    'certification_category_id' => $category->id,
                    'title' => $item['title'],
                    'summary' => 'Skema sertifikasi profesi sesuai bidang kompetensi yang tercantum pada profil lembaga.',
                    'description' => 'Skema ini difasilitasi melalui kerja sama LSP mitra sesuai standar BNSP dan ketentuan yang berlaku.',
                    'assessment_method' => 'Asesmen kompetensi berbasis skema',
                    'status' => 'published',
                    'published_at' => now(),
                    'verification_status' => 'verified',
                    'verification_notes' => 'Nama skema diambil dari PROFIL KAMI KOMPETEN.',
                ],
            );

            CompetencyUnit::query()->firstOrCreate(
                [
                    'certification_scheme_id' => $scheme->id,
                    'code' => 'UNIT-01',
                ],
                [
                    'title' => 'Unit kompetensi menyesuaikan dokumen skema resmi',
                    'description' => 'Detail elemen unit dapat diperbarui admin berdasarkan dokumen skema dari LSP mitra.',
                    'sort_order' => 1,
                ],
            );
        }
    }
}
