<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
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
    
    /*
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
                'status'  => 'Error',
                'code'    => 404,
                'message' => 'Please login with your instagram credentials.',
                'errors'  => [
                    'username' => $validator->valid()['username'] ??
                                  'YOUR_INSTAGRAM_USERNAME',
                    'password' => $validator->valid()['password'] ??
                                  'YOUR_INSTAGRAM_PASSWORD'
                ]
            ], 404);
        }
        
        // Valid Credentials
        $credentials = $validator->valid();
        
        try {
            $token = $this->instagram->login(
                $credentials['username'],
                $credentials['password'],
                $credentials['imap'] ?? null);
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
        
        return response()->json([
            'status'  => 'Ok',
            'code'    => 200,
            'message' => "Logged in as ({$credentials['username']})",
            'data'  => [
                'token' => $token
            ]
        ], 200);
    }
}
