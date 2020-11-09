<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\myaccount;
use Validator;
use App\activities;
class userController extends Controller
{
    public function farmars($uid)
    {
        $farmers=myaccount::select('user_id','name')->where([['report_to',$uid],['designation_id',3]])->get();
        return response()->json(['error'=>false,'farmers'=>$farmers],200);
    }
    public function userInfo($email)
    {
        $uinfo=myaccount::where('email',$email)->orWhere('mobile1','like',$email)->orWhere('mobile2','like',$email)->first();
        if($uinfo)
        {
            return response()->json(['error'=>false,'user_info'=>$uinfo],200);
        }
        return response()->json(['error'=>true,'user_info'=>$uinfo],200);
    }
    public function uploadImage(Request $req)
    {
        if($req->has('image'))
        {
            if($req->hasfile('image'))
            {
                $file=$req->file('image');
                $name=time().'.jpg';
                $file->move(public_path().'/extraImages/', $name);
                return response()->json(['error'=>false,'image'=>$name],200);
            }
        }
        return response()->json(['error'=>false,'image'=>'no image'],200);
    }
    public function chart(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'uid'=>'required',
            'from'=>'required',
            'to'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $users=myaccount::select('user_id')->where('report_to',$req->uid)->get();
        $chart=activities::join('activity_response','activity_response.activity_id','activities.id')->whereIn('activity_to',$users)->where([['activity_response.created_at','>=',$req->from],['activity_response.created_at','<=',$req->to]])->get();
        return response()->json(['error'=>false,'chart'=>$chart],200);
    }
    public function chart2(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'uid'=>'required',
            'from'=>'required',
            'to'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $users=myaccount::select('user_id')->where('report_to',$req->uid)->get();
        $chart=activities::whereIn('activity_to',$users)->where([['created_at','>=',$req->from],['created_at','<=',$req->to]])->get();
        return response()->json(['error'=>false,'chart'=>$chart],200);
    }
    public function designations()
    {
        $designation=\DB::table('designation')->wherein('id',['3','5'])->get();
        return response()->json(['error'=>false,'designation'=>$designation],200);
    }
}
