<?php

namespace App\Providers;

use App\Models\CertificationSchedule;
use App\Models\CertificationScheme;
use App\Models\Service;
use App\Models\TrainingProgram;
use App\Models\TrainingSchedule;
use App\Support\Settings;
use App\Support\WhatsApp;
use App\View\Components\AdminLayout;
use App\View\Components\PublicLayout;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Settings::class);
        $this->app->singleton(WhatsApp::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::anonymousComponentPath(resource_path('views/components'));
        Blade::component('layouts.admin', AdminLayout::class);
        Blade::component('layouts.public', PublicLayout::class);

        Relation::enforceMorphMap([
            'training' => TrainingProgram::class,
            'certification' => CertificationScheme::class,
            'service' => Service::class,
            'training_schedule' => TrainingSchedule::class,
            'certification_schedule' => CertificationSchedule::class,
        ]);
    }
}
