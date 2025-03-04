<?php
require_once "../../app/init.php";
if (isset($_SESSION['status'])) {
} else {
   echo "<script lang='javascript'>
    window.setTimeout(() => {
        alert('Maaf anda gagal masuk ke halaman utama ...'),
        document.location.href='../index.php'
    }, 3000);
    </script>";
   die;
   exit(0);
}
# Files Controllers and Files Models
$cmb = new core\Database();
# Files Models
$Example = new model\Example_model();
# Files Controllers
$exampling = new controllers\Example();
#
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