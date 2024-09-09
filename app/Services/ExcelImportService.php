<?php

namespace App\Services;

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
