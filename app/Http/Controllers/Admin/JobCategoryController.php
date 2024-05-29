<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\JobCategory;
use App\Skill;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class JobCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $job_categories = DB::table('job_categories')->orderBy('order_item')->get();
        return view('admin.job_categories', array('job_categories' => $job_categories, 'id' => '0'));
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
        return JobCategory::select('slug')->where('slug', 'like', $slug.'%')
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
        $job_category = new JobCategory();
        $max_order = DB::table('job_categories')->max('order_item');

        $max_order = $max_order == '' ? 0 : $max_order;

        if (!$request->display) {
            $request->display = 0;
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255'
        ]);

        // $validatedData = $request->validate([
        //     'title' => 'required|max:255',
        //     'skills' => 'required|array|min:1'
        // ]);

        $request['display'] = isset($request['display']) ? $request['display'] : '0';

        $job_category->title = $request->input('title');
        $job_category->slug = $this->createSlug($request->input('title'));

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/job-categories/";
            $thumbPath = "public/job-categories/thumbs";

            if (!file_exists($thumbPath)) {
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(400, 400, $image, $folderPath . "/thumbs/small_" . $filename);
            $this->resize_crop_images(68, 68, $image, $folderPath . "/thumbs/thumb_" . $filename);

            //Update the database
            $job_category->image = $filename;

        }


        $job_category->display = $request->input('display');
        $job_category->order_item = $max_order + 1;
        $job_category->created_by = Auth::user()->name;
        $job_category->updated_by = '';

        $skillArray = array();
        if (isset($request->skills)) {
            for($i=0; $i < count($request->skills); $i++){
            	array_push($skillArray, array("title" => $request->skills[$i]));
            }
        }
        // dd($skillArray);

        if ($job_category->save()) {
        	$job_category->skill()->createMany($skillArray);
			return redirect()->to('/admin/job-categories')->with('status', 'JobCategory Added Successfully!');	        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }

    public function edit($id)
    {
    	$id = base64_decode($id);
        $job_category = JobCategory::findOrFail($id);
        return view('admin.job_categories', array('job_category' => $job_category, 'id' => $id));
    }

    public function set_order(Request $request){
        $list_order = $request['list_order'];
        
        // $list = explode(',' , $list_order);
        $i = 1 ;
        foreach($list_order as $id) {
            $updateData = array("order_item" => $i);
            JobCategory::where('id', $id)->update($updateData);
            $i++ ;
        }
        $data = array('status'=> 'success');
		echo json_encode($data);
    }

    public function update(Request $request)
    {
        // dd($_POST);
        $job_category = JobCategory::find($request->id);

        $validatedData = $request->validate([
            'title' => 'required|max:255'
        ]);

        if ($request['display'] == '') {
            $request['display'] = 0;
        }

        $job_category->title = $request['title'];
        $job_category->slug = $this->createSlug($request['title'], $request['id']);

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $data = getimagesize($image);
            $folderPath = "public/job-categories/";
            $thumbPath = "public/job-categories/thumbs";

            if(!file_exists($thumbPath)){
                Storage::makeDirectory($thumbPath,0777,true,true);
            }

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(68, 68, $image, $folderPath . "/thumbs/thumb_" . $filename);
            $this->resize_crop_images(400, 400, $image, $folderPath . "/thumbs/small_" . $filename);

            $OldFilename = $job_category->image;

            //Update the database
            $job_category->image = $filename;


            //Delete the old photo
            Storage::delete($folderPath . "/" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/thumb_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/small_" . $OldFilename);
        }

        
        $job_category->display = $request['display'];
        $job_category->updated_by = Auth::user()->name;

        if ($job_category->save()) {

            if (isset($request->skills)) {

                $skillArray = array();

                for($i=0; $i<count($request->skills); $i++){
                    array_push($skillArray, array("title" => $request->skills[$i]));
                }   

                $job_category->skill()->createMany($skillArray);
            }

            if (isset($request->skill_id)) {
                for ($i=0; $i < count($request->skill_id); $i++) { 
                    $skill = Skill::find($request->skill_id[$i]);
                    $skill->title = $request->skill_db[$i];
                    $skill->updated_at = date('Y-m-d H:i:s');
                    $skill->save();
                }
            }

			return redirect()->to('/admin/job-categories')->with('status', 'JobCategory Updated Successfully!');	        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }
    
    public function delete($id)
    {
        $job_category = JobCategory::findOrFail(base64_decode($id));

        if ($job_category->delete()) {
            
            $skills = Skill::where('job_category_id',base64_decode($id));
            $skills->delete();

            return redirect()->back()->with('status', 'JobCategory Deleted Successfully!');
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');

    }

    public function delete_skill($id){
        $skill = Skill::findOrFail(base64_decode($id));

        if ($skill->delete()) {
            return redirect()->back()->with('status','Skill Deleted Successfully!!');
        }else{
            return redirect()->back()->with('status','Something went wrong!!');
        }
    }

    public function add_skills($cIndex){
        if($cIndex){
            return '<div class="col-md-3" id="row'.$cIndex.'">
                        <div class="input-group mb-3">
                            <input type="text" name="skills[]" placeholder="Skills Related to Job Category" class="form-control name_list" required/>
                            <div class="input-group-prepend">
                                <button type="button" name="remove" id="'.$cIndex.'" class="btn btn-danger btn_remove">
                                    <i class=" fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>';
        }
    }
}
