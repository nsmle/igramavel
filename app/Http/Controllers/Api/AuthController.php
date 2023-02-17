<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Facades\{App, Validator};
use App\Services\InstagramService;

class AuthController extends Controller
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
     * Login with instagram credentials.
     * 
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throw  \Exception
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'Bad Request',
                'code'    => 400,
                'message' => 'Please login with your instagram credentials.',
                'errors'  => [
                    'username' => $validator->valid()['username'] ??
                        'YOUR_INSTAGRAM_USERNAME',
                    'password' => $validator->valid()['password'] ??
                        'YOUR_INSTAGRAM_PASSWORD'
                ]
            ], 400);
        }

        // Valid Credentials
        $credentials = $validator->valid();

        try {
            $token = $this->instagram->login(
                $credentials['username'],
                $credentials['password'],
                $credentials['imap'] ?? null
            );
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'Error',
                'code'    => 401,
                'message' => $e->getMessage(),
                'errors'  => [
                    'credentials' => $request->input()
                ]
            ], 401);
        }

        return $this->instagram->jsonResponse([
            'token' => $token
        ], [
            "message" => "Logged in as {$this->instagram->user->fullName}"
        ]);
    }

    /**
     * Login with cookie instagram session id.
     * 
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throw  \Exception
     */
    public function loginAlternative(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'nullable',
            'value'   => 'required',
            'domain'  => 'nullable',
            'path'    => 'nullable',
            'expires' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'Bad Request',
                'code'    => 400,
                'message' => 'Please login with your instagram cookie sessionid.',
                'errors'  => [
                    'name'    => $validator->valid()['name']    ?? 'sessionid',
                    'value'   => $validator->valid()['value']   ?? 'YOUR_INSTAGRAM_SESSIONID',
                    'domain'  => $validator->valid()['domain']  ?? '.instagram.com',
                    'path'    => $validator->valid()['path']    ?? '/',
                    'expires' => $validator->valid()['expires'] ?? 'YOUR_INSTAGRAM_SESSIONID_EXPIRES',
                ]
            ], 400);
        }

        // Valid cookie
        $inputValid = $validator->valid();
        $cookie = [
            'Name'    => $inputValid['name'] ?? 'sessionid',
            'Value'   => $inputValid['value'],
            'Domain'  => $inputValid['domain'] ?? '.instagram.com',
            'Path'    => $inputValid['path'] ?? '/',
            'Expires' => $inputValid['expires'],
        ];

        try {
            $token = $this->instagram->loginWithCookie($cookie);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'Unauthorized',
                'code'    => 401,
                'message' => $e->getMessage(),
                'errors'  => [
                    'cookies' => [
                        $cookie
                    ]
                ]
            ], 401);
        }

        return $this->instagram->jsonResponse([
            'token' => $token
        ], [
            "message" => "Logged in as {$this->instagram->user->fullName}"
        ]);
    }
}
