<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Services\ProductPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App;
use App\User;
use App\Pages;
use App\Slider;
use App\JobCategory;
use App\Skill;
use App\NewsEvent;
use App\Location;
use App\Setting;
use App\Job;
use App\SkillAssessment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $job_categories = JobCategory::where('display',1)->orderBy('order_item')->limit(12)->get();
        $recent_hot_jobs = Job::orderBy('created_at','desc')->limit('20')->get();
        $top_locations = Location::where('display',1)->limit(5)->get();
        $featured_jobs = Job::where([['featured',1], ['deadline','>',date('Y-m-d H:i:s')]])->orderBy('created_at','desc')->get();
        // dd($search_categories);
        return view('home',compact('job_categories','recent_hot_jobs','top_locations','featured_jobs'));
    }

    public function job_details($slug)
    {
        $job = Job::where('slug',$slug)->firstOrFail(); 
        $dbViews = $job->views;
        $updateViews = Job::where('slug',$slug)->update(['views' => $dbViews+1]);
        $job = Job::where('slug',$slug)->firstOrFail(); 
        $employer_info = $job->user->employer_info;

        $featured_jobs = Job::where([['id','!=',$job->id],['display',1]])->orderBy('views','desc')->limit(4)->get();
        return view('jobs.job-details',compact('job','employer_info','featured_jobs'));
    }

    public function jobseeker_details($id)
    {
        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
            if (!in_array(Auth::user()->role, ['3','4'])) {
                return redirect()->back()->with('error', 'You are not Employer!!');
            }
        }

        
        $jobseeker = User::where('id', base64_decode($id))->firstOrFail(); 
        if ($jobseeker->role != 2) {
            return redirect()->back()->with('error','Something Went Wrong!');
        }
        $educations = $jobseeker->education;
        $experiences = $jobseeker->experience;
        $trainings = $jobseeker->training;
        $preferences = $jobseeker->preference;
        $applied_jobs = $jobseeker->applied_jobs()->orderBy('created_at','desc')->get();
        // dd($applied_jobs);
        // dd($preference);
        return view('jobseeker.public-profile', compact('jobseeker','educations','experiences','trainings','preferences','applied_jobs'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function register_jobseeker()
    {
        if (Auth::check()) {
            
            if (Auth::user()->role == 2) {
                return redirect()->route('job-seeker.profile');
            }else{
                $flag = 1;
            }

        }else{
            $flag = 0;
        }

        return view('register-jobseeker',compact('flag'));
    }

    public function register_employer()
    {
        if (Auth::check()) {
            
            if (Auth::user()->role == 3) {
                
                return redirect()->route('soletrader.profile');
            }elseif (Auth::user()->role == 4) {
                
                return redirect()->route('company.profile');
            }else{
                
                $flag = 1;
            }

        }else{
            $flag = 0;
        }

        return view('register-employer', compact('flag'));
    }

    public function job_search(Request $request)
    {
        $validator = Validator::make($request->all(), [
                        'location' => 'required_without:category',
                        'category' => 'required_without:location'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error','Please Enter at least one Field(Location/Job Category)');
        }

        $query = Job::query();

        if ($request->location != '') {

            $location = Location::where('title', $request->location)->first();

            if (!$location) {
                return redirect()->back()->with('error','Location Not Found');
            }

            $query = $query->where('location_id',$location->id);

        }

        if ($request->category != '') {

            $job_category = JobCategory::where('title', $request->category)->first();

            if (!$job_category) {
                return redirect()->back()->with('error','Job Category Not Found');
            }

            $query = $query->where('job_category_id', $job_category->id);

        }

        $search_results = $query->get();

        $pageDetails = ProductPaginator::get_current_page();
        $currentPage = $pageDetails['currentPage'];
        $perPage = $pageDetails['perPage'];

        $search_results = ProductPaginator::paginate_products($search_results, $currentPage, $perPage);
        $companies = User::has('employer_info')->whereIn('role',[3,4])->where('status',1)->limit(8)->get();

        // dd($search_results);

        return view('jobs.search_results', compact('search_results','companies'));
    }
    
    public function skill_assessment($slug)
    {
        $skill_assessment = SkillAssessment::where('slug',$slug)->firstOrFail();

        return view('skill-assessment-details',compact('skill_assessment'));
    }

    public function change_password(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('front_login')->with('error', 'Please Log In!!');
        }else{
            // if (!in_array(Auth::user()->role, ['3','4'])) {
            //     return redirect()->back()->with('error', 'You are not Employer!!');
            // }

            $user = User::find(Auth::user()->id);

            if (Hash::check($request->oldpassword, $user->password)) {

                $validatedData = $request->validate([
                    'password' => ['required', 'string', 'min:6', 'confirmed'],
                ]);
                $user->password = Hash::make($request['password']);
                $user->save();
                return redirect()->back()->with('status', 'Password updated Successfully!');
            } else {
                return redirect()->back()->with('error', 'Your Current Password Doesnot Match!!');
            }
        }

        

    }

}
