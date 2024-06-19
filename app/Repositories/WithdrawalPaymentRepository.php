<?php

namespace App\Repositories;

use App\Models\WithdrawalPayment;
use App\Models\WithdrawalPaymentStatus;

class WithdrawalPaymentRepository
{
    public function getTotalCountSuccess(): int
    {
        return WithdrawalPayment::query()
            ->where('withdrawal_payment_status_id', WithdrawalPaymentStatus::SUCCESSED)
            ->count();
    }

    public function getTotalCountFailed(): int
    {
        return WithdrawalPayment::query()
            ->where('withdrawal_payment_status_id', WithdrawalPaymentStatus::FAILED)
            ->count();
    }
}
