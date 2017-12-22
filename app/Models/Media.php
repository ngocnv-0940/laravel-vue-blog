<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Image;

class Media extends Model
{
    protected $fillable = ['url', 'is_local'];

    public static function upload(UploadedFile $file, $path = null)
    {
        $image = Storage::put($path, $file);

        Image::make($file)->resize(128, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('uploads') . '/' . static::getThumb($image));

        return $image;
    }

    public static function destroy($target)
    {
        if (Storage::exists($target)) {
            Storage::delete([$target, static::getThumb($target)]);

            return true;
        }

        return false;
    }

    public static function getThumb($target)
    {
        $ext = pathinfo($target, PATHINFO_EXTENSION);
        $name = chop($target, '.' . $ext);

        return "{$name}_thumb.{$ext}";
    }

    public static function url($target)
    {
        return Storage::url($target);
    }

    public static function thumb($target)
    {
        return Storage::url(static::getThumb($target));
    }
}
