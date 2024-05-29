<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEmployerInfo extends Model
{
    protected $fillable = [
        'user_id', 'employer_info_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function employer_info()
    {
        return $this->belongsTo('App\EmployerInfo','employer_info_id');
    }

}
