<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //
    public $fillable = [
        'id',
        'name',
        'address',
        'description',
        'contactNumber',
        'email',
        'password',
        'imageUrl',
        'timestamp'
    ];

    protected $table = "stores";

    public function account()
    {
        return $this->belongsTo('App\User',"userId" , "id");
    }

    public function product() {
        return $this->hasMany('App\Models\Products','storeId' , 'id');
    }
}
