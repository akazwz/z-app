<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\FileController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('file-upload', [FileController::class, 'UploadFile']);

Route::resource('/book', 'BookController');
