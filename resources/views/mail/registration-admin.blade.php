<h1>Pendaftaran baru</h1>
<p>Pendaftaran baru telah diterima dan perlu ditindaklanjuti.</p>
<ul>
    <li>Nama: {{ $registration->name }}</li>
    <li>Jenis: {{ ucfirst($registration->registration_type) }}</li>
    <li>Email: {{ $registration->email }}</li>
    <li>WhatsApp/telepon: {{ $registration->phone }}</li>
</ul>
<p><a href="{{ route('admin.registrations.index') }}">Buka daftar pendaftaran</a></p>
