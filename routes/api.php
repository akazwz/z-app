<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InfluxDBController;
use App\Http\Controllers\PDFController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// CHART
Route::prefix('chart')->group(function () {
    Route::get('to-bar-chart-data', [ChartController::class, 'GetToBarChartData']);
});

// CHART
Route::prefix('pdf')->group(function () {
    Route::get('parse', [PDFController::class, 'parsePdf']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Location

Route::post('file-upload', [FileController::class, 'UploadFile']);
Route::get('work-data', [InfluxDBController::class, 'getWorkDataToShow']);

Route::resource('/book', 'BookController');
