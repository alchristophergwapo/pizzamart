<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function handleTransactionRequest(Request $request)
    {
        $query = [];
        if ($request->type == 'pending') {
            $query = [
                'orders.storeId' => $request->id,
                'orders.status' => 0,
            ];
        } elseif ($request->type == 'resolve') {
            $query = [
                'orders.storeId' => $request->id,
                'orders.status' => 1,
            ];
        } elseif ($request->type == 'detail') {
            $query = [
                'orders.storeId' => Auth::user()->userInfoId,
                'orders.status' => 0,
                'orders.id' => $request->id,
            ];
        }

        return response()->json(["orders" => $this->viewTransaction($query)], 200);
    }

    //to retrieving transaction of a specific store
    public function viewTransaction($query)
    {
        $data = DB::table('orders')
            ->join('user_info', 'user_info.id', '=', 'orders.userId')
            ->join('stores', 'stores.id', '=', 'orders.storeId')
            ->join('products', 'products.id', '=', 'orders.productId')
            ->select('orders.id', 'products.productname', 'user_info.firstname', 'user_info.lastname', 'user_info.contactNumber', 'orders.quantity', 'orders.amount', 'orders.created_at')
            ->where($query)
            ->orderBy('orders.created_at', 'desc')
            ->get();
        return $data;
    }

    //this code is for ordering products
    public function order(Request $request)
    {
        try {
            DB::beginTransaction();
            $points = 0;
            foreach ($request->orders as $order) {
                $points += $order["total"] * 0.02;
                $quantity = $order["quantity"];
                $productId = $order["productId"];
                $amount = $order["total"];
                $storeId = $order["item"]['storeId'];
                Orders::create([
                    "quantity" => $quantity,
                    "productId" => $productId,
                    "amount" => $amount,
                    "userId" => $request->userId,
                    'storeId' => $storeId,
                    'status' => 0,
                ]);

                DB::table('products')
                    ->where('id', $productId)
                    ->increment('sold', $quantity);

                $this->notify($storeId);
            }
            DB::table('user_info')
                ->where('id', $request->userId)
                ->increment('points', $points);
            DB::commit();
            return response()->json(["success" => true, 'points' => $points]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error" => $e->getMessage()], 501);

        }

        return response()->json(["placed" => true, "points" => $points], 200);

    }


    public function updateOrder(Request $request)
    {
        # code...
        try {
            DB::beginTransaction();
            \DB::table("orders")
                ->where('id', $request->id)
                ->increment('status', 1);
            DB::commit();
            return response()->json(["status" => true], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["error" => $e->getMessage()], 501);
        }
    }


    public function notify($storeId)
    {
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true,
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        // fire new pusher event
        $pusher->trigger(
            'pizzamart-channel', //channel
            'new order', // event
            ["storeId" => $storeId]); //message
    }



    public function summary(Request $request)
    {
        $storeId = Auth::user()->userInfoId;

        $total = DB::table('orders')
            ->select('orders.id')
            ->where(['orders.storeId' => $storeId])
            ->count();
        $pending = DB::table('orders')
            ->select('orders.id')
            ->where([
                'orders.storeId' => $storeId,
                'orders.status' => 0]
            )
            ->count();
        $resolve = DB::table('orders')
            ->select('orders.id')
            ->where(['orders.storeId' => $storeId,
                'orders.status' => 1])
            ->count();

        $products = Products::select("productName", "sold")->where('storeId', $storeId)
            ->get();

        return response()->json(
            [
                'counter' => [
                    "total" => $total,
                    "pending" => $pending,
                    "resolve" => $resolve,
                ],
                'products' => $products,
            ], 200);
    }
}
