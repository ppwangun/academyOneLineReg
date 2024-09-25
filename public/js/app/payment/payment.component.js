'use strict';

angular.module('payment')
        .component('paymentList',{
            templateUrl: 'payments',
            controller: paymentCtrl 
});
function paymentCtrl($timeout,$http,$location,$mdDialog,DTOptionsBuilder,DTColumnDefBuilder,$scope,toastr){
    var $ctrl = this;
        $ctrl.payments = [];
        
    $ctrl.faculties =[];
    $ctrl.filieres =[];
    $ctrl.specialite =[];
    $scope.school = null;
    
    $ctrl.init = function(){

    $timeout(
     $http.get('paymentlist').then(function(response){
         
         $ctrl.payments = response.data[0];
     }),500);
     
        
    /*--------------------------------------------------------------------------
     *--------------------------- Load faculties--------------------------------
     *----------------------------------------------------------------------- */
    $http.get('faculty').then(
        function(response){
        $scope.faculties = response.data[0];
    });      
 };
 
 
  $ctrl.redirect= function(id){
     $location.path("/paymentdetails/"+id)
     
 };
 
     $ctrl.queryStudent = function(std)
    {
       var  dataString;
       if($ctrl.selectedAcadYr) dataString   = {matricule: std,acadYrId :$ctrl.selectedAcadYr.id,classeId: $ctrl.selectedClasse.id};
       else dataString   = {matricule: std};        
        var  config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('searchStudent',config).then(function(response){console.log(response.data[0])
                   return response.data[0];
                });
     };         

$scope.savePymtTransaction= function(std){
    $ctrl.semesters = [];
    var data = {std: std};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('savePymtTransaction',config).then(function(response){
        $ctrl.semesters = response.data[0];
                
    });
};
 
  $ctrl.dtOptions = DTOptionsBuilder.newOptions()
     .withButtons([
            //'columnsToggle',
            //'colvis',
            {extend:'copy',text:'Copier dans le presse papier',className: 'btn btn-default'},
            {extend:'print',text:"Imprimer",className: 'btn btn-default'}

        ])
        .withPaginationType('full_numbers')
        .withDisplayLength(100)
         /* .withFixedHeader({
    top: true
  })*/;
  
    $ctrl.dtColumnDefs = [
    DTColumnDefBuilder.newColumnDef(0),
    DTColumnDefBuilder.newColumnDef(1),
    DTColumnDefBuilder.newColumnDef(2),
    DTColumnDefBuilder.newColumnDef(3),
    DTColumnDefBuilder.newColumnDef(4).notSortable(),
    DTColumnDefBuilder.newColumnDef(5),
    DTColumnDefBuilder.newColumnDef(6)
  ];
     
    /*--------------------------------------------------------------------------
     *---------------------------Updating Student ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.importBalance = function(std,ev){
        $scope.student = std;
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/payment/importBalance.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
 /*--------------------------------------------------------------------------
     *---------------------------Loading files ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStdPayments = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/payment/uploadStdPayments.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
 /*--------------------------------------------------------------------------
     *---------------------------Loading files ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStdDotation = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/payment/uploadStdDotation.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
    
    /*--------------------------------------------------------------------------
     *---------------------------Loading subjects-------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.addStdToSubject = function(ev){
        $scope.isUpdate= true;

        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/addPymtStudentForm.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    };
    //Dialog Controller
/*function DialogController($scope, $mdDialog, $q,toastr) {
    $scope.selectedStd= null;
    $scope.studs = [];
    $scope.selectedSubject = null;


//Select subjects to be add    
     $scope.addStudent = function()
     {
         
         //Cehck if the value exist in the array be for pushinng
         //Avoiding duplicates
         if ($scope.studs.includes($scope.selectedStd) === false) 
         {
             $scope.selectedStd.num = $scope.studs.length + 1;
             $scope.selectedStd.status = 1;
             $scope.studs.push($scope.selectedStd);
         }
         
         
     };
//Delete selected subject
     $scope.removeStudent = function(sub){
         var index = $scope.studs.indexOf(sub);
         $scope.studs.splice(index,1);
     };



  } */    
    
 //Dialog Controller
  function DialogController($scope, $mdDialog,toastr) {
      
      $scope.std = null;

$scope.uploadBalance = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importBalance',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      response.data[0]?$mdDialog.cancel():toastr.error('Erreur pendant le processus d\'importation', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 };
 
$scope.uploadStdPayments = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importPayments',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      response.data[0]?$mdDialog.cancel():toastr.error('Erreur pendant le processus d\'importation', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 }; 
 
 
 $scope.uploadStdDotation = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importDotations',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      response.data[0]?$mdDialog.cancel():toastr.error('Erreur pendant le processus d\'importation', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 }; 
 }
 
     /*--------------------------------------------------------------------------
     *--------------------------- update student payment  ---------------------
     *----------------------------------------------------------------------- */
    $scope.savePymtTransaction = function(pymtDetails){ 
      var data = {pymtDetails: pymtDetails}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('updateStdPymt',config).then(
            function successCallback(response){

                $mdDialog.cancel();
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
                $mdDialog.cancel();
                
            });  
            
             
        
        
    }

    /*--------------------------------------------------------------------------
     *--------------------------- loading all specilaities  by field of study   ---------------------
     *----------------------------------------------------------------------- */
    $scope.loadSpecialities = function(id){
      var data = {fil_id: id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchSpeByFil',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $scope.specialites = response.data[0];
            
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });
            
           
        
    }     
    /*--------------------------------------------------------------------------
     *--------------------------- loading degrees by speciality   ---------------------
     *----------------------------------------------------------------------- */
    $scope.loadDegrees = function(id){
      var data = {spe_id: id};  console.log(data)
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchDegreeBySpeciality',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $scope.degrees = response.data[0];
            
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });    
        
    }
  
      $scope.cancel = function() {
      //$scope.faculties=[];
        $scope.selectedStd = null;

      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };    
};


