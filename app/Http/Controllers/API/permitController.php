<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\permitModel;
use Validator;

class permitController extends Controller
{
    public function show($id)
    {
        $permit=permitModel::where('id',$id)->get()->toarray();
        if(!empty($permit))
        {
            return response()->json(['permit'=>$permit]);
        }
        return response()->json(['Error'=>'Invalid Id']);
    }
    public function userShow($id)
    {
        $permit=permitModel::where('user_id',$id)->get()->toarray();
        if(!empty($permit))
        {
            return response()->json(['permit'=>$permit],200);
        }
        return response()->json(['Error'=>'Permit not found'],400);
    }
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'user_id' => 'required', 
            'name' => 'required', 
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        if($req->hasfile('image'))
        {
            $file=$req->file('image');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/permitPhotos/', $name);
        }
        $permit=new permitModel;
        $permit->user_id=$req->user_id;
        $permit->photopath=$name;
        $permit->isApproved=0;
        $permit->name=$req->name;
        if($permit->save())
        {
            return response()->json(['success'=>' Permit inserted Successfully'],200);
        }
        return response()->json(['error'=>'Somthing wents wrong'],500);

    }

    public function update(Request $req,$id)
    {
        $validator = Validator::make($req->all(), [ 
            'user_id' => 'required', 
            'name' => 'required', 
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isApproved'=>'required|numeric|in:1,0'

        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        if($req->hasfile('image'))
        {
            $file=$req->file('image');
            $name=time().$file->getClientOriginalName();
            $file->move(public_path().'/permitPhotos/', $name);
        }
        $permit_data=[
            'user_id'=>$req->user_id,
            'name'=>$req->name,
            'photopath'=>$name,
            'isApproved'=>$req->isApproved,
        ];
        $permit_update=permitModel::where('id',$id)->update($permit_data);
        if($permit_update==1)
        {
            return response()->json(['success'=>' Permit updated Successfully'],200);
        }
        return response()->json(['error'=>'Record not found'],500);

    }
    public function delete($id)
    {
        $permit_del=permitModel::find($id);
        if($permit_del)
        {
            $permit_del->delete();
            return response()->json(['success'=>'Record Deleted']);
        }
        return response()->json(['error'=>'Record not found']);
    }
}
