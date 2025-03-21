<!DOCTYPE html>
<html lang="en">

   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <?php session_start(); ?>
      <?php require_once("../route/route.php"); ?>
      <title><?php echo " - Beranda"; ?></title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.dataTables.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
      <link rel="stylesheet" href="style.css">
      <!--  -->
      <link href="<?= BASE_URL ?>dist/vendor/bootstrap-icons/bootstrap-icons.css" crossorigin="anonymous"
         rel="stylesheet">
      <link href="<?= BASE_URL ?>dist/vendor/boxicons/css/boxicons.min.css" crossorigin="anonymous" rel="stylesheet">
      <link href="<?= BASE_URL ?>dist/vendor/quill/quill.snow.css" crossorigin="anonymous" rel="stylesheet">
      <link href="<?= BASE_URL ?>dist/vendor/quill/quill.bubble.css" crossorigin="anonymous" rel="stylesheet">
      <link href="<?= BASE_URL ?>dist/vendor/remixicon/remixicon.css" crossorigin="anonymous" rel="stylesheet">
      <link href="<?= BASE_URL ?>dist/vendor/simple-datatables/style.css" crossorigin="anonymous" rel="stylesheet">
      <link href="<?= BASE_URL ?>dist/css/style.css" crossorigin="anonymous" rel="stylesheet">
   </head>

   <body>
