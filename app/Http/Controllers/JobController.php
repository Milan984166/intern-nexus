<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Services\ProductPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use App;
use App\Pages;
use App\Slider;
use App\JobCategory;
use App\Skill;
use App\EmployerInfo;
use App\Location;
use App\User;
use App\Job;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
class JobController extends Controller
{
    public function companies()
    {
        $popular_companies = EmployerInfo::orderBy('premium', 'desc')->get();
        // dd($popular_companies);
        $companies = $popular_companies->groupBy(function($item,$key) {
                return $item->organization_name[0];     //treats the name string as an array
            })->sortBy(function($item,$key){      //sorts A-Z at the top level
                    return $key;
            });

        $job_categories = JobCategory::where('display',1)->orderBy('order_item')->limit(8)->get();

        return view('companies',compact('companies','popular_companies','job_categories'));
    }

    public function categories()
    {
        $popular_job_categories = JobCategory::where('display',1)->orderBy('order_item')->get();
        $companies = User::has('employer_info')->whereIn('role',[3,4])->where('status',1)->limit(8)->get();

        $categories = $popular_job_categories->split(3);
        // dd($categories);
        return view('categories',compact('categories','popular_job_categories','companies'));
    }

    public function jobs()
    {
    	$job_categories = JobCategory::where('display',1)->orderBy('order_item')->get();
    	$companies = User::has('employer_info')->whereIn('role',[3,4])->where('status',1)->limit(8)->get();
    	// dd($companies);
    	$jobs = Job::orderBy('created_at','desc')->get();
        $featured_jobs = Job::where([['featured',1], ['deadline','>',date('Y-m-d H:i:s')]])->orderBy('created_at','desc')->get();
        // dd($featured_jobs);

    	$pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        // $jobs = ProductPaginator::paginate_products($jobs, $currentPage, $perPage);

        // $grouped = $jobs->groupBy(function($item,$key) {
        //         return $item->job_title[0];     //treats the name string as an array
        //     })
        //     ->sortBy(function($item,$key){      //sorts A-Z at the top level
        //             return $key;
        //     });

        //     dd($grouped);

    	return view('jobs.jobs',compact('jobs','job_categories','companies','featured_jobs'));
    }

    public function company_jobs($slug)
    {
    	$employer_info = EmployerInfo::where('slug', $slug)->firstOrFail(); 
        $employer = $employer_info->user;
        // dd($employer);
        $jobs = $employer->jobs;
        $job_categories = JobCategory::where('display',1)->orderBy('order_item')->get();

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        $jobs = ProductPaginator::paginate_products($jobs, $currentPage, $perPage);


        return view('jobs.company-jobs', compact('employer','employer_info','jobs','job_categories'));
    }

    public function category_jobs($slug)
    {
        $job_category = JobCategory::where('slug',$slug)->firstOrFail();
        $companies = User::has('employer_info')->whereIn('role',[3,4])->where('status',1)->limit(8)->get();
        $jobs = $job_category->jobs;

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        $jobs = ProductPaginator::paginate_products($jobs, $currentPage, $perPage);

        return view('jobs.category-jobs',compact('job_category','jobs','companies'));
    }

    public function location_jobs($slug)
    {
        $location = Location::where('slug',$slug)->firstOrFail();
        
        $companies = User::has('employer_info')->whereIn('role',[3,4])->where('status',1)->limit(8)->get();

        $jobs = $location->jobs;

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        $jobs = ProductPaginator::paginate_products($jobs, $currentPage, $perPage);
        return view('jobs.location-jobs',compact('location','jobs','companies'));
    }
}
