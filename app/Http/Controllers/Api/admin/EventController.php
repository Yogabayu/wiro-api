<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventComment;
use Illuminate\Http\Request;
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
            $data = Event::with(['user'])->get();
            return sendResponse($data,'succesfully get data');
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'could not get data');
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
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name'      =>'required',
            'place'     => 'required',
            'date'     => 'required',
            'desc'     => 'required',
        ]);
           
        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);
        
        try {
            if ($request->hasFile('photo')) {
                $photoEXT       = $request->file('photo')->getClientOriginalName();
                $filename       = pathinfo($photoEXT, PATHINFO_FILENAME);
                $EXT            = $request->file('photo')->getClientOriginalExtension();
                $filePhoto      = $filename. '_'.time().'.' .$EXT;
                $path           = $request->file('photo')->move(public_path('file/admin/event/image/'), $filePhoto);
            
                $event = Event::create([
                    'user_id'      => $user->id,
                    'name'          =>$request->name,
                    'place'        => $request->place,
                    'date'        => $request->date,
                    'desc'        => $request->desc,
                    'gmaps'       => $request->gmaps,
                    'photo'       =>$filePhoto,
                ]);
    
                return sendResponse(
                    $event,'Succesfully saved data'
                );
            }else {
                $event = Event::create([
                    'user_id'      => $user->id,
                    'name'          =>$request->name,
                    'place'        => $request->place,
                    'date'        => $request->date,
                    'desc'        => $request->desc,
                    'gmaps'       => $request->gmaps,
                ]);
    
                return sendResponse(
                    $event,'Succesfully saved data'
                );
            } 
            
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'eror could not store data');
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
            $cek = Event::where('id',$id)->count();
            if ($cek!=1) {
                return sendError('error data not found','Eror retrive data from database');
            } else {                
                $success['event'] = Event::find($id);
                $success['comment'] = EventComment::where('event_id',$id)->get();
                return sendResponse($success,'Success retrive data from database');
            }            
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'Eror retrive data from database');
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
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name'      =>'required',
            'place'     => 'required',
            'date'     => 'required',
            'desc'     => 'required',
        ]);
           
        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);

        try {
            if ($request->hasFile('photo')) {
                $photoEXT       = $request->file('photo')->getClientOriginalName();
                $filename       = pathinfo($photoEXT, PATHINFO_FILENAME);
                $EXT            = $request->file('photo')->getClientOriginalExtension();
                $filePhoto      = $filename. '_'.time().'.' .$EXT;
                $path           = $request->file('photo')->move(public_path('file/admin/event/image/'), $filePhoto);
                
                $event = Event::FindOrFail($id)->update([
                    'user_id'     => $user->id,
                    'name'        => $request->name,
                    'place'       => $request->place,
                    'date'        => $request->date,
                    'desc'        => $request->desc,
                    'gmaps'       => $request->gmaps,
                    'photo'       => $filePhoto,
                ]);

                return sendResponse(
                    $event,'Succesfully update data'
                );
            }         
            else {
                $event = Event::FindOrFail($id)->update([
                    'user_id'     => $user->id,
                    'name'        => $request->name,
                    'place'       => $request->place,
                    'date'        => $request->date,
                    'desc'        => $request->desc,
                    'gmaps'       => $request->gmaps,
                ]);

                return sendResponse(
                    $event,'Succesfully update data'
                );
            }
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'Eror update data from database');
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
            $data = Event::destroy($id);
            if ($data==0) {                
                return sendError("Event not found","error");
            } else {
                return sendResponse('Success Delete Event','success');
            }  
        } catch (\Throwable $th) {
            return sendError($th->getMessage(),'Eror delete data from database');
        }
    }

    //comment on event

    public function addcomment(Request $request)
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


    //end comment
}
