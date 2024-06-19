<?php

namespace App\Services;

use App\Repositories\WithdrawalPaymentRepository;

class WithdrawalPaymentService
{
    public function __construct(protected WithdrawalPaymentRepository $withdrawalRepository)
    {
    }

    public function getTotalCount(): array
    {
        return [
            'success' => $this->withdrawalRepository->getTotalCountSuccess(),
            'failed' => $this->withdrawalRepository->getTotalCountFailed(),
        ];
    }
}
