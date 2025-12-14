<?php
function loadEnv(string $path): void
{
   if (!file_exists($path)) {
      throw new InvalidArgumentException(".env file not found at path: $path");
   }

   $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

   foreach ($lines as $line) {
      $line = trim($line);
      // Skip comments and empty lines
      if ($line === '' || strpos($line, '#') === 0) {
         continue;
      }

      // Parse KEY=VALUE pairs
      if (strpos($line, '=') !== false) {
         list($name, $value) = explode('=', $line, 2);

         $name = trim($name);
         $value = trim($value);

         // Remove optional quotes around the value
         if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
            (substr($value, 0, 1) === "'" && substr($value, -1) === "'")
         ) {
            $value = substr($value, 1, -1);
         }

         // Set environment variable
         putenv("$name=$value");
         $_ENV[$name] = $value;
         $_SERVER[$name] = $value;
      }
   }
}
