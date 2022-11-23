<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data=Comment::All();
            return sendResponse($data,"success get data");
        } catch (\Exception $th) {
            return sendError('Not Found');
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
            $check = Comment::where('id',$id)->count();
            if ($check!=0) {
                $data = Comment::find($id);
                return sendResponse($data,'Success get data');
            } else{
                return sendError('Comment Not Found','Comment Not Found');
            }
        } catch (\Exception $e) {
           return sendError('Comment Not Found','Comment Not Found');
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
        $user =auth()->user();
        $validator = Validator::make($request->all(), [
            'tour_id'   => 'required',
            'date'      => 'required',
            'desc'      => 'required',
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);

        try {
            $com = Comment::FindOrFail($id)->update([
                'tour_id'       => $request->tour_id,
                'user_id'       => $user->id,
                'date'          => $request->date,
                'desc'          => $request->desc,
            ]);
    
            return response()->json([
                'message'   => 'success update data',
                'data'      => $com,
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
            $data = Comment::destroy($id);
            if ($data==0) {                
                return sendError("Comment not found","error");
            } else {
                return sendResponse('Success Delete Comment','success');
            }                       
        } catch (\Exception $exception) {
            return sendError("Comment not found","error");
        }
    }
}
