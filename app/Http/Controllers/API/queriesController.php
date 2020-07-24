<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\queriesModel;
use Validator;
class queriesController extends Controller
{
    public function show($id)
    {
        $queries=queries::where('id',$id)->get()->toarray();
        if(!empty($queries))
        {
            return response()->json(['queries'=>$queries]);
        }
        return response()->json(['Error'=>'Invalid id']);
    }
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'sender' => 'required', 
            'rec' => 'required', 
            'message'=>'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        if($req->hasfile('photo'))
        {
            $file=$req->file('photo');
            $name=$file->getClientOriginalName();
            $file->move(public_path().'/queriesPhoto/', $name);
        }
        $queries=new queriesModel;
        $queries->sender=$req->sender;
        $queries->rec=$req->rec;
        $queries->isRead=0;
        $queries->isViewed=0;
        $queries->date=date('Y-m-d');
        $queries->message=$req->message;
        $queries->photo=$name;
        if($queries->save())
        {
            return response()->json(['success'=>' Query inserted Successfully']);
        }
        else{
            return response()->json(['error'=>'Somthing wents wrong'],500);
        }
    }
    public function update(Request $req,$id)
    {
        $validator = Validator::make($req->all(), [ 
            'sender' => 'required', 
            'rec' => 'required', 
            'message'=>'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        if($req->hasfile('photo'))
        {
            $file=$req->file('photo');
            $name=$file->getClientOriginalName();
            $file->move(public_path().'/queriesPhoto/', $name);
        }
        $query_data=[
            'sender'=>$req->sender,
            'rec'=>$req->rec,
            'message'=>$req->message,
            'photo'=>$name,
        ];
        $queries_update=queriesModel::where('id',$id)->update($query_data);
        if($queries_update==1)
        {
            return response()->json(['success'=>' Query updated Successfully']);
        }
        else{
            return response()->json(['error'=>'Record not found'],500);
        }
    }
    public function delete($id)
    {
        $que_del=queriesModel::find($id);
        if($que_del)
        {
            $que_del->delete();
            return response()->json(['success'=>'Record Deleted']);
        }
        return response()->json(['error'=>'Record not found']);
    }

}
