<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelController extends Controller
{
    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function exportExcel(): BinaryFileResponse
    {
        $data = [
            ['date' => '2021-05-01', 'area' => "200", 'distance' => "500", 'workTime' => "18"],
            ['date' => '2021-05-02', 'area' => "456", 'distance' => "788", 'workTime' => "13"],
            ['date' => '2021-05-03', 'area' => "555", 'distance' => "444", 'workTime' => "22"],
            ['date' => '2021-05-04', 'area' => "767", 'distance' => "566", 'workTime' => "11"],
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
