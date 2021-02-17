var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('rekapCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.rekapLoadedSuccessfully = false;
  $scope.rekapPrdLoadedSuccessfully = false;
  $scope.preloadLoadedSuccessfully = true;
  $scope.errors = [];
  $scope.rekap = [];
  $scope.rekap_prd = [];
  $scope.filter = {};
  $scope.years;
  $scope.month;
  $scope.day;
  $scope.tgl = $(".tgl").val();

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 30; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getRekapData = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.rekapLoadedSuccessfully = false;
      $http({
        method: 'POST',
        url: baseURL + "rekapPrdListing",
        data :{tgl: $scope.tgl,
          num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.rekap = e.data.rekap;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.rekapLoadedSuccessfully = true;
        $scope.getYears();
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.rekapLoadedSuccessfully = true;
        alert('Data error');
      });
    };

    $scope.getRekapData($scope.pageno);


     $scope.getRekapPrdData = function(product) {
       $scope.preloadLoadedSuccessfully = false;
       $scope.rekapLoadedSuccessfully = false;
      $http({
        method: 'POST',
        url: baseURL + "rekapTrxPrdListing",
        data :{tgl: $scope.tgl,
          produk: product,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.rekap_prd = e.data.trx_rekap;
        $scope.preloadLoadedSuccessfully = true;
        $scope.rekapPrdLoadedSuccessfully = true;
      }, function error(e) {
        $scope.preloadLoadedSuccessfully = true;
        $scope.rekapPrdLoadedSuccessfully = true;
        alert('Data error');
      });
    };

    $scope.back = function() {
      $scope.rekapPrdLoadedSuccessfully = false;    
      $scope.rekapLoadedSuccessfully = true;
    };


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