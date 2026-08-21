<x-layouts.public title="Masuk administrator" robots="noindex, nofollow">
<section class="mx-auto max-w-md px-4 py-20"><x-card title="Masuk administrator"><p class="mb-6 text-sm text-neutral-600">Halaman ini hanya untuk administrator yang berwenang.</p>
@if(session('status'))<p class="mb-4 rounded bg-green-100 p-3 text-sm text-green-800">{{ session('status') }}</p>@endif
<form method="POST" action="{{ route('login.store') }}" class="space-y-5">@csrf
<x-form-input name="email" label="Alamat email" type="email" required autocomplete="username" autofocus />
<x-form-input name="password" label="Kata sandi" type="password" required autocomplete="current-password" />
<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1" class="rounded border-neutral-300"> Ingat saya</label>
<x-button type="submit" class="w-full">Masuk</x-button>
<a href="{{ route('password.request') }}" class="block text-center text-sm underline hover:text-brand-gold">Lupa kata sandi?</a>
</form></x-card></section>
</x-layouts.public>
