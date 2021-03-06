<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\activities;
use Validator;
use App\myaccount;
use App\farm;
use App\activityResponse;
use Illuminate\Support\Facades\Storage;

class activitiesController extends Controller
{
    public function show($id)
    {
        $activities=activities::where('id',$id)->get()->toarray();
        if(!empty($activities))
        {
            return response()->json(['activities'=>$activities]);
        }
        return response()->json(['Error'=>'Invalid Id']);
    }
    public function userShow($id)
    {
        $activities=activities::where('activity_by',$id)->get()->toarray();
        if(!empty($activities))
        {
            return response()->json(['activities'=>$activities]);
        }
        return response()->json(['Error'=>'Activity Not found']);
    }
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required',
            'descr' => 'required',
            'activity_by' => 'required',
            'activity_to' => 'required',
            'activity_date'=>'required',
            'activity_time'=>'required',
            'weather'=>'required',
            'elevation'=>'required',
            'lat'=>'required',
            'logtitute'=>'required',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $response = Http::get('api.openweathermap.org/data/2.5/weather', [
            'lat' => $req->lat,
            'lon' => $req->logtitute,
            'appid'=>'68e5fd7465f242db40ea574548292789'
        ]);
        if($req->hasfile('image'))
        {
            $file=$req->file('image');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/activityImages/', $name);
        }
        $activities=new activities;
        $activities->title=$req->title;
        $activities->descr=$req->descr;
        // dd($req->activity_date);
        $activities->date=date('Y-m-d',strtotime($req->activity_date));
        $activities->time=date('H:i:s',strtotime($req->activity_time));
        if($response->status()==200)
        {
            $activities->elevation=$response['main']['sea_level'];
            $activities->temp=$response['main']['temp'];
            $activities->weather=$response['weather'][0]['main'];
        }
        else{
            $activities->elevation=$req->elevation;
            $activities->weather=$req->weather;
            $activities->temp=0;
        }
        $activities->activity_by=$req->activity_by;
        $activities->activity_to=$req->activity_to;
        $activities->isComplete=0;
        $activities->isReassigned=0;
        $activities->remark=$req->remark;
        $activities->lat=$req->lat;
        $activities->longtitute=$req->logtitute;
        $activities->image=$name;
        $activities->notes=$req->notes;
        $activities->subActivities=$req->subActivities;
        $activities->notes=$req->notes;
        $activities->location=$req->location;
        $activities->rating=$req->rating;
        if($activities->save())
        {
            return response()->json(['success'=>' Activity inserted Successfully'],200);
        }
        else{
            return response()->json(['error'=>'Somthing wents wrong'],500);
        }
    }
    public function update(Request $req,$id)
    {
        $validator = Validator::make($req->all(), [
            'title' => 'required',
            'descr' => 'required',
            'activity_by' => 'required',
            'activity_to' => 'required',
            'weather'=>'required',
            'elevation'=>'required',
            'lat'=>'required',
            'logtitute'=>'required',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        if($req->hasfile('image'))
        {
            $file=$req->file('image');
            $name=$file->getClientOriginalName();
            $file->move(public_path().'/activityImages/', $name);
        }

        $activities_data=[
            'title'=>$req->title,
            'descr'=>$req->descr,
            'activity_by'=>$req->activity_by,
            'activity_to'=>$req->activity_to,
            'weather'=>$req->weather,
            'elevation'=>$req->elevation,
            'lat'=>$req->lat,
            'longtitute'=>$req->logtitute,
            'image'=>$req->image,
            'location'=>$req->location,
            'notes'=>$req->notes,
            'subActivities'=>$req->subActivities,
        ];

        $activities_update=activities::where('id',$id)->update($activities_data);
        if($activities_update==1)
        {
            return response()->json(['success'=>' Acctivity updated Successfully'],200);
        }
        else{
            return response()->json(['error'=>'Record not found'],500);
        }

    }
    public function delete($id)
    {
        $act_del=activities::find($id);
        if($act_del)
        {
            $act_del->delete();
            return response()->json(['success'=>'Record Deleted']);
        }
        return response()->json(['error'=>'Record not found']);
    }
    public function show2($id)
    {
         $uid=[$id];
            $today['myActivities']=myaccount::join('activities','activities.activity_by','my_account.user_id')->whereIn('my_account.user_id',$uid)->where('date',date('Y-m-d'))->select('activities.*')->get()->toarray();
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
            $today['lowerLevel']=myaccount::join('activities','activities.activity_by','my_account.user_id')->whereIn('my_account.user_id',$att)->where('date',date('Y-m-d'))->select('activities.*')->get()->toarray();

            $uid=[$id];
            $tomorrow['myActivities']=myaccount::join('activities','activities.activity_by','my_account.user_id')->whereIn('my_account.user_id',$uid)->where('activity_to','!=',$id)->where('date',date("Y-m-d", strtotime("+1 day")))->select('activities.*')->get()->toarray();
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
            $tomorrow['lowerLevel']=myaccount::join('activities','activities.activity_by','my_account.user_id')->whereIn('my_account.user_id',$att)->where('date',date("Y-m-d", strtotime("+1 day")))->select('activities.*')->get()->toarray();
            return response()->json(['error'=>false,'today'=>$today,'tomorrow'=>$tomorrow],200);
    }
    public function showdate($date,$uid)
    {
         $uid=[$uid];
         $date=date('Y-m-d',strtotime($date));
            $activities['myActivities']=myaccount::join('activities','activities.activity_by','my_account.user_id')->whereIn('my_account.user_id',$uid)->where('date',$date)->select('activities.*')->get()->toarray();
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
            $activities['lowerLevel']=myaccount::join('activities','activities.activity_by','my_account.user_id')->whereIn('my_account.user_id',$att)->where('date',$date)->select('activities.*')->get()->toarray();
            return response()->json(['error'=>false,'activities'=>$activities],200);
    }
     public function showdate2($date,$uid)
    {
         $date=date('Y-m-d',strtotime($date));
            $activities=myaccount::join('activities','activities.activity_by','my_account.user_id')->where('my_account.user_id',$uid)->where('date',$date)->select('activities.*')->get()->toarray();

            return response()->json(['error'=>false,'activities'=>$activities],200);
    }
    public function activityResponse($aid)
    {
        $ar=activityResponse::where('activity_id',$aid)->get()->toarray();
        return response()->json(['error'=>false,'activity_response'=>$ar],200);
    }
    public function addActivityResponse(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'activity_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $name="";
        if ($req->image) {
            if ($req->hasfile('image')) {
                $file=$req->file('image');
                $name=time().".jpg";
                $file->move(public_path().'/activityResponse/', $name);
            }
        }
        $ar=new activityResponse;
        $ar->activity_id=$req->activity_id;
        $ar->subActivities=$req->subActivities;
        $ar->image=$name;
        $ar->remark=$ar->remark;
        $ar->rating=$ar->rating;
        $ar->save();
        return response()->json(['error'=>false,'message'=>'Activity response added..','isComplete'=>1],200);
    }
    public function updateActivityResponse(Request $req,$id)
    {
        $ar=activityResponse::find($id);
        $name="";
            if ($req->image) {
                $image=activityResponse::where('id',$id)->first();
                if(Storage::disk('activity')->exists($image->image))
                {
                    Storage::disk('activity')->delete($image->image);
                }
                if ($req->hasfile('image')) {
                    $file=$req->file('image');
                    $name=time().".jpg";
                    $file->move(public_path().'/activityResponse/', $name);
                }
                $ar->image=$name;
            }
        $ar->subActivities=$req->subActivities;
        $ar->remark=$req->remark;
        $ar->rating=$req->rating;
        $ar->save();
        return response()->json(['error'=>false,'message'=>'Activity response updated..'],200);
    }
    public function activityInfo($uid)
    {
        $farmers=myaccount::select('user_id','name')->where([['report_to',$uid],['designation_id',3]])->get();
        $farm=farm::where('uid',$uid)->get()->toarray();
        $farm_work=\DB::table('farm_work')->get();
        if($farm)
        {
            return response()->json(['error'=>false,'farms'=>$farm], 200);
        }
        return response()->json(['error'=>false,'farm'=>$farm,'farm_works'=>$farm_work,'farmers'=>$farmers],200);
    }
    public function allActivities($uid)
    {
        $activities=activities::where('activity_by',$uid)->orwhere('activity_to',$uid)->get();
        foreach($activities as $ac)
        {
            $user=myaccount::where('user_id',$ac['activity_to'])->first();
            $user2=myaccount::where('user_id',$ac['activity_by'])->first();
            $ac->activity_to=$user->name;
            $ac->activity_by=$user2->name;
        }
        return response()->json(['error'=>false,'activities'=>$activities],200);
    }
}
