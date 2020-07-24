<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\activities;
use Validator;

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
    public function create(Request $req)
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
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/activityImages/', $name);
        }
        $activities=new activities;
        $activities->title=$req->title;
        $activities->descr=$req->descr;
        $activities->date=date('Y-m-d');
        $activities->time=date('H:i:s');
        $activities->activity_by=$req->activity_by;
        $activities->activity_to=$req->activity_to;
        $activities->isComplete=0;
        $activities->isReassigned=0;
        $activities->weather=$req->weather;
        $activities->remark=$req->remark;
        $activities->elevation=$req->elevation;
        $activities->lat=$req->lat;
        $activities->longtitute=$req->logtitute;
        $activities->image=$name;
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

}
