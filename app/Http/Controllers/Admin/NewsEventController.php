<?php

namespace App\Http\Controllers\Admin;

use App\NewsEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsEventController extends Controller
{

    public function index()
    {
        $newsevents = NewsEvent::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.news-and-events.index', compact('newsevents'));
    }

    public function show()
    {
        return view('admin.news-and-events.add');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        $newsevent = new NewsEvent();
        $newsevent->title = $request->title;
        $newsevent->content = $request->description;
        $newsevent->short_content = $request->short_content;
        $slug = NewsEvent::createSlug($request->title, 'id', 0);
        $newsevent->slug = $slug;
        if ($request->hasFile('image')) {
            $validatedData = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:50000',
            ]);
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $folderPath = "public/news-and-events/" . $slug;
            $thumPath = "public/news-and-events/" . $slug . "/thumbs";

            if (!file_exists($thumPath)) {
                Storage::makeDirectory($thumPath, 0777, true, true);
            }
            Storage::putFileAs($folderPath, new file($file), $fileName);

            NewsEvent::resize_crop_images(200, 150, $file, $folderPath . "/thumbs/thumb_" . $fileName);
            NewsEvent::resize_crop_images(335, 200, $file, $folderPath . "/thumbs/small_" . $fileName);
            NewsEvent::resize_crop_images(770, 400, $file, $folderPath . "/thumbs/big_" . $fileName);
        }
        $newsevent->image = $fileName;
        $newsevent->save();
        return redirect(route('news-and-events.list'))->with('status', 'News And Event Created Successfully !!');
    }

    public function edit($id)
    {
        $newsevent = NewsEvent::findOrFail(base64_decode($id))->first();
        return view('admin.news-and-events.add', compact('newsevent'));
    }

    public function update(Request $request)
    {
        $id = base64_decode($request->id);
        $newsevent = NewsEvent::findOrFail($id);
        $newsevent->title = $request->title;
        $newsevent->content = $request->description;
        $slug = NewsEvent::createSlug($request->title, base64_decode($request->id));
        $newsevent->short_content = $request->short_content;
        $newsevent->slug = $slug;
        $path = public_path() . '/storage/news-and-events/' . $newsevent->slug;
        if ($newsevent->slug != $slug) {
            $oldslug = $newsevent->slug;
            $newsevent->slug = $slug;
            if (file_exists($path)) {
                Storage::move('public/news-and-events/' . $oldslug, 'public/news-and-events/' . $slug);
            }
        }
        if ($request->status) {
            $newsevent->status = 1;
        } else {
            $newsevent->status = 0;
        }
        $folderPath = 'public/news-and-events/' . $slug;
        if (!file_exists($path)) {

            Storage::makeDirectory($folderPath, 0777, true, true);

            if (!is_dir($path . "/thumbs")) {
                Storage::makeDirectory($folderPath . '/thumbs', 0777, true, true);
            }
        }
        if ($request->hasFile('image')) {
            $validatedData = $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:50000',
            ]);
            $oldImage = $newsevent->image;
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $folderPath = "public/news-and-events/" . $slug;
            $thumPath = "public/news-and-events/" . $slug . "/thumbs";

            if (!file_exists($thumPath)) {
                Storage::makeDirectory($thumPath, 0777, true, true);
            }
            Storage::putFileAs($folderPath, new file($file), $fileName);
            NewsEvent::resize_crop_images(200, 150, $file, $folderPath . "/thumbs/thumb_" . $fileName);
            NewsEvent::resize_crop_images(335, 200, $file, $folderPath . "/thumbs/small_" . $fileName);
            NewsEvent::resize_crop_images(770, 400, $file, $folderPath . "/thumbs/big_" . $fileName);
            $newsevent->image = $fileName;
            Storage::delete('public/news-and-events/' . $slug . '/' . $oldImage);
            Storage::delete('public/news-and-events/' . $slug . '/thumbs/thumb_' . $oldImage);
            Storage::delete('public/news-and-events/' . $slug . '/thumbs/small_' . $oldImage);
            Storage::delete('public/news-and-events/' . $slug . '/thumbs/big_' . $oldImage);
        }
        $newsevent->save();
        return redirect(route('news-and-events.list'))->with('status', 'News And Event Updated Successfully !!');
    }

    public function delete($id)
    {
        
        $newsevent = NewsEvent::findOrFail($id);

        if ($newsevent) {

            $newsEventFolder = 'public/news-and-events/'.$newsevent->slug;
            Storage::deleteDirectory($newsEventFolder);

            $newsevent->delete();

            return redirect()->back()->with('status', 'News And Event Deleted Successfully!');

        }else{

            return redirect()->back()->with('status', 'Something Went Wrong!');
        }
    }

}

