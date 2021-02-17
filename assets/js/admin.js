var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('adminCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.admins = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.admin = $(".admin").val();
  $scope.ls_roles = [];
  $scope.ls_masjids = [];

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;


      $scope.getAdmin = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "adminListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.admins = e.data.adm;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.admins = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getAdmin();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.nama = $scope.admins[indexs].nama;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.deleteAdmin = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteAdmin",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteAdmin').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getAdmin();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteAdmin').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.addAdmin = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addAdmin",
          data :{admin: $scope.admin,
                 datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddAdmin').modal('hide');
          toastr.success(e.data.success);
          $scope.getAdmin();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.getRoles = function() {
      $http({
        method: 'GET',
        url: baseURL + "getRoles"
      }).then(function success(e) {
        $scope.ls_roles = e.data;
      }, function error(e) {
        console.log(e.data,e.error);
      });
   };


 $scope.getRoles();


 $scope.getInfoAdmin = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoAdmin",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.loadingModal = false;
          $scope.btnShow = false;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.error);
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };



  $scope.updateAdmin = function() {
    $scope.loadingModal = true;
    $http({
        method: 'POST',
        url: baseURL + "updateAdmin",
        data :{id: $scope.noid,
                datax: $scope.edit}
      }).then(function success(e) {
        $scope.loadingModal = false;
        $('#modalEditAdmin').modal('hide');
        $scope.btnShow = false;
        toastr.success(e.data.success);
        $scope.getAdmin();
      }, function error(e) {
        console.log(e.data.error);
        //$('#modalEditDonatur').modal('hide');
        $scope.btnShow = false;
        toastr.error(e.data.errors);
      });
  };


///////////////////////////////////////////////////////////////////////////////////////////////////
});


angular.module('App').directive('ngEnter', function() {
  return function(scope, element, attrs) {
      element.bind("keydown keypress", function(event) {
          if(event.which === 13) {
              scope.$apply(function(){
                  scope.$eval(attrs.ngEnter, {'event': event});
              });

              event.preventDefault();
          }
      });
  };
});