<?php

namespace App\Repositories\Eloquent;
use Intervention\Image\Facades\Image;
use App\Photo;
use App\Repositories\PhotoRepositoryInterface;


class PhotoRepository implements PhotoRepositoryInterface {

    private $model;

    public function __construct(Photo $model){
        $this -> model = $model;
    }

    public function add($image, $gallery_id, $type){
        $name = uniqid();
        $image_path = 'images/img_' . $name . '.' . $image -> getClientOriginalExtension();
        $thumb_path = 'images/tmb_' . $name . '.' . $image -> getClientOriginalExtension();

        switch ($type) {
            case 'background':
                // dodawanie avatara
                $thumb_path = null;
                Image::make($image) -> resize(1122, null, function ($constraint) {
                    $constraint->aspectRatio();
                }) -> crop(1122, 364) -> save( public_path($image_path) );
                break;

            case 'avatar':
                Image::make($image) -> resize(600, 600) -> save( public_path($image_path) );
                Image::make($image) -> resize(32, 32) -> save( public_path($thumb_path) );
                break;

            default:
                // dodawanie zwykłego zdjęcia
                $full_image = Image::make($image);
                if ($full_image -> width() > 1024){
                    $full_image -> resize(null, 1024, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                if ($full_image -> height() > 1024){
                    $full_image -> resize(1024, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $full_image -> save( public_path($image_path) );

                $thumb = Image::make($image);
                if ($thumb -> width() > 256){
                    $thumb -> resize(null, 256, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                if($thumb -> height() > 256){
                    $thumb -> resize(256, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $thumb -> save( public_path($thumb_path) );
                break;
        }

        $photo = new Photo();
        $photo -> gallery_id = $gallery_id;
        $photo -> image_url = $image_path;
        $photo -> thumb_url = $thumb_path;
        $photo -> save();

        return $photo -> id;
    }


    public function delete($id){
        $photo = $this -> model -> find($id);

        if($photo->image_url) unlink(public_path($photo->image_url));
        if($photo->thumb_url) unlink(public_path($photo->thumb_url));

        return $photo -> delete();
    }

    public function getById($id){
        return $this -> model -> find($id);
    }
}