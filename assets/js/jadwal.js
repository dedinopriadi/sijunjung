var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('jadwalCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.jadwal = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.admin = $(".admin").val();

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getJadwal = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "jadwalListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.jadwal = e.data.jad;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.jadwal = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getJadwal();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid   = noid;
      $scope.materi = $scope.jadwal[indexs].materi;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.getInfoJadwal = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoJadwal",
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


    $scope.deleteJadwal = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteJadwal",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteJadwal').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getJadwal();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteJadwal').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.insertJadwal = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addJadwal",
          data :{admin: $scope.admin,
                 tgl: $(".pick").val(),
                 waktu: $(".timepick").val(),
                 datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddJadwal').modal('hide');
          toastr.success(e.data.success);
          $scope.getJadwal();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateJadwal = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateJadwal",
          data :{id: $scope.noid,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditJadwal').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getJadwal();
        }, function error(e) {
          console.log(e.data.error);
          //$('#modalEditDonatur').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.testPicker = function() {
      toastr.error($(".pick").val());
      toastr.warning($(".timepick").val());
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