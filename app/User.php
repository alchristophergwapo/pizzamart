<?php

namespace App;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'email',
        'password',
        'userTypeId',
        'userInfoId'
    ];
    protected $table = "users";
    protected $hidden = [
        'password'
        ];

    public function userInfo()
    {
        return $this->hasOne('App\Models\UserInfo','id','userInfoId');
    }

    public function store() {
        return $this->hasOne('App\Models\Store', 'id', 'userInfoId');
    }

    public function setAttribute($key, $value)
{
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute)
        {
        parent::setAttribute($key, $value);
        }
}
}

