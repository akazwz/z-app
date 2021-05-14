<?php

use App\Http\Controllers\HtmlToPdfController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/chart', function () {
    return view('chart');
});
Route::get('/chart-no-animation', function () {
    return view('chart_no_animation');
});
Route::get('/pdf', function () {
    return view('html_to_pdf');
});
Route::get('/html-to-pdf', [HtmlToPdfController::class, 'HtmlToPdf']);
Route::get('/html-to-image', [HtmlToPdfController::class, 'HtmlToImage']);
