<?php
$error_title = '';
$error_message = '';
if (isset($_GET['HttpStatus'])) {
   if ($_GET['HttpStatus'] == 200) {
      echo "<script lang='javascript'>
      window.setTimeout(() => {
        document.location.href='../ui/header.php?page=beranda&status=berhasil'
      }, 1000);
    </script>";
   die;
   }
}
?>
