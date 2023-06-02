<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\HomeController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/uji', function (Request $request) {
//     return 'test';
// });

Route::post('/login',[HomeController::class,'login']);
Route::post('/register',[HomeController::class,'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('voting/{id?}',[HomeController::class,'voting']);
    Route::get('is-voting',[HomeController::class,'isVoting']);
    Route::get('category',[HomeController::class,'category']);
    Route::get('vote-check',[HomeController::class,'voteCheck']);
    Route::post('vote/{id}',[HomeController::class,'vote']);
    Route::post('batal/{id}',[HomeController::class,'batal']);
    Route::post('logout',[HomeController::class,'logout']);
});
