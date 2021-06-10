<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CommentsController;
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

// |--------------------------------------------------------------------------
// | Auth
// 1. registrate a new user (POST) /api/auth/register
// 2. log in user (POST) /api/auth/login
// 3. log out user (POST) /api/auth/logout
// 4. send a reset link (POST) /api/auth/password-reset
// 5. confirm new password (POST) /api/auth/password-reset/{confirm_token}
// |--------------------------------------------------------------------------

Route::post('/auth/register', [AuthController::class, 'register']); //auth register
Route::post('/auth/login', [AuthController::class, 'login']); //auth login
Route::post('/auth/password-reset', [AuthController::class, 'passwordReset']);
Route::post('/auth/password-reset/{token}', [AuthController::class, 'confirmToken']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// |--------------------------------------------------------------------------
// | User
// 1. get all users (GET) /api/users
// 2. get user data (GET) /api/users/{id}
// 3. create a user (POST) /api/users
// 4. upload an avatar (POST) /api/users/avatar
// 5. update user data (PATCH) /api/users/{id}
// 6. delete user (DELETE) /api/users/{id}
// |--------------------------------------------------------------------------

Route::get('/users', [UsersController::class, 'index']);
Route::get('/users/{id}', [UsersController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::post('/users', [UsersController::class, 'store']);
    Route::patch('/users/{id}', [UsersController::class, 'update']);
    Route::delete('/users', [UsersController::class, 'destroy']);
    Route::post('/users/avatar', [UsersController::class, 'setAvatar']);
});

// |--------------------------------------------------------------------------
// | Public post
// 1. get all posts (GET) /api/posts
// 2. get one post (GET) /api/posts/{id}
// 3. get all comments (GET) /api/posts/{id}/comments
// 4. create a comment (POST) /api/posts/{id}/comments
// 5. get all categories (GET) /api/posts/{id}/categories
// 6. get all likes (GET) /api/posts/{id}/like
// 7. create a post (POST) /api/posts
// 8. create a like (POST) /api/posts/{id}/like
// 9. update a post (PATCH) /api/posts/{id}
// 10. delete a post (DELETE) /api/posts/{id}
// 11. delete a like (DELETE) /api/posts/{id}/like
// |--------------------------------------------------------------------------

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::post('/posts', [PostController::class, 'store']);
    Route::patch('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::get('/posts/{id}/comments', [PostController::class, 'getComment']); //post get comments //public
    Route::post('/posts/{id}/comments', [PostController::class, 'createComment']); //post create comment

    Route::get('/posts/{id}/like', [PostController::class, 'getLikes']); //post get all likes
    Route::post('/posts/{id}/like', [PostController::class, 'like']); //post create like
    Route::delete('/posts/{id}/like', [PostController::class, 'removeLike']); //post delete like

    Route::get('/posts/{id}/categories', [PostController::class, 'getCategories']); //post get categories
});

// |--------------------------------------------------------------------------
// | Public categories
// 1. get all categories (GET) /api/categories
// 2. get category data (GET) /api/categories/{id}
// 3. get all posts under category (GET) /api/categories/{id}/posts
// 4. create a category (POST) /api/categories
// 5. update category data (PATCH) /api/categories/{id}
// 6. delete a category (DELETE) /api/categories/{id}
// |--------------------------------------------------------------------------

Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/categories/{id}', [CategoriesController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/categories', [CategoriesController::class, 'store']);
    Route::patch('/categories/{id}', [CategoriesController::class, 'update']);
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy']);
});

Route::get('/categories/{id}/posts', [CategoriesController::class, 'getPosts']); //categories get posts

// |--------------------------------------------------------------------------
// | Public comments
// 1. get comment (GET) /api/comments/{id}
// 2. get all likes (GET) /api/comments/{id}/like
// 3. create a like (POST) /api/comments/{id}/like
// 4. update comment (PATCH) /api/comments/{id}
// 5. delete a comment (DELETE) /api/comments/{id}
// 6. delete a like (DELETE) /api/comments/{id}/like
// |--------------------------------------------------------------------------

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/comments/{id}', [CommentsController::class, 'show']);
    Route::patch('/comments/{id}', [CommentsController::class, 'update']);
    Route::delete('/comments/{id}', [CommentsController::class, 'destroy']);

    Route::get('/comments/{id}/like', [CommentsController::class, 'getLikesComment']); //comments get likes
    Route::post('/comments/{id}/like', [CommentsController::class, 'likeComment']); //comments post likes
    Route::delete('/comments/{id}/like', [CommentsController::class, 'removeLikeComment']); //comments delete {id} comments likes
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
