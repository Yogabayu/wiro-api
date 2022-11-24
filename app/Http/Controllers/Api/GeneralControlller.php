<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralControlller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $tour = Tour::all();
            $category = Category::all();
            $success['tour'] = $tour;
            $success['categori'] = $category;
            return sendResponse($success,'Success get data');
        } catch (\Exception $th) {
            return sendError('error','error could not get data');
        }
    }

    public function detailtour($id)
    {
        try {
            try {
                $response['tourdetail'] = Tour::find($id);
                $response['comments'] = DB::table('comments')->where('tour_id',$id)->orderBy('date','ASC')->get();
               
                return sendResponse($response, 'You are successfully get data.');            
            } catch (\Throwable $th) {
                return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
            }
        } catch (\Throwable $th) {
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
