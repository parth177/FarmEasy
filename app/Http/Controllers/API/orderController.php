<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\orderModel;
use Validator;
use App\order_detail;
class orderController extends Controller
{
    public function updateStatus(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'order_id' => 'required|numeric', 
            'track_id' => 'required|numeric', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $order_data=[
        'tracking_stat'=>$req->track_id,
        ];

        $order_update=orderModel::where('id',$req->order_id)->update($order_data);
        if($order_update==1)
        {
            return response()->json(['success'=>'Order status updated Successfully'],200);
        }
        return response()->json(['error'=>'Record not found'],500);
        
    }
    public function updateisApproved(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'order_id' => 'required|numeric', 
            'is_approved' => 'required|in:0,1', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $order_data=[
        'isApproved'=>$req->is_approved,
        ];

        $order_update=orderModel::where('id',$req->order_id)->update($order_data);
        if($order_update==1)
        {
            return response()->json(['success'=>'Order status updated Successfully'],200);
        }
        return response()->json(['error'=>'Record not found'],500);
        
    }
    public function history($id)
    {
        $details=orderModel::join('order_details','order_details.order_id','orders.id')->join('stocks','stocks.id','order_details.product_id')->where('vendor_id',$id)->get();
        if(count($details)>0)
            return response()->json(['success'=>$details],200);
        
        return response()->json(['error'=>'Data not found'],400);
    }
}
