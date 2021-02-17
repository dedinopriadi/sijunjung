var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('memberCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded  = false;
  $scope.errors  = [];
  $scope.members = [];
  $scope.filter  = {};
  $scope.edithpemail = {};
  $scope.detail = {};
  $scope.edit   = {};
  $scope.nik;
  $scope.masjid = $(".masjid").val();

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 50; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getJamaahData = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "jamaahListing",
        data :{masjid: $scope.masjid,
          num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.jamaah = e.data.jam;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getJamaahData();

    $scope.enableButton = function(nik, indexs) {
      $scope.nik = nik;
      $scope.detail.nama       = $scope.jamaah[indexs].nama;
      $scope.detail.nohp_email = $scope.jamaah[indexs].email;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };

    $scope.getInfoJamaah = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoJamaah",
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

    $scope.updateJamaah = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateJamaah",
          data :{id: $scope.nik,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditMember').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getJamaahData();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalEditMember').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.deleteJamaah = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteJamaah",
          data :{id: $scope.nik}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteJamaah').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getJamaahData();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteJamaah').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    ////////////////////////////////////////////////////////////////////////////////////////////////////

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