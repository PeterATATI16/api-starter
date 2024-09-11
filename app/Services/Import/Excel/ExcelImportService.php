<?php

namespace App\Services\Import\Excel;
use App\Services\Import\ImportServiceInterface;
use App\Services\User\UserCreationService;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

class ExcelImportService implements ImportServiceInterface
{
    protected $userCreationService;

    public function __construct(UserCreationService $userCreationService)
    {
        $this->userCreationService = $userCreationService;
    }

    public function import($file)
    {
        Excel::import(new UserImport($this->userCreationService), $file);
    }
}
