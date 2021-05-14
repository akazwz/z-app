<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Barryvdh\Snappy;
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
        $img = SnappyPDF::loadHTML('chart_no_animation');
        return $img->inline();
    }
}
