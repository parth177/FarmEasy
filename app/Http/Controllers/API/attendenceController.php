<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\attendence;
use Carbon\Carbon;
use Validator;
class attendenceController extends Controller
{
    public function show($id)
    {
        $today= Carbon::today();
        $attendence=attendence::where([['user_id',$id],['date',$today]])->get()->toarray()  ;
        if(!empty($attendence))
        {
            return response()->json(['details'=>$attendence]); 
        }
        else{
            return response()->json(['Error'=>'Attendence not available']); 
        }
    }
    public function create(Request $req)
    {
       
        $validator = Validator::make($req->all(), [ 
            'user_id' => 'required', 
            'location' => 'required', 
            'lat' => 'required', 
            'lng' => 'required', 
            'weather'=>'required',
            'type'=>'required'
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $attendence=new attendence;
        $attendence->user_id=$req->user_id;
        $attendence->location=$req->location;
        $attendence->lat=$req->lat;
        $attendence->lng=$req->lng;
        $attendence->weather=$req->weather;
        $attendence->type=$req->type;
        $attendence->time=date('H:i:s');
        $attendence->date=date('Y-m-d');
        if($attendence->save())
        {
            return response()->json(['success'=>'insert Successfully']);
        }
        else{
            return response()->json(['error'=>'somthing wents wrong'],500);
        }
    }
        public function update(Request $req,$id)
        {
        
            $validator = Validator::make($req->all(), [ 
                'user_id' => 'required', 
                'location' => 'required', 
                'lat' => 'required', 
                'lng' => 'required', 
                'weather'=>'required',
                'type'=>'required'
            ]);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            $attendence_data=[
                'user_id'=>$req->user_id,
                'location'=>$req->location,
                'lat'=>$req->lat,
                'lng'=>$req->lng,
                'weather'=>$req->weather,
                'type'=>$req->type,
            ];
            $attendence_update=attendence::where('id',$id)->update($attendence_data);
            if($attendence_update==1)
            {
                return response()->json(['success'=>'Attedence record Updated Successfully']);
            }
            else{
                return response()->json(['error'=>'Record not found']);
            }
        }

        public function delete($id)
        {
            $att_del=attendence::find($id);
            if($att_del)
            {
                $att_del->delete();
                return response()->json(['success'=>'Record Deleted']);
            }
            return response()->json(['error'=>'Record not found']);
        }

}
