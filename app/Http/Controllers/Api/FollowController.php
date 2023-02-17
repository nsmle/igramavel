<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\App;
use App\Services\InstagramService;

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
     */
    public function follow(Request $request, mixed $userId): JsonResponse
    {
        $user = $request->get('user');
        return $this->process($userId, 'follow', $user);
    }

    /**
     * Unfollow user by userId/username
     * 
     * @param  \Illuminate\Http\Request $request
     * @param  string|int $userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unfollow(Request $request, mixed $userId): JsonResponse
    {
        $user = $request->get('user');
        return $this->process($userId, 'unfollow', $user);
    }

    /**
     * Execute to follow/unfollow user.
     * 
     * @param  string|int $userId
     * @param  string $action
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throw  \Exception
     */
    private function process(mixed $userId, string $action, array $user = null): JsonResponse
    {
        $userId = (int) $userId;
        $user = collect($user);
        $instagram = $this->instagram->Instagram;

        if (!is_numeric($userId) && $user->isEmpty()) {
            return response()->json([
                'status'  => 'Internal Server Error',
                'code'    => 500,
                'message' => "Unknown error, Plase try again later! or report this problem on https://github.com/nsmle/igramapi/issues",
                'errors'  => []
            ], 500);
        }

        $userNameMsg = $user->contains('fullName') ? $user->get('fullName') : ($user->contains('userName') ? $user->get('userName') : $userId);

        try {
            switch ($action) {
                case 'follow':
                    $data = ($instagram->follow($user->get('id') ?? $userId) == "ok");
                    break;

                case 'unfollow':
                    $data = ($instagram->unfollow($user->get('id') ?? $userId) == "ok");
                    break;

                default:
                    return response()->json([
                        'status'  => 'Internal Server Error',
                        'code'    => 500,
                        'message' => "Unknown error, Plase try again later! or report this problem on https://github.com/nsmle/igramapi/issues",
                        'errors'  => []
                    ], 500);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'status'  => 'Error',
                'code'    => 400,
                'message' => "Failed to $action $userNameMsg!" . $exception->getMessage(),
                'errors'  => [
                    'userId' => $userId
                ]
            ], 400);
        }

        return $this->instagram->jsonResponse([
            'userId' => !empty($user->get('userName')) ? "@" . $user->get('userName') : $userId,
            $action  => $data
        ], [
            "message" => (in_array($action, ['follow', 'unfollow']) ? "Successful " : " ") . "$action $userNameMsg!"
        ]);
    }
}
