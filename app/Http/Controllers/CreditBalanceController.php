<?php

namespace App\Http\Controllers;

use App\Http\ApiResponses\ApiResponse;
use App\Models\CreditPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditBalanceController extends Controller
{
    public function get(Request $request)
    {
        $user = Auth::user();

        $balance = CreditPayment::query()
            ->where('user_id', $user->id)
            ->sum('amount');

        return ApiResponse::common(['balance' => round($balance)]);
    }
}
