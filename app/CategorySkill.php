<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategorySkill extends Model
{
    protected $fillable = [
		    'job_preference_id', 'job_category_id', 'skill_ids'
		];

	protected $hidden = [
        'created_at', 'updated_at'
    ];

	public function job_category()
	{
		return $this->belongsTo('App\JobCategory','job_category_id');
	}
}
