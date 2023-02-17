<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{App, Validator};
use App\Services\InstagramService;
use Illuminate\Support\Collection;

class FollowController extends Controller
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
     * Follow user by userId/username
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  string|int $userId
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throw  \Exception
     */
    public function follow(Request $request, mixed $userId): JsonResponse
    {
        $user = [];
        if (empty(($request->get('userUserName')))) {
            $user = $this->ValidateUser($userId);
            if ($user instanceof JsonResponse) return $user;
        }

        $userNameMsg = !empty($user['fullName']) ? $user['fullName'] : (!empty($request->get('userFullName')) ? $request->get('userFullName') : $userId);

        try {
            $isFollow = $this->instagram->Instagram->follow($userId) == 'ok';
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => 'Error',
                'code'    => 400,
                'message' => "Failed to follow $userNameMsg!" . $exception->getMessage(),
                'errors'  => [
                    'userId' => $userId,
                    'follow' => false
                ]
            ], 400);
        }

        return $this->instagram->jsonResponse([
            'userId' => !empty($user['userName']) ? $user['userName'] : (!empty($request->get('userUserName')) ? $request->get('userUserName') : $userId),
            'isFollow' => $isFollow
        ], [
            "message" => "Successful follow $userNameMsg!"
        ]);
    }


    /**
     * Unfollow user by userId/username
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  string|int $userId
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throw  \Exception
     */
    public function unfollow(Request $request, mixed $userId): JsonResponse
    {
        $user = [];
        if (empty(($request->get('userUserName')))) {
            $user = $this->ValidateUser($userId);
            if ($user instanceof JsonResponse) return $user;
        }

        $userNameMsg = !empty($user['fullName']) ? $user['fullName'] : (!empty($request->get('userFullName')) ? $request->get('userFullName') : $userId);

        try {
            $isFollow = $this->instagram->Instagram->unfollow($userId) == 'ok';
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => 'Error',
                'code'    => 400,
                'message' => "Failed to unfollow $userNameMsg!" . $exception->getMessage(),
                'errors'  => [
                    'userId' => $userId,
                    'unfollow' => false
                ]
            ], 400);
        }

        return $this->instagram->jsonResponse([
            'userId' => !empty($user['userName']) ? $user['userName'] : (!empty($request->get('userUserName')) ? $request->get('userUserName') : $userId),
            'unfollow' => $isFollow
        ], [
            "message" => "Successful unfollow $userNameMsg!"
        ]);
    }




    /**
     * Validate username.
     * 
     * @param  string|int $userId
     *
     * @return array 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throw  \Exception
     */
    private function ValidateUser(mixed $userId): array|JsonResponse
    {
        $userId = (int) $userId;

        try {
            $user = $this->instagram->Instagram->getProfileById($userId);
            return $user->toArray();
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
    }
}
