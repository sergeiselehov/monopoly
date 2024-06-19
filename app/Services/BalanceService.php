<?php

namespace App\Services;

use App\Repositories\BalanceRepository;

class BalanceService
{
    public function __construct(protected BalanceRepository $balanceRepository)
    {
    }

    public function getBalanceList(): array
    {
        return $this->balanceRepository->getBalanceList();
    }
}
