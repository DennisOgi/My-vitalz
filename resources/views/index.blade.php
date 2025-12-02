<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>MyVitalz - Login</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />
    <link rel="stylesheet" href="/assets/css/app-custom.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
  </head>

  <body class="mv-auth-bg">
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Login -->
          <div class="card mv-card-glass">
            <div class="card-body p-4 p-md-5">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="/" class="app-brand-link gap-2">
                  <span class="brand-logo-box"><img src="/logo.png" alt="MyVitalz"></span>
                </a>
              </div>
              <!-- /Logo -->
              <?php 
                $role = isset($_GET['role']) ? strtolower(trim($_GET['role'])) : '';
                $valid_roles = ['patient','doctor','hospital','sales-rep'];
                if(!in_array($role, $valid_roles)) { $role = 'patient'; }
                $role_labels = [
                  'patient' => 'Patient',
                  'doctor' => 'Doctor',
                  'hospital' => 'Hospital',
                  'sales-rep' => 'Sales Rep'
                ];
                $role_icons = [
                  'patient' => 'bx-user',
                  'doctor' => 'bx-plus-medical',
                  'hospital' => 'bx-building',
                  'sales-rep' => 'bx-briefcase'
                ];
              ?>
              <div class="d-flex justify-content-center mb-3">
                <span class="badge bg-primary rounded-pill px-3 py-2">
                  <i class="bx <?php echo $role_icons[$role]; ?> me-1"></i>
                  Signing in as <strong><?php echo $role_labels[$role]; ?></strong>
                </span>
              </div>
              <div class="row g-2 mb-4">
                <div class="col-6 col-md-3">
                  <a href="/?role=patient" class="btn btn-sm d-flex align-items-center justify-content-center w-100 <?php echo $role==='patient' ? 'btn-primary' : 'btn-outline-primary'; ?>" style="border-radius: 10px; padding: 0.5rem 0.75rem;">
                    <i class="bx bx-user me-1"></i> Patient
                  </a>
                </div>
                <div class="col-6 col-md-3">
                  <a href="/?role=doctor" class="btn btn-sm d-flex align-items-center justify-content-center w-100 <?php echo $role==='doctor' ? 'btn-primary' : 'btn-outline-primary'; ?>" style="border-radius: 10px; padding: 0.5rem 0.75rem;">
                    <i class="bx bx-plus-medical me-1"></i> Doctor
                  </a>
                </div>
                <div class="col-6 col-md-3">
                  <a href="/?role=hospital" class="btn btn-sm d-flex align-items-center justify-content-center w-100 <?php echo $role==='hospital' ? 'btn-primary' : 'btn-outline-primary'; ?>" style="border-radius: 10px; padding: 0.5rem 0.75rem;">
                    <i class="bx bx-building me-1"></i> Hospital
                  </a>
                </div>
                <div class="col-6 col-md-3">
                  <a href="/?role=sales-rep" class="btn btn-sm d-flex align-items-center justify-content-center w-100 <?php echo $role==='sales-rep' ? 'btn-primary' : 'btn-outline-primary'; ?>" style="border-radius: 10px; padding: 0.5rem 0.75rem;">
                    <i class="bx bx-briefcase me-1"></i> Sales Rep
                  </a>
                </div>
              </div>
             <?php 
               if($rtn!=""){
                    ?>
             <div class="alert alert-danger mb-4"><?php echo $rtn; ?></div>
                <?php
               }else if($reg==1){
                   ?>
                <div class="alert alert-success mb-4"><?php echo "Registration successful! Login to continue."; ?></div>
              
                <?php }
                
                ?>
              <h4 class="mv-auth-title text-center mb-2">Welcome back</h4>
              <p class="text-center mv-helper mb-4">Sign in to continue to MyVitalz (<?php echo $role_labels[$role]; ?>)</p>

              <form class="mb-3" method="POST" >
                   <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>"> 
                   <input type="hidden" name="login_as" value="<?php echo $role; ?>">
                <div class="mb-3 mv-input">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email"  name="email"  placeholder="Enter your email or username"   autofocus required />
                </div>
                <div class="mb-3 form-password-toggle mv-input">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <a href="">
                      <small>Forgot Password?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input  type="password" id="password" class="form-control" name="password" placeholder="password" required  />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
               
                <div class="mb-3">
                  <button class="btn btn-primary mv-primary-btn d-grid w-100" type="submit">Sign in</button>
                </div>
              </form>

                 <p class="text-center">
                <span>New on our platform?</span>
                <a href="/signup" class="ms-1">
                  <span>Create an account</span>
                </a>
              </p>
              <p class="text-center small mb-0">
                <span>Pharmaceutical Sales Rep?</span>
                <a href="/signup-sales-rep" class="ms-1">
                  <span>Create a Sales Rep account</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Login -->
          
        </div>
      </div>
    </div>

    <!-- / Content -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
