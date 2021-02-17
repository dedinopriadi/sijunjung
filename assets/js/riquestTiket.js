var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('requesttiketlCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.errors = [];
  $scope.tiket = [];
  $scope.filter = {};
  //$scope.noid;
  $scope.validasi = {};

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getRequestTiket = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "reqTiketListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.tiket = e.data.tikets;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        alert('Data error');
      });
    };

    $scope.validationRequest = function(status) {
      $http({
        method: 'POST',
        url: baseURL + "validationRequest",
        data :{validasi: $scope.validasi,
               stat: status}
      }).then(function succes(e) {
         $scope.getRequestTiket();
         $scope.btnShow = false;
         $('#abaikanModal').modal('hide');
         $('#checkModal').modal('hide');
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        alert('Data error');
      });
    };

    $scope.getRequestTiket();

    $scope.enableButton = function(noid, indexs) {
      $scope.validasi.noid = noid;
      $scope.validasi.nama = $scope.tiket[indexs].nama;
      $scope.validasi.nominal = $scope.tiket[indexs].nominal;
      $scope.validasi.bank = $scope.tiket[indexs].bank;
      //$scope.btnShow = true;
      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


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