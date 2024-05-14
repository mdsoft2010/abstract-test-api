<?php
function loadEnv($filePath) {
    try {
        if (!file_exists($filePath)) {
            throw new Exception('.env non trovato', 404);
        }
    
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0 || strpos(trim($line), ';') === 0 || strpos($line, '=') === false) {
                continue;
            }
    
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
    
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    } catch (Exception $e) {
        http_response_code($e->getCode());
        echo json_encode(array('message' => $e->getMessage()));
        exit;
    }
}

$dotenv = __DIR__ . '/.env';

loadEnv($dotenv);