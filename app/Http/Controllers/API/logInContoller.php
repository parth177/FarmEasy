<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User; 
use App\myaccount; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\Hash;

class logInContoller extends Controller
{
    public $successStatus = 200;
    public function login()
    {
        $psw=request('password');
        $user=myaccount::where([['email',request('email')],['password',$psw]])->count();
        if($user==1){ 
            $user = myaccount::where('email',request('email'))->get()->toarray(); 
            // $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            // $user = Auth::user(); 
            return response()->json(['success' => $user], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        }
    }
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email|unique:my_account', 
            'password' => 'required', 
            'mobile1'=>'required|digits:10|unique:my_account',
            'dob'=>'required',
            'address'=>'required',
            'gender'=>'required',
            'lng'=>'required',
            'lat'=>'required',
            'type_id'=>'required',
        ]);
        if ($validator->fails()) { 
                    return response()->json(['error'=>$validator->errors()], 401);            
                }
        $input = $request->all(); 
        if($request->profile)
        {
            if($request->hasfile('profile'))
            {
                $file=$request->file('profile');
                $name=time().".jpg";
                $file->move(public_path().'/profilePhoto/', $name);
                $input['profile']=$name;
            }
        }
        
        $input['password'] = $request->password; 
        $input['dob']=date('Y-m-d H:i:s',strtotime($request->dob));
        $input['designation_id']=$request->type_id;
        $input['date_time']=date('Y-m-d H:i:s');
        $user = myaccount::create($input); 
        // $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['success'=> $success], $this-> successStatus); 
    }
}
