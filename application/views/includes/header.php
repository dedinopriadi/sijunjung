<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
      ============================================ -->
      <link href="<?php echo base_url(); ?>assets/softland/assets/img/kppn_sijunjung.png" rel="icon">
    <!-- Google Fonts
      ============================================ -->
      <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <!-- Bootstrap CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css">
    <!-- Bootstrap CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/font-awesome.min.css">
    <!-- owl.carousel CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/owl.carousel.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/owl.theme.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/owl.transitions.css">
    <!-- animate CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/animate.css">
    <!-- normalize CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/normalize.css">
    <!-- meanmenu icon CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/meanmenu.min.css">
    <!-- main CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/main.css">
    <!-- educate icon CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/educate-custon-icon.css">
    <!-- morrisjs CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/morrisjs/morris.css">
    <!-- mCustomScrollbar CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- metisMenu CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/metisMenu/metisMenu.min.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/metisMenu/metisMenu-vertical.css">
    <!-- calendar CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/calendar/fullcalendar.min.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/calendar/fullcalendar.print.min.css">
    <!-- style CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/style.css">
    <!-- responsive CSS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/responsive.css">
    <!-- modernizr JS
      ============================================ -->
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/data-table/bootstrap-table.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/data-table/bootstrap-editable.css">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/editor/bootstrap-editable.css">

      <!-- notifications CSS
        ============================================ -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/notifications/Lobibox.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/notifications/notifications.css">

    <!-- summernote CSS
    ============================================ -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/summernote/summernote.css">

    <script src="<?php echo base_url(); ?>assets/admin/js/vendor/modernizr-2.8.3.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/loading.css">

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.9/angular.min.js"></script>

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" /> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css" integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw==" crossorigin="anonymous" />

    <script type="text/javascript">
        var baseURL = "<?php echo base_url(); ?>";
    </script>

    <style type="text/css">
      .select23 {
        width: 100%;
        padding: 16px 20px;
        border: none;
        border-radius: 4px;
        background-color: #f1f1f1;
      }
    </style>


</head>

<body ng-app="App">
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="">
            <div class="sidebar-header">
                <a href="index.html"><img class="main-logo" src="<?php echo base_url(); ?>assets/softland/assets/img/kppn_sijunjung.png" alt="" style="height: 70px; margin-top: 12px; margin-bottom: 5px;" /></a>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1">
                      <li>
                        <a title="Dashboard" href="<?php echo base_url(); ?>Dashboard" aria-expanded="false"><span class="educate-icon educate-data-table icon-wrap sub-icon-mg" aria-hidden="true"></span> <span class="mini-click-non">Dashboard</span></a>
                      </li>
            <?php
        if($role == ROLE_MANAGER || $role == ROLE_EMPLOYEE || $role == ROLE_ADMIN)
        {
            ?>
                      <li>
                        <a title="Satuan Kerja" href="<?php echo base_url(); ?>Satker" aria-expanded="false"><span class="educate-icon educate-department icon-wrap" aria-hidden="true"></span> <span class="mini-click-non">Satuan Kerja</span></a>
                      </li>
            <?php
        }
            ?>

            <?php
        if($role == ROLE_EMPLOYEE || $role == ROLE_ADMIN)
        {
            ?>
                      <li class="">
                          <a class="has-arrow" href="index.html">
                           <span class="educate-icon educate-course icon-wrap sub-icon-mg"></span>
                           <span class="mini-click-non">Proses Penyelesaian</span>
                          </a>
                           <ul class="submenu-angle" aria-expanded="true">
                              <li><a title="SKPP" href="<?php echo base_url(); ?>Skpp"><span class="mini-sub-pro">SKPP</span></a></li>
                              <li><a title="SPM Koreksi" href="<?php echo base_url(); ?>Spmk"><span class="mini-sub-pro">SPM Koreksi</span></a></li>
                              <li><a title="Konfirmasi Penerimaan" href="<?php echo base_url(); ?>Konfirmasi"><span class="mini-sub-pro">Konfirmasi Penerimaan</span></a></li>
                          </ul>
                      </li>
            <?php
        }
            ?>

            <?php
        if($role == ROLE_MANAGER || $role == ROLE_ADMIN)
        {
            ?>
                      <li class="">
                          <a class="has-arrow" href="index.html">
                           <span class="educate-icon educate-bell icon-wrap sub-icon-mg"></span>
                           <span class="mini-click-non">Informasi</span>
                          </a>
                           <ul class="submenu-angle" aria-expanded="true">
                              <li><a title="Peraturan" href="<?php echo base_url(); ?>Peraturan"><span class="mini-sub-pro">Peraturan</span></a></li>
                              <li><a title="Format Surat" href="<?php echo base_url(); ?>Format"><span class="mini-sub-pro">Format Surat</span></a></li>
                              <li><a title="Jadwal Pelatihan" href="<?php echo base_url(); ?>Jadwal"><span class="mini-sub-pro">Jadwal Pelatihan</span></a></li>
                              <li><a title="Berita dan Informasi" href="<?php echo base_url(); ?>Berita"><span class="mini-sub-pro">Berita dan Informasi</span></a></li>

                          </ul>
                      </li>
                      <li class="">
                          <a class="has-arrow" href="index.html">
                           <span class="educate-icon educate-library icon-wrap"></span>
                           <span class="mini-click-non">Profil KPPN</span>
                          </a>
                           <ul class="submenu-angle" aria-expanded="true">
                              <li><a title="Profil" href="<?php echo base_url(); ?>Profil"><span class="mini-sub-pro">Profil</span></a></li>
                              <li><a title="Standar Pelayanan" href="<?php echo base_url(); ?>Standar"><span class="mini-sub-pro">Standar Pelayanan</span></a></li>
                              <!-- <li><a title="Informasi Zona Integritas" href="<?php echo base_url(); ?>Integritas"><span class="mini-sub-pro">Informasi Zona Integritas</span></a></li> -->

                          </ul>
                      </li>
            <?php
        }
            ?>

        <?php
        if($role == ROLE_COMPLAINT)
        {
            ?>
                      <li>
                        <a title="Pengaduan" href="<?php echo base_url(); ?>Pengaduan" aria-expanded="false"><span class="educate-icon educate-message icon-wrap" aria-hidden="true"></span> <span class="mini-click-non"> Pengaduan</span></a>
                      </li>
            <?php
        }
            ?>

        <?php
        if($role == ROLE_ADMIN)
        {
            ?>
                      <li class="">
                        <a class="has-arrow" href="index.html">
                         <span class="educate-icon educate-apps icon-wrap sub-icon-mg"></span>
                         <span class="mini-click-non">Admin Panel</span>
                        </a>
                         <ul class="submenu-angle" aria-expanded="true">
                            <li><a title="Data Admin" href="<?php echo base_url(); ?>Admin"><span class="mini-sub-pro">Data Admin</span></a></li>
                            <li><a title="User Satker" href="<?php echo base_url(); ?>Syssatker"><span class="mini-sub-pro">User Satker</span></a></li>
                          </ul>
                      </li>

            <?php
        }
            ?>

                  </ul>
              </nav>
          </div>
      </nav>
    </div>
    <!-- End Left menu area -->

    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="index.html"><img class="main-logo" src="" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area" style="height: 40px;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    <div class="col-lg-1 col-md-0 col-sm-1 col-xs-12" style="margin-top: -8px;">
                                        <div class="menu-switcher-pro">
                                            <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
                                                <i class="educate-icon educate-nav"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-7 col-sm-6 col-xs-12">
                                        <div class="header-top-menu tabl-d-n">

                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12" style="margin-top: -11px;">
                                        <div class="header-right-info">
                                            <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                                
                                                <li class="nav-item">
                                                    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                                        <img src="<?php echo base_url(); ?>assets/images/avatar.png" alt="" />
                                                        <span class="admin-name" style="font-size: 14px;"><?php echo $name; ?></span>
                                                        <i class="fa fa-angle-down edu-icon edu-down-arrow"></i>
                                                    </a>
                                                    <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                        <!-- <li><a href="#"><span class="edu-icon edu-settings author-log-ic"></span>Ubah Password</a> -->
                                                        <!-- </li> -->
                                                        <li><a href="<?php echo base_url(); ?>logout"><span class="edu-icon edu-locked author-log-ic"></span>Log Out</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <!-- <div ng-view></div> -->
        <!-- <div ng-include="'footer.php'"></div> -->