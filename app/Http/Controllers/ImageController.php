<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function profile($name)
    {
        $storagePath = public_path().'/profilePhoto/'.$name;

        if(file_exists($storagePath))
        {
            return response()->file($storagePath);
        }
        else {
            return view('welcome');
        }
    }
    public function equ($name)
    {
        $storagePath = public_path().'/equiepmentPhoto/'.$name;

        if(file_exists($storagePath))
        {
            return response()->file($storagePath);
        }
        else {
            return view('welcome');
        }
    }
    public function image($name)
    {
        $storagePath = public_path().'/extraImages/'.$name;

        if(file_exists($storagePath))
        {
            return response()->file($storagePath);
        }
        else {
            return view('welcome');
        }
    }
}
