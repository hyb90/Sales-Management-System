<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $products=Client::query()->paginate($request->limit??10);

        return response()->json(
            [
                "success"=>true,
                "message"=>'Clients retrieved successfully',
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
            'name' => 'required',
            'last_name' => 'required',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);
        $client=Client::create($validated);
        if(!$client){
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
                "message"=>'Client created successfully',
                "data"=>$client
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
        $client=Client::find($id);
        if(!$client){
            return response()->json(
                [
                    "success"=>false,
                    "message"=>'No such client',
                    "data"=>null
                ]
            );
        }
        $validated=$request->validate([
            'name' => 'nullable',
            'last_name' => 'nullable',
            'mobile' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);
        $client->update(array_filter($validated));

        return response()->json(
            [
                "success"=>false,
                "message"=>'Client updated successfully',
                "data"=>$client
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
