<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ApiResponses\ApiResponse;
use App\Services\PaymentService;

class TransactionController
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    public function getTotalCount(): ApiResponse
    {
        return ApiResponse::common($this->paymentService->getTotalCount());
    }
}
