<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        try {
            $user =auth()->user();
            $validator = Validator::make($request->all(), [
                'tour_id'   => 'required',
                'date'      => 'required',
                'desc'      => 'required',
            ]);

            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);

            try {
                $com = Comment::create([
                    'tour_id'       => $request->tour_id,
                    'user_id'       => $user->id,
                    'date'          => $request->date,
                    'desc'          => $request->desc,
                ]);
        
                return response()->json([
                    'message' => 'success input data',
                    'data' => $com,
                ],200);
            } catch (\Exception $th) {
                return response()->json([
                    'message' => 'error'
                ],400);
            }
        } catch (\Exception $th) {
            return sendError('error',$th);
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
