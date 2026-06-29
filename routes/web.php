<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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