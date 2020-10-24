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
            'price'=>'required',
            'grade'=>'required',
            'fid'=>'required',
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
        if($o->save())
        {
            return response()->json(['error'=>false,'message'=>'Output added successfully..'],200);
        }
        return response()->json(['error'=>true,'message'=>'Somthing wents wrong..'],200);
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
}
