<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Services\InstagramService;
use PhpParser\Node\Stmt\TryCatch;

class ValidateUsername
{
    /**
     * @var \App\Services\InstagramService
     */
    private InstagramService $instagram;

    /**
     * Initialization InstagramService
     */
    public function __construct()
    {
        $this->instagram = App::make(InstagramService::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $params = $request->route()->parameters();

        if (array_key_exists('userId', $params)) {
            if (!is_numeric($params['userId'])) {
                try {
                    $username = $request->userId;
                    $user = $this->instagram->Instagram->getProfile($username);

                    $request->route()->setParameter('userId', $user->getId());

                    $request->attributes->add(['userId'             => $user->getId()]);
                    $request->attributes->add(['userUserName'       => $user->getUserName()]);
                    $request->attributes->add(['userFullName'       => $user->getFullName()]);
                    $request->attributes->add(['userBiography'      => $user->getBiography()]);
                    $request->attributes->add(['userProfilePicture' => $user->getProfilePicture()]);
                    $request->attributes->add(['userPrivate'        => $user->isPrivate()]);
                    $request->attributes->add(['userVerified'       => $user->isVerified()]);
                } catch (\Exception $exception) {
                    preg_match('/404 Not Found/', $exception, $userNotFound);

                    if (isset($userNotFound)) {
                        return response()->json([
                            'status'  => 'User Not Found!',
                            'code'    => 404,
                            'message' => "User {$username} not found!",
                            'errors'  => [
                                'userId' => $username
                            ]
                        ], 404);
                    }

                    return response()->json([
                        'status'  => 'Bad Request',
                        'code'    => 400,
                        'message' => $exception->getMessage(),
                        'errors'  => [
                            'userId' => $username
                        ]
                    ], 400);
                }
            } else {
                $request->attributes->add(['userFullName' => $params['userId']]);
            }
        }

        return $next($request);
    }
}
