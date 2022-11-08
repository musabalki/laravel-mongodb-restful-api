<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Validator;
class ProductController extends Controller
{
 
    public function index()
    {
        $product =Product::all();
        return response()->json(["data"=>$product], 200);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();
            return response()->json($message,400);
        }

        $user = Auth::user();
        $role = $user["role"];
        
        if($role=="admin"){
            $product =Product::create($request->all());
            if($product){
                return response()->json(["data"=>$product], 201);
            }else{
                return response()->json(['message' => 'Error, the product could not be added'],404);
            }
        }else{
            return response()->json(['message' => 'User role cannot add products'],400);
        }
       
        
    }

    public function show($id)
    {
        $product =Product::find($id);
        if($product){
            return response()->json(["data"=>$product], 200);
        }else{
            return response()->json(['message' => 'Product not found'],404);
        }
        
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if($product){
            $product->name=$request->name;
            $product->price=$request->price;
            $product->description=$request->description;
            $product->image=$request->image;
            $product->save();
            return response()->json(["data" => $product], 201); 
        }
        else{
            return response()->json(["message" => "Product not found"], 404); 
        }
    }


    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return response()->json(["message" => "success"], 204); 
    }
}
