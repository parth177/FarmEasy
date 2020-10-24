<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class myaccount extends Model
{
    protected $table="my_account";
    
    public $timestamps=false;
    protected $fillable = [
        'name', 'email', 'password','dob','address','mobile1','mobile2','profile','gender','lng','lat','designation_id','date_time'
    ];
}
