var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('plafoundCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.errors = [];
  $scope.topup = [];
  $scope.years;
  $scope.month;
  $scope.day = ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"];
  $scope.filter = {};

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getTopupPlaf = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "topupPlafListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.topup = e.data.topupplaf;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        $scope.getYears();
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        alert('Data error');
      });
    };

    $scope.getTopupPlaf();

    $scope.enableButton = function(noid) {
      $scope.noid = noid;
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

     $scope.getYears = function() {
          $http({
            method: 'GET',
            url: baseURL + "getYear"
          }).then(function success(e) {
            $scope.years = e.data;
          }, function error(e) {
            console.log(e.data,e.error);
          });
       };


    $scope.getTotalAmount = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.topup.length; i++){
            $scope.ttopup = $scope.topup[i];
            if(parseInt($scope.ttopup.amount) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.ttopup.amount);
            } 
        }
        return $scope.total;
    }

    $scope.getTotalSaldo = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.topup.length; i++){
            $scope.ttopup = $scope.topup[i];
            if(parseInt($scope.ttopup.saldo) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.ttopup.saldo);
            } 
        }
        return $scope.total;
    }

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