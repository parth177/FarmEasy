<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\output;
use Validator;

class outputController extends Controller
{
    public function new(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'iname' => 'required',
            'qty'=>'required',
            'grade'=>'required',
            'fid'=>'required',
            'price'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $o=new output;
        $o->iname=$req->iname;
        $o->qty=$req->qty;
        $o->grade=$req->grade;
        $o->price=$req->price;
        $o->remark=$req->remark;
        $o->fid=$req->fid;
        $o->others=$req->others;
        if($o->save())
        {
            return response()->json(['error'=>false,'message'=>'Output added successfully..'],200);
        }
        return response()->json(['error'=>true,'message'=>'Somthing wents wrong..'],500);
    }
    public function show($fid)
    {
        $output=output::where('fid',$fid)->get()->toArray();
        if($output)
        {
            return response()->json(['error'=>false,'output'=>$output],200);
        }
        return response()->json(['error'=>true,'output'=>[],'message'=>'No output found..'],400);
    }
    public function delete($oid)
    {
        $output=output::find($oid);
        if($output)
        {
            $output->delete();
            return response()->json(['error'=>false,'message'=>'output deleted..'],200);
        }
        return response()->json(['error'=>true,'message'=>'No output found..'],400);
    }
    public function update(Request $req,$oid)
    {
        $validator = Validator::make($req->all(), [
            'iname' => 'required',
            'qty'=>'required',
            'grade'=>'required',
            'price'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $o=output::find($oid);
        if($o)
        {
            $o->iname=$req->iname;
            $o->qty=$req->qty;
            $o->grade=$req->grade;
            $o->price=$req->price;
            $o->remark=$req->remark;
            $o->others=$req->others;
            if($o->save())
            {
                return response()->json(['error'=>false,'message'=>'Output updated successfully..'],200);
            }
        }

        return response()->json(['error'=>true,'message'=>'Output not found..'],500);
    }
}
