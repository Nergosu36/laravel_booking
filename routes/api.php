<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//@@@@@@@@@@@@@ AUTH @@@@@@@@@@@@@
Route::any('unauthorized', [\App\Http\Controllers\AuthController::class, 'unauthorized'])->name('unauthorized');

Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');

//@@@@@@@@@@@@@ VACANCIES @@@@@@@@@@@@@
Route::get('vacancies/{vacancy?}', [\App\Http\Controllers\VacancyController::class, 'index'])->name('vacancies_get');
Route::post('vacancies', [\App\Http\Controllers\VacancyController::class, 'post'])->name('vacancies_post');
Route::put('vacancies/{vacancy}',  [\App\Http\Controllers\VacancyController::class, 'put'])->name('vacancies_put');
Route::patch('vacancies/{vacancy}', [\App\Http\Controllers\VacancyController::class, 'patch'])->name('vacancies_patch');
Route::delete('vacancies/{vacancy}', [\App\Http\Controllers\VacancyController::class, 'delete'])->name('vacancies_delete');

//@@@@@@@@@@@@@ RESERVATIONS @@@@@@@@@@@@@
Route::get('reservations/{reservation?}', [\App\Http\Controllers\ReservationController::class, 'index'])->name('reservations_get');
Route::post('reservations', [\App\Http\Controllers\ReservationController::class, 'post'])->name('reservations_post');
Route::put('reservations/{reservation}', [\App\Http\Controllers\ReservationController::class, 'put'])->name('reservations_put');
Route::patch('reservations/{reservation}', [\App\Http\Controllers\ReservationController::class, 'patch'])->name('reservations_patch');
Route::delete('reservations/{reservation}', [\App\Http\Controllers\ReservationController::class, 'delete'])->name('reservations_delete');

//@@@@@@@@@@@@@ CUSTOM @@@@@@@@@@@@@
Route::get('sandbox', [\App\Http\Controllers\Controller::class, 'sandbox'])->name('sandbox');