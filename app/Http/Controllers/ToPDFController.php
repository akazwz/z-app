<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use SnappyImage;
use SnappyPDF;

class ToPDFController extends Controller
{
    public function isURLValid(Request $request): JsonResponse
    {
        $url = $request->post('url');
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $res = ['valid' => false];
            return $this->commonFailed(4000, 'url正则无效', $res);
        }
        $arr = get_headers($url, 1);
        if (preg_match('/200/', $arr[0])) {
            $res = ['valid' => true];
            return $this->commonSuccess(2000, 'url合法有效', $res);
        }
        $res = ['valid' => false];
        return $this->commonFailed(4000, 'url无效', $res);
    }

    public function toPreviewPDF(Request $request): Response|string
    {
        $url = $request->query('url');
        $type = $request->query('type');
        SnappyPDF::setPaper('a4');
        return match ($type) {
            'link' => SnappyPDF::loadFile($url)->inline(),
            'html' => SnappyPDF::loadHTML('<h1>WE ARE DOING THIS</h1>')->inline(),
            default => 'error',
        };
    }

    public function toDownloadPDF(Request $request): Response|string
    {
        $url = $request->query('url');
        $type = $request->query('type');
        SnappyPDF::setPaper('a4');
        return match ($type) {
            'link' => SnappyPDF::loadFile($url)->download(),
            'html' => SnappyPDF::loadHTML('<h1>WE ARE DOING THIS</h1>')->download(),
            default => 'error',
        };
    }

    public function HtmlToImage(): Response
    {
        return SnappyImage::loadView('chart_no_animation')->inline();
    }
}
