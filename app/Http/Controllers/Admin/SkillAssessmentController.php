<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\SkillAssessment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;


/**
 * Class SkillAssessmentController
 * @package App\Http\Controllers\Admin
 */
class SkillAssessmentController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function single($slug)
    {
        $skill_assessment = SkillAssessment::where('slug', $slug)->first();
        if (!$skill_assessment) {
            abort('404');
        }
        $skill_assessments = $this->getFullListFromDB($skill_assessment->id);
        return view('admin.skill-assessments', array('skill_assessments' => $skill_assessments, 'skill_assessment' => $skill_assessment, 'id' => '0'));
    }

    /**
     * Skill Assessment index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
       	$skill_assessments = $this->getFullListFromDB();
        return view('admin.skill-assessments', array('skill_assessments' => $skill_assessments, 'id' => '0'));
    }

    /**
     * @param int $parent_id
     * @return \Illuminate\Support\Collection
     */
    public function getFullListFromDB($parent_id = 0)
    {
        $skill_assessments = DB::table('skill_assessments')->where('parent_id', $parent_id)->orderBy('order_item')->get();

        foreach ($skill_assessments as &$value) {
            $subresult = $this->getFullListFromDB($value->id);

            if (count($subresult) > 0) {
                $value->children = $subresult;
            }
        }

        unset($value);

        return $skill_assessments;
    }

    // Slug check and create starts

    /**
     * @param $title
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);

        $allSlugs = $this->getRelatedSlugs($slug, $id);

        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 10; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;
            }
        }

        throw new \Exception('Can not create a unique slug');
    }

    /**
     * @param $slug
     * @param int $id
     * @return mixed
     */
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return SkillAssessment::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * resize crop image
     *
     * @param $max_width
     * @param $max_height
     * @param $image
     * @param $filename
     */
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


    /**
     * Create New Skill Assessment
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
       
        $skill_assessment = new SkillAssessment();
        $validateData = $request->validate([
            "title" => 'required|max:255',
        ]);

        $max_order = DB::table('skill_assessments')->max('order_item');

        $skill_assessment->title = $request->title;
        $skill_assessment->slug = $this->createSlug($request->title);
        $skill_assessment->order_item = $max_order + 1;
        if ($request->subtitles) {
            $skill_assessment->subtitles = $request['subtitles'];
        }

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/skill_assessments/" . $skill_assessment->slug;
            $thumbPath = "public/skill_assessments/" . $skill_assessment->slug . "/thumbs";

            if (!file_exists($thumbPath)) {
                Storage::makeDirectory($thumbPath, 0777, true, true);
            }

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(1200, 400, $image, $folderPath . "/thumbs/cover_" . $filename);
            $this->resize_crop_images(600, 400, $image, $folderPath . "/thumbs/thumb_" . $filename);
            $this->resize_crop_images(200, 200, $image, $folderPath . "/thumbs/small_" . $filename);

            //Update the database
            $skill_assessment->image = $filename;

        }

        if (!$request['display']) {
            $request['display'] = 0;
        }
        if (!$request['featured']) {
            $request['featured'] = 0;
        }

        $skill_assessment->featured = $request['featured'];
        $skill_assessment->display = $request['display'];
        if ($request->parent_id) {
            $skill_assessment->parent_id = $request->parent_id;
        } else {
            $skill_assessment->parent_id = 0;
        }

        $skill_assessment->child = 0;
        $skill_assessment->excerpt = $request->excerpt;
        $skill_assessment->content = $request->content;

        $skill_assessment->save();

        return redirect()->back()->with('status', 'Content added Successfully!');
    }

    /**
     * Edit Skill Assessment
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $skill_assessment = SkillAssessment::find(base64_decode($id));
        return view('admin.skill-assessments', array('skill_assessment' => $skill_assessment, 'id' => base64_decode($id)));
    }

    /**
     * Update Skill Assessment
     *
     * @param Request $request
     */
    public function update(Request $request)
    {
       // dd($request);
        $skill_assessment = SkillAssessment::findOrFail($request->id);

        $validateData = $request->validate([
            "title" => 'required|max:255',
        ]);

        // for slug

        $slug = $this->createSlug($request->title, $request->id);

        $path = public_path() . '/storage/skill_assessments/' . $skill_assessment->slug;
        if ($skill_assessment->slug != $slug) {
            $oldslug = $skill_assessment->slug;
            // $project->slug = $this->createSlug($slug, $request['id']);
            $skill_assessment->slug = $this->createSlug($slug, $request->id);

            $slug = $skill_assessment->slug;
            if (file_exists($path)) {
                Storage::move('public/skill_assessments/' . $oldslug, 'public/skill_assessments/' . $slug);
            }
        }
        $folderPath = 'public/skill_assessments/' . $slug;


        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath, 0777, true, true);

            if (!is_dir($path . "/thumbs")) {
                Storage::makeDirectory($folderPath . '/thumbs', 0777, true, true);
            }
        }

        $skill_assessment->title = $request->title;
        $skill_assessment->subtitles = $request->subtitles;
        if ($request->featured) {
            $skill_assessment->featured = $request->featured;
        }
        if ($request->display) {
            $skill_assessment->display = $request->display;
        }

        $skill_assessment->excerpt = $request->excerpt;
        $skill_assessment->content = $request->content;

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $data = getimagesize($image);
            $folderPath = "public/skill_assessments/" . $slug;
            $thumbPath = "public/skill_assessments/" . $slug . "/thumbs";

            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(1200, 400, $image, $folderPath . "/thumbs/cover_" . $filename);
            $this->resize_crop_images(600, 400, $image, $folderPath . "/thumbs/thumb_" . $filename);
            $this->resize_crop_images(200, 200, $image, $folderPath . "/thumbs/small_" . $filename);

            $OldFilename = $skill_assessment->image;

            //Update the database
            $skill_assessment->image = $filename;


            //Delete the old photo
            Storage::delete($folderPath . "/" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/cover_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/thumb_" . $OldFilename);
            Storage::delete($folderPath . "/thumbs/small_" . $OldFilename);
        }

        $skill_assessment->save();
        return redirect()->to('admin/skill-assessments')->with('status', 'Content updated Successfully!');;


    }

    /**
     * Delete Skill Assessment
     *
     * @param $id
     */
    
    public function delete($id)
    {
        $skill_assessment = SkillAssessment::where('id' , $id)->firstOrFail();

        if ($skill_assessment->child == 1) {
            return redirect()->back()->with('parent_status' , array('type' => 'danger', 'primary' => 'Sorry, Skill Assessment has Child!', 'secondary' => 'Currently, It cannot be deleted.'));         
        }

        $parentId = $skill_assessment->parent_id;

        if ($skill_assessment) {

            if ($skill_assessment->delete()) {
                
                $folderPath = 'public/skill_assessments/' . $skill_assessment->slug;
                Storage::deleteDirectory($folderPath);

                $childCheck = SkillAssessment::where('parent_id' , $parentId)->doesntExist();

                if ($childCheck) {
                    $updateData = array("child" => 0);
                    SkillAssessment::where('id', $parentId)->update($updateData);
                }
            }
            return redirect()->back()->with('status', 'Content Deleted Successfully!!');
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');
    }


    /**
     * Upload Image on Server from summernote File Uploader
     *
     * @param Request $request
     */
    public function imageupload(Request $request)
    {
        if ($request->hasFile('file')) {
            //Add the new photo
            $image = $request->file('file');

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $folderPath = "public/summernote/";

        }


        if ($_FILES['file']['name']) {
            if (!$_FILES['file']['error']) {
                $name = md5(rand(100, 200));
                $ext = explode('.', $_FILES['file']['name']);
                $filename = $name . '.' . $ext[1];
                $destination = $folderPath; //change this directory
                $location = $_FILES["file"]["tmp_name"];
                move_uploaded_file($location, $destination);
                echo $folderPath . '/' . $filename;//change this URL
            } else {
                echo $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['file']['error'];
            }
        }
        $data = array('url' => $destination);
    }


    /**
     * @param Request $request
     */
    public function set_order(Request $request)
    {

        $skill_assessments = new SkillAssessment();
        $list_order = $request['list_order'];

        $this->saveList($list_order, $request->parent_id);
        $data = array('status' => 'success');
        echo json_encode($data);
        exit;
    }

    /**
     * @param $list
     * @param int $parent_id
     * @param int $child
     * @param int $m_order
     */
    function saveList($list, $parent_id = 0, $child = 0, &$m_order = 0)
    {

        foreach ($list as $item) {
            $m_order++;
            $updateData = array("parent_id" => $parent_id, "child" => $child, "order_item" => $m_order);
            SkillAssessment::where('id', $item['id'])->update($updateData);

            if (array_key_exists("children", $item)) {
                $updateData = array("child" => 1);
                SkillAssessment::where('id', $item['id'])->update($updateData);
                $this->saveList($item["children"], $item['id'], 0, $m_order);
            }
        }
    }
}
