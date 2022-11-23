<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {            
            $datas = Category::all();
            return response()->json([
             'message' => 'success get data',
             'data' =>  $datas,
            ], 200);
        } catch (Exception $e) {            
            return response()->json("Eror", 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);

        try {
            $cat = Category::create([
                'name'      => $request->name,
            ]);

            return response()->json([
                'message' => 'success input data',
                'data' => $cat,
            ],200);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'error'
            ],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Category::find($id);
            return sendResponse($data,'Success get data');
        } catch (\Exception $e) {
           return sendError('Category Not Found','Category Not Found');
        }
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);

        try {
            $cat = Category::FindOrFail($id)->update([
                'name'      => $request->name,
            ]);

            return response()->json([
                'message' => 'success update data',
                'data' => $cat,
            ],200);
        } catch (\Exception $th) {
            return response()->json([
                'message' => 'error'
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Category::destroy($id);
            if ($data==0) {                
                return sendError("Category not found","error");
            } else {
                return sendResponse('Success Delete Category','success');
            }                       
        } catch (\Exception $exception) {
            return sendError("Category not found","error");
        }
    }
}
