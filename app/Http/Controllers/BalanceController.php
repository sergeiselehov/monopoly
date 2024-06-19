<?php

namespace App\Http\Controllers;

use App\Http\ApiResponses\ApiResponse;
use App\Http\Resources\BalancesCardResource;
use App\Models\CreditPayment;
use App\Models\PaymentStatus;
use App\Models\WithdrawalPaymentStatus;
use App\Repositories\BalanceRepository;
use App\Services\BalanceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    /**
     * @param BalanceRepository $balanceRepository
     */

    public function __construct(
        private BalanceRepository $balanceRepository,
        private BalanceService $balanceService
    )
    {
    }

    public function getBalance(){
        $user = Auth::user();
        if($user->hasRole('Super Admin')){
            $balance = $this->balanceRepository->getSummaryBalance();
            $balance = $balance['summaryBalanceNotFormat'];
        }else{
            $balance = $this->balanceRepository->getCurrentBalance($user);
        }

        return ApiResponse::common([
            'balance'=>number_format(round($balance),2,'.',' ')
            ]
        );
    }

    public function getList()
    {
        $list = $this->balanceService->getBalanceList();
        return ApiResponse::common(BalancesCardResource::collection($list));
    }

    public function getSummary()
    {
        $balance = $this->balanceRepository->getSummaryBalance();
        return ApiResponse::common(['balance'=>$balance]);
    }

}
