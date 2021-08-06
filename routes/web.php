<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\PDFController;
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
    Route::get('', function () {
        return view('pdf/pdf');
    });
    Route::get('link-to-pdf', function () {
        return view('pdf/link_to_pdf');
    });
    Route::get('html-to-pdf', [ToPDFController::class, 'HtmlToPdf']);
    Route::post('is-url-valid', [ToPDFController::class, 'isURLValid']);
    Route::get('chose-pdf-option', function () {
        return view('pdf/chose_pdf_option');
    });
    Route::get('to-preview-pdf', [ToPDFController::class, 'toPreviewPDF']);
    Route::get('to-download-pdf', [ToPDFController::class, 'toDownloadPDF']);

    Route::get('parse-pdf', function () {
        return view('pdf/parse_pdf');
    });

    Route::get('show-parse-pdf', function () {
        return view('pdf/show_parse_pdf');
    });

    Route::get('word-to-pdf', function () {
        return view('pdf/word_to_pdf');
    });

    Route::get('chose-word-to-pdf-option', function () {
        return view('pdf/chose_word_to_pdf_option');
    });

    Route::get('word-to-pdf-preview', [PDFController::class, 'wordToPDFPreview']);
    Route::get('word-to-pdf-download', [PDFController::class, 'wordToPDFDownload']);
});

// CHART
Route::prefix('chart')->group(function () {
    Route::get('', function () {
        return view('chart/chart');
    });
    Route::get('excel-to-chart', function () {
        return view('chart/excel_to_chart');
    });
    Route::get('chose-chart-option', function () {
        return view('chart/chose_chart_option');
    });
    Route::get('to-bar-chart', function () {
        return view('chart/bar_chart');
    });
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chart-no-animation', function () {
    return view('chart_no_animation');
});

Route::get('/map', function () {
    return view('map/index');
});

Route::get('/calculate', function () {
    return view('map/calculate_area_and_distance');
});

Route::get('/example', function () {
    return view('vue/example');
});

Route::get('/export-excel', [ExcelController::class, 'exportExcel']);


Route::get('/html-to-image', [ToPDFController::class, 'HtmlToImage']);

Route::get('/component', function () {
    return view('component', ['message' => 'this is message']);
});
