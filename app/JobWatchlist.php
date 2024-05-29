<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobWatchlist extends Model
{
    protected $fillable = [
		    'job_id', 'jobseeker_id'
		];

	public function job_seeker()
	{
		return $this->belongsTo('App\User','jobseeker_id');
	}

	public function job_details()
	{
		return $this->belongsTo('App\Job','job_id');
	}

}
