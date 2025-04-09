<?php
$envPath = __DIR__ . '/../.env'; // modifica se il path è diverso

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Elimina eventuali doppi apici
        $value = trim($value, '"');

        $_ENV[$name] = $value;
        putenv("$name=$value"); // utile anche per getenv()
    }
}