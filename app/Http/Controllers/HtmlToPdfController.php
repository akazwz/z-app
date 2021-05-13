<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use PDF;

class HtmlToPdfController extends Controller
{
    public function HtmlToPdf(): Response
    {
        $pdf = PDF::loadView('html_to_pdf');
        return $pdf->download();
    }
}
