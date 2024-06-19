<?php

namespace App\Http\Controllers;

use App\Http\ApiResponses\ApiResponse;
use App\Repositories\BalanceRepository;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{

    private BalanceRepository $balanceRepository;

    /**
     * @param BalanceRepository $balanceRepository
     */

    public function __construct(BalanceRepository $balanceRepository)
    {
        $this->balanceRepository = $balanceRepository;
    }

    public function getBalance(){
        $user = Auth::user();
        $balance = $this->balanceRepository->getCurrentBalance($user);
        return ApiResponse::common(['balance'=>$balance]);
    }

}
