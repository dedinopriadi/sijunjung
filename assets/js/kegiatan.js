var app = angular.module('App', ['angularUtils.directives.dirPagination']);
app.controller('kegiatanCtrl',function($scope, $rootScope, $http){
  var vm = this;

  $scope.dataLoadedSuccessfully = false;
  $scope.btnShow = false;
  $scope.loaded = false;
  $scope.loadingModal = false;
  $scope.errors = [];
  $scope.kegiatan = [];
  $scope.detail = {};
  $scope.edit = {};
  $scope.filter = {};
  $scope.noid;
  $scope.konten;
  $scope.masjid = $(".masjid").val();
  $scope.admin = $(".admin").val();
  $scope.inputIsi = $("#ckck2").val();
  $scope.form = [];
  $scope.files = [];

    $scope.pageno = 1; // initialize page no to 1
    $scope.total_count = 0;
    $scope.itemsPerPage = 15; //this could be a dynamic value from a drop down    

    $scope.p = vm.pageno;
    $scope.query = {};

    $scope.sortField = undefined;
    $scope.reverse = false;

    $scope.getKegiatan = function(pageno) {
       $scope.dataLoadedSuccessfully = false;
       $scope.loaded = false;
      $http({
        method: 'POST',
        url: baseURL + "kegiatanListing",
        data :{masjid: $scope.masjid,
          num: $scope.itemsPerPage,
          page: pageno,
          filter: $scope.filter}
      }).then(function succes(e) {
        $scope.kegiatan = e.data.kaj;
        $scope.total_count = e.data.total_count;
        $scope.dataLoadedSuccessfully = true;
        $scope.loaded = true;
      }, function error(e) {
        $scope.dataLoadedSuccessfully = true;
        $scope.kegiatan = [];
        $scope.loaded = true;
        toastr.warning("Referensi Kosong");
      });
    };

    $scope.getKegiatan();

    $scope.enableButton = function(noid, indexs) {
      $scope.noid  = noid;
      $scope.nama  = $scope.kegiatan[indexs].nama;

      switch ($scope.btnShow) {
      case true:
        $scope.btnShow = false;
        break;
      case false:
        $scope.btnShow = true;
        break;
      }
    };


    $scope.deleteKegiatan = function() {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "deleteKegiatan",
          data :{id: $scope.noid}
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalDeleteKegiatan').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getKegiatan();
        }, function error(e) {
          console.log(e.data.error);
          $('#modalDeleteKegiatan').modal('hide');
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.getInfoKegiatan = function(idx) {
      $scope.loadingModal = true;
      $http({
          method: 'POST',
          url: baseURL + "getInfoKegiatan",
          data :{id: idx}
        }).then(function success(e) {
          $scope.edit = e.data.info;
          $scope.konten = $scope.edit.kegiatan_isi;
          $('#ckck').summernote('code', $scope.konten);
          $scope.loadingModal = false;
          $scope.btnShow = false;
          toastr.success(e.data.success);
        }, function error(e) {
          console.log(e.data.error);
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };


    $scope.uploadImage = function() {
      $scope.loadingModal = true;
      $scope.detail.gambar = $scope.files[0];

      $http({
          method: 'POST',
          url: baseURL + "addKegiatan", 
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData();
              formData.append("adminy", $scope.admin); 
              formData.append("masjid", $scope.masjid); 
              formData.append("tgl", $(".pick").val());
              formData.append("isi", $("#ckck2").val());
              formData.append("judul", $scope.detail.judul);
              formData.append("gambar", $scope.detail.gambar);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalAddKegiatan').modal('hide');
          toastr.success(e.data.success);
          $scope.getKegiatan();
        }, function error(e) {
          $scope.loadingModal = false;
          console.log(e.data.errors);
          toastr.error(e.data.errors);
        });
    };


    $scope.updateKegiatan = function() {
      $scope.loadingModal = true;
      $scope.edit.kegiatan_photo = $scope.files[0];

      $http({
          method: 'POST',
          url: baseURL + "updateKegiatan",
          processData: false,
          transformRequest: function (data) {
              var formData = new FormData();
              formData.append("id", $scope.noid); 
              formData.append("tgl", $scope.edit.kegiatan_tgl);
              formData.append("isi", $("#ckck").val());
              formData.append("judul", $scope.edit.kegiatan_nama);
              formData.append("kegiatan_photo", $scope.edit.kegiatan_photo);  
              return formData;  
          }, 
          headers: { 'Content-Type': undefined }
        }).then(function success(e) {
          $scope.loadingModal = false;
          $('#modalEditKegiatan').modal('hide');
          $scope.btnShow = false;
          toastr.success(e.data.success);
          $scope.getKegiatan();
        }, function error(e) {
          console.log(e.data.error);
          $scope.btnShow = false;
          toastr.error(e.data.errors);
        });
    };

    $scope.testPicker = function() {
      $scope.edit.kegiatan_photo = $scope.files[0];
      console.log($scope.edit.kegiatan_photo);
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