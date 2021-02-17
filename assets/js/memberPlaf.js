var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('memberplafCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.errors = [];
  $scope.members = [];
  $scope.filter = {};
  $scope.years;
  $scope.month;
  $scope.day;
  $scope.account;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 30 //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getMemberPlaf = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
      $http({
        method: 'POST',
        url: baseURL + "memberPlafListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.members = e.data.mem;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        alert('Data error');
      });
    };


     $scope.getTotalAwal = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.members.length; i++){
            $scope.member = $scope.members[i];
            if(parseInt($scope.member.sisa_saldo) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.member.sisa_saldo);
            } 
        }
        return $scope.total;
    }

    $scope.getTotalSaldo = function(){
        $scope.total = 0;
        for(var i = 0; i < $scope.members.length; i++){
            $scope.member = $scope.members[i];
            if(parseInt($scope.member.saldo) >= 0){
              $scope.total = parseInt($scope.total) + parseInt($scope.member.saldo);
            } 
        }
        return $scope.total;
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

     $scope.loadMonth = function() {
        $http({
          method: 'GET',
          url: baseURL + "getMonth/" + $scope.filter.sl_year
        }).then(function success(e) {
          $scope.month = e.data;
        }, function error(e) {
          console.log(e.data,e.error);
        });
     };

     $scope.loadDay = function() {
        $http({
          method: 'GET',
          url: baseURL + "getDay/" + $scope.filter.sl_year + "/" + $scope.filter.sl_month
        }).then(function success(e) {
          $scope.day = e.data;
        }, function error(e) {
          console.log(e.data,e.error);
        });
     };

     $scope.getYears();
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