<x-layouts.public title="Pilih kata sandi baru" robots="noindex, nofollow"><section class="mx-auto max-w-md px-4 py-20"><x-card title="Pilih kata sandi baru">
<form method="POST" action="{{ route('password.store') }}" class="space-y-5">@csrf
<input type="hidden" name="token" value="{{ $request->route('token') }}">
<x-form-input name="email" label="Alamat email" type="email" :value="old('email', $request->email)" required autocomplete="username" />
<x-form-input name="password" label="Kata sandi baru" type="password" required autocomplete="new-password" />
<x-form-input name="password_confirmation" label="Konfirmasi kata sandi baru" type="password" required autocomplete="new-password" />
<x-button type="submit" class="w-full">Atur ulang kata sandi</x-button></form>
</x-card></section></x-layouts.public>
