<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use PDF;

class HtmlToPdfController extends Controller
{
    public function HtmlToPdf(): bool
    {
        $is = extension_loaded('imagick');
        if (!$is) {
            return 'imagick未安装';
        }
        $pdf = PDF::loadView('html_to_pdf');
        return phpinfo();
        //return $pdf->download();
    }
}
