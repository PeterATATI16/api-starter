<?php

// app/Utils/FileUploader.php

namespace App\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;


class FileUploader
{
    public static function upload(UploadedFile $file, $destinationPath)
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($destinationPath), $fileName);

        return $destinationPath . '/' . $fileName;
    }

    public static function uploadMultiple(UploadedFile $file, $destinationPath)
    {
        $uniqueFileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($destinationPath), $uniqueFileName);

        return $destinationPath . '/' . $uniqueFileName;
    }


    public static function fileStore($request, $fieldName, $destinationPath)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            return FileUploader::upload($file, $destinationPath);
        }

        return null;
    }

    public static function _fileStore($request, $fieldName, $destinationPath)
    {
        if ($request->hasFile($fieldName)) {
            $files = $request->file($fieldName);
            $uploadedImagePaths = [];

            foreach ($files as $file) {
                $uploadedImagePaths[] = FileUploader::uploadMultiple($file, $destinationPath);
            }

            return $uploadedImagePaths;
        }

        return null;
    }


    public static function update($existingImagePath, UploadedFile $newFile, $destinationPath)
    {
        if (File::exists(public_path($existingImagePath))) {
            File::delete(public_path($existingImagePath));
        }

        $fileName = time() . '.' . $newFile->getClientOriginalExtension();
        $newFile->move(public_path($destinationPath), $fileName);

        return $destinationPath . '/' . $fileName;
    }

    public static function fileUpdate($request, $existingImagePath, $fieldName, $destinationPath)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            return FileUploader::update($existingImagePath, $file, $destinationPath);
        }

        return $existingImagePath;
    }

    public static function updateMultiple($existingImagePaths, $newFiles, $destinationPath)
    {
        foreach ($existingImagePaths as $existingImagePath) {
            if (File::exists(public_path($existingImagePath))) {
                File::delete(public_path($existingImagePath));
            }
        }

        $uploadedImagePaths = [];
        foreach ($newFiles as $newFile) {
            $uniqueFileName = uniqid() . '_' . time() . '.' . $newFile->getClientOriginalExtension();
            $newFile->move(public_path($destinationPath), $uniqueFileName);
            $uploadedImagePaths[] = $destinationPath . '/' . $uniqueFileName;
        }

        return $uploadedImagePaths;
    }

    public static function fileUpdateMultiple($request, $existingImagePaths, $fieldName, $destinationPath)
    {
        if ($request->hasFile($fieldName)) {
            $newFiles = $request->file($fieldName);

            return FileUploader::updateMultiple($existingImagePaths, $newFiles, $destinationPath);
        }

        return $existingImagePaths;
    }

}
