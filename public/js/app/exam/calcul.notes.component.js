'use strict';

angular.module('exam')
        .component('calculNotes',{
            templateUrl: 'calculnotestpl',
            controller: calculnotesCtrl 
});
function calculnotesCtrl($timeout,$http,$scope, toastr,$mdDialog){

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
    $ctrl.isActivatedSubjectSelect = false;
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
    $scope.tableExamShow = 1;
    $scope.tableModuleShow = 0;
    $ctrl.registeredStd = [];
    $ctrl.regStd = [];
    $ctrl.subjects = [];
    $ctrl.selectedUe = null;
    $ctrl.selectedSubject = null;
    $ctrl.examCode = null;
    $ctrl.markCalculationStatus = 0;
    $ctrl.isModularComputation = 0;
    $ctrl.isModuleComputation = 0;
   
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

$ctrl.activateSubjectSelect = function(){
   $ctrl.isActivatedSubjectSelect = true;
 

};

$ctrl.selectedItemChange = function(classe){
    $ctrl.sem = null;
   $ctrl.selectedUe = null;
   $ctrl.selectedSem = null;
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
    $ctrl.subjects = [];
    $ctrl.selectedSubject = null;
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
            $ctrl.isActivatedMatiereSelect = true;
            $ctrl.isActivatedSubjectSelect = true;
            $ctrl.isMatiereRequired = true;
        }),1000);
  };
 
  $ctrl.loadSubjects = function(ue){
    $ctrl.ues = null;
    var data = {id: ue.id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };
    //Loading selected class information for update
    $timeout(
        $http.get('subjectbyue',config).then(

        function(response){
            $ctrl.subjects=response.data[0];
            $ctrl.isActivatedMatiereSelect = true;
            $ctrl.isActivatedSubjectSelect = true;
            $ctrl.isMatiereRequired = true;

        }),1000);
 };
 //Load Exams based on selected UE code

 $ctrl.loadExams = function(ueId,subjectID){
      
        var i;
        var data = { id: {classe_id:$ctrl.selectedClasse.id,sem_id:$ctrl.selectedSem.id,ue_id: ueId,subject_id:subjectID}};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        }; 
        $scope.tableExamShow = 1;
        $scope.tableModuleShow = 0;
        $http.get('calculmp',config).then(function(response){
            $ctrl.exams = response.data[0];
            for(i=0;i<$ctrl.exams.length;i++)
            {
              $ctrl.exams[i].num=i+1;
              $ctrl.exams[i].date = $ctrl.exams[i].date.date;
            }
            $ctrl.isActivatedMatiereSelect = true;
            $ctrl.isActivatedSubjectSelect = true;
            $ctrl.isMatiereRequired = true;            
        });
   };

//Load exam statues of all subject from the module
 $ctrl.loadExamsPerModuleStatus = function(ueId){
      
        var i;
        var data = { id: {classe_id:$ctrl.selectedClasse.id,sem_id:$ctrl.selectedSem.id,ue_id: ueId,isModular:1}};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        }; 
        $scope.tableModuleShow = 1;
        $scope.tableExamShow = 0;
        $http.get('calculmp',config).then(function(response){
            $ctrl.exams = response.data[0];
            for(i=0;i<$ctrl.exams.length;i++)
            {
              $ctrl.exams[i].num=i+1;
              
            }
        });
   };
   
 //Looad all student who are registered to the subject
 //Load all subjects associated withe the UE as well
 $ctrl.loadStd = function(selectedUeId){
        $ctrl.isActivatedMatiereSelect = false;
        $ctrl.isMatiereRequired = false;
        $ctrl.isActivatedSubjectSelect = false;
        //var id = {id: selectedUeId,sem_id:$ctrl.selectedSem.id};

        var data = {id: selectedUeId};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        $http.get('stdregisteredtosubject',config).then(function(response){
            $ctrl.registeredStd = response.data[0];
            if($ctrl.registeredStd.length>0)
            {
                for(i=0;i<$ctrl.registeredStd.length;i++)
                {
                  $ctrl.registeredStd[i].num=i+1;
                  $ctrl.registeredStd[i].note = 0;
                }
            }
        }).then(function(){
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };   

                $http.get('subjectbyue',config).then(function(response){
                    $ctrl.subjects = response.data[0];
                    if($ctrl.subjects.length>0)
                    {
                        $ctrl.isActivatedMatiereSelect = true;
                        $ctrl.isActivatedSubjectSelect = true;
                        $ctrl.isMatiereRequired = true;
                        
                    }
                });
        });

        };

 //Looad all student who are registered to the subject
 //Load all subjects associated withe the UE as well
 $ctrl.loadStdReg = function(selectedUeId){
        $ctrl.isActivatedMatiereSelect = false;
        $ctrl.isMatiereRequired = false;
        $ctrl.isActivatedSubjectSelect = false;
        //var id = {id: selectedUeId,sem_id:$ctrl.selectedSem.id};

        var data = {id: {ueId:selectedUeId,subjectId:$ctrl.selectedSubject.id}};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        $http.get('stdregisteredtosubject',config).then(function(response){
            $ctrl.registeredStd = response.data[0];
            if($ctrl.registeredStd.length>0)
            {
                for(i=0;i<$ctrl.registeredStd.length;i++)
                {
                  $ctrl.registeredStd[i].num=i+1;
                  $ctrl.registeredStd[i].note = 0;
                }
            }
        })};
        
        
$ctrl.calculNotes = function(ev){
    if($ctrl.selectedSubject)
        var data = {class_id:$ctrl.selectedClasse.id, sem_id:$ctrl.selectedSem.id, ue_id:$ctrl.selectedUe.id,subject_id:$ctrl.selectedSubject.id,isMarkAggregation: 1};
    else    var data = {class_id:$ctrl.selectedClasse.id, sem_id:$ctrl.selectedSem.id, ue_id:$ctrl.selectedUe.id};
    $timeout(function(){
        $http.post('calculmp',data).then(function(response){
            
            if(response.data[0] === "ERROR_NO_CC_OR_EXAM_DONE")
            {
                    $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('Erreur lors du calcul des notes')
                      .textContent("Rassurez vous qu 'au moins un CC et un EXAM ont été réalisés par unité d'enseignement ou enseignment et que toutes les notes sont enrégistrées\n")
                      .ariaLabel('Alert Dialog Demo')
                      .ok('Fermer!')
                      .targetEvent(ev)
                  );
                
                return;
            }
            else if(response.data[0] === "ERROR_PED_REGISTRATION")
            {
                    $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('Erreur lors du calcul des notes')
                      .textContent("Erreur sur inscription pédagogique\n")
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
                $ctrl.isActivatedMatiereSelect = true;
                $ctrl.isActivatedSubjectSelect = true;
                $ctrl.isMatiereRequired = true;                
            }            
        })
    },100);
    
};

$ctrl.moduleMarkAggregation = function(ev){

$ctrl.isModuleComputation = 1
    var data = {class_id:$ctrl.selectedClasse.id, sem_id:$ctrl.selectedSem.id, ue_id:$ctrl.selectedUe.id,isMarkAggregation: 1};
    $timeout(function(){
        $http.post('calculmp',data).then(function(response){
            
            if(response.data[0] === "ERROR_NO_CC_OR_EXAM_DONE")
            {
                    $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('Erreur lors du calcul des notes')
                      .textContent("Rassurez vous qu 'au moins un CC et un EXAM ont été réalisés par unité d'enseignement ou enseignment et que toutes les notes sont enrégistrées\n")
                      .ariaLabel('Alert Dialog Demo')
                      .ok('Fermer!')
                      .targetEvent(ev)
                  );
                
                return;
            }
            toastr.success("Opération effectuée avec succès");
            $ctrl.regStd = response.data[0];
            if($ctrl.regStd.length>0)
            {
                $ctrl.isActivatedMatiereSelect = true;
                $ctrl.isActivatedSubjectSelect = true;
                $ctrl.isMatiereRequired = true;                
            }            
        })
    },100);    
}

$ctrl.clotureCalculNotes = function(coshsID,ev)
{
var data = {id: coshsID}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez cloturer cette UE?')
            .textContent('La clôture du calcul des notes sur une UE verouille l\'accès à toute modification sur cette note')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('ueMarkTransactionLock',config).then(
          function successCallback(response){
              $ctrl.markCalculationStatus = 1;
              $ctrl.selectedUe.mark_calculation_status = 1;
              toastr.success("Opéraction effectuée avec succès");
              
              

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });        
}

$ctrl.printNotes = function(ev){
    
        var data = {id: $ctrl.selectedUe.id};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };

          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printpvindiv/'+$ctrl.selectedUe.id+'/'+$ctrl.selectedClasse.id+'/'+$ctrl.selectedSem.id,
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

$ctrl.printNotesFailures = function(ev){
    
        var data = {id: $ctrl.selectedUe.id};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };

          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printpvfailures/'+$ctrl.selectedUe.id+'/'+$ctrl.selectedClasse.id+'/'+$ctrl.selectedSem.id,
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

$ctrl.printSubjectMarkReport = function(ev){
    
        var data = {id: $ctrl.selectedUe.id};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };

          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printSubjectMarkReport/'+$ctrl.selectedUe.id+'/'+$ctrl.selectedSubject.id+'/'+$ctrl.selectedClasse.id+'/'+$ctrl.selectedSem.id,
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

$ctrl.printModuleMarkReport = function(ev){
    
        var data = {id: $ctrl.selectedUe.id};
        var i;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };

          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printModuleMarkReport/'+$ctrl.selectedUe.id+'/'+$ctrl.selectedClasse.id+'/'+$ctrl.selectedSem.id,
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

$ctrl.deliberation = function(ev){
    if($ctrl.selectedSubject)
    var  subjectCode = $ctrl.selectedSubject.subjectCode;
    else var subjectCode =  $ctrl.selectedUe.code;
    // Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez procèder à la delibération de l\'UE: '+subjectCode)
            .textContent('Rassurez vous d\'avoir préalement défini les conditions de délibération applicables')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
     $mdDialog.show(confirm).then(function() {
        if($ctrl.selectedSubject)
            var templateUrl = 'startDeliberation/'+$ctrl.selectedClasse.id+'/'+$ctrl.selectedUe.id+'/'+$ctrl.selectedSem.id+'/'+$ctrl.selectedSubject.id; 
        else var templateUrl = 'startDeliberation/'+$ctrl.selectedClasse.id+'/'+$ctrl.selectedUe.id+'/'+$ctrl.selectedSem.id;

          $mdDialog.show({
          controller: DialogController,
          templateUrl: templateUrl,
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        }).then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        }); 
        
         //Dialog Controller
        function DialogController($scope, $mdDialog,readFileData) {
         
            $scope.saveDelib = function(ueID,subjectID,semID,classeID)
            {
                var  dataString = {ueID: ueID,subjectId:subjectID,semID:semID,classeID:classeID,isModularComputation:$ctrl.isModularComputation},
                config = {
                params: dataString,
                headers : {'Accept' : 'application/json; charset=utf-8'}
                };
                $http.get('applyDelibCondition',config).then(function(response){
                      
                    toastr.success("Opperation effectuer avac succès");
                    $ctrl.registeredStd = response.data[0];
                    if($ctrl.registeredStd.length>0)
                    {
                        for(var i=0;i<$ctrl.registeredStd.length;i++)
                        {
                          $ctrl.registeredStd[i].num=i+1;
                          $ctrl.registeredStd[i].note = 0;
                        }
                    }                   
                       $mdDialog.hide();
                    });
            }
            
        $scope.cancel = function() {
            $mdDialog.cancel();
        };

        $scope.answer = function(answer) {
          $mdDialog.hide(answer);
        };   

        };
    
})
};
};


