<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\App;
use App\Services\InstagramService;

class ProfileController extends Controller
{
    /**
     * @var \App\Services\InstagramService
     */
    private InstagramService $instagram;

    /*
     * Initialization InstagramService
     */
    public function __construct()
    {
        $this->instagram = App::make(InstagramService::class);
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
        $userId = $this->instagram->user->id;

        try {
            $profile = $this->instagram->Instagram->getProfileById($userId);
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

        return $this->instagram->jsonResponse([
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
        $instagram = $this->instagram->Instagram;

        try {
            if (is_numeric($userId)) {
                $profile = $instagram->getProfileById($userId);
            } else {
                $profile = $instagram->getProfile($userId);
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

        return $this->instagram->jsonResponse([
            $profile->toArray()
        ], [
            "message" => "Profile {$profile->getFullName()}"
        ]);
    }
}
