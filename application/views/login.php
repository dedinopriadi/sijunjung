<!DOCTYPE html>
<html lang="en">
<head>
  <title>KPPN Sijunjung | Authentication</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->  
  <link href="<?php echo base_url(); ?>assets/softland/assets/img/kppn_sijunjung.png" rel="icon">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/animate/animate.css">
<!--===============================================================================================-->  
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/vendor/select2/select2.min.css">
<!--===============================================================================================-->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/css/util.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/login/css/main.css">
<!--===============================================================================================-->

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script>

<script type="text/javascript">
    var baseURL = "<?php echo base_url(); ?>";
</script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/login.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/loading.css">

</head>
<body ng-app="App">
  <div ng-controller="loginCtrl">
  <div class="limiter">
  <div class="loading" ng-show="!loaded">Loading&#8230;</div>
    <div ng-show="dataLoadedSuccessfully" ng-cloak class="container-login100" style="background-image: url('<?php echo base_url(); ?>assets/softland/assets/img/hero-bg.jpg');">
      <div class="wrap-login100 p-t-10 p-b-0">
        <form class="login100-form validate-form">
          <div class="login100-form-avatar">
            <img src="<?php echo base_url(); ?>assets/softland/assets/img/kppn_sijunjung.png" alt="AVATAR">
          </div>

          <span class="login100-form-title p-t-10 p-b-35">
            KPPN Sijunjung
          </span>

          <div class="wrap-input100 validate-input m-b-10" data-validate = "Email is required" style="margin-left: 15%;">
            <input ng-model="login.email" class="input100" type="email" name="email" placeholder="Email">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-user"></i>
            </span>
          </div>

          <div class="text-center wrap-input100 validate-input m-b-10" data-validate = "Password is required" style="margin-left: 15%;">
            <input ng-model="login.password" class="input100" type="password" name="password" placeholder="Password">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-lock"></i>
            </span>
          </div>

          <div class="container-login100-form-btn p-t-10" style="margin-left: 15%;">
            <button class="login100-form-btn" ng-click="checkLogin()">
              Login
            </button>
          </div>

          <div class="text-center w-full p-t-25 p-b-20">
            <a href="#" class="txt1">
              Powered by GIT Software Developer
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  

  
<!--===============================================================================================-->  
  <script src="<?php echo base_url(); ?>assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
  <script src="<?php echo base_url(); ?>assets/login/vendor/bootstrap/js/popper.js"></script>
  <script src="<?php echo base_url(); ?>assets/login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
  <script src="<?php echo base_url(); ?>assets/login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
  <script src="<?php echo base_url(); ?>assets/login/js/main.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

</body>
</html>