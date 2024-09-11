<?php

namespace App\Imports;

use App\Services\User\UserCreationService;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel
{
    protected $userCreationService;

    public function __construct(UserCreationService $userCreationService)
    {
        $this->userCreationService = $userCreationService;
    }

    public function model(array $row)
    {
        $this->userCreationService->createUser([
            'name' => $row[0],
            'email' => $row[1],
            'role_id' => $row[2] ?? 1,
        ]);
    }
}
