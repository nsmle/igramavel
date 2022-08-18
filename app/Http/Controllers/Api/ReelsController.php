<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Http\JsonResponse;
use App\Services\InstagramService;
use Illuminate\Http\Request;

class ReelsController extends Controller
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
     * Get reels of user.
     * 
     * @param  Request     $request
     * @param  string|int  $userId
     *
     * @return \Instagram\Model\Profile|string
     * @throw  \Exception
     */
    public function __invoke(Request $request, mixed $userId): JsonResponse
    {
        $reelsUserId = (int) $userId;
        $reelsUserName = '';
        $cursor = $request->query('cursor');

        if (!is_numeric($userId)) {
            try {
                $profile = $this->igram->Instagram->getProfile($userId);
                $reelsUserName = $profile->getFullName();
                $reelsUserId = $profile->getId();
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

        if (empty($cursor)) {
            try {
                $reelsFeed = $this->igram->Instagram->getReels($reelsUserId)->toArray();
            } catch (\Exception $exception) {
                return response()->json([
                    'status'  => 'Unknown error',
                    'code'    => 500,
                    'message' => $exception->getMessage(),
                ], 500);
            }
        } else {
            try {
                $reelsFeed = $this->igram->Instagram->getReels($reelsUserId, $cursor)->toArray();
            } catch (\Exception $exception) {
                return response()->json([
                    'status'  => 'Cursor Invalid!',
                    'code'    => 400,
                    'message' => $exception->getMessage(),
                    'errors'  => [
                        'cursor' => $cursor
                    ]
                ], 400);
            }
        }

        return $this->igram->jsonResponse($reelsFeed, [
            "message" => "Reels Feed " . (!empty($reelsUserName) ? $reelsUserName : $reelsUserId)
        ]);
    }
}
