<h1>Pesan kontak baru</h1>
<p><strong>Nama:</strong> {{ $contactMessage->name }}</p>
<p><strong>Email:</strong> {{ $contactMessage->email }}</p>
<p><strong>Telepon:</strong> {{ $contactMessage->phone ?: '—' }}</p>
<p><strong>Subjek:</strong> {{ $contactMessage->subject ?: 'Tanpa subjek' }}</p>
<p>{!! nl2br(e($contactMessage->message)) !!}</p>
<p><a href="{{ route('admin.messages.show', $contactMessage) }}">Buka pesan di admin</a></p>
