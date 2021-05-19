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
        $data = Excel::toArray($import, $filePath)[0]; //excel原始数据

        $head = $data[0]; // excel第一行数据,excel头部

        $xText = $head[0]; // excel头部数据第一条,A1,当作X轴

        array_shift($data); // 去掉原始数据第一条,也就是去掉头部数据,剩下的就是excel数据

        $xData = [];
        $yData = [];

        foreach ($data as $excelData) {
            array_push($xData, $excelData[0]); //excel数据的第一列,就是x轴数据
            array_shift($excelData);
            array_push($yData, $excelData); // excel y轴数据
        }

        $yDataRes = [];

        // 矩阵转置
        for ($i = 0; $i < count($yData); $i++) {
            for ($j = 0; $j < count($yData[$i]); $j++) {
                $yDataRes[$j][$i] = $yData[$i][$j];
            }
        }

        array_shift($head); //头部数据去掉第一条,就是y轴

        $chartData = [
            'x_text' => $xText,
            'y_head' => $head,
            'x_data' => $xData,
            'y_data' => $yDataRes,
        ];
        $res = [
            'code' => 2000,
            'msg' => 'SUCCESS',
            'data' => $chartData
        ];

        return response()->json($res);
    }
}
