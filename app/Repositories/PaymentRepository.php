<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\Auth;

class PaymentRepository
{
    public function getTotalCountSuccess(): int
    {
        return Payment::query()
            ->whereIn('payment_status_id', [PaymentStatus::ACCEPTED_SUCCESSED, PaymentStatus::SUCCESSED])
            ->count();
    }

    public function getTotalCountFailed(): int
    {
        return Payment::query()
            ->whereNotIn('payment_status_id', [PaymentStatus::ACCEPTED_SUCCESSED, PaymentStatus::SUCCESSED])
            ->count();
    }

    public function getTotal(string $timeFrame): int
    {
        $isAdmin = Auth::user()->isAdmin();
        return Payment::query()
            ->where('created_at', '>=', $timeFrame)
            ->when(!$isAdmin, function($query) {
                $query->where('user_id', Auth::id());
            })->count();
    }

    public function getSuccess(string $timeFrame): int
    {
        $isAdmin = Auth::user()->isAdmin();
        return Payment::query()
            ->where('created_at', '>=', $timeFrame)
            ->when(!$isAdmin, function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereIn('payment_status_id', [
                PaymentStatus::ACCEPTED_SUCCESSED,
                PaymentStatus::SUCCESSED
            ])->count();
    }

    public function getTotalUser(string $timeFrame, int $userId): int
    {
        return Payment::query()
            ->where('created_at', '>=', $timeFrame)
            ->where('user_id', $userId)
            ->count();
    }

    public function getSuccessUser(string $timeFrame, int $userId): int
    {
        return Payment::query()
            ->where('created_at', '>=', $timeFrame)
            ->where('user_id', $userId)
            ->whereIn('payment_status_id', [
                PaymentStatus::ACCEPTED_SUCCESSED,
                PaymentStatus::SUCCESSED
            ])->count();
    }
}
