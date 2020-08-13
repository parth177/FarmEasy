<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\newsModel;
class newsController extends Controller
{
    public function show($id)
    {
        $news=newsModel::where('id',$id)->get()->toarray();
        if(!empty($news))
        {
            return response()->json(['news'=>$news]);
        }
        return response()->json(['Error'=>'Invalid Id']);
    }
    
    public function create(Request $req)
    {
        $validator = Validator::make($req->all(), [ 
            'news_type' => 'required', 
            'title' => 'required', 
            'description'=> 'required|min:2',

        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $news=new newsModel;
        $news->news_type=$req->news_type;
        $news->title=$req->title;
        $news->description=$req->description;
        $news->isActive=1;
        $news->datetime=date('Y-m-d H:i:s');
        if($news->save())
        {
            return response()->json(['success'=>' News inserted Successfully'],200);
        }
        return response()->json(['error'=>'Somthing wents wrong'],500);
    }
    public function update(Request $req,$id)
    {
        $validator = Validator::make($req->all(), [ 
            'news_type' => 'required', 
            'title' => 'required', 
            'description'=> 'required|min:2',
            'isActive'=>'required|in:1,0',
            'datetime'=>'required|date_format:Y-m-d H:i:s',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $news_data=[
            'news_type'=>$req->news_type,
            'title'=>$req->title,
            'description'=>$req->description,
            'isActive'=>$req->isActive,
            'datetime'=>$req->datetime
        ];
        $news_update=newsModel::where('id',$id)->update($news_data);
        if($news_update==1)
        {
            return response()->json(['success'=>' News updated Successfully'],200);
        }
        return response()->json(['error'=>'Record not found'],500);
    }
    public function delete($id)
    {
        $news_del=newsModel::find($id);
        if($news_del)
        {
            $news_del->delete();
            return response()->json(['success'=>'Record Deleted']);
        }
        return response()->json(['error'=>'Record not found']);
    }
    
}
