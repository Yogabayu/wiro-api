<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 422);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user             = Auth::user();
            $success['name']  = $user;
            $success['token'] = $user->createToken('accessToken')->accessToken;

            return sendResponse($success, 'You are successfully logged in.');
        } else {
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        if ($request->role == 2) {
            $validator = Validator::make($request->all(), [
                'name'     => 'required',
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:8',
                'nik' => 'required',
                'photo' => 'required',
                'role' => 'required',
                'address' => 'required',
                'contact' => 'required',
            ]);
    
            if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);
            
            $check = DB::table('users')->where('email',$request->email)->count();
            if ($check != 0) {
                $success['token'] = [];
                $message          = 'Oops! Unable to create a new user. 22';
                return sendResponse($success, $message);
            } else {
                try {
                    if ($request->hasFile('photo')) {
                        $photoEXT       = $request->file('photo')->getClientOriginalName();
                        $filename       = pathinfo($photoEXT, PATHINFO_FILENAME);
                        $EXT            = $request->file('photo')->getClientOriginalExtension();
                        $filePhoto      = $filename. '_'.time().'.' .$EXT;
                        $path           = $request->file('photo')->move(public_path('file/user/user/image/'), $filePhoto);
                    }else {
                        $filePhoto = 'null';
                    }      
                    $user = User::create([
                        'name'      => $request->name,
                        'email'     => $request->email,
                        'password'  => bcrypt($request->password),
                        'nik'       => $request->nik,
                        'photo'     => $filePhoto,
                        'role'      => $request->role,
                        'address'    => $request->address,
                        'contact'    => $request->contact,
                    ]);
        
                    $success['name']  = $user;
                    $message          = 'User create successfully';
                    $success['token'] = $user->createToken('accessToken')->accessToken;
                } catch (Exception $e) {
                    $success['token'] = [];
                    $message          = 'Oops! Unable to create a new user.';
                }
            }      
            return sendResponse($success, $message);
        } else {
            $success['token'] = [];
            $message          = 'Oops! Unable to create a new user.';
            return sendResponse($success, $message);
        }
    }

    public function index()
    {
        dd('masuk index');
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
        try {
            $check=User::where('id',$id)->where('role',2)->count();
            if ($check!=0) {
                $data=User::find($id);
                return sendResponse($data,"success get data");
            } else {
                return sendError('not found','error get data');
            }
        } catch (\Exception $e) {
            return sendError($e,'error get data');
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
            if ($request->hasFile('photo')) {
                $validator = Validator::make($request->all(), [
                    'name'     => 'required',
                    'email'    => 'required|email|unique:users',
                    'password' => 'required|min:8',
                    'nik' => 'required',
                    'photo' => 'required',
                    'role' => 'required',
                    'address' => 'required',
                    'contact' => 'required',
                ]);
        
                if ($validator->fails()) return sendError('Validation Error.', $validator->errors(), 401);
                
                if ($request->hasFile('photo')) {
                    $photoEXT       = $request->file('photo')->getClientOriginalName();
                    $filename       = pathinfo($photoEXT, PATHINFO_FILENAME);
                    $EXT            = $request->file('photo')->getClientOriginalExtension();
                    $filePhoto      = $filename. '_'.time().'.' .$EXT;
                    $path           = $request->file('photo')->move(public_path('file/admin/user/image/'), $filePhoto);
                }
                $data = User::findOrfail($id)->update([
                        'name'      => $request->name,
                        'email'     => $request->email,
                        'password'  => bcrypt($request->password),
                        'nik'       => $request->nik,
                        'photo'     => $filePhoto,
                        'role'      => $request->role,
                        'address'    => $request->address,
                        'contact'    => $request->contact,
                ]);
                return sendResponse($data,'success update');
            } else {
                $data = User::findOrfail($id)->update([
                        'name'      => $request->name,
                        'email'     => $request->email,
                        'password'  => bcrypt($request->password),
                        'nik'       => $request->nik,
                        'role'      => $request->role,
                        'address'    => $request->address,
                        'contact'    => $request->contact,
                ]);
                return sendResponse($data,'success update');
            }
        } catch (\Throwable $th) {
            return sendError('error','error');
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
            $data = User::destroy($id);
            if ($data==0) {                
                return sendError("User not found","error");
            } else {
                return sendResponse('Success Delete User','success');
            }                       
        } catch (\Exception $exception) {
            return sendError("User not found","error");
        }
    }

    public function detail($id)
    {
        try {
            $comment = DB::table('comments')
                        ->join('tours','tours.id','=','comments.tour_id')
                        ->where('comments.user_id',$id)
                        ->select('comments.*','tours.name as place')
                        ->get();
            $userDetail = User::find($id);
            $success['comment']  = $comment;
            $success['userDetail']  = $userDetail;
            
            return sendResponse($success, 'You are successfully get data.');
        } catch (\Throwable $th) {            
            return sendError('Unauthorised', ['error' => 'Unauthorised'], 401);
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            $token = Auth::user()->token();
            $token->revoke();
            return sendResponse('sukses', 'User is logout');
        } 
        else{ 
            return sendError('Unauthorised.', ['error'=>'Unauthorised'] , Response::HTTP_UNAUTHORIZED);
        } 
    }
}
