<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Store;
use App\Models\UserInfo;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'updateProfile', 'getHistory','viewStores','updateStoreProfile']);
    }

    public function init()
    {

        if (Auth::check()) {
            if (Auth::user()->userTypeId === 1) {
                $user = User::with('store')->get()->find(Auth::user()->id);
                return response()->json(["user" => $user], 200);
            } else {
                $user = User::with('userInfo')->get()->find(Auth::user()->id);
                return response()->json(["user" => $user], 200);
            }

        }
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->only("email", "password", "userTypeId");
        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return response()->json(["user" => $user], 200);
            } else {
                return response()->json(['error' => 'invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function register(UserRegisterRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        if (isset($user)) {
            return response()->json(['error' => 'email already taken'], 401);
        }

        try {
            DB::beginTransaction();
            $userInfo = UserInfo::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'contactNumber' => (string) $request->contactNumber,
                'address' => $request->address,
            ]);
            $user = $userInfo->account()->create([
                "email" => $request->email,
                "password" => bcrypt($request->password),
                'userTypeId' => $request->user_typeId,
                'userInfoId' => $userInfo->id,
            ]);
            DB::commit();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function registerStore(StoreRegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            // creating a store account
            $store = Store::create([
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,
                'contactNumber' => (string) $request->contactNumber,
                'imageUrl' => $request->imageUrl,
            ]);

            // creating a user account
            $storeOwner = $store->account()->create([
                "email" => $request->email,
                "password" => bcrypt($request->password),
                'userTypeId' => $request->user_typeId,
                'userInfoId' => $store->id,
            ]);

            DB::commit();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function updateStoreProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'address' => 'required|min:3',
            'contactNumber' => 'required|min:3',
        ]);

        try {
            DB::beginTransaction();
            $store = Store::update([
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,
                'contactNumber' => (string) $request->contactNumber,
                'imageUrl' => $request->imageUrl,
            ]);

            $storeOwner = $store->account()->create([
                "email" => $request->email,
                "password" => bcrypt($request->password),
                'userTypeId' => $request->user_typeId,
                'userInfoId' => $store->id,
            ]);

            DB::commit();
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    ///this code is for updating the customer profile

    public function updateProfile(Request $request)
    {
        try {

            

            // $request->validate([
            //     'firstname' => 'required',
            //     'lastname' => 'required',
            //     'contactNumber' => 'required',
            //     'address' => 'required',
            // ]);

            $request->validate([
                "firstname" => [
                    'regex:/^[a-zA-Z]+$/u', //must be letters
                    'required',
                    'min:2', // must be at least 2 characters in length
                ],
                "lastname" => [
                    'regex:/^[a-zA-Z]+$/u', //must be letters
                    'required',
                    'min:3', // must be at least 3 characters in length
                ],
                "address" => [
                    'required', //must be letters
                    'string',
                    'min:4', // must be at least 4 characters in length
                ],
                "email" => [
                    'required',
                    'email',
                ],
                'contactNumber' => [
                    'required',
                    'min:10',
                    'min:11',
                    'string',
                ],
            ]);
            DB::beginTransaction();
            //inserting to the database the updated data to the userinfo table
            UserInfo::where('id', $request->userInfoId)
                ->update([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'address' => $request->address,
                    'contactNumber' => $request->contactNumber,
                ]);
            //inserting to the database the updated data to the user table
            User::where('userInfoId', $request->id)
                ->update([
                    'email' => $request->email,
                ]);
            DB::commit();
            return redirect("/auth/init");
        } catch (\Exception $e) {
            // dd($e);
            return response()->json(['Error : ', $e]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(["success" => true], 200);
    }

    // retrieving all stores
    public function viewStores()
    {
        $store = Store::get();
        return response(['stores' => $store], 200);
    }

    public function getHistory(Request $request)
    {
        $data = \DB::table('orders')
            ->join('user_info', 'user_info.id', '=', 'orders.userId')
            ->join('stores', 'stores.id', '=', 'orders.storeId')
            ->join('products', 'products.id', '=', 'orders.productId')
            ->select('orders.id', 'stores.name', 'products.imageUrl', 'products.productname', 'orders.quantity', 'orders.amount', 'orders.created_at')
            ->where(['orders.userId' => $request->id])
            ->orderBy('orders.created_at', 'desc')
            ->get();
        return $data;
    }
}
