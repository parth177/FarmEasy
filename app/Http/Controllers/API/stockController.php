<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\stock;
use App\stock_item;
use Validator;

class stockController extends Controller
{
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'item_name' => 'required', 
            'price' => 'required|numeric', 
            'amount'=> 'required|numeric',
            'count'=>'required|numeric',
            'uid'=>'required',
            'remarks'=>'required',

        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $stock=new stock;
        $stock->item_name=$req->item_name;
        $stock->price=$req->price;
        $stock->uid=$req->uid;
        $stock->save();
        
        $si=new stock_item;
        $si->stock_id=$stock->id;
        $si->amount=$req->amount;
        $si->count=$req->count;
        $si->remarks=$req->remarks;
        if($si->save())
        return response()->json(['success'=>'Stock Inserted successfully'],200);

    }
    public function supplies($uid)
    {
        $stock=stock::join('stock_item','stock_item.stock_id','stocks.id')->where('stocks.uid',$uid)->get()->toArray();
        if($stock)
        {
            return response()->json(['error'=>false,'stock'=>$stock],200);
        }
        return response()->json(['error'=>true,'stock'=>[]],400);
    }
}
