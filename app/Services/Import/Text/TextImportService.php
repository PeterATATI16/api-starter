<?php

namespace App\Services\Import\Text;
use App\Services\Import\ImportServiceInterface;
use App\Services\User\UserCreationService;

class TextImportService implements ImportServiceInterface
{
    protected $userCreationService;

    public function __construct(UserCreationService $userCreationService)
    {
        $this->userCreationService = $userCreationService;
    }

    public function import($file)
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {
            $userData = explode(',', $line);

            if (count($userData) < 2) {
                continue;
            }

            $this->userCreationService->createUser([
                'name' => $userData[0],
                'email' => $userData[1],
                'role_id' => $userData[2] ?? 1,
            ]);
        }
    }
}
