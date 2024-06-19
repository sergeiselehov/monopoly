<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\DisputeService;

class DisputeController extends Controller
{
    public function __construct(protected DisputeService $disputeService)
    {
    }

    public function getTotalCount(): ApiResponse
    {
        return ApiResponse::common($this->disputeService->getTotalCount());
    }
}

