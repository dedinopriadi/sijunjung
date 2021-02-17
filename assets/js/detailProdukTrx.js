var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('jaringanCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.errors = [];
  $scope.trx = [];
  $scope.filter = {};
  $scope.years;
  $scope.month;
  $scope.day;
  $scope.noid = $(".noid").val();
  $scope.product = $(".product").val();
  $scope.year  = $(".year").val();
  $scope.month = $(".month").val();
  $scope.day   = $(".day").val();
  $scope.loketName;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 30; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getDetailTrxData = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
      $http({
        method: 'POST',
        url: baseURL + "detailPodukTrxListing",
        data :{noid: $scope.noid,
          product: $scope.product,
          year: $scope.year,
          month: $scope.month,
          day: $scope.day,
          num: $scope.itemsPerPage,
          page: pageno}
      }).then(function succes(e) {
        $scope.trx = e.data.trans;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        alert('Data error');
      });
    };

    $scope.getDetailTrxData($scope.pageno);

  });