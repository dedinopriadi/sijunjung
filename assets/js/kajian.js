var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('kajianCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.kajian = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.masjid = $(".masjid").val();
  $scope.admin = $(".admin").val();

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getKajian = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "kajianListing",
        data :{masjid: $scope.masjid,
          num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.kajian = e.data.kaj;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.kajian = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getKajian();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.judul = $scope.kajian[indexs].judul;
      $scope.tgl   = $scope.kajian[indexs].tanggal;
      $scope.jam   = $scope.kajian[indexs].waktu;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.getInfoKajian = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoKajian",
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


    $scope.deleteKajian = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteKajian",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteKajian').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getKajian();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteKajian').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.insertKajian = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addKajian",
          data :{admin: $scope.admin,
                 masjid: $scope.masjid,
                 tgl: $(".pick").val(),
                 waktu: $(".timepick").val(),
                 datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddKajian').modal('hide');
          toastr.success(e.data.success);
          $scope.getKajian();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateKajian = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateKajian",
          data :{id: $scope.noid,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditKajian').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getKajian();
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