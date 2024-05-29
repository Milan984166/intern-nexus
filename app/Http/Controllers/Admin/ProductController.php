<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App;
use App\Product;
use App\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index()
    {

    	if (Auth::user()->role != 1) {
            return redirect()->back()->with('log_status','Sorry, You are not authorized to another Account!');
        }

        $categories = $this->getFullListFromDB();
        $products = DB::table('products')->orderBy('order_item')->get();

        return view('admin.products', array('products' => $products, 'categories' => $categories, 'id' => '0'));
    }

    public function getFullListFromDB($parent_id = 0)
	{
		$categories =  DB::table('categories')->where([['display', 1],['parent_id', $parent_id]])->select('id','title','parent_id', 'child')->orderBy('order_item')->get();
	    
	    foreach ($categories as &$value) {
	        $subresult = $this->getFullListFromDB($value->id);

	        if (count($subresult) > 0) {
	            $value->children = $subresult;
	        }
	    }

	    unset($value);

	    return $categories;
	}

    // Slug check and create starts
	public function createSlug($title, $id = 0)
    {
        // Normalize the title
        $slug = str_slug($title);

        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
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
        return Product::select('slug')->where('slug', 'like', $slug.'%')
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

    public function createproduct(Request $request)
    {
    	// echo "<pre>";
    	// var_dump($_POST);
    	// exit();

    	$validator = Validator::make($request->all(), [
            "title" => 'required|max:255',
			"sku" => 'required',
            "image" => 'required',
            "originalPrice" => 'required'
        ]);

		if ($validator->fails()) {
            return redirect()
            			->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $product = new Product();

		$max_order = DB::table('products')->max('order_item');

		$product->title = $request['title'];
        $product->slug = $this->createSlug($request['title']);
        $product->order_item = $max_order + 1;

		$path = public_path().'/storage/products/'.$product->slug;
        $folderPath = 'public/products/'.$product->slug;

        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath,0777,true,true);

            if (!is_dir($path."/thumbs")) {
                Storage::makeDirectory($folderPath.'/thumbs',0777,true,true);
            }

            if (!is_dir($path."/gallery")) {
                Storage::makeDirectory($folderPath.'/gallery',0777,true,true);
                Storage::makeDirectory($folderPath.'/gallery/thumbs',0777,true,true);
            }

        }

		if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(1200, 1200, $image, $folderPath."/thumbs/cover_".$filename);
            $this->resize_crop_images(800, 800, $image, $folderPath."/thumbs/large_".$filename);
            $this->resize_crop_images(200, 200, $image, $folderPath."/thumbs/thumb_".$filename);

        }

        if ($request->hasFile('other_images')) {
            //Add the new photo
            $otherImages = $request->file('other_images');
            foreach ($otherImages as $key => $other) {

                $filename_o = time().$key.'_.'.$other->getClientOriginalExtension();
                Storage::putFileAs($folderPath."/gallery", new File($other), $filename_o);

                $this->resize_crop_images(1200, 1200, $other, $folderPath."/gallery/thumbs/cover_".$filename_o);
	            $this->resize_crop_images(800, 800, $other, $folderPath."/gallery/thumbs/large_".$filename_o);
	            $this->resize_crop_images(200, 200, $other, $folderPath."/gallery/thumbs/thumb_".$filename_o);
            }

        }

		$product->image = isset($filename) ? $filename : '';

		$product->categories = json_encode($request['categories']);
        $product->sku = $request['sku'];
        $product->barrels = json_encode($request['barrels']);

		if (!$request['display']) {
			$request['display'] = 0;
		}

		if (!$request['featured']) {
			$request['featured'] = 0;
		}

        if (!$request['stock']) {
            $request['stock'] = 0;
        }

        if (!$request['discountedPrice']) {
            $request['discountedPrice'] = 0;
        }

		$product->display = $request['display'];
		$product->featured = $request['featured'];
        $product->stock = $request['stock'];
        $product->originalPrice = $request['originalPrice'];
        $product->discountedPrice = $request['discountedPrice'];
		$product->short_content = $request['short_content'];
		$product->long_content = $request['long_content'];
		$product->created_by = Auth::user()->name;
		$product->updated_by = "";

		$product->save();

        return redirect()->to('/admin/products')->with('status', 'Product added Successfully!');
    }

    public function editproduct($id)
    {
    	if (Auth::user()->role != 1) {
            return redirect()->back()->with('log_status','Sorry, You are not authorized to another Account!');
        }

        $categories = $this->getFullListFromDB();
        $product = Product::where('id' , base64_decode($id))->firstOrFail();
        return view('admin.products', array('product' => $product, 'id' => base64_decode($id), 'categories' => $categories));
    }

    public function updateproduct(Request $request){
    	$validator = Validator::make($request->all(), [
            "title" => 'required|max:255',
            "sku" => 'required',
            "originalPrice" => 'required',
        ]);

		if ($validator->fails()) {
            return redirect()
            			->back()
                        ->withErrors($validator)
                        ->withInput();
        }        
        
    	$product = Product::find($request['id']);

        $product->title = $request['title'];
		
    	$slug = str_slug($request['title'], '-');
        $path = public_path().'/storage/products/'.$product->slug;

        if ($product->slug != $slug) {
            if (file_exists($path)) {
                Storage::move('public/products/'. $product->slug , 'public/products/'.$slug);
            }
            $product->slug = $this->createSlug($slug, $request['id']);
            $slug = $product->slug;
        }

        $folderPath = 'public/products/'. $slug;
        
        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath,0777,true,true);

            if (!is_dir($path."/thumbs")) {
                Storage::makeDirectory($folderPath.'/thumbs',0777,true,true);
            }

            if (!is_dir($path."/gallery")) {
                Storage::makeDirectory($folderPath.'/gallery',0777,true,true);
                Storage::makeDirectory($folderPath.'/gallery/thumbs',0777,true,true);
            }
        }

        if ($request->hasFile('image')) {
            //Add the new photo
            $image = $request->file('image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            Storage::putFileAs($folderPath, new File($image), $filename);

            $this->resize_crop_images(1200, 1200, $image, $folderPath."/thumbs/cover_".$filename);
            $this->resize_crop_images(800, 800, $image, $folderPath."/thumbs/large_".$filename);
            $this->resize_crop_images(200, 200, $image, $folderPath."/thumbs/thumb_".$filename);

            $OldFilename = $product->image;

            //Update the database
            $product->image = $filename;

            //Delete the old photo
            Storage::delete($folderPath ."/".$OldFilename);
            Storage::delete($folderPath ."/thumbs/cover_".$OldFilename);
            Storage::delete($folderPath ."/thumbs/large_".$OldFilename);
            Storage::delete($folderPath ."/thumbs/thumb_".$OldFilename);

        }

        if ($request->hasFile('other_images')) {
            //Add the new photo
            $otherImages = $request->file('other_images');
            foreach ($otherImages as $key => $other) {

                $filename = time().$key.'_.'.$other->getClientOriginalExtension();
                Storage::putFileAs($folderPath."/gallery", new File($other), $filename);

                $this->resize_crop_images(1200, 1200, $other, $folderPath."/gallery/thumbs/cover_".$filename);
                $this->resize_crop_images(800, 800, $other, $folderPath."/gallery/thumbs/large_".$filename);
                $this->resize_crop_images(200, 200, $other, $folderPath."/gallery/thumbs/thumb_".$filename);
            }

        }

        $product->categories = json_encode($request['categories']);
        $product->sku = $request['sku'];
        $product->barrels = json_encode($request['barrels']);

        if (!$request['display']) {
            $request['display'] = 0;
        }

        if (!$request['featured']) {
            $request['featured'] = 0;
        }

        if (!$request['stock']) {
            $request['stock'] = 0;
        }

        if (!$request['discountedPrice']) {
            $request['discountedPrice'] = 0;
        }

        $product->display = $request['display'];
        $product->featured = $request['featured'];
        $product->stock = $request['stock'];
        $product->originalPrice = $request['originalPrice'];
        $product->discountedPrice = $request['discountedPrice'];
        $product->short_content = $request['short_content'];
        $product->long_content = $request['long_content'];
        $product->updated_by = Auth::user()->name;
        $product->updated_at = date('Y-m-d H:i:s');
        
        $product->save();
    	return redirect('admin/products')->with('status', 'Product updated Successfully!');
    }


    public function delete($id){

        $product = Product::where('id' , base64_decode($id))->firstOrFail();

        if ($product) {

            $productFolder = 'public/products/'.$product->slug;
            Storage::deleteDirectory($productFolder);

            $product->delete();

            return redirect()->back()->with('status', 'Product Deleted Successfully!');

        }else{

            return redirect()->back()->with('status', 'Something Went Wrong!');
        }
    }

    public function set_order(Request $request){

        $list_order = $request->list_order;

        $i = 1 ;
        foreach($list_order as $id) {
            $updateData = array("order_item" => $i);
            Product::where('id', $id)->update($updateData);

            $i++ ;
        }
        $data = array('status'=> 'success');
        echo json_encode($data);
    }

    public function add_barrel($cIndex){
        if($cIndex){
        	return '<div class="col-md-2" style="padding-bottom: 5px;" id="row'.$cIndex.'">
                        <div class="input-group mb-3">
                            <input type="text" name="barrels[]" placeholder="BBL" class="form-control name_list" required/>
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background-color: #d2d6de;">
                                    BBL
                                </span>
                                <button type="button" name="remove" id="'.$cIndex.'" class="btn btn-danger btn_remove">
                                    <i class=" fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>';
        }
    }

    public function delete_product_gallery_image($albumName, $photoName){

        Storage::delete("public/products/".$albumName."/gallery/".$photoName);
        Storage::delete("public/products/".$albumName."/gallery/thumbs/small_".$photoName);
        Storage::delete("public/products/".$albumName."/gallery/thumbs/large_".$photoName);
        Storage::delete("public/products/".$albumName."/gallery/thumbs/thumb_".$photoName);

        return redirect()->back()->with('status','Gallery Image Deleted Successfully!');

    }

}
