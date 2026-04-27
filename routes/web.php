<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\DeveloperWebController;

/*
| Web Routes — Frontend Blade
|
| Rotas para as páginas de frontend.
|
*/

// Página principal — lista de developers
Route::get('/', [DeveloperWebController::class, 'index'])->name('developers.index');

// Página de detalhe de um developer
Route::get('/developers/{id}', [DeveloperWebController::class, 'show'])->name('developers.show');

// Página de criar developer
Route::get('/developers/create', [DeveloperWebController::class, 'create'])->name('developers.create');
