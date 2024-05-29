<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobIndustry extends Model
{
    protected $fillable = [
        'title', 'slug', 'display', 'order_item', 'created_by', 'updated_by'
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function employer_info()
    {
    	return $this->hasMany('App\EmployerInfo','industry_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job','job_industry_id');
    }
}
