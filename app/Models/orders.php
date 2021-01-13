<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Orders extends Model
{

    protected $fillable = [
        'trasactionId',
        'quantity',
        'productId',
        'amount',
        'timestamp',
        'userId',
        'status',
        'storeId'
    ];

    public function userInfor(){
        return $this->hasMany('App\products');
    }
   
}
