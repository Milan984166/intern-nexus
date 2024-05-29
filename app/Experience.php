<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
		    'user_id', 'organization_name', 'job_location', 'job_title', 'job_category_id', 'working_here', 'start_year', 'start_month', 'end_year', 'end_month', 'duties_responsibilities', 'created_by', 'updated_by'
		];

	protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
