<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\MailService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;
    protected $mailService;

    public function __construct(AuthService $authService, MailService $mailService)
    {
        $this->authService = $authService;
        $this->mailService = $mailService;
    }

    public function register(AuthRequest $request)
    {
        $data = $request->validated(
        );
        $result = $this->authService->register($data);
        $user = $result['user'];
        $token = $result['token'];

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'nullable|string|email',
            'phone' => 'nullable|string',
            'password' => 'required|string',
        ]);

        if ($request->has('email')) {
            $loginField = 'email';
        } elseif ($request->has('phone')) {
            $loginField = 'phone';
        } else {
            return response()->json(['message' => 'Login field is required'], 400);
        }

        $credentials = $request->only($loginField, 'password');
        $token = $this->authService->login($credentials, $loginField);

        if (!$token) {
            return response()->json(['message' => 'Invalid login credentials'], 401);
        }
        
        $auth = Auth::user();
        return response()->json([
            'auth' => $auth,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
