<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ProfileController,
    ReelsController
};

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

Route::middleware('guest')->group(function () {
    // Get all api endpoints.
    Route::get('/', function (Request $request) {
        return response()->json([
            'status'   => 'Ok',
            'code'     => 200,
            'message'  => 'Please see all endpoints bellow.',
            'endpoint' => get_all_api_endpoint()
        ], 200);
    });

    // Login with instagram credentials.
    Route::post('/auth/login', [AuthController::class, 'login'])->name('api.login');
    // Login with instagram session id.
    Route::post('/auth/login/alternative', [AuthController::class, 'loginAlternative'])->name('api.login.alternative');
});

Route::middleware('auth.jwt')->group(function () {
    // Get profile of user login
    Route::get('/profile', [ProfileController::class, 'getProfileSelf'])->name('api.profile');
    // Get profile by user id
    Route::get('/profile/{userId}', [ProfileController::class, 'getProfileById'])->name('api.profile.by_user_id');
    // Get Reels of user
    Route::get('/reels/{userId}', ReelsController::class)->name('api.reels');
});

// Handle route/method not found.
Route::fallback(function (Request $request) {
    return response()->json([
        'status'  => 'Error',
        'code'    => 404,
        'message' => 'Endpoint not Found.',
        'data'    => [
            'note' => 'Please see /api for list all api endpoints.',
            'example' => [
                'url'    => url('/api'),
                'method' => 'GET'
            ]
        ]
    ], 404);
});
