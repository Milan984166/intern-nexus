<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Intervention\Image\Facades\Image;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
     use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'status', 'image', 'phone', 'current_address', 'permanent_address', 'gender', 'dob', 'religion', 'maritial_status', 'nationality','remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function employer_info()
    {
        return $this->hasOne('App\EmployerInfo');
    }

    public function user_employer_info()
    {
        return $this->hasOne('App\UserEmployerInfo');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job');
    }

    // --------------------------------------------------------------------------------------------
    
    public function preference()
    {
        return $this->hasOne('App\JobPreference');
    }

    public function education()
    {
        return $this->hasMany('App\Education');
    }

    public function training()
    {
        return $this->hasMany('App\Training');
    }

    public function experience()
    {
        return $this->hasMany('App\Experience');
    }

    public function applicants()
    {
        return $this->hasMany('App\JobApply','employer_id');
    }

    public function applied_jobs()
    {
        return $this->hasMany('App\JobApply','jobseeker_id');
    }

    public function watchlist_jobs()
    {
        return $this->hasMany('App\JobWatchlist','jobseeker_id');
    }

    public static function resize_crop_images($max_width, $max_height, $image, $filename){
        $imgSize = getimagesize($image);
        $width = $imgSize[0];
        $height = $imgSize[1];

        $width_new = round($height * $max_width / $max_height);
        $height_new = round($width * $max_height / $max_width);

        if ($width_new > $width) {
            //cut point by height
            $h_point = round(($height - $height_new) / 2);

            $cover = storage_path('app/'.$filename);
            Image::make($image)->crop($width, $height_new, 0, $h_point)->resize($max_width, $max_height)->save($cover);
        } else {
            //cut point by width
            $w_point = round(($width - $width_new) / 2);
            $cover = storage_path('app/'.$filename);
            Image::make($image)->crop($width_new, $height, $w_point, 0)->resize($max_width, $max_height)->save($cover);
        }

    }
}
