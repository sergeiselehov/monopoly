<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function __construct(protected PaymentRepository $paymentRepository)
    {
    }

    public function getTotalCount(): array
    {
        return [
            'success' => $this->paymentRepository->getTotalCountSuccess(),
            'failed' => $this->paymentRepository->getTotalCountFailed(),
        ];
    }

    public function getConversion(string $period): array
    {
        $timeFrame = match ($period) {
            'hour' => now()->subHour(),
            'day' => now()->subDays(3),
            'week' => now()->subDays(70),
            default => null,
        };
        if ($timeFrame) {
            $paymentsTotal = $this->paymentRepository->getTotal($timeFrame);
            $paymentsSuccess = $this->paymentRepository->getSuccess($timeFrame);
        }
        return [$paymentsTotal, $paymentsSuccess];
    }

    public function getConversionUser(string $period, int $userId): array
    {
        $timeFrame = match ($period) {
            'hour' => now()->subHour(),
            'day' => now()->subDays(3),
            'week' => now()->subDays(7),
            default => null,
        };
        if ($timeFrame) {
            $paymentsTotal = $this->paymentRepository->getTotalUser($timeFrame, $userId);
            $paymentsSuccess = $this->paymentRepository->getSuccessUser($timeFrame, $userId);
        }
        return [$paymentsTotal, $paymentsSuccess];
    }
}
