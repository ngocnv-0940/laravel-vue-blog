<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Image;

class Media extends Model
{
    const NO_IMAGE_URL = 'images/no_image.png';

    protected $fillable = ['url', 'is_local'];

    public static function upload(UploadedFile $file, $path = null)
    {
        $image = Storage::put($path, $file);

        Image::make($file)->fit(128, 128, function ($constraint) {
            $constraint->upsize();
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
        return Storage::exists($target) ? Storage::url($target) : Storage::url(self::NO_IMAGE_URL);
    }

    public static function thumb($target)
    {
        $isUrl = str_contains($target, ['http://', 'https://']);
        $isLocal = str_contains($target, Storage::url('/'));

        if ($isUrl) {
            if (!$isLocal) {
                return $target;
            }

            $target = str_after($target, Storage::url('/'));
        }

        return Storage::exists($target)
            ? Storage::url(static::getThumb($target))
            : Storage::url(static::getThumb(self::NO_IMAGE_URL));
    }
}
