<?php
# time default indonesia

define("DB_HOST", "");
define("DB_DATABASE", "");
define("DB_USERNAME", "");
define("DB_PASSWORD", "");

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
