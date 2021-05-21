<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Ramsey\Uuid\Uuid;
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
        $res = mb_convert_encoding($res, 'UTF-8', 'auto');
        return response()->json($res);
    }


    /**
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function wordToPDFPreview(Request $request)
    {
        $res = $this->wordToPDF($request);
        if (count($res) > 1) {
            return response()->json($res);
        }
        $filePath = $res['filePath'];
        $fileName = $res['fileName'];
        PDF::loadFile($filePath);
        return PDF::inline($fileName);
    }

    /**
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function wordToPDFDownload(Request $request)
    {
        $res = $this->wordToPDF($request);
        if (count($res) > 1) {
            return response()->json($res);
        }
        $filePath = $res['filePath'];
        $fileName = $res['fileName'];
        PDF::loadFile($filePath);
        return PDF::download($fileName);
    }

    /**
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function wordToPDF(Request $request): array
    {
        $domPdfPath = base_path('vendor/dompdf/dompdf');
        Settings::setPdfRendererPath($domPdfPath);
        Settings::setPdfRendererName('DomPDF');
        $fileName = $request->query('file_name');
        if (is_null($fileName)) {
            return [
                'code' => 4000,
                'msg' => 'SUCCESS',
            ];
        }
        $filePath = storage_path('app/public/file/' . $fileName);
        $Content = IOFactory::load($filePath, 'MsDoc');

        $PDFWriter = IOFactory::createWriter($Content, 'PDF');
        $saveFilName = Uuid::uuid4() . '.pdf';
        $savePath = storage_path('app/public/file/' . $saveFilName);
        $PDFWriter->save($savePath);
        return [
            'filePath' => $savePath,
            'fileName' => $saveFilName
        ];
    }

}
