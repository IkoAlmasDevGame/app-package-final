<?php
$error_title = '';
$error_message = '';
if (isset($_GET['HttpStatus'])) {
   if ($_GET['HttpStatus'] == 404) {
      $error_title = '404 Page Not Found';
      $error_message = 'The document/file requested was not found on this server';
   }
   if ($_GET['HttpStatus'] == 400) {
      $error_title = "400 Bad HTTP request ";
      $error_message = 'Bad HTTP request.';
   }
   if ($_GET['HttpStatus'] == 401) {
      $error_title = "401 Unauthorized - Iinvalid password ";
      $error_message = 'Unauthorized - Iinvalid password.';
   }
   if ($_GET['HttpStatus'] == 403) {
      $error_title = "403 Forbidden";
      $error_message = 'Forbidden.';
   }
   if ($_GET['HttpStatus'] == 500) {
      $error_title = "500 Forbidden";
      $error_message = 'Internal Server Error.';
   }
   if ($_GET['HttpStatus'] == 200) {
      $error_title = '200 Document has been processed and sent to you';
      $error_message = 'Document has been processed and sent to you.';
      echo "<script lang='javascript'>
    window.setTimeout(() => {
        alert('$error_message'),
        document.location.href='../ui/header.php?page=beranda'
    }, 3000);
    </script>";
      die;
   }
}
?>

<!doctype html>
<html>

   <head>
      <meta charset="utf-8">
      <title>Error</title>
      <!-- Style CSS start -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
      <!-- Style CSS Finish -->
      <!-- Javascript Start -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
      </script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js">
      </script>
      <script crossorigin="anonymous" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <!-- Javascript Finish -->
   </head>

   <body>
      <div class="d-flex justify-content-center align-items-center flex-wrap 
            mt-5 ms-0 me-0 mb-0 pt-5 ps-0 pe-0 pb-0">
         <div class="card col-sm-4 mb-3">
            <div class="card-heder py-3">
               <h2 class="card-title fw-semibold fs-2 display-2 text-center">
                  <?php echo $error_title ?>
               </h2>
            </div>
            <div class="card-body my-2">
               <h2 class="card-title fs-2 display-2 text-center">
                  <?php echo $error_message; ?>
               </h2>
               <div class="card-footer my-2">
                  <div class="text-center">
                     <a href="../index.php" aria-current="page" class="btn btn-primary btn-outline-dark">HOME</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </body>

</html>