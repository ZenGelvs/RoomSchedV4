<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return view('welcome');
});

// Login 
Route::group(['prefix' => 'login', 'as' => 'login.'], function () {
    Route::post('/', [LoginController::class, 'login']);
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect Login Depending on Account logged in
Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/admin', [DashboardController::class, 'adminIndex'])->name('adminIndex');
    Route::get('/room-coordinator', [DashboardController::class, 'roomCoordIndex'])->name('roomCoordIndex');
});

// Admin Route (Subjects handling)
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/manage-subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::post('/upload-subjects', [SubjectController::class, 'upload'])->name('subjects.upload');
    Route::post('/store-subject', [SubjectController::class, 'store'])->name('subjects.store');
    Route::delete('/subjects/delete-all', [SubjectController::class, 'deleteAll'] )->name('subjects.deleteAll');

});
