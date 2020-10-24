<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User; 
use App\myaccount;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use Illuminate\Support\Facades\Hash;
use App\permitModel;
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
                    $success['error'] =  true;
                    $success['msg'] = 'Mobile or Email already in use try different one..';
                    return response()->json(['error'=>$success], 200);            
                }
        $input = $request->all(); 
        if ($request->profile) {
            if ($request->hasfile('profile')) {
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
    public function profileView($uid)
    {
        $user=myaccount::join('designation','designation.id','designation_id')
        ->select('my_account.*','designation.name as designation')
        ->where('user_id',$uid)->get()->toArray();
        if($user)
        {
            return response()->json(['error'=>false,'user'=> $user], $this-> successStatus);
        }
        return response()->json(['error'=>true,'user'=> [],'message'=>'User not found'], 400);
    }
    public function update(Request $request,$uid)
    {
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'mobile1'=>'required|digits:10',
            'dob'=>'required',
            'address'=>'required',
            'gender'=>'required',
            'lng'=>'required',
            'lat'=>'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>true,'message'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        if ($request->profile) 
        {
            $u=myaccount::where('user_id',$uid)->first();
            if(Storage::disk('profile')->exists($u->profile))
            {
                Storage::disk('profile')->delete($u->profile);
            }
            if ($request->hasfile('profile')) {
                $file=$request->file('profile');
                $name=time().".jpg";
                $file->move(public_path().'/profilePhoto/', $name);
                $input['profile']=$name;
                // dd($name);
            }
        }
        $input['dob']=date('Y-m-d H:i:s',strtotime($request->dob));
        $user = myaccount::where('user_id',$uid)->update($input);
        return response()->json(['error'=>false,'message'=>'profile updated'], 200);
    }
    public function passReset(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
           'uid'=>'required',
           'newpass'=>'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>true,'message'=>$validator->errors()], 401);            
        }        
        $user=myaccount::where('user_id',$req->uid)->update(['password'=>$req->newpass]);
            return response()->json(['error'=>false,'message'=>'Password changed..'], 200); 
        
    }
    public function addResource(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
           'name'=>'required',
           'type'=>'required',
           'email'=>'required',
           'phone'=>'required',
           'address'=>'required',
           'designation'=>'required',
           'dob'=>'required',
           'gender'=>'required',
           'password'=>'required'
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>true,'message'=>$validator->errors()], 401);            
        } 
        if($req->permit)
        {
            $validator = Validator::make($req->all(), [ 
               'permit_name'=>'required',
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>true,'message'=>$validator->errors()], 401);            
            }
        } 
        
        $res=new myaccount;
        $res->name=$req->name;
        $res->u_type=$req->type;
        $res->email=$req->email;
        $res->password=$req->password;
        $res->mobile1=$req->phone;
        $res->address=$req->address;
        $res->designation_id=$req->designation;
        $res->dob=date('Y-m-d',strtotime($req->dob));
        $res->gender=$req->gender;
        $res->date_time=date('Y-m-d H:i:s');
        $res->save(); 
        if($req->permit)
        {
            
            $pr=new permitModel;
            $pr->user_id=$res->id;
            $file=$req->file('permit');
            $name=time().".jpg";
            $file->move(public_path().'/permitPhotos/', $name);
            $pr->photopath=$name;
            $pr->name=$req->permit_name;
            $pr->save();
        }
          return response()->json(['error'=>false,'message'=>'Resource added successfully..'], 200); 
    }
}
