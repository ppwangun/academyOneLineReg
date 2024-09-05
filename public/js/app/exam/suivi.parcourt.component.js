'use strict';

angular.module('exam')
        .component('suiviParcourt',{
            templateUrl: 'suiviparcourtpl',
            controller: calculnotesCtrl 
});
function calculnotesCtrl($timeout,$http,$scope, toastr,$mdDialog){

    var $ctrl= this;
    $ctrl.backlogs = null;
    $ctrl.isDisabled    = false;
    $ctrl.sem = null;
    $ctrl.selectedExamType = null;
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedClasse= null;
    $ctrl.isUpdate = false;
    //Making sure UE Select element is active only after semester is selested
    $ctrl.isActivatedUeSelect = false;
    $ctrl.isActivatedMatiereSelect = false;


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
    $ctrl.examCode = null;
   
    var id,ue_class_id;
   // $ctrl.selectedItemChange = selectedItemChange;


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
};

$ctrl.selectedItemChange = function(classe){
    $ctrl.sem = null;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
}
    
$ctrl.asignedSemToClasse = function(class_code){
    var data = {id: class_code};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('assignsemtoclass',config).then(function(response){
        $ctrl.semesters = response.data[0];
    });
}; 


 $ctrl.loadUE = function(classe){
     
                $ctrl.ues = null;
                var data = {id: {classe_id:classe.id,sem_id:$ctrl.selectedSem.id}};
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
 //Load Exams based on selected UE code
 
 $ctrl.loadExams = function(ueCode){
        var i;
        var data = {id: $ctrl.selectedUe.id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        }; 

        $http.get('calculmp',config).then(function(response){
            $ctrl.exams = response.data[0];
            for(i=0;i<$ctrl.exams.length;i++)
            {
              $ctrl.exams[i].num=i+1;
              $ctrl.exams[i].date = $ctrl.exams[i].date.date;
            }
        });
   };

 //Looad all student who are registered to the subject
 //Load all subjects associated withe the UE as well
 $ctrl.loadStdPath = function(selectedClasseId){

        var data = {id: selectedClasseId};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        $http.get('afficherparcourt',config).then(function(response){
            $ctrl.registeredStd = response.data[0];

        });

        };

$ctrl.loadBackLogSubjet = function(mat,ev){
    var data = {std_mat:mat};
    
    $timeout(function(){

        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        $http.get('afficheruenonvalides',config).then(function(response){
            $ctrl.backlogs = response.data[0];
            if($ctrl.backlogs.length>0)
            {
                for(var i=0;i<$ctrl.backlogs.length;i++)
                {
                  $ctrl.backlogs[i].num=i+1;
                  $ctrl.backlogs[i].note = 0;
                }
            }
        });

    },100);
    
};

$ctrl.printNotes = function(ev){
    
        var data = {id: $ctrl.selectedUe.id};
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
          templateUrl: 'printpvindiv/'+$ctrl.selectedUe.id,
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

};


