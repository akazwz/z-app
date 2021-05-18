<?php

namespace App\Http\Controllers;

use App\Imports\MyImport;
use Excel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function GetToBarChartData(Request $request): JsonResponse
    {
        $fileName = $request->post('file_name');
        $filePath = storage_path('app/public/file/' . $fileName);
        $arr = Excel::import(new MyImport, $filePath);
        return response()->json($arr);
    }
}
