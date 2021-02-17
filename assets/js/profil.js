var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('profilCtrl',function($scope, $rootScope, $http){
  var vm = this;


  $scope.dataLoadedSuccessfully = false;
  $scope.loadingModal = false;
  $scope.loaded = false;
  $scope.errors = [];
  $scope.profil = {};
  $scope.noid;
  $scope.form = [];
  $scope.files = [];


  $scope.getProfil = function() {
    $scope.dataLoadedSuccessfully = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoProfil",
          data :{}
        }).then(function success(e) {
          $scope.profil = e.data.info;
          $scope.konten = $scope.profil.profile;
          $('#txtProfil').summernote('code', $scope.konten);
          $scope.loaded = true;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.errors);
          $scope.loaded = true;
          toastr.error(e.data.errors);
        });
  };

  $scope.getProfil();

  $scope.updateProfil = function() {
      $scope.dataLoadedSuccessfully = false;
      $scope.loaded = false;
      $scope.profil.gambar = $scope.files[0];

      $http({
          method: 'POST',
          url: baseURL + "saveProfil",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData();
              formData.append("alamat", $scope.profil.alamat); 
              formData.append("cso", $scope.profil.cso);
              formData.append("profile", $("#txtProfil").val());
              formData.append("gambar", scope.profil.gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.success(e.data.success);
        }, function error(e) {
          // console.log(e.data.error);
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.error(e.data.errors);
        });
    };

    $scope.test = function() {
      $scope.profil.gambar = $scope.files[0];
      console.log($scope.profil.gambar);
    };


    $scope.saveProfil = function() {
      $scope.profil.gambar = $scope.files[0];
      $scope.dataLoadedSuccessfully = false;
      $scope.loaded = false;
      $http({
          method: 'POST',
          url: baseURL + "updateProfil",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData();
              formData.append("alamat", $scope.profil.alamat); 
              formData.append("cso", $scope.profil.cso);
              formData.append("profil", $("#txtProfil").val());
              formData.append("gambar", scope.profil.gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
          toastr.success(e.data.success);
        }, function error(e) {
          $scope.dataLoadedSuccessfully = true;
          $scope.loaded = true;
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