<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
	protected $fillable = [
		    'user_id', 'name', 'institution', 'duration', 'duration_unit', 'year', 'month', 'created_by', 'updated_by'
		];

	protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
