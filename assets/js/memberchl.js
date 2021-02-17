var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('memberchnlCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.btnBlok = false;
  $scope.btnUnblok = false;
  $scope.loaded = false;
  $scope.formIP = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.members = [];
  $scope.filter = {};
  $scope.edithpemail = {};
  $scope.noid;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getMemberChnl = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "memberChnlListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.members = e.data.mem;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
        alert('Data error');
      });
    };

    $scope.getMemberChnl();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.alias = $scope.members[indexs].alias;
      //$scope.btnShow = true;
      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        $scope.btnBlok = false;
        $scope.btnUnblok = false;
        break;
      case false:
        $scope.btnShow = true;
        if($scope.members[indexs].status == 0){
          $scope.btnBlok = false;
          $scope.btnUnblok = true;
        }else if($scope.members[indexs].status == 1){
          $scope.btnBlok = true;
          $scope.btnUnblok = false;
        }
        break;
      }
    };

    $scope.blockUnblock = function(status) {
      $scope.loadingModal = true;
      $http({
        method: 'POST',
        url: baseURL + "blockUnblockChnl",
        data :{id: $scope.noid,
               stat: status}
      }).then(function succes(e) {
         $scope.loadingModal = false;
         $('#blokirModal').modal('hide');
         $('#unblokModal').modal('hide');
         $scope.getMemberChnl();
         $scope.btnShow = false;
         $scope.btnBlok = false;
         $scope.btnUnblok = false;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.loadingModal = false;
        $('#blokirModal').modal('hide');
        $('#unblokModal').modal('hide');
        alert('Data error');
        $scope.btnShow = false;
        $scope.btnBlok = false;
        $scope.btnUnblok = false;
      });
    };

    $scope.getNoHP = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getHpEmail",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edithpemail.nohp_email = e.data.nohp_email;
          $scope.loadingModal = false;
        }, function error(e) {
          console.log(e.data.error);
        });
    };

    $scope.selectJenis = function() {
      if($scope.detail.jenis == "H2H"){
        $scope.formIP = true;
      }else{
        $scope.formIP = false;
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