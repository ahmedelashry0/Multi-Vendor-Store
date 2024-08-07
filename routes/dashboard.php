<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboadrController;
use App\Http\Controllers\Dashboard\ProductsController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => ['auth'],
    'as' => 'dashboard.',
    'prefix' => 'dashboard'
], function () {
    Route::get('/', [DashboadrController::class, 'index'])->name('dashboard');
    Route::get('/categories/trash', [CategoriesController::class , 'trash'])->name('categories.trash');
    Route::put('/categories/{category}/restore', [CategoriesController::class , 'restore' ])->name('categories.restore');
    Route::delete('/categories/{category}/force-delete', [CategoriesController::class , 'force-delete' ])->name('categories.force-delete');
    Route::resource('/categories', CategoriesController::class);


    Route::resource('/products', ProductsController::class);


});




//Route::middleware('auth')->group(function (){
//    Route::get('/dashboard', [DashboadrController::class , 'index'])->name('dashboard');
//    Route::resource('dashboard/categories', Categories Controller::class);
//});
