<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use SnappyPDF;
use Knp\Snappy\Image;

class HtmlToPdfController extends Controller
{
    public function HtmlToPdf(): Response
    {
        SnappyPDF::setOptions([
        ]);
        SnappyPDF::setPaper('a4');
        $pdf = SnappyPDF::loadView('chart_no_animation');
        return $pdf->inline();
    }

    public function HtmlToImage()
    {
        $img = new Image(base_path('vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64'));
        $img->generateFromHtml('<h1>ZWZ</h1>', 'test.jpg');
    }
}
