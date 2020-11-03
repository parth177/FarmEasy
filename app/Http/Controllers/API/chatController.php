<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\myaccount;
class chatController extends Controller
{
    public function userList($uid)
    {
        $user=myaccount::where('user_id',$uid)->first();
        $chatUsers=myaccount::where('user_id',$user->report_to)->orWhere(function ($query) use($uid,$user) {
            $query->where('report_to',$user->report_to)
                ->where('user_id','!=',$uid);})->orwhere('report_to',$uid)->get()->toarray();

        if($chatUsers)
        {
            return response()->json(['error'=>false,'chatUsers'=>$chatUsers],200);
        }
        return response()->json(['error'=>true,'chatUsers'=>$chatUsers],200);
    }
}
