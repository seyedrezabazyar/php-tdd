<?php

namespace App\Helpers;

use App\Exeptions\configFileNotFoundException;

class Config
{
    public static function getFileContents(string $filename)
    {
        $filePath = realpath(__DIR__ . '/../configs/' . $filename . '.php');
        if (!$filePath) {
            throw new configFileNotFoundException();
        }
        $fileContents = require $filePath;
        return $fileContents;
    }

    public static function get(string $filename, string $key = null)
    {
        $fileContents = self::getFileContents($filename);
        if (is_null($key))
            return $fileContents;
        return $fileContents[$key] ?? null;
    }
}
