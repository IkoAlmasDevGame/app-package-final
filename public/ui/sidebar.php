<?php
if ($_SESSION['hak_akses'] == "") {
   header("location:" . URL_BASE . "index.php");
   exit(0);
}
?>

<?php if ($_SESSION['hak_akses'] == ""): ?>
<?php else: ?>
<header id="header" class="header fixed-top d-flex align-items-center" style="position:fixed">
   <div class="d-flex align-items-center justify-content-between">
      <a href="" role="button" class="d-flex align-items-center fs-5 fst-normal fw-semibold">
         <h4 class="fw-semibold fst-italic fs-5 display-4"><?php echo "APP PACKAGE"; ?></h4>
      </a>
      <i class="bi bi-list toggle-sidebar-btn mx-5 mx-lg-5"></i>
   </div><!-- End Logo -->

   <nav class="header-nav ms-auto mx-3">
      <ul class="d-flex justify-content-center align-items-center mx-auto">
         <li class="nav-item dropdown pe-3">
            <a class="nav-link d-flex align-items-center pe-0" href="#" role="button" data-bs-toggle="dropdown"
               aria-controls="dropdown">
               <i class="fa fa-regular fa-calendar fa-2x"></i>
               <span class="d-none d-md-block dropdown-toggle ps-2"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
               <?php require_once("../ui/calendar.php") ?>
            </ul>
         </li>
         <li class="nav-item dropdown pe-4">
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" role="button"
               data-bs-toggle="dropdown" aria-controls="dropdown">
               <?php if ($_SESSION['foto'] != "") { ?>
               <img src="<?php echo BASE_URL . "assets/foto/$_SESSION[foto]" ?>" class="img-responsive rounded-2"
                  style="width: 25px; max-width: 100%;" alt="<?= $_SESSION['foto'] ?>">
               <?php } else { ?>
               <img src="<?php echo BASE_URL . "assets/default/user_logo.png"; ?>" class="img-responsive rounded-2"
                  style="width: 25px; max-width: 100%;" alt="user_logo.png">
               <?php } ?>
               <span class="d-none d-md-block dropdown-toggle ps-2"></span>
            </a>
            <!-- End Profile Iamge Icon -->
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
               <li class="dropdown-header">
                  <h4 class="fs-6 fw-normal text-start text-dark">
                     <div class="form-inline row justify-content-start align-items-start flex-wrap my-2">
                        <div class="col-sm-4 col-md-4">
                           <label for="">username</label>
                        </div>
                        <div class="col-sm-1 col-md-1">:</div>
                        <div class="col-sm-6 col-md-6">
                           <?php echo "Administrator"; ?>
                        </div>
                     </div>
                  </h4>
                  <hr class="dropdown-divider">
                  <h4 class="fs-6 fw-normal text-start text-dark">
                     <div class="form-inline row justify-content-start align-items-start flex-wrap my-2">
                        <div class="col-sm-4 col-md-4">
                           <label for="">nama profile</label>
                        </div>
                        <div class="col-sm-1 col-md-1">:</div>
                        <div class="col-sm-6 col-md-6">
                           <?php echo "Administrator"; ?>
                        </div>
                     </div>
                  </h4>
                  <hr class="dropdown-divider">
                  <h4 class="fs-6 fw-normal text-start text-dark">
                     <div class="form-inline row justify-content-start align-items-start flex-wrap my-2">
                        <div class="col-sm-4 col-md-4">
                           <label for="">Jabatan</label>
                        </div>
                        <div class="col-sm-1 col-md-1">:</div>
                        <div class="col-sm-6 col-md-6">
                           <?php echo "admin"; ?>
                        </div>
                     </div>
                  </h4>
                  <hr class="dropdown-divider my-2">
                  <div class="text-center">
                     <a href="" onclick="return confirm('Apakah anda ingin keluar dari website ini ?')"
                        aria-current="page" class="btn btn-sm btn-danger mx-1">
                        <i class="fas fa-fw fa-sign-out-alt fa-1x"></i>
                        Log Out
                     </a>
                  </div>
               </li>
            </ul><!-- End Profile Dropdown Items -->
         </li><!-- End Profile Nav -->
      </ul>
   </nav><!-- End Icons Navigation -->
</header>
<!-- ======= Header ======= -->
<!-- ======= Sidebar ======= -->
<aside id="sidebar" style="background: rgba(100, 107, 107, 1);" class="sidebar">
   <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
         <a href="" aria-current="page" class="nav-link collapsed">
            <i class="fas fa-home fa-1x"></i>
            <div class="fs-6 display-4 text-dark fw-normal">Beranda</div>
         </a>
      </li>
      <div class="my-3 border border-top"></div>
   </ul>
</aside>
<!-- ======= Sidebar ======= -->
<main id="main" class="main">
   <section class="section dashboard">
      <div class="row">

         <!-- Left side columns -->
         <div class="col-lg-8">
            <div class="row">

            </div>

         </div><!-- End Right side columns -->

      </div>
   </section>
   <?php
      header("location:" . URL_BASE . "index.php");
      exit(0);
      ?>
   <?php endif; ?>