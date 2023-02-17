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
    public function feed(Request $request, mixed $userId): JsonResponse
    {
        $cursor = $request->query('cursor');
        $fullName = $request->get('userFullName');
        $userName = $request->get('userUserName');

        if (empty($cursor)) {
            try {
                $reelsFeed = $this->igram->Instagram->getReels($userId)->toArray();
            } catch (\Exception $exception) {
                return response()->json([
                    'status'  => 'Unknown error',
                    'code'    => 500,
                    'message' => $exception->getMessage(),
                ], 500);
            }
        } else {
            try {
                $reelsFeed = $this->igram->Instagram->getReels($userId, $cursor)->toArray();
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

        $userNameMsg = !empty($fullName) ? $fullName : (!empty($userName) ? $userName : $userId);

        return $this->igram->jsonResponse($reelsFeed, [
            "message" => "Reels Feed " . $userNameMsg
        ]);
    }
}
