<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPreference extends Model
{
    protected $fillable = [
		    'user_id', 'looking_for', 'employment_type', 'expected_salary', 'expected_salary_period', 'career_objective', 'location_id', 'created_by', 'updated_by'
		];

	protected $hidden = [
        'created_by', 'updated_by', 'updated_at'
    ];

	public function category_skills()
    {
        return $this->hasMany('App\CategorySkill');
    }
}

