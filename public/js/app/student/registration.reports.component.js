'use strict';

angular.module('student').controller('registrationCtrl',registrationCtrl).config(['$routeProvider','$mdDateLocaleProvider', function($routeProvider,$mdDateLocaleProvider) {

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
function registrationCtrl($timeout,$http,$scope, toastr,$mdDialog){

    var $ctrl= this;
   
    $ctrl.isDisabled    = false;
    $ctrl.sem = null;
    $ctrl.selectedExamType = null;
    $ctrl.duplicata = 0;
    $ctrl.transcriptReferenceGenerationStatus = 0;
    $ctrl.stdMat = ''
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedClasse= null;
    $ctrl.isUpdate = false;
    //Making sure UE Select element is active only after semester is selested
    $ctrl.isActivatedUeSelect = false;
    $ctrl.isActivatedMatiereSelect = false;
    $ctrl.isSpecialDelibAllow= 0;
    $ctrl.nbreUeDelibSpecial =0;


    $ctrl.ues = null;

    $ctrl.isMatiereRequired = false;
    $ctrl.classes = []; 
    $ctrl.dateExam = new Date();
    $ctrl.degrees = [];
    $ctrl.semesters = [];
    $ctrl.exams = [];
    $scope.sem = null;
    $scope.searchClasse = null;
    

    $ctrl.registeredStd = [];
    $ctrl.subjects = [];
    $ctrl.selectedUe = null;
    $ctrl.selectedAcadYr = null;
    $ctrl.examCode = null;
    $ctrl.markCalculationStatus = 0;
    
    //default value is equal to 0 for printing in alpahbetical order
    $ctrl.printingOption =-1;
   
    var id,ue_class_id;
   // $ctrl.selectedItemChange = selectedItemChange;
$ctrl.disabledSelect = true;

$ctrl.formatDate = function(date){
  var dateOut = new Date(date);
  return dateOut;
};
    
 //collecte and load all the available classes of study  
 $ctrl.init = function(){
     
    //Loading classes of study asynchronously
    $ctrl.query = function(classe)
    {
       var  dataString = {id: classe},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('classes',config).then(function(response){
                   return response.data[0];
                });
     };
     
    $ctrl.queryAcademicYear = function(academicYear)
    {
       var  dataString = {id: academicYear},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('searchAcademicYear',config).then(function(response){
                   return response.data[0];
                });
     };     
     
    $ctrl.queryStudent = function(std)
    {
       var  dataString;
       if($ctrl.selectedAcadYr) dataString   = {matricule: std,acadYrId :$ctrl.selectedAcadYr.id,classeId: $ctrl.selectedClasse.id};
       else dataString   = {matricule: std,classeId: $ctrl.selectedClasse.id};        
        var  config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('searchStudent',config).then(function(response){
                   return response.data[0];
                });
     }; 
     
     $timeout(    
         $http.get('semester').then(function(response){
             //$ctrl.semesters = response.data[0];
         }).then(function(){
             $http.get('examtype').then(function(response){
                 $ctrl.examtypes = response.data[0];
                 
             });
         }),500); 
         

 
   
 };
 
 
$ctrl.activateUeSelect = function(){
   $ctrl.isActivatedUeSelect = true;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
   $ctrl.registeredStd = [];
   
        var data = {acadYrId:$ctrl.selectedAcadYr.id,sem_id: $ctrl.selectedSem.id,classe:$ctrl.selectedClasse.code};
        var i,link;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
   
       $http.get('getTranscriptReferenceGenerationStatus',config).then(function(response){
       $ctrl.transcriptReferenceGenerationStatus = response.data[0].status;
       console.log(response.data[0]);
       //return response.data[0];
       });
};

$ctrl.selectedYearChange = function(classe){
    $ctrl.selectedClasse = null;
    $ctrl.selectedSem = null;
    $ctrl.disabledSelect = true;
}
$ctrl.selectedClasseChange = function(classe){
  $ctrl.selectedSem = null;
$ctrl.selectedStd = null
$ctrl.disabledSelect = false;
if($ctrl.selectedClasse=== null)
    $ctrl.disabledSelect = true;
}
    
$ctrl.asignedSemToClasse = function(acadYrID,classCode){
    var data = {id:{acadYrId:acadYrID,classeCode:classCode}};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('assignsemtoclass',config).then(function(response){
        $ctrl.semesters = response.data[0];
    });
}; 

$ctrl.generateTranscriptsReferences = function(ev){
    
        var data = {acadYrId:$ctrl.selectedAcadYr.id,sem_id: $ctrl.selectedSem.id,classe:$ctrl.selectedClasse.code};
        var i,link;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        $http.get('generateTranscriptsReferences',config).then(function(response){
                toastr.success("Opération effectuée avec succès");
                $ctrl.transcriptReferenceGenerationStatus = response.data[0].status;
                //return response.data[0];
                });
    
};

$ctrl.printTranscripts = function(ev){
    
        var data = {acadYrId:$ctrl.selectedAcadYr.id,sem_id: $ctrl.selectedSem.id,classe:$ctrl.selectedClasse.code,printingOption:$ctrl.printingOption};
        var i,link;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        if($ctrl.selectedStd)
            link = 'printTranscripts/'+$ctrl.selectedClasse.code+'/'+$ctrl.selectedSem.id+'/'+$ctrl.selectedAcadYr.id+'/'+$ctrl.duplicata+'/'+$ctrl.selectedStd.id;
        else
            link = 'printTranscripts/'+$ctrl.selectedClasse.code+'/'+$ctrl.selectedSem.id+'/'+$ctrl.selectedAcadYr.id+'/'+$ctrl.duplicata;

        
          $mdDialog.show({
          controller: DialogController,
          templateUrl: link,
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
        
         //Dialog Controller
        function DialogController($scope, $mdDialog,readFileData) {
            
            
        $scope.cancel = function() {
            $mdDialog.cancel();
        };

        $scope.answer = function(answer) {
          $mdDialog.hide(answer);
        };   

        };
    };
    
$ctrl.printScholarshipCertificates = function(ev){
    
        var link;
        if($ctrl.selectedStd)
            link = 'printScholarshipCertificates/'+$ctrl.selectedClasse.code+'/'+$ctrl.selectedStd.id;
        else
            link = 'printScholarshipCertificates/'+$ctrl.selectedClasse.code;

        
          $mdDialog.show({
          controller: DialogController,
          templateUrl: link,
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
        
         //Dialog Controller
        function DialogController($scope, $mdDialog,readFileData) {
            
            
        $scope.cancel = function() {
            $mdDialog.cancel();
        };

        $scope.answer = function(answer) {
          $mdDialog.hide(answer);
        };   

        };
    };    
}
