

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
  <?php if(!isset($title)){ ?>
    Panabo HRMIS
  <?php } else { ?>
    <?php echo($title); ?>
  <?php } ?>
  </title>
  <link rel="icon" type="image/png" sizes="16x16" href="resources/hr_logo.png">
  <link rel="stylesheet" href="AdminLTE_new/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="AdminLTE_new/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="AdminLTE_new/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <style>
    .menu-icons i{
      font-size: 27px;
    }

    html{
      font-size:80%;
    }

    .master-links .active{
    /* background-color: #007bff; */
    color: #007BFF;
}

    .master-links li a{
      color:#c2c7d0;
    }
  </style>
</head>


<?php
$currentUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path = parse_url($currentUrl, PHP_URL_PATH);
$lastWord = basename($path);

// dump(get_defined_vars());

// dump($_SESSION);
if(isset($_SESSION["hris"]))
$uniqueParentRoles = array_unique(array_column($_SESSION["hris"]["roles"], 'parent'));
// dump($uniqueParentRoles);

?>


<body class="hold-transition sidebar-mini
<?php if(!isset($navbar)): ?>
  layout-fixed
<?php else: ?>
  sidebar-collapse
<?php endif; ?>



">
<!-- Site wrapper -->
<div class="wrapper">

<!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="AdminLTE_new/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div> -->

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-light" >
    <!-- Left navbar links -->
    <ul class="navbar-nav nav justify-content-center">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <div class="collapse navbar-collapse justify-content-center menu-icons">
    <ul class="nav justify-content-center master-links">
      <li class="nav-item" style="
      <?php  
          if(in_array("PDS",$uniqueParentRoles)):
          
          else:
            echo("display:none;");
          endif;
          ?>
      ">
        <a href="pds" title="PERSONAL DATA SHEET" class="nav-link <?php echo ($app == 'pds') ? 'active' : ''; ?>"><i class="fas fa-user"></i></a>
      </li>
      <li class="nav-item" style="
      <?php  
          if(in_array("APPOINTMENT",$uniqueParentRoles)):
          
          else:
            echo("display:none;");
          endif;
          ?>
      ">
        <a href="appointment" title="EMPLOYEES" class="nav-link <?php echo ($app == 'plantilla') ? 'active' : ''; ?>"><i class="fas fa-users"></i></a>
      </li>
      <li class="nav-item" style="
      <?php  
          if(in_array("LEAVE",$uniqueParentRoles)):
          
          else:
            echo("display:none;");
          endif;
          ?>
      ">
        <a href="apply_leave" title="LEAVE MANAGEMENT" class="nav-link <?php echo ($app == 'leave') ? 'active' : ''; ?>"><i class="fas fa-file-contract"></i></a>
      </li>
      <li class="nav-item" style="
      <?php  
          if(in_array("SPMS",$uniqueParentRoles)):
          else:
            echo("display:none;");
          endif;
          ?>
      ">
        <a href="support_functions" title="SPMS MANAGEMENT" class="nav-link <?php echo ($app == 'spms') ? 'active' : ''; ?>"><i class="fas fa-chart-line"></i></a>
      </li>
      <li class="nav-item" style="
      <?php  
          if(in_array("ATTENDANCE",$uniqueParentRoles)):
          
          else:
            echo("display:none;");
          endif;
          ?>
      ">
        <a href="attendance" title="ATTENDANCE" class="nav-link <?php echo ($app == 'attendance') ? 'active' : ''; ?>"><i class="fas fa-fingerprint"></i></a>
      </li>
      <li class="nav-item" style="
      <?php  
          if(in_array("PAYROLL",$uniqueParentRoles)):
          
          else:
            echo("display:none;");
          endif;
          ?>
      "> 
        <a href="payroll_permanent" title="PAYROLL" class="nav-link <?php echo ($app == 'payroll') ? 'active' : ''; ?>"><i class="fas fa-money-bill"></i></a>
      </li>
      <li class="nav-item" style="
      <?php  
          if(in_array("RECORDS",$uniqueParentRoles)):
          
          else:
            echo("display:none;");
          endif;
          ?>
      ">
        <a href="records" title="201 FILE" class="nav-link <?php echo ($app == 'records') ? 'active' : ''; ?>"><i class="fas fa-folder-open"></i></a>
      </li>
      <li class="nav-item" style="
      <?php  
          if(in_array("SETUP",$uniqueParentRoles)):
          
          else:
            echo("display:none;");
          endif;
          ?>
      ">
        <a href="users" title="SETTINGS" class="nav-link <?php echo ($app == 'setup') ? 'active' : ''; ?>"><i class="fas fa-cogs"></i></a>
      </li>
    </ul>
</div>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="logout" role="button">
        <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li> -->
    </ul>
  </nav>
<script src="AdminLTE_new/plugins/jquery/jquery.min.js"></script>
<script src="AdminLTE_new/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="AdminLTE_new/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="AdminLTE_new/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="AdminLTE_new/dist/js/adminlte.min.js"></script>

