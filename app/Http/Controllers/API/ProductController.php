<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ConsumeController as ConsumeController;
use App\Models\Product;
use Validator;
use App\Http\Resources\ProductResource;

class ProductController extends Controller{
    
    //get all products  
    public function index(){
        $products = Product::all();
        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
    }

    //function to store/register new product
    public function store(Request $request){
        $input = $request->all();
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

    //function to get 1 single product and search it from specific id
    public function show($id){
        $product = Product::find($id);
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    //function to update product data 
    public function update(Request $request, Product $product){
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'detail' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->save();
        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    //function for delete a product
    public function destroy(Product $product){
        $product->delete();
        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
