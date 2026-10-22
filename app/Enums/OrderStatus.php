<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PendingPayment = 'PENDING_PAYMENT';
    case WaitingVerification = 'WAITING_VERIFICATION';
    case Queued = 'QUEUED';
    case Processing = 'PROCESSING';
    case Ready = 'READY';
    case Completed = 'COMPLETED';
    case Cancelled = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::PendingPayment => 'Menunggu Pembayaran',
            self::WaitingVerification => 'Menunggu Verifikasi',
            self::Queued => 'Dalam Antrean',
            self::Processing => 'Diproses',
            self::Ready => 'Siap Diambil',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
        };
    }

    /**
     * Badge variant per DESIGN.md 11.5. Kept beside label() so the
     * status-to-style mapping stays server-side and testable.
     */
    public function badgeVariant(): string
    {
        return match ($this) {
            self::PendingPayment, self::WaitingVerification => 'warning',
            self::Processing => 'info',
            self::Ready => 'success',
            self::Queued, self::Completed => 'neutral',
            self::Cancelled => 'error',
        };
    }
}
