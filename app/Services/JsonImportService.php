<?php

namespace App\Services;

class JsonImportService implements ImportServiceInterface
{
    protected $userCreationService;

    public function __construct(UserCreationService $userCreationService)
    {
        $this->userCreationService = $userCreationService;
    }

    public function import($file)
    {
        $data = json_decode(file_get_contents($file), true);

        foreach ($data as $userData) {
            $this->userCreationService->createUser([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role_id' => $userData['role_id'] ?? 1,
            ]);
        }
    }
}
