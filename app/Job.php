<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
		    'user_id', 'job_title', 'slug', 'job_category_id', 'skill_ids', 'no_of_vacancy', 'job_level', 'employment_type', 'deadline', 'location_id', 'salary_type', 'min_salary', 'max_salary', 'image',
            'contact_email', 'contact_phone', 'messenger_link', 'viber_number', 'whatsapp_number', 'education_level', 'experience_type', 'experience_year', 'job_description', 'benefits', 'display', 'featured', 'views', 'applied'
		];

	public function job_category()
	{
		return $this->belongsTo('App\JobCategory','job_category_id');
	}

	public function location()
	{
		return $this->belongsTo('App\Location','location_id');
	}

	public function user()
	{
		return $this->belongsTo('App\User','user_id');
	}

    public function applicants()
    {
        return $this->hasMany('App\JobApply','job_id');
    }    

	// public function skills()
	// {
	// 	return $this->hasMany('App\Location','skill_ids');
	// }	

	// Slug check and create starts
	public static function createSlug($title, $id = 0)
    {
        $slug = str_slug($title);

        $allSlugs = Self::getRelatedSlugs($slug, $id);

        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }

        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug.'-'.$i;
            if (! $allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }
        
        throw new \Exception('Can not create a unique slug');
    }
    
    protected static function getRelatedSlugs($slug, $id = 0)
    {
        return Job::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }
}
