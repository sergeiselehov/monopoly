<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\WithdrawalStoreRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Resources\ApiWithdrawalResource;
use App\Models\Withdrawal;
use App\Models\WithdrawalStatus;
use App\Repositories\WithdrawalRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{

    private WithdrawalRepository $withdrawalRepository;

    public function __construct(WithdrawalRepository $withdrawalRepository)
    {

        $this->withdrawalRepository = $withdrawalRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request):ApiResponse
    {
        $user = Auth::user();
        if($user === null){
            return ApiResponse::unauthorized();
        }
        $list = Withdrawal::query()->whereHas('users',function ($query) use ($user){
            $query->when(!$user->hasRole('Super Admin'),function($query) use ($user){
                $query->where('user_id',$user->id);
            });
        })->orderBy('id','DESC')->paginate(25,['*'],$request->input('page'));;

        return ApiResponse::list()
            ->items(ApiWithdrawalResource::collection($list)->toArray($request));
    }

    public function getWithdrawalsSum(){
        $user = Auth::user();
        $withdrawalls = $this->withdrawalRepository->getWithdrawalls($user);
        return ApiResponse::common(['withdrawals'=>$withdrawalls]);
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
     * @param WithdrawalStoreRequest $request
     * @return ApiResponse
     */
    public function store(WithdrawalStoreRequest $request):ApiResponse
    {
        $user = Auth::user();
        $withdrawal_id = Withdrawal::create([
            "user_id"=>$user->id,
            "uuid"=>Str::uuid(),
            "amount"=>$request->input('amount'),
            "wallet"=>$request->input('wallet'),
            "status"=>WithdrawalStatus::CREATED
        ]);
        if($withdrawal_id === null){
            return ApiResponse::error("Ошибка добавления вывода");
        }
        return ApiResponse::success('Вывод успешно добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function show(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function edit(Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Withdrawal $withdrawal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Withdrawal  $withdrawal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Withdrawal $withdrawal)
    {
        //
    }
}
