<?php

use App\Http\Controllers\CompositionController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PractitionerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SatuSehatController;
use App\Http\Controllers\ObservationController;

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
    return view('generate-token');
});
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

// Controller Encounter Controller
Route::get('/encounter-by-id', [EncounterController::class, 'SearchId'])->name('encounter.search-by-id');
Route::get('/encounter-by-name', [EncounterController::class, 'SearchSubject'])->name('encounter.search-by-subject');
Route::get('/encounters/{id}', [EncounterController::class, 'viewEncounter'])->name('encounter.view');
Route::get('/encounter/create', [EncounterController::class, 'createEncounter'])->name('encounter.create');
Route::post('/encounter/store', [EncounterController::class, 'storeEncounter'])->name('encounter.store');
Route::get('/encounter/edit/{id}', [EncounterController::class, 'editEncounter'])->name('encounter.edit');
Route::put('/encounter/update/{id}', [EncounterController::class, 'putEncounter'])->name('encounter.update');

// Controller Condition Controller
Route::get('/condition-by-id', [ConditionController::class, 'SearchId'])->name('condition.search-by-id');
Route::get('/condition-by-subject', [ConditionController::class, 'SearchSubject'])->name('condition.search-by-subject');
Route::get('/condition-by-encounter', [ConditionController::class, 'SearchEncounter'])->name('condition.search-by-encounter');
Route::get('/condition-by-subject-encounter', [ConditionController::class, 'SearchSubjectEncounter'])->name('condition.search-by-subject-encounter');
Route::get('/conditions/{id}', [ConditionController::class, 'viewEncounter'])->name('condition.view');
Route::get('/condition/create', [ConditionController::class, 'createEncounter'])->name('condition.create');
Route::post('/condition/store', [ConditionController::class, 'storeEncounter'])->name('condition.store');
Route::get('/condition/edit/{id}', [ConditionController::class, 'editEncounter'])->name('condition.edit');
Route::put('/condition/update/{id}', [ConditionController::class, 'putEncounter'])->name('condition.update');

// Controller Composition Controller
Route::get('/composition-by-id', [CompositionController::class, 'SearchId'])->name('composition.search-by-id');
Route::get('/composition-by-subject', [CompositionController::class, 'SearchSubject'])->name('composition.search-by-subject');
Route::get('/composition-by-encounter', [CompositionController::class, 'SearchEncounter'])->name('composition.search-by-encounter');
Route::get('/composition-by-subject-encounter', [CompositionController::class, 'SearchSubjectEncounter'])->name('composition.search-by-subject-encounter');
Route::get('/compositions/{id}', [CompositionController::class, 'viewEncounter'])->name('composition.view');
Route::get('/composition/create', [CompositionController::class, 'createEncounter'])->name('composition.create');
Route::post('/composition/store', [CompositionController::class, 'storeEncounter'])->name('composition.store');
Route::get('/composition/edit/{id}', [CompositionController::class, 'editEncounter'])->name('composition.edit');
Route::put('/composition/update/{id}', [CompositionController::class, 'putEncounter'])->name('composition.update');

// Controller Patient Controller
Route::get('/patient-by-id', [PatientController::class, 'SearchId'])->name('patient.search-by-id');
Route::get('/patient-by-nik', [PatientController::class, 'SearchNik'])->name('patient.search-by-nik');
Route::get('/patient-by-bayi', [PatientController::class, 'SearchBayi'])->name('patient.search-by-bayi');
Route::get('/patient-by-name-birth-nik', [PatientController::class, 'SearchNameBirthNik'])->name('patient.search-by-name-birth-nik');
Route::get('/patient-by-name-birth-gender', [PatientController::class, 'SearchNameBirthGender'])->name('patient.search-by-name-birth-gender');
Route::get('/patient/detail-1', [PatientController::class, 'viewDetail1'])->name('patient.view');
Route::get('/patient/create', function () { return view('patient.form-patient');})->name('patient.form');
Route::post('/patient/create', [PatientController::class, 'createOrganization'])->name('patient.create');
Route::get('/patient/edit/{id}', [PatientController::class, 'editOrganization'])->name('patient.edit');
Route::put('/patient/update/{id}', [PatientController::class, 'updateOrganization'])->name('patient.update');

// Controller Observation Controller
Route::get('/observation-by-id', [ObservationController::class, 'SearchId'])->name('observation.search-by-id');
Route::get('/observation-by-subject', [ObservationController::class, 'SearchSubject'])->name('observation.search-by-subject');
Route::get('/observation-by-subject-encounter', [ObservationController::class, 'SearchSubjectEncounter'])->name('observation.search-by-subject-encounter');
Route::get('/observation-by-encounter', [ObservationController::class, 'SearchEncounter'])->name('observation.search-by-encounter');
Route::get('/observation-by-service-request', [ObservationController::class, 'SearchServiceRequest'])->name('observation.search-by-service-request');
Route::get('/observation/detail-1', [ObservationController::class, 'viewDetail1'])->name('observation.view');
Route::get('/observation/create', function () { return view('observation.form-observation');})->name('observation.form');
Route::post('/observation/create', [ObservationController::class, 'createObservation'])->name('observation.create');
Route::get('/observation/edit/{id}', [ObservationController::class, 'editOrganization'])->name('observation.edit');
Route::put('/observation/update/{id}', [ObservationController::class, 'updateOrganization'])->name('observation.update');