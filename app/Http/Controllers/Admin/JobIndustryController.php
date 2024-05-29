<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\JobIndustry;
use App\Skill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class JobIndustryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $job_industries = DB::table('job_industries')->orderBy('order_item')->get();
        return view('admin.job_industries', array('job_industries' => $job_industries, 'id' => '0'));
    }

    // Slug check and create starts
	public function createSlug($title, $id = 0)
    {
        $slug = str_slug($title);

        $allSlugs = $this->getRelatedSlugs($slug, $id);

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
    
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return JobIndustry::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function resize_crop_images($max_width, $max_height, $image, $filename){
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

    public function create(Request $request)
    {
    	// dd($_POST);
        $job_industry = new JobIndustry();
        $max_order = DB::table('job_industries')->max('order_item');

        $max_order = $max_order == '' ? 0 : $max_order;

        if (!$request->display) {
            $request->display = 0;
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255'
        ]);

        $request['display'] = isset($request['display']) ? $request['display'] : '0';

        $job_industry->title = $request->input('title');
        $job_industry->slug = $this->createSlug($request->input('title'));

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/job-industries/";
            $thumbPath = "public/job-industries/thumbs";

            if (!file_exists($thumbPath)) {
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(400, 400, $image, $folderPath . "/thumbs/small_" . $filename);
            $this->resize_crop_images(68, 68, $image, $folderPath . "/thumbs/thumb_" . $filename);

            //Update the database
            $job_industry->image = $filename;

        }


        $job_industry->display = $request->input('display');
        $job_industry->order_item = $max_order + 1;
        $job_industry->created_by = Auth::user()->name;
        $job_industry->updated_by = '';

        if ($job_industry->save()) {

			return redirect()->to('/admin/job-industries')->with('status', 'Job Industry Added Successfully!');	        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }

    public function edit($id)
    {
    	$id = base64_decode($id);
        $job_industry = JobIndustry::findOrFail($id);
        return view('admin.job_industries', array('job_industry' => $job_industry, 'id' => $id));
    }

    public function set_order(Request $request){
        $list_order = $request['list_order'];
        
        // $list = explode(',' , $list_order);
        $i = 1 ;
        foreach($list_order as $id) {
            $updateData = array("order_item" => $i);
            JobIndustry::where('id', $id)->update($updateData);
            $i++ ;
        }
        $data = array('status'=> 'success');
		echo json_encode($data);
    }

    public function update(Request $request)
    {
        // dd($_POST);
        $job_industry = JobIndustry::find($request->id);

        $validatedData = $request->validate([
            'title' => 'required|max:255'
        ]);

        if ($request['display'] == '') {
            $request['display'] = 0;
        }

        $job_industry->title = $request['title'];
        $job_industry->slug = $this->createSlug($request['title'], $request['id']);

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $data = getimagesize($image);
            $folderPath = "public/job-industries/";
            $thumbPath = "public/job-industries/thumbs";

            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(68, 68, $image, $folderPath . "/thumbs/thumb_" . $filename);
            $this->resize_crop_images(400, 400, $image, $folderPath . "/thumbs/small_" . $filename);

            $OldFilename = $job_industry->image;

            //Update the database
            $job_industry->image = $filename;


            //Delete the old photo
            Storage::delete($folderPath . "/" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/thumb_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/small_" . $OldFilename);
        }

        
        $job_industry->display = $request['display'];
        $job_industry->updated_by = Auth::user()->name;

        if ($job_industry->save()) {
			return redirect()->to('/admin/job-industries')->with('status', 'Job Industry Updated Successfully!');	        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }
    
    public function delete($id)
    {
        $job_industry = JobIndustry::findOrFail(base64_decode($id));

        if ($job_industry->delete()) {

            return redirect()->back()->with('status', 'Job Industry Deleted Successfully!');
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');

    }

}
