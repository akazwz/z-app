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
        $import = new MyImport();
        $data = Excel::toArray($import, $filePath)[0];
        $head = $data[0];
        $xText = $head[0];
        $yText = $head[1];
        array_shift($data);

        $xData = [];
        foreach ($data as $x) {
            array_push($xData, $x[0]);
        }
        $yData = [];
        foreach ($data as $x) {
            array_push($yData, $x[1]);
        }
        $chartData = [
            'x_text' => $xText,
            'y_text' => $yText,
            'x_data' => $xData,
            'y_data' => $yData,
        ];
        $res = [
            'code' => 2000,
            'msg' => 'SUCCESS',
            'data' => $chartData
        ];

        return response()->json($res);
    }
}
