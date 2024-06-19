<?php

namespace App\Repositories;

use App\Models\Dispute;
use App\Models\DisputeStatus;

class DisputeRepository
{
    public function getTotalCountSuccess(): int
    {
        return Dispute::query()
            ->where('dispute_status_id', DisputeStatus::SUCCESSED)
            ->count();
    }

    public function getTotalCountFailed(): int
    {
        return Dispute::query()
            ->where('dispute_status_id', DisputeStatus::FAILED)
            ->count();
    }
}
