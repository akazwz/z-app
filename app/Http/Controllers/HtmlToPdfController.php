<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SnappyImage;
use SnappyPDF;

class HtmlToPdfController extends Controller
{
    public function isURLValid(Request $request): JsonResponse
    {
        $url = $request->post('url');
        $arr = get_headers($url, 1);
        if (preg_match('/200/', $arr[0])) {
            $res = ['valid' => true];
            $this->commonSuccess(2000, 'url合法有效', $res);
        } else {
            $res = ['valid' => false];
            $this->commonFailed(4000, 'url无效', $res);
        }
    }

    public function linkToPdf(Request $request): string
    {
        $url = $request->post('url');
        SnappyPDF::setPaper('a4');
        $pdf = SnappyPDF::loadFile($url);
        return $pdf->inline();
    }

    public function HtmlToImage(): Response
    {
        return SnappyImage::loadView('chart_no_animation')->inline();
    }
}
