<?php

use App\Http\Controllers\{HomeController, CategoryController, PropertyController, ContactController};
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

// jezički switch (session-based)
Route::get('/locale/{lang}', function (Request $request, string $lang) {
    $supported = ['en','sr','ru'];
    if (in_array($lang, $supported, true)) {
        $request->session()->put('locale', $lang);
        App::setLocale($lang); // važi odmah za ovaj response
    }
    return back();
})->name('locale.switch');

// kontakt
Route::view('/contact', 'contact')->name('contact'); // GET forma
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send'); // POST submit

// home
Route::get('/', [HomeController::class, 'index'])->name('home');

// kategorije i nekretnine
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{slug}', [PropertyController::class, 'show'])->name('properties.show');