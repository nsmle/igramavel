<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Services\InstagramService;

class AuthenticateWithJwtAuth
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
        $token = $request->bearerToken() ?? $request->input('token');
        
        if (!$token) {
            $exampleRequest = [
                'url'    => url($request->getRequestUri()),
                'method' => $request->method(),
                'header' => [
                    'Authorization' => "Bearer <token>"
                ]
            ];
            
            if ($request->isMethod('post')) {
                $exampleRequest['body'] = $request->input();
            }
            
            return response()->json([
                'status'  => 'Error',
                'code'    => 401,
                'message' => 'Token not found!, Please send your token in all request or login if you not have token.',
                'example'    => $exampleRequest
            ], 401);
        }
        
        $authorized = $this->instagram->authorize($token);
        
        if (!$authorized['valid']) {
            return response()->json([
                'status'  => 'Error',
                'code'    => 401,
                'message' => "Token Invalid! ({$authorized['message']})",
                'errors'  => [
                    'token' => $token
                ]
            ], 401);
        }
        
        return $next($request);
    }
}
