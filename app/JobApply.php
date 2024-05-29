<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    protected $fillable = [
		    'job_id', 'employer_id', 'jobseeker_id', 'status', 'approved'
		];

	public function job_seeker()
	{
		return $this->belongsTo('App\User','jobseeker_id');
	}

	public function job_details()
	{
		return $this->belongsTo('App\Job','job_id');
	}

	public function employer()
	{
		return $this->belongsTo('App\User','employer_id');
	}

}
