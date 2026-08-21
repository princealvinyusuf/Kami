<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContentStatus;
use App\Http\Controllers\Controller;
use App\Models\CertificationScheme;
use App\Models\ContactMessage;
use App\Models\Portfolio;
use App\Models\Registration;
use App\Models\Trainer;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        abort_unless((bool) $request->user()?->is_admin, 403);

        return view('admin.dashboard', [
            'counts' => [
                'Program pelatihan' => TrainingProgram::count(),
                'Pendaftaran' => Registration::count(),
                'Skema sertifikasi' => CertificationScheme::count(),
                'Trainer' => Trainer::count(),
                'Program aktif/terbit' => TrainingProgram::query()->where('status', ContentStatus::Published)->where('published_at', '<=', now())->count(),
                'Pertanyaan baru' => ContactMessage::query()->where('status', 'new')->count(),
                'Portofolio' => Portfolio::count(),
            ],
            'recentRegistrations' => Registration::query()->with('registrable')->latest()->limit(8)->get(),
            'recentMessages' => ContactMessage::query()->latest()->limit(8)->get(),
        ]);
    }
}
