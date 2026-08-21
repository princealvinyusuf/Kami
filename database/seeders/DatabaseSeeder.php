<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TaxonomySeeder::class,
            LeadSettingsSeeder::class,
            KamiProfileSeeder::class,
            KamiServiceSeeder::class,
            KamiCertificationSeeder::class,
            KamiPartnerSeeder::class,
            KamiPortfolioSeeder::class,
            KamiLegalSeeder::class,
        ]);
    }
}
