<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
	protected $fillable = ['job_category_id', 'title'];

	protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function job_category()
    {
    	return $this->belongsTo('App\JobCategory');
    }

}
