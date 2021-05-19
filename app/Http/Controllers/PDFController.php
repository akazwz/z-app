<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class PDFController extends Controller
{
    /**
     * @throws Exception
     */
    public function parsePdf()
    {
        $parser = new Parser();

        $filePath = storage_path('app/public/file/' . '1588849738d7a73d408ca7d479.pdf');
        $pdf = $parser->parseFile($filePath);

        $pages = $pdf->getPages();
        $texts = [];
        foreach ($pages as $page) {
            $text = $page->getText();
            array_push($texts, $text);
        }
        $details = $pdf->getDetails();
        $data = [
            'texts' => $texts,
            'details' => $details,
        ];
        $res = [
            'code' => 2000,
            'msg' => 'SUCCESS',
            'data' => $data
        ];
        $res = mb_convert_encoding($res,'UTF-8', 'auto');
        return json_encode($res);
    }
}
