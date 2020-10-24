<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\equipmentModel;

class equipmentController extends Controller
{
    public function new(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'name' => 'required', 
            'model_no' => 'required|numeric',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $e=new equipmentModel;
        $e->name=$req->name;
        $e->model_no=$req->model_no;
        $e->owning_type=$req->owning_type;
        $e->main_notes=$req->main_notes;
        $e->mec_name=$req->mec_name;
        $e->mec_phon=$req->mec_phon;
        $e->mec_add=$req->mec_add;
        if($e->save())
        {
            
        }
    }
}
