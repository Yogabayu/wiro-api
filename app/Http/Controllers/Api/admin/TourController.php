<?php

namespace App\Http\Controllers\Api\admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data=Tour::All();
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
        // dd($user->role);
        $validator = Validator::make($request->all(), [
            'category_id'   => 'required',
            'name'          => 'required',
            'address'       => 'required',
            'photo'         => 'required',
            'gmaps'         => 'required',
            'contact'       => 'required',
            'entry_fee'     => 'required',
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);
        if ($request->hasFile('photo')) {
            $photoEXT       = $request->file('photo')->getClientOriginalName();
            $filename       = pathinfo($photoEXT, PATHINFO_FILENAME);
            $EXT            = $request->file('photo')->getClientOriginalExtension();
            $filePhoto      = $filename. '_'.time().'.' .$EXT;
            $path           = $request->file('photo')->move(public_path('file/admin/tour/image/'), $filePhoto);
        } else {
            $filePhoto=null;
        }
        try {
            $cat = Tour::create([
                'user_id'       => $user->id,
                'category_id'   => $request->category_id,
                'name'          => $request->name,
                'address'       => $request->address,
                'photo'         => $filePhoto,
                'gmaps'         => $request->gmaps,
                'contact'       => $request->contact,
                'entry_fee'     => $request->entry_fee,
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
            $check = Tour::where('id',$id)->count();
            if ($check!=0) {
                $data = Tour::find($id);
                return sendResponse($data,'Success get data');
            } else{
                return sendError('Place Not Found','Place Not Found');
            }
        } catch (\Exception $e) {
           return sendError('Place Not Found','Place Not Found');
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
        if ($request->hasFile('photo')) {
            $validator = Validator::make($request->all(), [
                'category_id'   => 'required',
                'name'          => 'required',
                'address'       => 'required',
                'photo'         => 'required',
                'gmaps'         => 'required',
                'contact'       => 'required',
                'entry_fee'     => 'required',
            ]);    
            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);
            try {
                $photoEXT       = $request->file('photo')->getClientOriginalName();
                $filename       = pathinfo($photoEXT, PATHINFO_FILENAME);
                $EXT            = $request->file('photo')->getClientOriginalExtension();
                $filePhoto      = $filename. '_'.time().'.' .$EXT;
                $path           = $request->file('photo')->move(public_path('file/admin/tour/image/'), $filePhoto);
                
                $cat = Tour::FindOrFail($id)->update([
                    'user_id'       => $user->id,
                    'category_id'   => $request->category_id,
                    'name'          => $request->name,
                    'address'       => $request->address,
                    'photo'         => $filePhoto,
                    'gmaps'         => $request->gmaps,
                    'contact'       => $request->contact,
                    'entry_fee'     => $request->entry_fee,
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
        } else {
            try {
                $cat = Tour::FindOrFail($id)->update([
                    'user_id'       => $user->id,
                    'category_id'   => $request->category_id,
                    'name'          => $request->name,
                    'address'       => $request->address,
                    'gmaps'         => $request->gmaps,
                    'contact'       => $request->contact,
                    'entry_fee'     => $request->entry_fee,
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
            $data = Tour::destroy($id);
            if ($data==0) {                
                return sendError("Place not found","error");
            } else {
                return sendResponse('Success Delete Place','success');
            }                       
        } catch (\Exception $exception) {
            return sendError("Place not found","error");
        }
    }
}
