<?php

namespace App\Http\Controllers\API;

use Validator;
use App\equipmentModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class equipmentController extends Controller
{
     public function show($fid)
    {
        $eq=equipmentModel::where('fid',$fid)->get()->toArray();
        if($eq)
        {
            return response()->json(['error'=>false,'equipment'=>$eq], 200);  
        } 
        return response()->json(['error'=>true,'equipment'=>[],'message'=>'no equipment found'], 400);  
    }
    public function new(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'name' => 'required', 
            'model_no' => 'required', 
            'photo'=> 'required',
            'fid'=>'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>true,'message'=>$validator->errors()], 401);            
        }
        $name="";
        if ($req->photo) {
            if ($req->hasfile('photo')) {
                $file=$req->file('photo');
                $name=time().".jpg";
                $file->move(public_path().'/equiepmentPhoto/', $name);
            }
        }
        $eq=new equipmentModel;
        $eq->name=$req->name;
        $eq->model_no=$req->model_no;
        $eq->photo=$name;
        $eq->owening_type=$req->o_type;
        $eq->main_note=$req->main_note;
        $eq->mec_name=$req->mec_name;
        $eq->fid=$req->fid;
        $eq->mec_add=$req->mec_add;
        $eq->mec_phone=$req->mec_phone;
        $eq->save();
        return response()->json(['error'=>false,'message'=>'equiepment added..'], 200);
    }
    public function edit(Request $req,$eid)
    {
        $validator = Validator::make($req->all(), [ 
            'name' => 'required', 
            'model_no' => 'required', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>true,'message'=>$validator->errors()], 401);            
        }
        $eq=equipmentModel::find($eid);
        if($eq)
        {
            $eq->name=$req->name;
            $eq->model_no=$req->model_no;
            $eq->owening_type=$req->o_type;
            $eq->main_note=$req->main_note;
            $eq->mec_name=$req->mec_name;
            $eq->mec_add=$req->mec_add;
            $eq->mec_phone=$req->mec_phone;
            $eq->save();
            return response()->json(['error'=>false,'message'=>'equiepment updated..'], 200);
        }
        return response()->json(['error'=>true,'message'=>'equiepment not found..'], 400);
    }
    public function delete($eid)
    {
        $eq=equipmentModel::find($eid);
        if($eq)
        {
            $eq->delete();
            return response()->json(['error'=>false,'message'=>'equiepment deleted..'], 200);
        }
        return response()->json(['error'=>true,'message'=>'equiepment not found..'], 400);
    }
}
