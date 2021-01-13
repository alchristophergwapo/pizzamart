<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'id',
        'productName',
        'description',
        'price',
        'imageUrl',
        'sold',
        'storeId'
    ];

    public function store() {
        return $this->hasOne('App\Models\Store','id', 'storeId');
    }

    public function isOwned() {
        return $this.belongsTo('App\Model\Store');
    }
}
