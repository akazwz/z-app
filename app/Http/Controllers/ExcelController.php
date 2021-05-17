<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelController extends Controller
{
    public function exportExcel(): BinaryFileResponse
    {
        $data = [
            ['2021-05-01', 200, 500, 18],
            ['2021-05-02', 300, 700, 12],
            ['2021-05-03', 170, 534, 6],
            ['2021-05-04', 149, 345, 7],
        ];
        $headings = [
            ['日期', '面积', '长度', '时长'],
        ];
        $columnFormats = [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
        $export = new InvoicesExport($data, $headings, $columnFormats);
        $fileName = 'export.xlsx';
        return Excel::download($export, $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }
}
