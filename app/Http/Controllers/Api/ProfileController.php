<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\App;
use App\Services\InstagramService;
use Instagram\Model\Profile;
use Instagram\Exception\InstagramFetchException;
use Instagram\Utils\CacheResponse;
use GuzzleHttp\Exception\ClientException;

class ProfileController extends Controller
{
    /**
     * @var \App\Services\InstagramService
     */
    private InstagramService $igram;

    /*
     * Initialization InstagramService
     */
    public function __construct()
    {
        $this->igram = App::make(InstagramService::class);
    }

    /**
     * Get profile of user login.
     * 
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throw  \InstagramFetchException
     * @throw  \Exception
     */
    public function getProfileSelf(Request $request): JsonResponse
    {
        $userId = $this->igram->user->id;

        try {
            $profile = $this->igram->Instagram->getProfileById($userId);
        } catch (\Exception $exception) {
            preg_match('/404 Not Found/', $exception, $userNotFound);

            if (isset($userNotFound)) {
                return response()->json([
                    'status'  => 'Not Found',
                    'code'    => 404,
                    'message' => "User {$userId} not found!",
                    'errors'  => [
                        'userId' => $userId
                    ]
                ], 404);
            }

            return response()->json([
                'status'  => 'Bad Request',
                'code'    => 400,
                'message' => $exception->getMessage(),
                'errors'  => [
                    'userId' => $userId
                ]
            ], 400);
        }

        return $this->igram->jsonResponse([
            $profile->toArray()
        ], [
            "message" => "Logged in as {$profile->getFullName()}"
        ]);
    }

    /**
     * Get profile by user id.
     * 
     * @param  Request     $request
     * @param  string|int  $userId
     *
     * @return \Instagram\Model\Profile|string
     * @throw  \Exception
     */
    public function getProfileById(Request $request, mixed $userId): JsonResponse
    {
        try {
            if (is_numeric($userId)) {
                $profile = $this->igram->Instagram->getProfileById($userId);
            } else {
                $profile = $this->igram->Instagram->getProfile($userId);
            }
        } catch (\Exception $exception) {
            preg_match('/404 Not Found/', $exception, $userNotFound);

            if (isset($userNotFound)) {
                return response()->json([
                    'status'  => 'Not Found',
                    'code'    => 404,
                    'message' => "User {$userId} not found!",
                    'errors'  => [
                        'userId' => $userId
                    ]
                ], 404);
            }

            return response()->json([
                'status'  => 'Bad Request',
                'code'    => 400,
                'message' => $exception->getMessage(),
                'errors'  => [
                    'userId' => $userId
                ]
            ], 400);
        }

        return $this->igram->jsonResponse([
            $profile->toArray()
        ], [
            "message" => "Profile {$profile->getFullName()}"
        ]);
    }
}
