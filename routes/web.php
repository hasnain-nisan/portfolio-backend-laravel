<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();
Auth::routes([
  'register' => false, // Registration Routes...
  'reset' => false, // Password Reset Routes...
  'verify' => false, // Email Verification Routes...
]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::post('/about', [App\Http\Controllers\HomeController::class, 'aboutPost'])->name('about-post');

Route::get('/brands', [App\Http\Controllers\HomeController::class, 'brands'])->name('brands');
Route::post('/add-brand', [App\Http\Controllers\HomeController::class, 'addBrand'])->name('add-brand');
Route::post('/edit-brand', [App\Http\Controllers\HomeController::class, 'editBrand'])->name('edit-brand');
Route::post('/delete-brand', [App\Http\Controllers\HomeController::class, 'deleteBrand'])->name('delete-brand');

Route::get('/contacts', [App\Http\Controllers\HomeController::class, 'contacts'])->name('contacts');
Route::post('/contact-update', [App\Http\Controllers\HomeController::class, 'updateContact'])->name('update-contact');
Route::post('/delete-contact', [App\Http\Controllers\HomeController::class, 'deleteContact'])->name('delete-contact');

Route::get('/experiences', [App\Http\Controllers\HomeController::class, 'experiences'])->name('experiences');
Route::post('/add-experience', [App\Http\Controllers\HomeController::class, 'addExperience'])->name('add-experience');
Route::post('/edit-experience', [App\Http\Controllers\HomeController::class, 'editExperience'])->name('edit-experience');
Route::post('/delete-experience', [App\Http\Controllers\HomeController::class, 'deleteExperience'])->name('delete-experience');

Route::get('/skills', [App\Http\Controllers\HomeController::class, 'skills'])->name('skills');
Route::post('/add-skill', [App\Http\Controllers\HomeController::class, 'addSkill'])->name('add-skill');
Route::post('/edit-skill', [App\Http\Controllers\HomeController::class, 'editSkill'])->name('edit-skill');
Route::post('/delete-skill', [App\Http\Controllers\HomeController::class, 'deleteSkill'])->name('delete-skill');