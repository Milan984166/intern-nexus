<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployerInfo extends Model
{
    protected $fillable = [
		    'user_id', 'organization_name', 'slug', 'email', 'address', 'phone', 'image', 'category_id', 'ownership_type', 'organization_size', 'website', 'facebook', 'twitter', 'linkedin',  'cp_name', 'cp_email', 'cp_designation', 'cp_contact', 'about'
		];

    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];

    public function employer_category()
    {
        return $this->belongsTo('App\JobCategory','category_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function user_employer_info()
    {
        return $this->hasMany('App\UserEmployerInfo');
    }


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
        return EmployerInfo::select('slug')->where('slug', 'like', $slug.'%')
            ->where('user_id', '<>', $id)
            ->get();
    }
}
