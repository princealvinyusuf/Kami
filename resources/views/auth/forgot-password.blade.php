<x-layouts.public title="Atur ulang kata sandi administrator" robots="noindex, nofollow"><section class="mx-auto max-w-md px-4 py-20"><x-card title="Atur ulang kata sandi">
<p class="mb-6 text-sm text-neutral-600">Masukkan alamat email administrator untuk meminta tautan pengaturan ulang yang aman.</p>
@if(session('status'))<p class="mb-4 rounded bg-green-100 p-3 text-sm text-green-800">{{ session('status') }}</p>@endif
<form method="POST" action="{{ route('password.email') }}" class="space-y-5">@csrf<x-form-input name="email" label="Alamat email" type="email" required autocomplete="email" autofocus /><x-button type="submit" class="w-full">Kirim tautan pengaturan ulang</x-button></form>
</x-card></section></x-layouts.public>
