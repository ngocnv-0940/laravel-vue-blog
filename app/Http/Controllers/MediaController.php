<?php

namespace App\Http\Controllers;

use GDText\Box;
use GDText\Color;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function imgFromText(Request $request)
    {
        $text = $request->get('t', env('APP_NAME') . "\n" . url('/'));
        $width = (int) ((is_numeric($request->get('w', 100)) && $request->get('w', 100) >= 100) ? $request->get('w', 100) : 100);
        $height = (int) ((is_numeric($request->get('h', 100)) && $request->get('h', 100) >= 100) ? $request->get('h', 100) : 100);
        $fontSize = (int) ((is_numeric($request->get('fs', 20)) && $request->get('fs', 20) >= 15) ? $request->get('fs', 20) : 15);
        $margin = 5;

        $image = imagecreatetruecolor($width, $height);
        $backgroundColor = imagecolorallocate($image, 204, 204, 204);
        imagefill($image, 0, 0, $backgroundColor);

        $box = new Box($image);
        $box->setFontFace(public_path('fonts/other/RobotoCondensed-Regular.ttf'));
        $box->setFontSize($fontSize);
        $box->setFontColor(new Color(135, 135, 135));
        $box->setTextShadow(new Color(150, 150, 150, 50), 0, 1);
        $box->setBox($margin, $margin, $width - 2 * $margin, $height - 2 * $margin);
        $box->setTextAlign('center', 'center');
        $box->draw($text);
        header('Content-Type: image/png');
        imagepng($image);
        // imagedestroy($image);
        exit;
    }
}
