<?php

namespace App\Services;

use App\Repositories\DisputeRepository;

class DisputeService
{
    public function __construct(protected DisputeRepository $disputeRepository)
    {
    }

    public function getTotalCount(): array
    {
        return [
            'success' => $this->disputeRepository->getTotalCountSuccess(),
            'failed' => $this->disputeRepository->getTotalCountFailed(),
        ];
    }
}
