<?php
namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserImport;

class CsvExcelImportService implements ImportServiceInterface
{
    public function import($file)
    {
        Excel::import(new UserImport, $file);
    }
}
