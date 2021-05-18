<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
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

// Route::resource('users', UsersController::class);

//Public
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/users', [UsersController::class, 'index']);
Route::get('/users/{id}', [UsersController::class, 'show']);

//Protected
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/users', [UsersController::class, 'store']);
    Route::put('/users/{id}', [UsersController::class, 'update']);
    Route::delete('/users', [UsersController::class, 'destroy']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// Schema::create('users_models', function (Blueprint $table) {
//     $table->id();
//     $table->string('login');
//     $table->string('password');
//     $table->string('name');
//     $table->string('email')->nullable();
//     $table->integer('rating')->nullable();
//     $table->enum('role', ['user', 'admin'])->default('user');
//     $table->timestamps();
// });