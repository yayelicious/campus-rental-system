<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Livewire\AddNewItem;
use App\Livewire\MyListings;
use App\Livewire\MyRentals;
use App\Livewire\ViewItem;
use App\Livewire\EditItem;

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
    Route::get('/item/{item}/edit', EditItem::class)->name('edit-item');
});
