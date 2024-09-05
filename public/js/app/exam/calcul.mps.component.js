'use strict';

angular.module('exam')
        .component('calculMps',{
            templateUrl: 'calculmpstpl',
            controller: calculmpsCtrl 
});
function calculmpsCtrl($timeout,$http,$scope, toastr,$mdDialog){

    var $ctrl= this;
   
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
    $ctrl.examCode = null;
    $ctrl.markCalculationStatus = 0;
    
    //default value is equal to 0 for printing in alpahbetical order
    $ctrl.printingOption =-1;
   
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
   $ctrl.registeredStd = [];
};

$ctrl.selectedItemChange = function(classe){
    $ctrl.sem = null;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
   $ctrl.selectedSem = null;
    $ctrl.registeredStd = [];
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
        var data = {id: $ctrl.selectedUe.code};
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
 $ctrl.loadStd = function(selectedClasse,selectedSem){
        $ctrl.isActivatedMatiereSelect = false;
        $ctrl.isMatiereRequired = false;

        var data = {classe: selectedClasse, sem: selectedSem,
            isSpecialDelibAllow : $ctrl.isSpecialDelibAllow,
            nbreUeDelibSpecial : $ctrl.nbreUeDelibSpecial};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //From registration module, IndexController, stdregisteredbyclasseAction
        $http.get('stdregisteredbyclasse',config).then(function(response){
            $ctrl.registeredStd = response.data[0];
            toastr.success("Opéraction effectuée avec succès");
            
        })};

$ctrl.calculNotes = function(ev){
    var data = {class_id:$ctrl.selectedClasse.id, sem_id:$ctrl.selectedSem.id, ue_id:$ctrl.selectedUe.id,
    isSpecialDelibAllow : $ctrl.isSpecialDelibAllow,
    nbreUeDelibSpecial : $ctrl.nbreUeDelibSpecial
    };
    $timeout(function(){
        $http.post('calculmp',data).then(function(response){
            
            if(response.data[0] === "ERROR_NO_CC_OR_EXAM_DONE")
            {
                    $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('Erreur lors du calcul des notes')
                      .textContent("Rassurez vous qu 'au moins un CC et un EXAM ont été réalisés par matière ou sous matière et que toutes les notes sont enrégistrées et validées\n")
                      .ariaLabel('Alert Dialog Demo')
                      .ok('Fermer!')
                      .targetEvent(ev)
                  );
                
                return;
            }
            toastr.success("Opération effectuée avec succès");
            $ctrl.registeredStd = response.data[0];
            if($ctrl.registeredStd.length>0)
            {
                for(var i=0;i<$ctrl.registeredStd.length;i++)
                {
                  $ctrl.registeredStd[i].num=i+1;
                  $ctrl.registeredStd[i].note = 0;
                }
            }            
        })
    },100);
    
};

$ctrl.clotureCalculNotes = function(classCode,semID,ev)
{
var data = {classCode: classCode,semID:semID}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez cloturer ce Semestere?')
            .textContent('Cette opération va verouiller l\'accès à toute modification sur les UE de la classe sélectionnée')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.get('semMarkTransactionLock',config).then(
          function successCallback(response){
              $ctrl.markCalculationStatus = 1;
              $ctrl.selectedSem.markCalculationStatus = 1;
              toastr.success("Opéraction effectuée avec succès");
              
              

         },
        function errorCallback(response){
            toastr.error("Un erreure inattendue s'est produite");
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });        
}

$ctrl.sendResultBySMS = function(classCode,semID,ev)
{
var data = {classCode: classCode,semID:semID}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Envoie de SMS')
            .textContent('Cette opération va transmttre les resultats de chaque étudiants par SMS (MPC, %crédits validés, admis(e)/redouble)')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.get('sendResultBySMS',config).then(
          function successCallback(response){
              $ctrl.markCalculationStatus = 1;
              $ctrl.selectedSem.sendSmsStatus = 1;
              toastr.success("Opéraction effectuée avec succès");
              
              

         },
        function errorCallback(response){
             toastr.error("Un erreure inattendue s'est produite");
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });        
}

$ctrl.printMps = function(ev){
    
        var data = {sem_id: $ctrl.selectedSem.id,classe:$ctrl.selectedClasse.code};
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
          templateUrl: 'printmps/'+$ctrl.selectedClasse.code+'/'+$ctrl.selectedSem.id,
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
$ctrl.printDetailedMps = function(ev){
    
        var data = {sem_id: $ctrl.selectedSem.id,classe:$ctrl.selectedClasse.code,printingOption:$ctrl.printingOption};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };

        
          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printdetailedmps/'+$ctrl.selectedClasse.code+'/'+$ctrl.selectedSem.id+'/'+$ctrl.printingOption,
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


$ctrl.print7thYrMps = function(ev){
    
        var data = {sem_id: $ctrl.selectedSem.id,classe:$ctrl.selectedClasse.code};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };

        
          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printFinalYrMps/'+$ctrl.selectedClasse.code+'/'+$ctrl.selectedSem.id,
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

$ctrl.printTranscripts = function(ev){
    
        var data = {sem_id: $ctrl.selectedSem.id,classe:$ctrl.selectedClasse.code,printingOption:$ctrl.printingOption};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };

        
          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printTranscripts/'+$ctrl.selectedClasse.code+'/'+$ctrl.selectedSem.id+'/'+$ctrl.printingOption,
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
