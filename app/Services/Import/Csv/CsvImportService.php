<?php

namespace App\Services;

use League\Csv\Reader;

class CsvImportService implements ImportServiceInterface
{
    protected $userCreationService;

    public function __construct(UserCreationService $userCreationService)
    {
        $this->userCreationService = $userCreationService;
    }

    public function import($file)
    {
        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $this->userCreationService->createUser([
                'name' => $record['name'],
                'email' => $record['email'],
                'role_id' => $record['role_id'] ?? 1,
            ]);
        }
    }
}
