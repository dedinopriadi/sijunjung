var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('standarCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.standar = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 10; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getStandar = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "standarListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.standar = e.data.stand;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.standar = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getStandar();


    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.judul = $scope.standar[indexs].judul;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


  $scope.getInfoStandar = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoStandar",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.konten = $scope.edit.standar_isi;
          $('#ckck2').summernote('code', $scope.konten);
          $scope.loadingModal = false;
          $scope.btnShow = false;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.error);
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.deleteStandar = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteStandar",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteStandar').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getStandar();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteStandar').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.insertStandar = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "addStandar",
          data :{datax: $scope.detail, 
            konten: $("#summernote1").summernote('code')}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddStandar').modal('hide');
          toastr.success(e.data.success);
          $scope.getStandar();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateStandar = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "updateStandar",
          data :{id: $scope.noid,
                  datax: $scope.edit,
                  konten: $("#ckck2").summernote('code')}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditStandar').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getStandar();
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