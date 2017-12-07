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
        Image::make($file)->resize(64, 64)->save(public_path('uploads') . '/thumbs/' . $image);
        return $image;
    }

    public static function destroy($target)
    {
        if (Storage::exists($target)) {
            Storage::delete($target);
            return true;
        }
        return false;
    }
}
