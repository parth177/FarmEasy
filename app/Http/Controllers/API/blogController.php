<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\blogModel;
use Validator;
use Illuminate\Support\Facades\Storage; 
class blogController extends Controller
{
    public function show($uid)
    {
        $blog=blogModel::where('blog_by',$uid)->get()->toarray();
        if($blog)
        {
            return response()->json(['error'=>false,'blog'=>$blog], 200);
        }
    }
    public function new(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'title'=>'required',
            'description'=>'required',
            'blog_by'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 200);
        }
        $blog=new blogModel;
        $blog->title=$req->title;
        $blog->description=$req->description;
        $name="";
        if ($req->image) {
            if ($req->hasfile('image')) {
                $file=$req->file('image');
                $name=time().".jpg";
                Storage::disk('blog')->put($name, $file);
            }
        }
        $blog->image=$name;
        $blog->blog_by=$req->blog_by;
        $blog->save();
        return response()->json(['error'=>false,'message'=>'blog created..'], 200);
    }
    public function update(Request $req,$bid)
    {
        $validator = Validator::make($req->all(), [
            'title'=>'required',
            'description'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $blog=blogModel::find($bid);
        if($blog)
        {
            $blog->title=$req->title;
            $blog->description=$req->description;
            $name="";
            if ($req->image) {
                $image=blogModel::where('id',$bid)->first();
                if(Storage::disk('blog')->exists($image->image))
                {
                    Storage::disk('blog')->delete($image->image);
                }
                if ($req->hasfile('image')) {
                    $file=$req->file('image');
                    $name=time().".jpg";
                    Storage::disk('blog')->put($name, $file);
                }
                $blog->image=$name;
            }
            $blog->save();
            return response()->json(['error'=>false,'message'=>'blog updated..'], 200);
        }
        return response()->json(['error'=>true,'message'=>'blog not found..'], 400);
    }
    public function delete($bid)
    {
        $blog=blogModel::find($bid);
        if($blog)
        {
            $blog->delete();
            return response()->json(['error'=>false,'message'=>'blog deleted..'], 200);
        }
        return response()->json(['error'=>true,'message'=>'blog not found..'], 400);
    }
}
