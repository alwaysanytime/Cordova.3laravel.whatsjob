<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

	Route::prefix('/v1')->group(function () {
		Route::get('/dashboard', [ApiController::class,'index'])->name('dashboard');
		Route::get('/employee', [ApiController::class,'employee'])->name('employee');
		Route::post('/store-employee', [ApiController::class,'store_employee'])->name('store-employee');
		Route::post('/update-employee', [ApiController::class,'update_employee'])->name('update-employee');
		Route::post('/delete-employee', [ApiController::class,'delete_employee'])->name('delete-employee');

		Route::get('/category', [ApiController::class,'category'])->name('category');
		Route::post('/store-category', [ApiController::class,'store_category'])->name('store-category');
		Route::post('/update-category', [ApiController::class,'update_category'])->name('update-category');
		Route::post('/delete-category', [ApiController::class,'delete_category'])->name('delete-category');		
		
		Route::post('/jobchat', [ApiController::class,'jobchat'])->name('jobchat');

	});

