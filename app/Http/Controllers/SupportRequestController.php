<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\SupportRequestStore;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Resources\ApiSupportRequestList;
use App\Models\SupportRequest;
use App\Models\SupportRequestCategory;
use App\Models\SupportRequestStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request):ApiResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $list = SupportRequest::query()->where('user_id',$user->id)->get();

        return ApiResponse::list()->items(ApiSupportRequestList::collection($list)->toArray($request));
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
     * @param SupportRequestStore $request
     * @return ApiResponse
     */
    public function store(SupportRequestStore $request):ApiResponse
    {
        $support_request = SupportRequest::create([
            'email'=>$request->email,
            'telegram'=>$request->telegram,
            'theme'=>$request->theme,
            'description'=>$request->description,
            'support_request_status_id'=>SupportRequestStatus::CREATED,
            'support_request_category_id'=>$request->category,
            'user_id'=>Auth::user()->id
        ]);
        if($support_request === null){
            return ApiResponse::error('Ошибка создания');
        }
        return ApiResponse::success('Успешно добавлено');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportRequest  $supportRequest
     * @return \Illuminate\Http\Response
     */
    public function show(SupportRequest $supportRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportRequest  $supportRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(SupportRequest $supportRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportRequest  $supportRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupportRequest $supportRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupportRequest  $supportRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportRequest $supportRequest)
    {
        //
    }

    public function getCategory(){
        $all_category = SupportRequestCategory::get();
        return ApiResponse::common(['categories'=>$all_category]);
    }
}
