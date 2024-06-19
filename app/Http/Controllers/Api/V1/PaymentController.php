<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ApiRequests\ApiTransactionListRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiTransactionResource;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ApiResponse
     */
    public function list(ApiTransactionListRequest $request):ApiResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        if($user === null){
            return ApiResponse::unauthorized();
        }

        $query_result = Payment::query()->whereHas('merchant.users',function($query) use ($user){
            $query->when(!$user->hasRole('Super Admin'),function($query) use ($user){
                $query->where('user_id',$user->id);
            });
        });

        if($request->input('date_from') !== null){
                $query_result->whereDate('created_at','>=',$request->input('date_from'));
        }

        if($request->input('date_to') !== null){
            $query_result->whereDate('created_at','<=',$request->input('date_to'));
        }

        if($request->input('uuid') !== null){
            $query_result->where('uuid',$request->input('uuid'));
        }

        $list = $query_result->orderBy('created_at','DESC')->paginate(25,['*'],$request->input('page'));



        return ApiResponse::list()
            ->items(ApiTransactionResource::collection($list)->toArray($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
