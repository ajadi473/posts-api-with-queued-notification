<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group([
    'prefix' => '',
    'as' => 'api.',
], function () {
    Route::get('all-users', function() {
        $users = App\Models\User::all();

        return response()->json($users);
    });

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('feed/posts', [PostsController::class, 'index']);
});

Route::group([
    'prefix' => '',
    'as' => 'api.',
    'middleware' => ['auth:sanctum']
], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    
    
});
