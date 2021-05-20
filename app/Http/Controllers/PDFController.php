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
    public function parsePdf(Request $request): JsonResponse
    {
        $parser = new Parser();

        $fileName = $request->query('file_name');
        if (is_null($fileName)) {
            $res = [
                'code' => 4000,
                'msg' => 'SUCCESS',
            ];
            return response()->json($res);
        }

        $filePath = storage_path('app/public/file/' . $fileName);
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
        return response()->json($res);
    }
}
