<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\RedirectLogsController;


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
    // Retorna as estatísticas de acesso do redirect
    Route::get('/{code}/stats', [RedirectLogsController::class, 'showStats'])
        ->name('redirects.stats');

    // Retorna os logs de acesso do redirect
    Route::get('/{code}/logs', [RedirectLogsController::class, 'showLogs'])
        ->name('redirects.logs');
});

Route::resource('redirects', RedirectController::class)
    ->names([
        'index' => 'redirects.index',
        'store' => 'redirects.store',
        'update' => 'redirects.update',
        'destroy' => 'redirects.destroy',
    ]);
