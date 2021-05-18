<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ToPDFController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// PDF
Route::prefix('pdf')->group(function () {
    Route::get('/', function () {
        return view('pdf/pdf');
    });
    Route::get('/link-to-pdf', function () {
        return view('pdf/link_to_pdf');
    });
    Route::get('/html-to-pdf', [ToPDFController::class, 'HtmlToPdf']);
    Route::post('/is-url-valid', [ToPDFController::class, 'isURLValid']);
    Route::get('/chose-pdf-option', function () {
        return view('pdf/chose-pdf-option');
    });
    Route::get('/to-preview-pdf', [ToPDFController::class, 'toPreviewPDF']);
    Route::get('/to-download-pdf', [ToPDFController::class, 'toDownloadPDF']);
});

// CHART
Route::prefix('chart')->group(function () {
    Route::get('/', function () {
        return view('chart/chart');
    });
    Route::get('excel-to-chart', function () {
        return view('chart/excel_to_chart');
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chart-no-animation', function () {
    return view('chart_no_animation');
});

Route::get('/export-excel', [ExcelController::class, 'exportExcel']);


Route::get('/html-to-image', [ToPDFController::class, 'HtmlToImage']);
