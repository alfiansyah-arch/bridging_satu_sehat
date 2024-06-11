<?php

use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrganizationController;
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
Route::get('/generate-token', [SatuSehatController::class, 'generateToken'])->name('generate-token');


// Controller Practitioner Controller
Route::get('/practitioner-by-id', [PractitionerController::class, 'SearchId'])->name('practitioner.search-by-id');
Route::get('/practitioner-by-nik', [PractitionerController::class, 'SearchNik'])->name('practitioner.search-by-nik');
Route::get('/practitioner-by-name', [PractitionerController::class, 'SearchName'])->name('practitioner.search-by-name');
Route::get('/practitioners/{id}', [PractitionerController::class, 'view'])->name('practitioner.view');

// Controller Organization Controller
Route::get('/organization-by-id', [OrganizationController::class, 'SearchId'])->name('organization.search-by-id');
Route::get('/organization-by-name', [OrganizationController::class, 'SearchName'])->name('organization.search-by-name');
Route::get('/organization-by-partof', [OrganizationController::class, 'SearchPartOf'])->name('organization.search-by-partof');
Route::get('/organizations/{id}', [OrganizationController::class, 'view'])->name('organization.view');
Route::get('/organization/create', function () { return view('organization.form-organization');})->name('organization.form');
Route::post('/organization/create', [OrganizationController::class, 'createOrganization'])->name('organization.create');
Route::get('/organization/edit/{id}', [OrganizationController::class, 'editOrganization'])->name('organization.edit');
Route::put('/organization/update/{id}', [OrganizationController::class, 'updateOrganization'])->name('organization.update');

// Controller Location Controller
Route::get('/location-by-id', [LocationController::class, 'SearchId'])->name('location.search-by-id');
Route::get('/location-by-name', [LocationController::class, 'SearchName'])->name('location.search-by-name');
Route::get('/location-by-partof', [LocationController::class, 'SearchPartOf'])->name('location.search-by-partof');
Route::get('/locations/{id}', [LocationController::class, 'viewLocation'])->name('location.view');
Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
Route::post('/location/post', [LocationController::class, 'postLocation'])->name('location.post');
Route::get('/location/edit/{id}', [LocationController::class, 'editLocation'])->name('location.edit');
Route::put('/location/update/{id}', [LocationController::class, 'putLocation'])->name('location.update');