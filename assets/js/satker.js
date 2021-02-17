/**
 * @author Dedi Nopriadi
 */


var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('satkerCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.satker = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getSatker = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "satkerListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.satker = e.data.sat;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.satker = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getSatker();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.nama = $scope.satker[indexs].nama;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.getInfoSatker = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoSatker",
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


    $scope.deleteSatker = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteSatker",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteSatker').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getSatker();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteSatker').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.insertSatker = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addSatker",
          data :{datax: $scope.detail}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddSatker').modal('hide');
          toastr.success(e.data.success);
          $scope.getSatker();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateSatker = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateSatker",
          data :{id: $scope.noid,
                  datax: $scope.edit}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditSatker').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getSatker();
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