<?php
namespace Core;

class EnvLoader {
    public static function load($file) {
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
            [$key, $value] = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value, " \"'"));
        }
    }
} 
