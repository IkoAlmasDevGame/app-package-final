<?php
define("DB_HOST", "localhost");
define("DB_PORT", "3306");
define("DB_NAME", "");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

# time default indonesia
date_default_timezone_set("Asia/Jakarta");
function salam()
{
   $b = time();
   $hour = date("G", $b);

   if ($hour >= 0 && $hour <= 11) {
      echo "Selamat Pagi";
   } elseif ($hour >= 11 && $hour <= 15) {
      echo "Selamat Siang";
   } elseif ($hour >= 15 && $hour <= 17) {
      echo "Selamat Sore";
   } elseif ($hour >= 18 && $hour < 24) {
      echo "Selamat Malam";
   }
}

# Capthca
$min_number = 1;
$max_number = 15;
$angka1 = mt_rand($min_number, $max_number);
$angka2 = mt_rand($min_number, $max_number);
