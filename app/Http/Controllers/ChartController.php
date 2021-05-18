<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function GetToBarChartData(Request $request): JsonResponse
    {
        $fileName = $request->post('file_name');
        $filePath = storage_path('public/file/' . $fileName);
        return response()->json($filePath);
    }
}
