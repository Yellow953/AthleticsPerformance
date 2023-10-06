<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

// Meetings
Route::get('/meetings', [App\Http\Controllers\MeetingController::class, 'index']);
Route::get('/meetings/export', [App\Http\Controllers\MeetingController::class, 'export']);
Route::get('/meetings/upload/{id}', [App\Http\Controllers\MeetingController::class, 'upload']);
Route::get('/meetings/upload', [App\Http\Controllers\MeetingController::class, 'upload_all']);
Route::get('/meeting/new', [App\Http\Controllers\MeetingController::class, 'new']);
Route::post('/meeting/create', [App\Http\Controllers\MeetingController::class, 'create']);
Route::get('/meeting/{id}/edit', [App\Http\Controllers\MeetingController::class, 'edit']);
Route::post('/meeting/{id}/update', [App\Http\Controllers\MeetingController::class, 'update']);
Route::get('/meeting/{id}/destroy', [App\Http\Controllers\MeetingController::class, 'destroy']);
Route::get('/meeting/{id}/events', [App\Http\Controllers\MeetingController::class, 'events']);
Route::post('/event_create', [App\Http\Controllers\MeetingController::class, 'event_create']);

// Events
Route::get('/events', [App\Http\Controllers\EventController::class, 'index']);
Route::get('/events/export', [App\Http\Controllers\EventController::class, 'export']);
Route::get('/events/upload/{id}', [App\Http\Controllers\EventController::class, 'upload']);
Route::get('/events/upload', [App\Http\Controllers\EventController::class, 'upload_all']);
Route::get('/event/new', [App\Http\Controllers\EventController::class, 'new']);
Route::post('/event/create', [App\Http\Controllers\EventController::class, 'create']);
Route::get('/event/{id}/edit', [App\Http\Controllers\EventController::class, 'edit']);
Route::post('/event/{id}/update', [App\Http\Controllers\EventController::class, 'update']);
Route::get('/event/{id}/destroy', [App\Http\Controllers\EventController::class, 'destroy']);
Route::get('/event/{id}/results', [App\Http\Controllers\EventController::class, 'results']);
Route::get('/event/{id}/get_results', [App\Http\Controllers\EventController::class, 'get_results']);
Route::post('/result_create', [App\Http\Controllers\EventController::class, 'result_create']);

// Competitors
Route::get('/competitors', [App\Http\Controllers\CompetitorController::class, 'index']);
Route::get('/competitors/export', [App\Http\Controllers\CompetitorController::class, 'export']);
Route::get('/competitors/upload/{id}', [App\Http\Controllers\CompetitorController::class, 'upload']);
Route::get('/competitors/upload', [App\Http\Controllers\CompetitorController::class, 'upload_all']);
Route::get('/competitor/new', [App\Http\Controllers\CompetitorController::class, 'new']);
Route::post('/competitor/create', [App\Http\Controllers\CompetitorController::class, 'create']);
Route::get('/competitor/{id}/edit', [App\Http\Controllers\CompetitorController::class, 'edit']);
Route::post('/competitor/{id}/update', [App\Http\Controllers\CompetitorController::class, 'update']);
Route::get('/competitor/{id}/destroy', [App\Http\Controllers\CompetitorController::class, 'destroy']);

// Athletes
Route::get('/athletes', [App\Http\Controllers\AthleteController::class, 'index']);
Route::get('/athletes/export', [App\Http\Controllers\AthleteController::class, 'export']);
Route::get('/athletes/upload/{id}', [App\Http\Controllers\AthleteController::class, 'upload']);
Route::get('/athletes/upload', [App\Http\Controllers\AthleteController::class, 'upload_all']);
Route::get('/athlete/new', [App\Http\Controllers\AthleteController::class, 'new']);
Route::post('/athlete/create', [App\Http\Controllers\AthleteController::class, 'create']);
Route::get('/athlete/{id}/edit', [App\Http\Controllers\AthleteController::class, 'edit']);
Route::post('/athlete/{id}/update', [App\Http\Controllers\AthleteController::class, 'update']);
Route::get('/athlete/{id}/destroy', [App\Http\Controllers\AthleteController::class, 'destroy']);

// Records
Route::get('/records', [App\Http\Controllers\RecordController::class, 'index']);
Route::get('/records/export', [App\Http\Controllers\RecordController::class, 'export']);
Route::get('/records/upload/{id}', [App\Http\Controllers\RecordController::class, 'upload']);
Route::get('/records/upload', [App\Http\Controllers\RecordController::class, 'upload_all']);
Route::get('/record/new', [App\Http\Controllers\RecordController::class, 'new']);
Route::post('/record/create', [App\Http\Controllers\RecordController::class, 'create']);
Route::get('/record/{id}/edit', [App\Http\Controllers\RecordController::class, 'edit']);
Route::post('/record/{id}/update', [App\Http\Controllers\RecordController::class, 'update']);
Route::get('/record/{id}/destroy', [App\Http\Controllers\RecordController::class, 'destroy']);
Route::get('/record/{id}/copy', [App\Http\Controllers\RecordController::class, 'copy']);

// Results
Route::get('/results', [App\Http\Controllers\ResultController::class, 'index']);
Route::get('/results/scoring', [App\Http\Controllers\ResultController::class, 'scoring']);
Route::get('/results/export', [App\Http\Controllers\ResultController::class, 'export']);
Route::get('/results/upload/{id}', [App\Http\Controllers\ResultController::class, 'upload']);
Route::get('/results/upload', [App\Http\Controllers\ResultController::class, 'upload_all']);
Route::get('/result/new', [App\Http\Controllers\ResultController::class, 'new']);
Route::post('/result/create', [App\Http\Controllers\ResultController::class, 'create']);
Route::get('/result/{id}/edit', [App\Http\Controllers\ResultController::class, 'edit']);
Route::post('/result/{id}/update', [App\Http\Controllers\ResultController::class, 'update']);
Route::get('/result/{id}/destroy', [App\Http\Controllers\ResultController::class, 'destroy']);
Route::get('/result/{id}/new_record', [App\Http\Controllers\ResultController::class, 'new_record']);
Route::post('/result/{id}/create_record', [App\Http\Controllers\ResultController::class, 'create_record']);

// Users
Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::get('/users/export', [App\Http\Controllers\UserController::class, 'export']);
Route::get('/user/new', [App\Http\Controllers\UserController::class, 'new']);
Route::post('/user/create', [App\Http\Controllers\UserController::class, 'create']);
Route::get('/user/{id}/edit', [App\Http\Controllers\UserController::class, 'edit']);
Route::post('/user/{id}/update', [App\Http\Controllers\UserController::class, 'update']);
Route::get('/user/{id}/destroy', [App\Http\Controllers\UserController::class, 'destroy']);

// Reports
Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index']);

// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);