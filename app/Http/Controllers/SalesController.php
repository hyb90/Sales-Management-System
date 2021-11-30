<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $sales=Sale::query()->with(['client','seller','transactions'])->paginate($request->limit??10);
        return response()->json(
            [
                "success"=>true,
                "message"=>'Sales retrieved successfully',
                "data"=>$sales
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
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'seller_id' => 'required|exists:sellers,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer',
        ]);
        $quantityPrices=[];
        foreach ( $request->products as $item){
            $product=Product::query()->where('id',$item['id'])->select('price')->first();
            array_push($quantityPrices,$product->price*$item['quantity']);
        }

        $sale=Sale::create([
            'client_id' => $request->client_id,
            'seller_id' => $request->seller_id,
            'total'=>array_sum($quantityPrices)
        ]);
        if(!$sale){
            return response()->json(
                [
                    "success"=>false,
                    "message"=>'Something went Wrong',
                    "data"=>null
                ]
            );
        }

        $productIds=array_column($request->products, 'id');
        $productQuantities=array_column($request->products, 'quantity');

        for ($i = 0; $i < count($productIds); $i++)

        $sale->products()->attach($productIds[$i],['quantity' => $productQuantities[$i],'amount' => $quantityPrices[$i]]);

        return response()->json(
            [
                "success"=>true,
                "message"=>'Thanks for your purchase',
                "data"=>$sale->load(['client','seller','transactions'])
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
     * @return \Illuminate\Http\Response
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
        $sale=Sale::find($id);
        if(!$sale){
            return response()->json(
                [
                    "success"=>false,
                    "message"=>'Sale not found',
                    "data"=>null
                ]
            );
        }
        $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'seller_id' => 'nullable|exists:sellers,id',
            'products' => 'nullable|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer',
        ]);
        $quantityPrices=[];
        if($request->products){
            foreach ($request->products as $item) {
                $product = Product::query()->where('id', $item['id'])->select('price')->first();
                array_push($quantityPrices, $product->price * $item['quantity']);
            }
            $productIds=array_column($request->products, 'id');
            $productQuantities=array_column($request->products, 'quantity');
            for ($i = 0; $i < count($productIds); $i++) {
                $sale->products()->sync([
                    $productIds[$i] => ['quantity' => $productQuantities[$i], 'amount' => $quantityPrices[$i]]
                ], false);
                DB::table('transactions_log')->insert([
                    'sale_id' => $sale->id,
                    'product_id' => $productIds[$i],
                    'quantity' => $productQuantities[$i],
                    'amount' => $quantityPrices[$i],
                    'user_id' => Auth::id(),
                    'created_at' => now()
                ]);
            }
        }
        $saleData=[
            'client_id' => $request->client_id,
            'seller_id' => $request->seller_id,
            'total'=>array_sum($quantityPrices)
        ];

        $sale->update(array_filter($saleData));
        return response()->json(
            [
                "success"=>true,
                "message"=>'Sale Updated Successfully',
                "data"=>$sale->load(['client','seller','transactions'])
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
    public function log(Request $request)
    {
        return response()->json(
            [
                "success"=>true,
                "message"=>'Sale Updated Successfully',
                "data"=>DB::table('transactions_log')->paginate($request->limit??10)
            ]
        );
    }

}
