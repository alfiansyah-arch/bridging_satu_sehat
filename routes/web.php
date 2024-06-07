<?php

use App\Http\Controllers\PractitionerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SatuSehatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('layouts');
});

Route::get('/satusehat', [SatuSehatController::class, 'index'])->name('satusehat.index');
Route::get('/generate-token', [SatuSehatController::class, 'getAccessToken'])->name('generate-token');

// Controller Practitioner Controller
Route::get('/practitioner-by-id', [PractitionerController::class, 'SearchId'])->name('practitioner.search-by-id');
Route::get('/practitioner-by-nik', [PractitionerController::class, 'SearchNik'])->name('practitioner.search-by-nik');
Route::get('/practitioner-by-name', [PractitionerController::class, 'SearchName'])->name('practitioner.search-by-name');
Route::get('/practitioners/{id}', [PractitionerController::class, 'view'])->name('practitioner.view');