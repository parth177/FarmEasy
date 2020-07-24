<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User; 
use App\myaccount; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class logInContoller extends Controller
{
    public $successStatus = 200;
    public function login()
    {
        // $psw=Hash::make(request('password'));
        $user=myaccount::where([['email',request('email')],['password', request('password')]])->count();
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
    // public function register(Request $request) 
    // { 
    //     $validator = Validator::make($request->all(), [ 
    //         'name' => 'required', 
    //         'email' => 'required|email', 
    //         'password' => 'required', 
    //         'c_password' => 'required|same:password', 
    //     ]);
    //     if ($validator->fails()) { 
    //                 return response()->json(['error'=>$validator->errors()], 401);            
    //             }
    //     $input = $request->all(); 
    //             $input['password'] = bcrypt($input['password']); 
    //             $user = User::create($input); 
    //             $success['token'] =  $user->createToken('MyApp')-> accessToken; 
    //             $success['name'] =  $user->name;
    //     return response()->json(['success'=>$success], $this-> successStatus); 
    // }
}
