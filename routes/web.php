<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Livewire\AddNewItem;
use App\Http\Livewire\MyListings;
use App\Http\Livewire\MyRentals;
use App\Http\Livewire\ViewItem;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/my-listings', MyListings::class)->name('my-listings');
    Route::get('/add-item', AddNewItem::class)->name('add-item');
    Route::get('/my-rentals', MyRentals::class)->name('my-rentals');
    Route::get('/item/{id}', ViewItem::class)->name('item.view');
});
