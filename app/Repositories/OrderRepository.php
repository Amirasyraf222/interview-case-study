<?php

namespace App\Repositories;

use App\Models\ActivityLogUser;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Models\Cart;
use App\Models\CustomerInfo;
use Carbon\Carbon;
use Auth;

class OrderRepository extends GeneralRepository
{
    public function index()
    {
        try {

            $data = [
                'DataArray' => $this->toArray(
                    Order::where('status', 'Pending Payment')
                    ->where('user_id',Auth::user()->id)
                    ->with('orderProduct', 'orderProduct.product')->get()->toArray()
                )
            ];

            if (!empty($data)) {
                return $this->response(
                    200,
                    'Successfully retrieved record.',
                    $data
                );
            } else {
                return $this->response(
                    404,
                    'Record not available.'
                );
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->response(
                404,
                'Record not available.'
            );
        }
    }

    public function toArray($results)
    {
        $data = [];

        foreach ($results as $key => $result) {
            $productNames = array_map(function ($orderProduct) {
                return $orderProduct['product']['name'] ?? 'Unknown Product';
            }, $result['order_product']);
            $data[$key] = [
                'id'                    => $result['id'],
                'orderId'               => $result['reference_number'],
                'totalAmount'           => $result['total_amount'],
                'orderStatus'           => $result['status'],
                'numberOfProduct'       => count($result['order_product']),
                'product'               => $productNames ,
                'createdAt'             => Carbon::parse($result['created_at'])->format('d/m/Y'),
                'updatedAt'             => Carbon::parse($result['updated_at'])->format('d/m/Y'),
            ];
        }

        return $data;
    }

    public function store($elements)
    {
        try {
            // Create the order
            $order = Order::create([
                'user_id'               => Auth::user()->id,
                'customer_info_id'      => CustomerInfo::where('user_id', Auth::user()->id)->value('id'),
                'reference_number'      => rand(10000, 19999),
                'total_amount'          => $elements['total_amount'],
                'status'                => 'Pending Payment',
            ])->toArray();

            // Decode product IDs from JSON
            $cartIds = json_decode($elements['cart_ids'], true);

            // Process each product ID
            foreach ($cartIds as $value) {
                $cart = Cart::where('id', $value)
                    ->with('product')
                    ->first();

                // Create order products
                OrderProduct::create([
                    'order_id' => $order['id'],
                    'user_id' => Order::where('id',$order['id'])->value('user_id'),
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'total_price' => $cart->product->price * $cart->quantity,
                ]);

                // Remove item from cart
                $cart->delete();
            }

            // Check if order creation was successful
            if ($order) {
                ActivityLogUser::log([
                    'user_id'   => Auth::user()->id,
                    'action'    => 'Make order'
                ]);
                return [
                    'status' => 200,
                    'message' => 'Order successfully created.'
                ];
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Record not available.'
                ], 404);
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while processing the order.'
            ], 500);
        }
    }
}
