<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function jobs()
	{
		return $this->hasMany('App\Job');
	}
}
