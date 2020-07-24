<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\activities;
use App\attendence;
use App\myaccount;
use Carbon\Carbon;
class deshboardController extends Controller
{
    public function show($id)
    {
        $activities=activities::where('activity_by',$id)->get()->toarray();
        $dashboard['activities']=$activities;
        $today= Carbon::today();
        $clockin=attendence::select('time')->where([['user_id',$id],['date',$today],['type','clock in']])->get()->toarray();
        $dashboard['clockIn']=$clockin;
        $clockin=attendence::select('time')->where([['user_id',$id],['date',$today],['type','clock out']])->get()->toarray();
        $dashboard['clockOut']=$clockin;
        $dashboard['weather']=25;
        $user=myaccount::select('user_id')->where('report_to',$id)->get()->toarray();
        $dashboard['lower_level']=myaccount::whereIn('user_id',$user)->get()->toarray();
        $report_to=myaccount::select('report_to')->where('user_id',$id)->get()->toarray();
        $dashboard['uper_level']=myaccount::whereIn('user_id',$report_to)->get()->toarray();
        $days=attendence::where([['user_id',$id],['type','clock in']])->get()->count();
        $wages=\DB::table('wages')->select('salary')->where('user_id',$id)->first();
        $dashboard['salary']=round($days*($wages->salary));
        return response()->json(['details'=>$dashboard]);
    }
}
