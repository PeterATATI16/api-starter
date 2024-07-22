<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\MailService;
use App\Services\PasswordResetService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;
    protected $mailService;
    protected $passwordResetService;

    public function __construct(AuthService $authService, MailService $mailService, PasswordResetService $passwordResetService)
    {
        $this->authService = $authService;
        $this->mailService = $mailService;
        $this->passwordResetService = $passwordResetService;
    }

    public function register(AuthRequest $request)
    {
        $data = $request->validated();
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

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|string|email']);
        $result = $this->passwordResetService->sendResetCode($request->email);

        if (!$result) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        return response()->json(['message' => 'Reset code sent to email']);
    }

    public function resetPassword(Request $request)
    {
        $result = $this->passwordResetService->resetPassword($request->email, $request->code, $request->password);

        if (!$result) {
            return response()->json(['message' => 'Invalid reset code or email'], 400);
        }

        return response()->json(['message' => 'Password reset successfully', 'result' => $result]);
    }
}
