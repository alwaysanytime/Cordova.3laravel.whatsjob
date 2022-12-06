<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/signup', [App\Http\Controllers\AuthController::class, 'create'])->name('signup');
Route::post('/signin', [App\Http\Controllers\AuthController::class, 'authenticate'])->name('signin');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
		Route::get('/dashboard', [AdminController::class,'index'])->name('dashboard');
		Route::get('/employee', [AdminController::class,'employee'])->name('employee');
		Route::post('/store-employee', [AdminController::class,'store_employee'])->name('store-employee');
		Route::post('/update-employee', [AdminController::class,'update_employee'])->name('update-employee');
		Route::post('/delete-employee', [AdminController::class,'delete_employee'])->name('delete-employee');
		Route::get('/category', [AdminController::class,'category'])->name('category');
		Route::post('/store-category', [AdminController::class,'store_category'])->name('store-category');
		Route::post('/update-category', [AdminController::class,'update_category'])->name('update-category');
		Route::post('/delete-category', [AdminController::class,'delete_category'])->name('delete-category');
		Route::get('/jobchat', [AdminController::class,'jobchat'])->name('jobchat');
		Route::get('/message-list/{id}', [AdminController::class,'message_list'])->name('message-list');		
		Route::post('/store-message-header', [AdminController::class,'store_message_header'])->name('store-message-header');
		Route::post('/store-message', [AdminController::class,'store_message'])->name('store-message');
		Route::get('/generation-usercode', [AdminController::class,'generation_usercode'])->name('generation-usercode');
		Route::get('/generate-usercode', [AdminController::class,'generate_usercode'])->name('generate-usercode');
	}
);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
