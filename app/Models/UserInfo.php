<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Account;
class UserInfo extends Model
{
    //
    protected $fillable = [
        'firstname',
        'lastname',
        'contactNumber',
        'address',
        'points'
    ];

    protected $table = "user_info";

    public function account()
    {
        return $this->belongsTo('App\User',"userId" , "id");
    }
   
}
