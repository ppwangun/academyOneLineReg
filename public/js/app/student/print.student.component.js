'use strict';

angular.module('student').controller('printStdCtrl',printStdCtrl);

function printStdCtrl($timeout,$http,$location,$mdDialog,$routeParams,$scope,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var id,exam_id;
    var $ctrl= this;
   
    $ctrl.isDisabled    = false;
    $ctrl.sem = null;
    $ctrl.selectedExamType = null;
    
 
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedClasse= null;
    $ctrl.ues = null;
    $ctrl.classes = []; 
    $ctrl.semesters = [];
    $scope.sem = null;
    $scope.searchClasse = null;
    $ctrl.selectedSem = null;
    $ctrl.selectedUe = null;

   
    var id,ue_class_id;
   // $ctrl.selectedItemChange = selectedItemChange;
    
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
  
 };
 
 
$ctrl.activateUeSelect = function(){
   $ctrl.isActivatedUeSelect = true;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
};

$ctrl.selectedItemChange = function(classe){
    $ctrl.sem = null;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
}
    
$ctrl.asignedSemToClasse = function(class_code){
    $ctrl.semesters = [];
    var data = {id: class_code};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('assignsemtoclass',config).then(function(response){
        $ctrl.semesters = response.data[0];
                
    });
};


 $ctrl.loadUE = function(classe,sem_id){
     
                $ctrl.ues = [];
                var data = {id: {classe_id:classe.id,sem_id:sem_id}};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading selected class information for update
                $timeout(
                        
                        $http.get('teachingunit',config).then(
     
                        function(response){
                            $ctrl.ues=response.data[0];

                        }),1000);
                      


 };
 
 $ctrl.printStdList = function(classeCode,ueId,ev)
 {
     
        var data =  {classeCode:classeCode.code,ueId:ueId};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        /*$http.get('printpvindiv',config).then(function(response){
            $ctrl.registeredStd = response.data[0];
            if($ctrl.registeredStd.length>0)
            {
                for(i=0;i<$ctrl.registeredStd.length;i++)
                {
                  $ctrl.registeredStd[i].num=i+1;
                  $ctrl.registeredStd[i].note = 0;
                }
            }
        });*/
        
          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printStdList/'+classeCode.code+'/'+ueId,
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
         
 
   
 }
 

 
}
