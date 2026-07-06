<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CatalogueController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-layout', function () {
    return view('test');
});


Route::get('/el-meu-perfil', [ProfileController::class, 'edit']); // GET
Route::post('/el-meu-perfil', [ProfileController::class, 'update']); // POST

Route::post('/orders/add', [OrderController::class, 'addToCurrentOrder'])->name('orders.add');
Route::post('/orders/remove', [OrderController::class, 'removeFromCurrentOrder'])->name('orders.remove');
Route::post('/orders/confirm', [OrderController::class, 'confirmOrder'])->name('orders.confirm');


Route::get('/comandes', [OrderController::class, 'showOrders'])->name('orders.showOrders');
Route::get('/comandes/{id}', [OrderController::class, 'showOrderDetails'])->name('orders.showOrderDetails');

// Les rutes fixes han d'anar a dalt i la dinàmica a baix del tot.
Route::get('/{slug}', [CatalogueController::class, 'showChildProducts']);