<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\farm;
use Validator;

class farmController extends Controller
{
    public function new(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'uid'=>'required',
            'name'=>'required',
            'lng'=>'required',
            'lat'=>'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $farm=new farm;
        $farm->uid=$req->uid;
        $farm->name=$req->name;
        $farm->lng=$req->lng;
        $farm->lat=$req->lat;
        if($farm->save())
        {
            return response()->json(['error'=>false,'message'=>'farm added..'], 200);            
        }
        return response()->json(['error'=>false,'message'=>'Somthings went wrong..'], 500);            
    }
    public function show($uid)
    {
        $farm=farm::where('uid',$uid)->get()->toarray();
        if($farm)
        {
            return response()->json(['error'=>false,'farms'=>$farm], 200);            
        }
        return response()->json(['error'=>true,'farms'=>[],'message'=>'no farms available'], 400);            
    }
}
