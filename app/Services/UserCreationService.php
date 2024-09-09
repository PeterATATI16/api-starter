<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\GenericPasswordMail;

class UserCreationService
{
    protected $mailService;
    protected $passwordGeneratorService;

    public function __construct(GenericMailService $mailService, PasswordGeneratorService $passwordGeneratorService)
    {
        $this->mailService = $mailService;
        $this->passwordGeneratorService = $passwordGeneratorService;
    }

    public function createUser(array $userData)
    {
        $generatedPassword = $this->passwordGeneratorService->generateCustomPassword();

        $user = User::create([
            'name' => trim($userData['name']),
            'email' => trim($userData['email']),
            'password' => Hash::make($generatedPassword),
            'role_id' => $userData['role_id'] ?? 1,
        ]);

        $this->mailService->sendEmail(
            $user->email,
            new GenericPasswordMail($user, $generatedPassword)
        );
    }
}
