<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Verified = 'verified';
    case WaitingPayment = 'waiting_payment';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Baru',
            self::Contacted => 'Sudah dihubungi',
            self::Verified => 'Terverifikasi',
            self::WaitingPayment => 'Menunggu pembayaran',
            self::Confirmed => 'Terkonfirmasi',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }
}
