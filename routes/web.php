<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [AboutController::class, 'index'])->name('about');
Route::get('/alumni-directory', [AlumniController::class, 'index'])->name('alumni.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/news-updates', [NewsController::class, 'index'])->name('news.index');
Route::get('/news-updates/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/contributions', [ContributionController::class, 'index'])->name('contributions.index');
Route::get('/career-networking', [CareerController::class, 'index'])->name('career.index');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/{gallery:slug}', [GalleryController::class, 'show'])->name('gallery.show');
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact-us', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::get('/registration', [RegistrationController::class, 'index'])->name('registration.index');
