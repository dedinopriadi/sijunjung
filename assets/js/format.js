var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('formatCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.format = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.form = [];
  $scope.files = [];

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 10; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getFormat = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "formatListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.format = e.data.forma;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.format = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getFormat();


    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.judul = $scope.format[indexs].judul;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


  $scope.getInfoFormat = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoFormat",
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


    $scope.deleteFormat = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteFormat",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteFormat').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getFormat();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteFormat').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.insertFormat = function() {
      $scope.loadingModal = true;
      $scope.detail.file = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "addFormat",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("judulx", $scope.detail.judul);
              formData.append("filex", $scope.detail.file);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddFormat').modal('hide');
          toastr.success(e.data.success);
          $scope.getFormat();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateFormat = function() {
      $scope.loadingModal = true;
      $scope.edit.format_file = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "updateFormat",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("idx", $scope.noid); 
              formData.append("judulx", $scope.edit.format_judul);
              formData.append("filex", $scope.edit.format_file);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditFormat').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getFormat();
        }, function error(e) {
          console.log(e.data.error);
          //$('#modalEditDonatur').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.uploadedFile = function(element) {
        $scope.currentFile = element.files[0];
        var reader = new FileReader();
        reader.onload = function(event) {
          $scope.image_source = event.target.result
          $scope.$apply(function($scope) {
            $scope.files = element.files;
          });
        }
        reader.readAsDataURL(element.files[0]);
      }


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