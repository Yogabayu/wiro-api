<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = EventComment::all();
            return sendResponse($data,'Successfully retrive data');
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'Failed to retrive data from server');
        }
    }

    public function detailevent($id)
    {
        try {
            $success['event']   = Event::find($id);
            $success['comment'] = DB::table('event_comments')->where('event_id',$id)->get();

            return sendResponse($success,'successful retrive data from server');
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'Failed to retrive data from server');
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
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'event_id'     => 'required',
                'date'     => 'required',
                'desc'     => 'required',
            ]);
            
            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);
            
            $comm = EventComment::create([
                'user_id'      => $user->id,
                'event_id'      => $request->event_id,
                'date'      => $request->date,
                'desc'      => $request->desc,
            ]);

            return sendResponse(
                $comm,'Succesfully saved data'
            );
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'error saved comment');
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
            $data = EventComment::where('id',$id)->get();
            return sendResponse($data,'Succesfully retrive data');
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'error on comment');
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
        try {
            $user = auth()->user();
            $validator = Validator::make($request->all(), [
                'event_id'     => 'required',
                'date'     => 'required',
                'desc'     => 'required',
            ]);
            
            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);
            
            $comm = EventComment::FindOrFail($id)->update([
                'user_id'   => $user->id,
                'event_id'  => $request->event_id,
                'date'      => $request->date,
                'desc'      => $request->desc,
            ]);

            return sendResponse(
                $comm,'Succesfully updated data'
            );
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'error saved comment');
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
            $data = EventComment::destroy($id);
            if ($data==0) {                
                return sendError("Comment not found","error");
            } else {
                return sendResponse('Success Delete Comment','success');
            }  
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'Eror delete data from database');
        }
    }
}
