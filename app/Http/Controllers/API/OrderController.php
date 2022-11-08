<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $order =Order::all();
        return response()->json(["data"=>$order], 200);
    }

    public function store(Request $request)
    {
        $notFound = 0;

        foreach($request->products as $val){
            $product = Product::find($val["product"]);
            if(!$product){
                $notFound = 1;
                break;
            }
        }

        if($notFound){
            return response()->json(['message' => 'Products not found'],404);
        }
        $order = Order::create($request->all());
        if($order){
            return response()->json(["data"=>$order], 201);
        }else{
            return response()->json(['message' => 'Order not found'],404);
        }
    }

    public function show($id)
    {
        $order =Order::find($id);
        if($order){
            return response()->json(["data"=>$order], 200);
        }else{
            return response()->json(['message' => 'Product not found'],404);
        }
    }

    public function update(Request $request, $id)
    {
        $order =Order::find($id);
        if($order){
            $order->products = $request->products;
            $order->save();
            return response()->json(["data"=>$order], 201);
        }else{
            return response()->json(['message' => 'Product not found'],404);
        }
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        return response()->json(["message" => "success"], 204); 
    }
}
