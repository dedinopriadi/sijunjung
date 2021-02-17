var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('dhuafaCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.dhuafa = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.masjid = $(".masjid").val();

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getDhuafa = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "dhuafaListing",
        data :{masjid: $scope.masjid,
          num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.dhuafa = e.data.don;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.dhuafa = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getDhuafa();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.nama = $scope.dhuafa[indexs].nama;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.getInfoDhuafa = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoDhuafa",
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


    $scope.deleteDhuafa = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteDhuafa",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteDhuafa').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getDhuafa();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteDhuafa').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.insertDhuafa = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addDhuafa",
          data :{masjid: $scope.masjid,
                 datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddDhuafa').modal('hide');
          toastr.success(e.data.success);
          $scope.getDhuafa();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateDhuafa = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateDhuafa",
          data :{id: $scope.noid,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditDhuafa').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getDhuafa();
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