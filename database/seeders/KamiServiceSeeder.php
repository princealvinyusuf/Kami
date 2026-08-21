<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class KamiServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Provider Training',
                'type' => 'training',
                'summary' => 'Program pelatihan berbasis kompetensi untuk peningkatan keterampilan kerja yang relevan dengan kebutuhan industri.',
                'description' => "Layanan mencakup pelatihan berbasis kompetensi, pelatihan bergelar non-akademik, pelatihan terintegrasi sertifikasi, layanan tes kompetensi tertentu, serta penguatan keterampilan kerja melalui kurikulum adaptif.\n\nCatatan verifikasi: detail tiap layanan dan mitra spesifik dapat diperbarui admin sesuai dokumen pendukung terbaru.",
            ],
            [
                'name' => 'Sertifikasi Profesi',
                'type' => 'certification',
                'summary' => 'Fasilitasi asesmen dan sertifikasi kompetensi melalui skema yang berlaku bersama mitra LSP.',
                'description' => "KAMI Kompeten memfasilitasi proses sertifikasi kompetensi sesuai standar BNSP melalui kerja sama dengan LSP mitra lintas sektor.\n\nPeran KAMI pada layanan ini adalah fasilitator pelatihan, pendampingan, dan pelaksanaan asesmen di TUK sesuai skema yang berlaku.",
            ],
            [
                'name' => 'Konsultan',
                'type' => 'konsultan',
                'summary' => 'Pendampingan perkantoran, legalitas lembaga, perpajakan, UMKM, dan pengembangan kelembagaan pelatihan/sertifikasi.',
                'description' => "Unit usaha konsultan berfokus pada pendirian dan pengembangan lembaga seperti LPK, LKP, LSP, asosiasi/perkumpulan, dan badan usaha lainnya, termasuk pendampingan dokumen persyaratan dan percepatan proses pendirian.\n\nLayanan juga mencakup pendampingan fasilitator akreditasi, UMKM, kewirausahaan, pajak, dan digital marketing.",
            ],
            [
                'name' => 'Pemagangan & Internship',
                'type' => 'pemagangan',
                'summary' => 'Program magang terstruktur untuk membangun pengalaman kerja nyata, kompetensi profesional, dan kesiapan karier.',
                'description' => "Program mencakup Magang Berdampak, Magang Luar Negeri, Link & Match Industri, serta Placement & Job Matching.\n\nSetiap program dirancang untuk menyelaraskan kompetensi peserta dengan kebutuhan pasar kerja melalui pendampingan dan evaluasi berbasis industri.",
            ],
            [
                'name' => 'Placement & Job Matching',
                'type' => 'pemagangan',
                'summary' => 'Layanan penempatan dan pencocokan kerja berbasis kompetensi.',
                'description' => 'Menghubungkan lulusan dengan perusahaan mitra melalui proses seleksi terukur dan pendampingan karier profesional.',
            ],
        ];

        foreach ($services as $index => $service) {
            Service::query()->updateOrCreate(
                ['slug' => str($service['name'])->slug()],
                [
                    ...$service,
                    'sort_order' => $index,
                    'status' => 'published',
                    'published_at' => now(),
                    'verification_status' => 'verified',
                    'verification_notes' => 'Konten bersumber dari PROFIL KAMI KOMPETEN.',
                ],
            );
        }
    }
}
