<?php
function loadEnv($filePath)
{
   if (!file_exists($filePath)) {
      throw new Exception("File .env tidak ditemukan.");
   }

   $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
   $env = [];

   foreach ($lines as $line) {
      // Mengabaikan komentar
      if (strpos(trim($line), '#') === 0) {
         continue;
      }

      list($key, $value) = explode('=', $line, 2);
      $env[trim($key)] = trim($value);
   }

   return $env;
}

// Memuat file .env
$env = loadEnv('../../.env');

// Mengakses variabel
$dbHost = $env['DB_HOST'];
$dbName = $env['DB_NAME'];
$dbUser  = $env['DB_USERNAME'];
$dbPass = $env['DB_PASSWORD'];
$dbPort = $env['DB_PORT'];