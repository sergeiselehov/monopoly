<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\UserAddAndAttachRequest;
use App\Http\ApiRequests\UserStoreRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Resources\ApiTransactionResource;
use App\Http\Resources\ApiUserResource;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Nusagates\Helper\Responses;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index(Request $request): ApiResponse
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $users = User::query()
            ->with('roles.permissions')
            ->when(!$user->hasRole('Super Admin'), function ($query) use ($user) {
                $query->where('id', $user->id);
            })->paginate(25, ['*'], $request->input('page'));

        return ApiResponse::list()->items(ApiUserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function store(UserStoreRequest $request): ApiResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'secret_key' => sha1(Str::uuid() . Str::random(30)),
            'app_key' => sha1(Str::uuid() . Str::random(30))
        ]);
        $roles = Role::whereIn('id', $request->roles)->get();
        $user = $user->syncRoles($roles);
        foreach ($roles as $role) {
            $user->syncPermissions($role->permissions);
        }
        return ApiResponse::success('Пользователь успешно добавлен');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed|min:8',
            'password_confirmation' => 'nullable|string',
            'secret_key' => sha1(Str::uuid() . Str::random(30)),
            'app_key' => sha1(Str::uuid() . Str::random(30))
        ];

        $validation = Validator::make($request->post(), $rules);
        if ($validation->fails()) {
            return Responses::showValidationError($validation);
        }
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if ($request->password !== null) {
            $userData['password'] = bcrypt($request->password);
        }
        $user->update($userData);
        $roles = Role::with('permissions')->whereIn('id', $request->roles)->get();
        $user = $user->syncRoles($roles);
        foreach ($roles as $role) {
            $user->syncPermissions($role->permissions);
        }
        return Responses::showSuccessMessage('User has been updated', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy(User $user)
    {
        $user->delete();
        return Responses::showSuccessMessage('User has been deleted');
    }

    public function addAndAttach(UserAddAndAttachRequest $request)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();
        $merchant_data = Merchant::where('user_id', $user->id)->where('uuid', $request->input('merchant'))->first();
        if ($merchant_data === null) {
            return ApiResponse::notFound('У вас нет доступа к указанному магазину');
        }
        /**
         * @var $user_created User
         */
        $user_created = User::create([
            'parent_user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $roles = Role::whereIn('id', $request->roles)->get();
        $user_created->syncRoles($roles);
        foreach ($roles as $role) {
            $user_created->syncPermissions($role->permissions);
        }
        $user_created->merchants()->attach($merchant_data);
        return ApiResponse::common(['message' => 'Пользователь успешно добавлен']);
    }
}
