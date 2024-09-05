'use strict';

angular.module('student').component('prospectiveStd',{
            templateUrl: 'prospects',
            controller: studentCtrl 
}).component('prospectiveDetails',{
            templateUrl: 'prospect',
            controller: studentCtrl 
})
        .controller('studentCtrl',studentCtrl).config(['$routeProvider','$mdDateLocaleProvider', function($routeProvider,$mdDateLocaleProvider) {

    $mdDateLocaleProvider.parseDate = function(dateString) {
      var m = moment(dateString, 'YYYY-MM-DD', true);
      return m.isValid() ? m.toDate() : new Date(NaN);
    };  
  
    $mdDateLocaleProvider.formatDate = function(date) {
      var m = moment(date);
      return m.isValid() ? m.format('YYYY-MM-DD') : '';
    };
  $routeProvider.when('/studentinfos', {
    templateUrl: 'studentinfostpl',
    controller: 'studentCtrl'
  });
}]);

//Student controller
function studentCtrl($timeout,$http,$location,$mdDialog,$scope,toastr,$routeParams,DTOptionsBuilder,DTColumnDefBuilder){
    var $ctrl = this;
     $ctrl.students=[];
     $ctrl.prospects = [];
     $ctrl.stds = [];

     var temp =[];
     var std_id;
     var data; 

    $timeout(
     $http.get('getProspects').then(
     function successCallback(response){
       $scope.cpt = 1;
        $ctrl.prospects = response.data[0]; 
        //$ctrl.students= response.data;

       //$ctrl.students=response.data.data;
     },
     function errorCallback(response){
        
     }),1000);
     
    $timeout(
     $http.get('stdAdmitted').then(
     function successCallback(response){
        $ctrl.students = response.data[0]; 
        //$ctrl.students= response.data;
        
        
        for(var i=0;i<$ctrl.students.length;i++)
        {
            $ctrl.students[i].dateAdmission = $ctrl.students[i].dateAdmission.date;
            $ctrl.students[i].num = i+1;
        }
        console.log($ctrl.students);
       //$ctrl.students=response.data.data;
     },
     function errorCallback(response){
         
     }),1000);
     
     if($routeParams.id)
     {
        var data = {id: $routeParams.id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('getProspects',config).then(
         function successCallback(response){
           $scope.cpt = 1;
            $ctrl.prospect = response.data[0];
            $ctrl.prospect.classe = $ctrl.prospect.choixFormation1;
            //$ctrl.students= response.data;

           //$ctrl.students=response.data.data;
         },
         function errorCallback(response){

         }).then(function(){
            $http.get('getProspectCursus',config).then(
                     function successCallback(response){
                       $scope.cpt = 1;
                        $ctrl.cursus = response.data[0]; 
                        //$ctrl.students= response.data;

                       //$ctrl.students=response.data.data;
                     })             
         }),1000);         
     }
   
 

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
    DTColumnDefBuilder.newColumnDef(1).withClass("td-small"),
    DTColumnDefBuilder.newColumnDef(2).notSortable(),
    DTColumnDefBuilder.newColumnDef(3).notSortable(),
    DTColumnDefBuilder.newColumnDef(4).notSortable().withClass("td-2-small")
  ];

    $ctrl.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
    
 $ctrl.redirectProspectiveStd = function(id){
    $location.path("/prospectDetails/"+id);    
 }
 //Redirect user when double click
  $ctrl.redirect= function(id){
     
     $location.path("/studentinfos/"+id);
     std_id=id;

      //collect student registered subject
    
    var data = {id: std_id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };
     $timeout(
     $http.get('subjectregistration',config).then(function(response){
      
        $ctrl.subjects = response.data[0];
       
       
     }),1000);
     
     //Collect all the laible classes
      $timeout(
     $http.get('classes').then(function(response){
         //convert all loaded data to lower case
        angular.forEach(response.data[0],function(item){

          $ctrl.classes.push({id:item.id,code:item.code, name:item.name.toLowerCase()+"["+item.code+"]"}); 

        });
     }).then(function(){
         $http.get('semester').then(function(response){
             $ctrl.semesters = response.data[0];
         });
     }),500); 
 };
 
 $ctrl.acceptProspectivieStd = function(numDossier,ev)
 {
     
        var data = {"numDossier":numDossier,"status":1,"classe":$ctrl.prospect.classe}
     
        var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment accepter cette candidature?')
            .textContent('')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Valider la candidature')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.post('getProspects',data).then(
             function successCallback(response){
                 //reinitialise form
                 toastr.success("Opération effectuée avec succès")
             },
             function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite")

         });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
     
 }
 
 $ctrl.rejectProspectivieStd = function(numDossier,ev)
 {
     
        var data = {"numDossier":numDossier,"status":2}
     
        var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment rejeter cette candidature?')
            .textContent('')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Rejeter la candidature')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.post('getProspects',data).then(
             function successCallback(response){
                 //reinitialise form
                 toastr.success("Opération effectuée avec succès")
             },
             function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite")

         });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });    
 } 
 
    /*--------------------------------------------------------------------------
     *---------------------------Updating Student ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.showPaymentProof = function(std,ev){
        $scope.student = std;
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'showpaymentproof/'+std.numDossier,
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
     *---------------------------Updating Student ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStd = function(std,ev){
        $scope.student = std;
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/updateStd.html',
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
  function DialogController($scope, $mdDialog) {
      



 
$scope.confirmAdmission = function(data,ev){
    //var data = {id:data.id,nom:data.nom,prenom:data.prenom,phoneNumber:data.phoneNumber,classe:data.classe}
    $timeout(
     $http.post('stdAdmitted',data).then(function(response){
      
        $scope.student = response.data[0];
       
      }),500);

  };
 
 
 }
 /*--------------------------------------------------------------------------
     *---------------------------Loading files ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController1,
          templateUrl: 'js/app/student/uploadAdmissionform.html',
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
  function DialogController1($scope, $mdDialog) {
      

 
$scope.upload = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importAdmittedStudent',
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
  
      $scope.cancel = function() {
      //$scope.faculties=[];

      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };   
};