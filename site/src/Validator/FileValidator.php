<?php

namespace App\Validator;

class FileValidator
{
    public function validateFileExists(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new \Exception('The specified file does not exist.');
        }
    }
}
