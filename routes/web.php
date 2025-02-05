<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

// Meetings
Route::prefix('meetings')->group(function () {
    Route::get('/', [App\Http\Controllers\MeetingController::class, 'index'])->name('meetings');
    Route::get('/export', [App\Http\Controllers\MeetingController::class, 'export'])->name('meetings.export');
    Route::get('/upload/{meeting}', [App\Http\Controllers\MeetingController::class, 'upload'])->name('meetings.upload');
    Route::get('/upload', [App\Http\Controllers\MeetingController::class, 'upload_all'])->name('meetings.upload_all');
    Route::get('/new', [App\Http\Controllers\MeetingController::class, 'new'])->name('meetings.new');
    Route::post('/create', [App\Http\Controllers\MeetingController::class, 'create'])->name('meetings.create');
    Route::get('/{meeting}/edit', [App\Http\Controllers\MeetingController::class, 'edit'])->name('meetings.edit');
    Route::post('/{meeting}/update', [App\Http\Controllers\MeetingController::class, 'update'])->name('meetings.update');
    Route::get('/{meeting}/destroy', [App\Http\Controllers\MeetingController::class, 'destroy'])->name('meetings.destroy');
    Route::get('/{meeting}/events', [App\Http\Controllers\MeetingController::class, 'events'])->name('meetings.events');
    Route::post('/event_create', [App\Http\Controllers\MeetingController::class, 'event_create'])->name('meetings.event_create');
});

// Events
Route::prefix('events')->group(function () {
    Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('events');
    Route::get('/export', [App\Http\Controllers\EventController::class, 'export'])->name('events.export');
    Route::get('/upload/{event}', [App\Http\Controllers\EventController::class, 'upload'])->name('events.upload');
    Route::get('/upload', [App\Http\Controllers\EventController::class, 'upload_all'])->name('events.upload_all');
    Route::get('/new', [App\Http\Controllers\EventController::class, 'new'])->name('events.new');
    Route::post('/create', [App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::get('/{event}/edit', [App\Http\Controllers\EventController::class, 'edit'])->name('events.edit');
    Route::post('/{event}/update', [App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::get('/{event}/destroy', [App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/{event}/results', [App\Http\Controllers\EventController::class, 'results'])->name('events.results');
    Route::get('/{event}/get_results', [App\Http\Controllers\EventController::class, 'get_results'])->name('events.get_results');
    Route::post('/result_create', [App\Http\Controllers\EventController::class, 'result_create'])->name('events.result_create');
});

// Competitors
Route::prefix('competitors')->group(function () {
    Route::get('/', [App\Http\Controllers\CompetitorController::class, 'index'])->name('competitors');
    Route::get('/export', [App\Http\Controllers\CompetitorController::class, 'export'])->name('competitors.export');
    Route::get('/upload/{competitor}', [App\Http\Controllers\CompetitorController::class, 'upload'])->name('competitors.upload');
    Route::get('/upload', [App\Http\Controllers\CompetitorController::class, 'upload_all'])->name('competitors.upload_all');
    Route::get('/new', [App\Http\Controllers\CompetitorController::class, 'new'])->name('competitors.new');
    Route::post('/create', [App\Http\Controllers\CompetitorController::class, 'create'])->name('competitors.create');
    Route::get('/{competitor}/edit', [App\Http\Controllers\CompetitorController::class, 'edit'])->name('competitors.edit');
    Route::post('/{competitor}/update', [App\Http\Controllers\CompetitorController::class, 'update'])->name('competitors.update');
    Route::get('/{competitor}/destroy', [App\Http\Controllers\CompetitorController::class, 'destroy'])->name('competitors.destroy');
});

// Athletes
Route::prefix('athletes')->group(function () {
    Route::get('/', [App\Http\Controllers\AthleteController::class, 'index'])->name('athletes');
    Route::get('/export', [App\Http\Controllers\AthleteController::class, 'export'])->name('athletes.export');
    Route::get('/upload/{athlete}', [App\Http\Controllers\AthleteController::class, 'upload'])->name('athletes.upload');
    Route::get('/upload', [App\Http\Controllers\AthleteController::class, 'upload_all'])->name('athletes.upload_all');
    Route::get('/new', [App\Http\Controllers\AthleteController::class, 'new'])->name('athletes.new');
    Route::post('/create', [App\Http\Controllers\AthleteController::class, 'create'])->name('athletes.create');
    Route::get('/{athlete}/edit', [App\Http\Controllers\AthleteController::class, 'edit'])->name('athletes.edit');
    Route::post('/{athlete}/update', [App\Http\Controllers\AthleteController::class, 'update'])->name('athletes.update');
    Route::get('/{athlete}/destroy', [App\Http\Controllers\AthleteController::class, 'destroy'])->name('athletes.destroy');
});

// Records
Route::prefix('records')->group(function () {
    Route::get('/', [App\Http\Controllers\RecordController::class, 'index'])->name('records');
    Route::get('/export', [App\Http\Controllers\RecordController::class, 'export'])->name('records.export');
    Route::get('/upload/{record}', [App\Http\Controllers\RecordController::class, 'upload'])->name('records.upload');
    Route::get('/upload', [App\Http\Controllers\RecordController::class, 'upload_all'])->name('records.upload_all');
    Route::get('/new', [App\Http\Controllers\RecordController::class, 'new'])->name('records.new');
    Route::post('/create', [App\Http\Controllers\RecordController::class, 'create'])->name('records.create');
    Route::get('/{record}/edit', [App\Http\Controllers\RecordController::class, 'edit'])->name('records.edit');
    Route::post('/{record}/update', [App\Http\Controllers\RecordController::class, 'update'])->name('records.update');
    Route::get('/{record}/destroy', [App\Http\Controllers\RecordController::class, 'destroy'])->name('records.destroy');
    Route::get('/{record}/copy', [App\Http\Controllers\RecordController::class, 'copy'])->name('records.copy');
});

// Results
Route::prefix('results')->group(function () {
    Route::get('/', [App\Http\Controllers\ResultController::class, 'index'])->name('results');
    Route::get('/scoring', [App\Http\Controllers\ResultController::class, 'scoring'])->name('results.scoring');
    Route::get('/export', [App\Http\Controllers\ResultController::class, 'export'])->name('results.export');
    Route::get('/upload/{result}', [App\Http\Controllers\ResultController::class, 'upload'])->name('results.upload');
    Route::get('/upload', [App\Http\Controllers\ResultController::class, 'upload_all'])->name('results.upload_all');
    Route::get('/new', [App\Http\Controllers\ResultController::class, 'new'])->name('results.new');
    Route::post('/create', [App\Http\Controllers\ResultController::class, 'create'])->name('results.create');
    Route::get('/{result}/edit', [App\Http\Controllers\ResultController::class, 'edit'])->name('results.edit');
    Route::post('/{result}/update', [App\Http\Controllers\ResultController::class, 'update'])->name('results.update');
    Route::get('/{result}/destroy', [App\Http\Controllers\ResultController::class, 'destroy'])->name('results.destroy');
    Route::get('/{result}/new_record', [App\Http\Controllers\ResultController::class, 'new_record'])->name('results.new_record');
    Route::post('/{result}/create_record', [App\Http\Controllers\ResultController::class, 'create_record'])->name('results.create_record');
});

// Users
Route::prefix('users')->group(function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/export', [App\Http\Controllers\UserController::class, 'export'])->name('users.export');
    Route::get('/new', [App\Http\Controllers\UserController::class, 'new'])->name('users.new');
    Route::post('/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
    Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
    Route::post('/{user}/update', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
    Route::get('/{user}/destroy', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/{user}/change_password', [App\Http\Controllers\UserController::class, 'change_password']);
});

// Reports
Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports');

Route::get('/password', [App\Http\Controllers\UserController::class, 'password']);

// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
