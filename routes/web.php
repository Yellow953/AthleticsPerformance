<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

// Meetings
Route::get('/meetings', [App\Http\Controllers\MeetingController::class, 'index']);
Route::get('/meeting/export', [App\Http\Controllers\MeetingController::class, 'export']);
Route::get('/meeting/new', [App\Http\Controllers\MeetingController::class, 'new']);
Route::post('/meeting/create', [App\Http\Controllers\MeetingController::class, 'create']);
Route::get('/meeting/{id}/edit', [App\Http\Controllers\MeetingController::class, 'edit']);
Route::post('/meeting/{id}/update', [App\Http\Controllers\MeetingController::class, 'update']);
Route::get('/meeting/{id}/delete', [App\Http\Controllers\MeetingController::class, 'destroy']);

// Events
Route::get('/events', [App\Http\Controllers\EventController::class, 'index']);
Route::get('/event/export', [App\Http\Controllers\EventController::class, 'export']);
Route::get('/event/new', [App\Http\Controllers\EventController::class, 'new']);
Route::post('/event/create', [App\Http\Controllers\EventController::class, 'create']);
Route::get('/event/{id}/edit', [App\Http\Controllers\EventController::class, 'edit']);
Route::post('/event/{id}/update', [App\Http\Controllers\EventController::class, 'update']);
Route::get('/event/{id}/delete', [App\Http\Controllers\EventController::class, 'destroy']);

// Competitors
Route::get('/competitors', [App\Http\Controllers\CompetitorController::class, 'index']);
Route::get('/competitor/export', [App\Http\Controllers\CompetitorController::class, 'export']);
Route::get('/competitor/new', [App\Http\Controllers\CompetitorController::class, 'new']);
Route::post('/competitor/create', [App\Http\Controllers\CompetitorController::class, 'create']);
Route::get('/competitor/{id}/edit', [App\Http\Controllers\CompetitorController::class, 'edit']);
Route::post('/competitor/{id}/update', [App\Http\Controllers\CompetitorController::class, 'update']);
Route::get('/competitor/{id}/delete', [App\Http\Controllers\CompetitorController::class, 'destroy']);

// Athletes
Route::get('/athletes', [App\Http\Controllers\AthleteController::class, 'index']);
Route::get('/athlete/export', [App\Http\Controllers\AthleteController::class, 'export']);
Route::get('/athlete/new', [App\Http\Controllers\AthleteController::class, 'new']);
Route::post('/athlete/create', [App\Http\Controllers\AthleteController::class, 'create']);
Route::get('/athlete/{id}/edit', [App\Http\Controllers\AthleteController::class, 'edit']);
Route::post('/athlete/{id}/update', [App\Http\Controllers\AthleteController::class, 'update']);
Route::get('/athlete/{id}/delete', [App\Http\Controllers\AthleteController::class, 'destroy']);

// Records
Route::get('/records', [App\Http\Controllers\RecordController::class, 'index']);
Route::get('/record/export', [App\Http\Controllers\RecordController::class, 'export']);
Route::get('/record/new', [App\Http\Controllers\RecordController::class, 'new']);
Route::post('/record/create', [App\Http\Controllers\RecordController::class, 'create']);
Route::get('/record/{id}/edit', [App\Http\Controllers\RecordController::class, 'edit']);
Route::post('/record/{id}/update', [App\Http\Controllers\RecordController::class, 'update']);
Route::get('/record/{id}/delete', [App\Http\Controllers\RecordController::class, 'destroy']);

// Results
Route::get('/results', [App\Http\Controllers\ResultController::class, 'index']);
Route::get('/result/export', [App\Http\Controllers\ResultController::class, 'export']);
Route::get('/result/new', [App\Http\Controllers\ResultController::class, 'new']);
Route::post('/result/create', [App\Http\Controllers\ResultController::class, 'create']);
Route::get('/result/{id}/edit', [App\Http\Controllers\ResultController::class, 'edit']);
Route::post('/result/{id}/update', [App\Http\Controllers\ResultController::class, 'update']);
Route::get('/result/{id}/delete', [App\Http\Controllers\ResultController::class, 'destroy']);

// Users
Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::get('/user/export', [App\Http\Controllers\UserController::class, 'export']);
Route::get('/user/new', [App\Http\Controllers\UserController::class, 'new']);
Route::post('/user/create', [App\Http\Controllers\UserController::class, 'create']);
Route::get('/user/{id}/edit', [App\Http\Controllers\UserController::class, 'edit']);
Route::post('/user/{id}/update', [App\Http\Controllers\UserController::class, 'update']);
Route::get('/user/{id}/delete', [App\Http\Controllers\UserController::class, 'destroy']);

// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);