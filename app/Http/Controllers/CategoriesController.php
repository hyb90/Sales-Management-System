<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $categories=Category::query()->paginate($request->limit??10);

        return response()->json(
            [
                "success"=>true,
                "message"=>'Categories retrieved successfully',
                "data"=>$categories
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
        ]);
        $category=Category::create($validated);
        if(!$category){
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
                "message"=>'Category created successfully',
                "data"=>$category
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
        $category=Category::find($id);
        if(!$category){
            return response()->json(
                [
                    "success"=>false,
                    "message"=>'No such category',
                    "data"=>null
                ]
            );
        }
        $validated=$request->validate([
            'name' => 'nullable',
        ]);
        $category->update(array_filter($validated));

        return response()->json(
            [
                "success"=>false,
                "message"=>'Category updated successfully',
                "data"=>$category
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
