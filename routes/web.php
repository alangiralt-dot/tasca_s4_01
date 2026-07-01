<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogueController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-layout', function () {
    return view('test');
});
// GET
Route::get('/el-meu-perfil', [ProfileController::class, 'edit']);
// POST
Route::post('/el-meu-perfil', [ProfileController::class, 'update']);
// Les rutes fixes han d'anar a dalt i la dinàmica a baix del tot.
Route::get('/{slug}', [CatalogueController::class, 'showChildProducts']);