<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use SnappyPDF;

class HtmlToPdfController extends Controller
{
    public function HtmlToPdf(): Response
    {
        $pdf = SnappyPDF::loadHTML('<h1>ZWZ</h1>>');
        return $pdf->download();
    }
}
