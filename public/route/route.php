<?php
require_once "../../../app/init.php";
if (isset($_SESSION['status'])) {
} else {
   echo "<script lang='javascript'>
    window.setTimeout(() => {
        alert('Maaf anda gagal masuk ke halaman utama ...'),
        document.location.href='../index.php'
    }, 3000);
    </script>";
   die;
}
# Files Controllers and Files Models and Core
use core\Database;
use core\Database2;
use models\Example_model;
# Files include
$dbInput = Database::getInstance();
$dbInput2 = Database2::getInstance();
$ExampleModel = Example_model::getInstance();
# Files Controllers
$Example = new controllers\Example();
# Page Headers
if (!isset($_GET['page'])) {
} else {
   switch ($_GET['page']) {
      case 'value':
         # code ...
         break;

      default:
         # code...
         break;
   }
}
#
# Action Headers
if (!isset($_GET['aksi'])) {
} else {
   switch ($_GET['aksi']) {
      case 'value':
         # code ...
         break;

      default:
         # code...
         break;
   }
}
