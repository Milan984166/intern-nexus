<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{


	protected $fillable = [
        'title', 'slug', 'display', 'order_item', 'created_by', 'updated_by'
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function skill()
    {
    	return $this->hasMany('App\Skill');
    }

    public function employer_info()
    {
    	return $this->hasMany('App\EmployerInfo','category_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job','job_category_id');
    }

}
