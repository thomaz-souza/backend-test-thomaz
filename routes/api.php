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

Route::prefix('redirects')->middleware('auth')->group(function () {
    Route::post('/', [RedirectController::class, 'store']);
    Route::get('/{code}', [RedirectController::class, 'show']);
    Route::put('/{code}', [RedirectController::class, 'update']);
    Route::delete('/{code}', [RedirectController::class, 'destroy']);
});
