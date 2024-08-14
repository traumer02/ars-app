<?php

namespace App\Http\Controllers;


use App\Http\Resources\ProductResource;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::where('user_id', Auth::id())->get();

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    public function store(Request $request): JsonResponse
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();



        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);
        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    public function show($id): JsonResponse
    {
        $product = Product::where('id', $id)->where('user_id' , Auth::id())->first();

        if(is_null($product)){
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully. ');
    }

    public function update(Request $request , Product $product): JsonResponse
    {
        if ($product->user_id != Auth::id()) {
            return $this->sendError('Unauthorized.', 'You are not allowed to update this product.');
        }

        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product->name =$input['name'];
        $product->detail = $input ['detail'];
        $product->save();

        return $this->sendResponse(new ProductResource($product),'Product updated successfully.');

    }

    public function destroy(Product $product): JsonResponse
    {
        if($product->user_id != Auth::id()){
            return $this->sendError('Unautharized.', 'You are not allowed to delete this product.');
        }
        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }

}
