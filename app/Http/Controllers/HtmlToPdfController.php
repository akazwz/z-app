<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use SnappyImage;
use SnappyPDF;

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

    public function HtmlToImage(): Response
    {
        return SnappyImage::loadView('<h1>ZWZ</h1>')->inline();
    }
}
