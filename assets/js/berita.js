var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('beritaCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.berita = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.lampiran = {};
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

    $scope.getBerita = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "beritaListing",
        data :{num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.berita = e.data.ber;
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

    $scope.getBerita();


    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.judul = $scope.berita[indexs].judul;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


  $scope.getInfoBerita = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoBerita",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.konten = $scope.edit.berita_isi;
          $('#txtEditBerita').summernote('code', $scope.konten);
          $scope.loadingModal = false;
          $scope.btnShow = false;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.error);
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.deleteBerita = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteBerita",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteBerita').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getBerita();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteBerita').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.insertBerita = function() {
      $scope.loadingModal = true;
      $scope.detail.gambar = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "addBerita",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("userx", $(".admin").val());
              formData.append("judulx", $scope.detail.judul);
              formData.append("isix", $("#summernote1").summernote('code'));
              formData.append("gambarx", $scope.detail.gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddBerita').modal('hide');
          toastr.success(e.data.success);
          $scope.getBerita();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.uploadFileBerita = function() {
      $scope.loadingModal = true;
      $scope.lampiran.file = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "uploadFileBerita",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("idx", $scope.noid); 
              formData.append("filex", $scope.lampiran.file);  
              return formData; 
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalFileBerita').modal('hide');
          toastr.success(e.data.success);
          $scope.btnShow = false;
          $scope.getBerita();
        }, function error(e) {
          $scope.loadingModal = false;
          $scope.btnShow = false;
          console.log(e.data.error);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateBerita = function() {
      $scope.loadingModal = true;
      $scope.edit.berita_gambar = $scope.files[0];
      $http({
          method: 'POST',
          url: baseURL + "updateBerita",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData(); 
              formData.append("idx", $scope.noid); 
              formData.append("judulx", $scope.edit.berita_judul);
              formData.append("isix", $("#txtEditBerita").summernote('code'));
              formData.append("gambarx", $scope.edit.berita_gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditBerita').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getBerita();
        }, function error(e) {
          console.log(e.data.error);
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