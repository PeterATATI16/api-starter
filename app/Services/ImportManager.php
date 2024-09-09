<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImportManager
{
    protected $services = [
        'json' => JsonImportService::class,
        'csv' => CsvImportService::class,
        'xlsx' => ExcelImportService::class,
        'txt' => TextImportService::class,
    ];

    public function handle(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();

        if (!isset($this->services[$extension])) {
            throw new \Exception("Unsupported file type: $extension");
        }

        $service = app($this->services[$extension]);
        $service->import($file);
    }
}
