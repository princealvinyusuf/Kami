<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:create-admin {email? : Alamat email administrator} {--name= : Nama administrator}', function () {
    $email = mb_strtolower(trim((string) ($this->argument('email') ?: $this->ask('Alamat email'))));
    $name = trim((string) ($this->option('name') ?: $this->ask('Nama', 'Administrator')));
    $password = (string) $this->secret('Kata sandi (minimal 12 karakter)');
    $confirmation = (string) $this->secret('Ulangi kata sandi');

    $validator = Validator::make(compact('email', 'name', 'password'), [
        'email' => ['required', 'email:rfc', 'max:255'],
        'name' => ['required', 'string', 'max:120'],
        'password' => ['required', 'string', 'min:12'],
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    if (! hash_equals($password, $confirmation)) {
        $this->error('Konfirmasi kata sandi tidak cocok.');

        return 1;
    }

    if (User::query()->where('email', $email)->exists()) {
        $this->error('Email tersebut sudah terdaftar. Perintah ini tidak menaikkan hak akun yang ada.');

        return 1;
    }

    User::query()->create([
        'name' => $name,
        'email' => $email,
        'email_verified_at' => now(),
        'password' => Hash::make($password),
        'is_admin' => true,
    ]);

    $this->info('Administrator berhasil dibuat.');

    return 0;
})->purpose('Membuat akun administrator secara aman');
