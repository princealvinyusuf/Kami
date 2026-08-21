<?php

namespace App\Http\Controllers;

use App\Actions\CreateRegistration;
use App\Http\Requests\StoreRegistrationRequest;
use App\Services\RegistrationTargetResolver;
use App\Support\Settings;
use App\Support\WhatsApp;
use Illuminate\Http\Request;
use Throwable;

class RegistrationController extends Controller
{
    public function create(
        Request $request,
        RegistrationTargetResolver $resolver,
        Settings $settings,
        WhatsApp $whatsApp,
    ) {
        $type = (string) $request->route('type');
        $slug = $request->route('slug');
        $target = $resolver->resolve($type, $slug);
        $schedules = collect();

        if ($target && in_array($type, ['training', 'certification'], true)) {
            $target->load(['schedules' => fn ($query) => $query
                ->where('status', 'scheduled')
                ->where('starts_at', '>=', now())
                ->orderBy('starts_at')]);
            $schedules = $target->schedules;
        }

        $title = $resolver->title($target, $type);

        return view('public.registrations.create', [
            'type' => $type,
            'typeLabel' => $resolver->label($type),
            'target' => $target,
            'title' => $title,
            'schedules' => $schedules,
            'collectSensitive' => (bool) $settings->get('registration.collect_sensitive_fields', false),
            'requireSensitive' => (bool) $settings->get('registration.require_sensitive_fields', false),
            'whatsappUrl' => $whatsApp->url($title, $request->url()),
        ]);
    }

    public function store(
        StoreRegistrationRequest $request,
        RegistrationTargetResolver $resolver,
        CreateRegistration $action,
    ) {
        $type = (string) $request->route('type');
        $slug = $request->route('slug');
        $target = $resolver->resolve($type, $slug);

        try {
            $action->handle($request->validated(), $type, $target, $request->file('document'));
        } catch (Throwable $exception) {
            report($exception);

            return back()->withInput()->with('error', 'Pendaftaran belum dapat dikirim. Silakan coba lagi atau hubungi kami melalui WhatsApp.');
        }

        return back()->with(
            'success',
            'Pendaftaran berhasil dikirim. Tim kami akan menghubungi Anda melalui WhatsApp atau email untuk proses selanjutnya.'
        );
    }
}
