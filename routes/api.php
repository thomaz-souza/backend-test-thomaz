<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('redirects')->group(function () {
    //Mostra todos os redirects
    Route::get('/', [RedirectController::class, 'index']);

    //Cria uma redirect
    Route::post('/', [RedirectController::class, 'store']);

    //Registra o RedirectLog
    Route::get('/{code}', [RedirectController::class, 'show'])
        ->middleware('logRedirectAccess');

    //Retorna as estatísticas de acesso do redirect
    Route::get('/{code}/stats', [RedirectController::class, 'showStats']);

    //Retorna os logs de acesso do redirect
    Route::get('/{code}/logs', [
        RedirectController::class, 'showLogs'
    ]);

    //Atualiza um redirect
    Route::put('/{code}', [RedirectController::class, 'update']);

    //Deleta um Redirect
    Route::delete('/{code}', [RedirectController::class, 'destroy']);
});
