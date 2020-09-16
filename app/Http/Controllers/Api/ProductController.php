<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $offset = $request->has('offset') ? $request->query('offset') : 0;
        $limit = $request->has('limit') ? $request->query('limit') : 10;
        $qb = Product::query()->with('categories');
        if($request->has('q'))
            $qb->where('name','like', '%' . $request->query('q') . '%');
            if($request->has('sortBy'))
                $qb->orderBy($request->query('sortBy'), $request->query('sort', 'DESC'));

            $data = $qb->offset($offset)->limit($limit)->get();

            return response($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $product = Product::create($input);
        return response([
            'data' => $product,
            'message' => 'successfully added'
        ], 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
//        $input = $request->all();
//        $product->update($input);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->save();
        return response([
            'data' => $product,
            'message' => 'successfully updated'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response([
            'message' => 'successfully deleted'
        ], 200);
    }
    public function custom1()
    {
       return Product::selectRaw('id as product_id, name as product_name')
            ->orderBy('created_at', 'DESC')
            ->take(10)->get();
    }

    public function custom2()
    {
        $product = Product::orderBy('created_at', 'DESC')
            ->take(10)->get();
        $mapped = $product->map(function($product){
           return [
               "_id" => $product['id'],
               "product_name" => $product['name'],
               "price" => $product['price'] * 1.03,
           ];
        });
        return $mapped->all();
    }
    public function custom3()
    {
        $products = Product::paginate(10);
        return ProductResource::collection($products);
    }
}
