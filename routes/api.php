<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ProfileController,
    ReelsController,
    FollowController
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

function CommingSoon()
{
    return response()->json([
        'status'  => 'OK',
        'code'    => 200,
        'message' => 'Comming Soon!',
        'data'    => [
            'note' => 'Please see available api endpoints.',
            'example' => [
                'url'    => url(config('app.url')),
                'method' => 'GET'
            ]
        ]
    ], 200);
}

Route::middleware('guest')->group(function () {
    // Get all api endpoints.
    Route::get('/', function (Request $request) {
        return redirect()->route('homepage');
    });

    Route::prefix('auth')->group(function () {
        // Login with instagram credentials.
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        // Login with instagram session id.
        Route::post('/login/alternative', [AuthController::class, 'loginAlternative'])->name('login.alternative');
    })->name('auth.');
});

Route::middleware(['auth.jwt', 'validate.username'])->group(function () {

    Route::prefix('user')->group(function () {
        Route::name('profile.')->group(function () {
            // Get profile of user login
            Route::get('/', [ProfileController::class, 'getProfileSelf'])->name('self');
            // Get profile by user id
            Route::get('/{userId}', [ProfileController::class, 'getProfileById'])->name('byUserId');
        });

        Route::name('follow.')->group(function () {
            // Follow a user 
            Route::post("/{userId}/follow", [FollowController::class, 'follow'])->name('follow');
            Route::post("/{userId}/unfollow", [FollowController::class, 'unfollow'])->name('unfollow');
        });
    })->name('user.');

    Route::prefix('reels')->group(function () {
        // Get Reels of user
        Route::get('/{userId}', [ReelsController::class, 'feed'])->name('reels');
    })->name('reels.');


    /**
     * COMING SOON!
     */
    Route::prefix('post')->group(function () {
        Route::get("/{userId}", function () {
            return CommingSoon();
        })->name('posts');
        Route::get("/{userId}/tags", function () {
            return CommingSoon();
        })->name('tags');
        Route::get("/{shortCode}/detail", function () {
            return CommingSoon();
        })->name('details');
        Route::get("/{shortCode}/comment", function () {
            return CommingSoon();
        })->name('comments');
        Route::post("/{shortCode}/comment", function () {
            return CommingSoon();
        })->name('comment.post');
        Route::post("/{shortCode}/like", function () {
            return CommingSoon();
        })->name('like');
        Route::post("/{shortCode}/unlike", function () {
            return CommingSoon();
        })->name('unlike');
    })->name('post.');
});

// Handle route/method not found.
Route::fallback(function (Request $request) {
    return response()->json([
        'status'  => 'Error',
        'code'    => 404,
        'message' => 'Endpoint not Found.',
        'data'    => [
            'note' => 'Please see lists api endpoints.',
            'example' => [
                'url'    => url(config('app.url')),
                'method' => 'GET'
            ]
        ]
    ], 404);
});
