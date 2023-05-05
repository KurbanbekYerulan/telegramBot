<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\HomeworksController;
use Illuminate\Support\Facades\Route;

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
    return \route('login');
});

Route::match(['get', 'post'], 'botman', [BotManController::class, 'handle']);

Route::controller(LoginRegisterController::class)->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
});

Route::resource('homeworks', HomeworksController::class, ['except' => ['show']]);
Route::resource('groups', GroupsController::class, ['except' => ['show']]);
Route::resource('schedule', \App\Http\Controllers\ScheduleController::class, ['except' => ['show']]);
Route::get('message', [BotManController::class, 'messageIndex'])->name('message.index');
Route::post('/send-message', [App\Http\Controllers\MessageController::class, 'messageSend'])->name('message.send');
Route::resource('questions',\App\Http\Controllers\QuestionController::class);
Route::resource('answers',\App\Http\Controllers\AnswerController::class);
Route::resource('test',\App\Http\Controllers\TestController::class);
Route::resource('material',\App\Http\Controllers\MaterialController::class);
