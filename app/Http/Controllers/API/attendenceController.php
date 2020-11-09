<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\attendence;
use Carbon\Carbon;
use Validator;
use App\myaccount;
class attendenceController extends Controller
{
    public function show($id)
    {
        $today= Carbon::today();
        $attendence=attendence::where([['user_id',$id],['date',$today]])->get()->toarray()  ;
        if(!empty($attendence))
        {
            return response()->json(['details'=>$attendence]);
        }
        else{
            return response()->json(['Error'=>'Attendence not available']);
        }
    }

    public function create(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'user_id' => 'required',
            'location' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'weather'=>'required',
            'type'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $attendence=new attendence;
        $attendence->user_id=$req->user_id;
        $attendence->location=$req->location;
        $attendence->lat=$req->lat;
        $attendence->lng=$req->lng;
        $attendence->weather=$req->weather;
        $attendence->type=$req->type;
        $attendence->time=date('H:i:s');
        $attendence->date=date('Y-m-d');
        if($attendence->save())
        {
            return response()->json(['success'=>'insert Successfully']);
        }
        else{
            return response()->json(['error'=>'somthing wents wrong'],500);
        }
    }
        public function update(Request $req,$id)
        {

            $validator = Validator::make($req->all(), [
                'user_id' => 'required',
                'location' => 'required',
                'lat' => 'required',
                'lng' => 'required',
                'weather'=>'required',
                'type'=>'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);
            }
            $attendence_data=[
                'user_id'=>$req->user_id,
                'location'=>$req->location,
                'lat'=>$req->lat,
                'lng'=>$req->lng,
                'weather'=>$req->weather,
                'type'=>$req->type,
            ];
            $attendence_update=attendence::where('id',$id)->update($attendence_data);
            if($attendence_update==1)
            {
                return response()->json(['success'=>'Attedence record Updated Successfully']);
            }
            else{
                return response()->json(['error'=>'Record not found']);
            }
        }

        public function delete($id)
        {
            $att_del=attendence::find($id);
            if($att_del)
            {
                $att_del->delete();
                return response()->json(['success'=>'Record Deleted']);
            }
            return response()->json(['error'=>'Record not found']);
        }
       public function attendence($uid)
        {
            $uid=[$uid];
            $attendence['myAttendence']=myaccount::join('attendance','attendance.user_id','my_account.user_id')->whereIn('my_account.user_id',$uid)->get()->toarray();
            $att=array();
            while($uid){
                $usr=myaccount::select('user_id')->whereIn('report_to',$uid)->get()->toarray();
                $uid=null;
                foreach($usr as $u)
                {
                    $uid[]=$u['user_id'];
                    $att[]=$u['user_id'];
                }
            }
            $attendence['lowerLevel']=myaccount::join('attendance','attendance.user_id','my_account.user_id')->whereIn('my_account.user_id',$att)->get()->toarray();
            return response()->json(['error'=>false,'attendence'=>$attendence]);
        }
        public function checkOut($uid)
        {
            $clockin=attendence::where([['user_id',$uid],['type','like','clock-in'],['date',date('Y-m-d')]])->first();
            $clockout=attendence::where([['user_id',$uid],['type','like','clock-out'],['date',date('Y-m-d')]])->first();
            if($clockin)
            {
                $clock_in_time=$clockin->time;
                $clockin=true;

            }
            else{
                $clockin=false;
                $clock_in_time=null;
            }
            if($clockout)
            {
                $clock_out_time=$clockout->time;
                $clockout=true;

            }
            else{
                $clock_out_time=null;
            }
            return response()->json(['error'=>false,'clock_in'=>$clockin,'clock_in_time'=>$clock_in_time,'clock_out'=>$clockout,'clock_out_time'=>$clock_out_time],200);
        }
        public function attendenceDateWise($uid,$date)
        {
            $uid=[$uid];
            $attendence['myAttendence']=myaccount::join('attendance','attendance.user_id','my_account.user_id')->whereIn('my_account.user_id',$uid)->where('date',$date)->get()->toarray();
            $att=array();
            while($uid){
                $usr=myaccount::select('user_id')->whereIn('report_to',$uid)->get()->toarray();
                $uid=null;
                foreach($usr as $u)
                {
                    $uid[]=$u['user_id'];
                    $att[]=$u['user_id'];
                }
            }
            $attendence['lowerLevel']=myaccount::join('attendance','attendance.user_id','my_account.user_id')->whereIn('my_account.user_id',$att)->where('date',$date)->get()->toarray();
            $total=count($att);
            $present=myaccount::join('attendance','attendance.user_id','my_account.user_id')->whereIn('my_account.user_id',$att)->where([['date','like',$date],['type','like','clock-in']])->count();
            $absent=$total-$present;
            return response()->json(['error'=>false,'attendence'=>$attendence,'absent'=>$absent,'present'=>$present]);
        }
}
