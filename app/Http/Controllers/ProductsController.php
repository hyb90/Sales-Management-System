<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $products=Product::query()->with('category')->paginate($request->limit??10);

        return response()->json(
            [
                "success"=>true,
                "message"=>'Products retrieved successfully',
                "data"=>$products
            ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
            'category_id' => 'required|exists:clients,id',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);
        $product=Product::create($validated);
        if(!$product){
            return response()->json(
                [
                    "success"=>false,
                    "message"=>'Something went Wrong',
                    "data"=>null
                ]
            );
        }
        return response()->json(
            [
                "success"=>false,
                "message"=>'Product created successfully',
                "data"=>$product->load('category')
            ]
        );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product=Product::find($id);
        if(!$product){
            return response()->json(
                [
                    "success"=>false,
                    "message"=>'No such product',
                    "data"=>null
                ]
            );
        }
        $validated=$request->validate([
            'category_id' => 'nullable|exists:clients,id',
            'name' => 'nullable',
            'description' => 'nullable',
            'price' => 'nullable|numeric',
        ]);
        $product->update(array_filter($validated));

        return response()->json(
            [
                "success"=>false,
                "message"=>'Product updated successfully',
                "data"=>$product->load('category')
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
