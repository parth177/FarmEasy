<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\notificationModel;
class notificationController extends Controller
{
    public function show($id)
    {
        $notification=notificationModel::where('id',$id)->get()->toarray();
        if(!empty($notification))
        {
            return response()->json(['notification'=>$notification],200);
        }
        return response()->json(['Error'=>'Invalid Id']);
    }
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'sender' => 'required', 
            'receiver' => 'required', 
            'title'=> 'required|min:2',
            'description'=>'required',
            // 'date_time'=>'required|date_format:Y-m-d H:i:s',
            'noti_type'=>'required'

        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $notification=new notificationModel;
        $notification->sender=$req->sender;
        $notification->receiver=$req->receiver;
        $notification->title=$req->title;
        $notification->description=$req->title;
        $notification->date_time=date('Y-m-d H:i:s');
        $notification->isRead=0;
        $notification->noti_type=$req->noti_type;
        if($notification->save())
        {
            return response()->json(['success'=>' Notification inserted Successfully'],200);
        }
        return response()->json(['error'=>'Somthing wents wrong'],500);
        
    }
    public function update(Request $req,$id)
    {
        $validator = Validator::make($req->all(), [ 
            'sender' => 'required', 
            'receiver' => 'required', 
            'title'=> 'required|min:2',
            'description'=>'required',
            'date_time'=>'required|date_format:Y-m-d H:i:s',
            'noti_type'=>'required',
            'isRead'=>'required|in:1,0'

        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $notification_data=[
        'sender'=>$req->sender,
        'receiver'=>$req->receiver,
        'title'=>$req->title,
        'description'=>$req->title,
        'date_time'=>$req->date_time,
        'isRead'=>$req->isRead,
        'noti_type'=>$req->noti_type,
        ];

        $notification_update=notificationModel::where('id',$id)->update($notification_data);
        if($notification_update==1)
        {
            return response()->json(['success'=>' Notification updated Successfully'],200);
        }
        return response()->json(['error'=>'Record not found'],500);
        
    }
    public function delete($id)
    {
        $noti_del=notificationModel::find($id);
        if($noti_del)
        {
            $noti_del->delete();
            return response()->json(['success'=>'Record Deleted']);
        }
        return response()->json(['error'=>'Record not found']);
    }
}
