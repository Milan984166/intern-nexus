<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Location;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $locations = DB::table('locations')->orderBy('order_item')->get();
        return view('admin.locations', array('locations' => $locations, 'id' => '0'));
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
        return Location::select('slug')->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    public function create(Request $request)
    {
    	// dd($request);
        $location = new Location();
        $max_order = DB::table('locations')->max('order_item');

        $max_order = $max_order == '' ? 0 : $max_order;

        if (!$request->display) {
            $request->display = 0;
        }

        $validatedData = $request->validate([
            'title' => 'required|max:255',
        ]);

        $request['display'] = isset($request['display']) ? $request['display'] : '0';


        $location->title = $request->input('title');
        $location->slug = $this->createSlug($request->input('title'));
        $location->display = $request->input('display');
        $location->order_item = $max_order + 1;
        $location->created_by = Auth::user()->name;
        $location->updated_by = '';

        if ($location->save()) {
			return redirect()->to('/admin/locations')->with('status', 'Location Added Successfully!');	        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }

    public function edit($id)
    {
    	$id = base64_decode($id);
        $location = Location::findOrFail($id);
        return view('admin.locations', array('location' => $location, 'id' => $id));
    }

    public function set_order(Request $request){
        $list_order = $request['list_order'];
        
        // $list = explode(',' , $list_order);
        $i = 1 ;
        foreach($list_order as $id) {
            $updateData = array("order_item" => $i);
            Location::where('id', $id)->update($updateData);
            $i++ ;
        }
        $data = array('status'=> 'success');
		echo json_encode($data);
    }

    public function update(Request $request)
    {

        $location = Location::find($request->id);

        $validatedData = $request->validate([
            'title' => 'required|max:255'
        ]);

        if ($request['display'] == '') {
            $request['display'] = 0;
        }

        $location->title = $request['title'];
        $location->slug = $this->createSlug($request['title'], $request['id']);
        $location->display = $request['display'];
        $location->updated_by = Auth::user()->name;

        if ($location->save()) {

			return redirect()->to('/admin/locations')->with('status', 'Location Updated Successfully!');	        	
        }else{
        	return redirect()->back()->with('status', 'Something went Wrong!');	
        }

    }
    
    public function delete($id)
    {
        $location = Location::findOrFail(base64_decode($id));

        if ($location) {

            $location->delete();            
            return redirect()->back()->with('status', 'Location Deleted Successfully!');
        }

        return redirect()->back()->with('error', 'Something Went Wrong!');

    }
}
