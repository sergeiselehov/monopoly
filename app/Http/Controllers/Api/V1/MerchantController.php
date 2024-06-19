<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ApiRequests\ApiMerchantStoreRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiMerchantResource;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class MerchantController extends Controller
{
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
        $list = Merchant::query()->whereHas('users',function ($query) use ($user){
            $query->when(!$user->hasRole('Super Admin'),function($query) use ($user){
                $query->where('user_id',$user->id);
            });
        })->orderBy('id','DESC')->paginate(25,['*'],$request->input('page'));;

        return ApiResponse::list()
            ->items(ApiMerchantResource::collection($list)->toArray($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ApiMerchantStoreRequest $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApiMerchantStoreRequest $request
     * @return ApiResponse
     */
    public function store(ApiMerchantStoreRequest $request):ApiResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        if($user === null){
            return ApiResponse::unauthorized();
        }
        $merchant = Merchant::create([
            'uuid'=>Str::uuid(),
            'name'=>$request->input('name'),
            'user_id'=>$user->id,
            'success_url_callback'=>$request->input('success_url'),
            'error_url_callback'=>$request->input('error_url'),
            'secret_key'=>sha1(Str::uuid().Str::random(30)),
        ]);
        if($merchant === null){
            return ApiResponse::error('Ошибка создания мерчанта');
        }
        $user->merchants()->attach($merchant);
        return ApiResponse::success('Мерчант добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $uuid):ApiResponse
    {
        $user = Auth::user();
        if($user === null){
            return ApiResponse::unauthorized();
        }
        $merchant = Merchant::query()->where('uuid',$uuid)->whereHas('users',function ($query) use ($user){
            $query->when(!$user->hasRole('Super Admin'),function($query) use ($user){
                $query->where('user_id',$user->id);
            });
        })->first();
        if($merchant === null){
            return ApiResponse::notFound('Мерчант не найден');
        }
        return ApiResponse::common(['data'=>ApiMerchantResource::make($merchant)->toArray($request)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function edit(Merchant $merchant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Merchant $merchant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Merchant $merchant)
    {
        //
    }
}
