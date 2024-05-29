<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class NewsEvent extends Model
{
    protected function createSlug($title, $id = 0)
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
        return NewsEvent::select('slug')->where('slug', 'like', $slug . '%')
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
    protected function resize_crop_images($max_width, $max_height, $image, $filename)
    {
//        add watermark on image
//        $watermark = Image::make(asset('/img/AHWatermarks.png'))->resize('300', '200');
        $imgSize = getimagesize($image);
        $width = $imgSize[0];
        $height = $imgSize[1];

        $width_new = round($height * $max_width / $max_height);
        $height_new = round($width * $max_height / $max_width);

        if ($width_new > $width) {
            //cut point by height
            $h_point = round(($height - $height_new) / 2);

            $cover = storage_path('app/' . $filename);
            Image::make($image)->crop($width, $height_new, 0, $h_point)->resize($max_width, $max_height)
                ->save($cover);
//                ->insert($watermark, 'bottom-right', 10, -45)

        } else {
            //cut point by width
            $w_point = round(($width - $width_new) / 2);
            $cover = storage_path('app/' . $filename);
            Image::make($image)->crop($width_new, $height, $w_point, 0)->resize($max_width, $max_height)
                ->save($cover);
//            ->insert($watermark, 'bottom-right', 10, -45)

        }

    }


}
