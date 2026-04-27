<?php

use App\Http\Controllers\Api\DeveloperController;
use Illuminate\Support\Facades\Route;

/*
| Endpoints expostos:
|
|  POST   /devs              → Criar developer
|  GET    /devs              → Developers (primeiros 20)
|  GET    /devs?terms=[:t]   → Developers por termo
|  GET    /devs/{id}         → Detalhe de um developer
|
*/

Route::post('/devs', [DeveloperController::class, 'store']);
Route::get('/devs', [DeveloperController::class, 'index']);
Route::get('/devs/{id}', [DeveloperController::class, 'show']);
