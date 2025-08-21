'use strict';

angular.module('teachingunit')
        .component('assignnewteachingunitDetails',{
            templateUrl: 'assignnewteachingunitpl',
            controller: newteachingunitCtrl 
});
function newteachingunitCtrl($timeout,$http,$location,$mdDialog,$routeParams,$scope,toastr){
    var id;
    var $ctrl= this;
   // $scope.cycles = [];
    $ctrl.simulateQuery = false;
    $ctrl.isDisabled    = false;
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedItem= null;
    $ctrl.isUpdate = false;
    $ctrl.ue = {code:'',name:'',credits: '',hours_vol:'',class_id: '',sem_id: '',cm_hrs:'',tp_hrs:'',td_hrs:''};
    $scope.subject = {code:'',name: '',sem: '',subjectWeight: '',credits: '',hours_vol:'',class_id: '',cm_hrs:'',tp_hrs:'',td_hrs:''};
    $ctrl.cycle = null;
    $ctrl.level = null;
    $ctrl.isCycleRequired= false;
    $ctrl.classes = []; 
    $ctrl.trainingCycles = [];
    $ctrl.degrees = [];
    $ctrl.semesters = [];
    $ctrl.querySearch   = querySearch;
    $scope.subjects = [];
    
   
    var id,ue_class_id;
   // $ctrl.selectedItemChange = selectedItemChange;
    
 //collecte and load all the available classes of study  
 $ctrl.init = function(){

    /* $timeout(

         $http.get('semester').then(function(response){
             $ctrl.semesters = response.data[0];
     }),500); */
     
     id = $routeParams.id;
     ue_class_id =$routeParams.ue_class_id;
     if(ue_class_id)
     {
                
                $ctrl.isUpdate = true;
                var data = {id: ue_class_id};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading selected class information for update
                $timeout(
                        $http.get('assignnewteachingunit',config).then(
                        function(response){
                            $ctrl.ue=response.data[0];
                            $ctrl.ue.sem_id = parseInt($routeParams.ue_sem_id);
                            
                            
                        }).then(function(){
                            var data = {id: $ctrl.ue.class_id};
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };      
                            $http.get('classes',config).then(function(response){
                                $ctrl.selectedItem = response.data[0];
                            }).then(function(){
      
                            var data= {id: id};
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };
                             $http.get('subjectbyue',config).then(function(response){
                                                $scope.subjects=response.data[0];
                                            });
                                
                            }).then(function(){
                            var data = {id: $routeParams.ue_sem_id};
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };                                 
                                         $http.get('semester',config).then(function(response){
                                         $ctrl.semester = response.data[0];
                                         });
                            }).then(function(){
                                         $http.get('semester').then(function(response){
                                         $ctrl.semesters = response.data[0];
                                         });
                            });
                        }),1000);

     }
     
 };
 
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

 //classes level of study
 $ctrl.levelsStudy =[{id:'1',name:'Niveau 1'},{id:'2',name:'Niveau 2'},{id:'3',name:'Niveau 3'},{id:'4',name:'Niveau 4'}
 ,{id:'5',name:'Niveau 5'},{id:'6',name:'Niveau 6'},{id:'7',name:'Niveau 7'}]
 


    $ctrl.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
    
  $ctrl.duplicateUe = function(){
      $ctrl.isUpdate = false;
  }

  $ctrl.addUe = function(){
      $ctrl.ue.class_id = $ctrl.selectedItem.id;
      $ctrl.ue.sem_id = $ctrl.semester.id;
      $timeout(
              $http.post('assignnewteachingunit',$ctrl.ue).then(
              function successCallback(){
                  toastr.success("opération effectuée avec succès");
               $location.path("/assignedteachingunit") ;  
              },
              function errorCallback(){
                  toastr.error("Une erreure inattendue s'est produite");
              }),500);
      
  };  
  
$ctrl.asignedSemToClasse = function(class_code){
    var data = {id: class_code};
    $ctrl.semesters = null;
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('assignsemtoclass',config).then(function(response){
        $ctrl.semesters = response.data[0];
    });
};   
  
      /*-------------------------------------------------------------------------
     *--------------------------- deleting degree-------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteUe= function(ev)
      {
       
        var data = {id: $ctrl.ue.ue_class_id}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer?')
            .textContent('Toutes les données associées à cette information seront perdues')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Supprimer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('assignnewteachingunit',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
            /*  var index = $ctrl.classes.findIndex(x=>x.id === id);
              //remove the current object from the array
              $ctrl.classes.splice(index,1);*/
            const output = {
                SUBJECT_HAS_COMPONENT_ERROR: 'Vous devez au préalable supprimer les composantes de l\'unité d\'enseignement',
                REGISTERED_STUDENT_ERROR: 'Impossible de supprimer: des étudiants sont inscrits sur cette unité d\'enseignement',
                EXAMS_EXISTS_ERROR: 'Impossible de supprimer: des évaluations existent sur cette unité d\'enseignement ',
                CONTRACTS_EXIST_ERROR: 'Impossible de supprimer: un contrat d\'enseignement existe sur cette unité d\'enseignement',
                BILL_EXIST_ERROR: 'Impossible de supprimer: une  ou plusieurs factures   existent sur cette unité d\'enseignement',
                PROGRESSION_EXIST_ERROR: 'Impossible de supprimer: des cours ont été réalisés sur cette unité d\'enseignement',
                SUBJECT_REGISTERED_STUDENT_ERROR: 'Impossible de supprimer: des étudiants sont inscrit  sur une ou plusieurs matières',
                SUBJECT_EXAMS_EXISTS_ERROR: 'Impossible de supprimer: des évaluations existes  sur une ou plusieurs matières ',
                SUBJECT_CONTRACTS_EXIST_ERROR: 'Impossible de supprimer: un contrat d\'enseignement existe sur une ou plusieurs matières',
                SUBJECT_BILL_EXIST_ERROR: 'Impossible de supprimer: une  ou plusieurs factures   existent sur une ou plusieurs matières',
                SUBJECT_PROGRESSION_EXIST_ERROR: 'Impossible de supprimer: des cours ont été réalisés sur une ou plusieurs matières',
            } 
           
              if(response.data[0]!="DONE")
              {

                        $mdDialog.show(
                          $mdDialog.alert()
                            .parent(angular.element(document.body))
                            .clickOutsideToClose(true)
                            .title('Erreur')
                            .textContent(output[response.data[0]])
                            .ariaLabel('Alert Dialog Demo')
                            .ok('OK')
                            .targetEvent(ev)
                        );

                  
              }
              else
              {
              
                toastr.success("Opération effectuée avec succès");
                $location.path("/assignedteachingunit");
              }

         },
        function errorCallback(response){
                toastr.error("Une erreure inattendue s'est produite");
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };
    
    
    /*--------------------------------------------------------------------------
    *--------------------------- updating teaching unit-------------------------
    *-------------------------------------------------------------------------*/    
   $ctrl.updateUe = function(){
       $ctrl.ue.class_id = $ctrl.selectedItem.id;
       $ctrl.ue.sem_id = $ctrl.semester.id
        var data = {id: $ctrl.ue.id,data:$ctrl.ue}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
      // $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('assignnewteachingunit',data,config).then(
            function successCallback(response){
                //reinitialise form
                $location.path("/assignedteachingunit");
                toastr.success("Opération effectué avec succès");
            },
            function errorCallback(response){
                toastr.error("Une erreur inattendue s'est produite");
           
            });
   };
    
    
    /*--------------------------------------------------------------------------
     *--------------------------- Adding subject--------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.addSubject= function(ev) {
        $scope.isUpdate= false;
        $scope.subject = {code:'',name:'',credits: '',hours_vol:'',cm_hrs:'',tp_hrs:'',td_hrs:'',ue_classe_id: ue_class_id, ue_id:id};
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/subjectformtpl.html',
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
     *--------------------------- Deleting curriculum---------------------------
     *----------------------------------------------------------------------- */  
   $scope.deleteSubject = function(subject,ev)
      {
      
      var data = {id: {subject_id:subject.id,smester_id:$ctrl.ue.sem_id}}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer?')
            .textContent('Toutes les données associées à cette information seront perdues')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Supprimer')
            .cancel('Annuler');      
      
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('subject',config).then(
          function successCallback(response){      

            const output = {
                SUBJECT_HAS_COMPONENT_ERROR: 'Vous devez au préalable supprimer les composantes de l\'matière',
                REGISTERED_STUDENT_ERROR: 'Impossible de supprimer: des étudiants sont inscrits sur cette matière',
                EXAMS_EXISTS_ERROR: 'Impossible de supprimer: des évaluations existent sur cette matière ',
                CONTRACTS_EXIST_ERROR: 'Impossible de supprimer: un contrat d\'enseignement existe sur cette matière',
                BILL_EXIST_ERROR: 'Impossible de supprimer: une  ou plusieurs factures   existent sur cette matière',
                PROGRESSION_EXIST_ERROR: 'Impossible de supprimer: des cours ont été réalisés sur cette matière',
                SUBJECT_REGISTERED_STUDENT_ERROR: 'Impossible de supprimer: des étudiants sont inscrit  sur une ou plusieurs matières',
                SUBJECT_EXAMS_EXISTS_ERROR: 'Impossible de supprimer: des évaluations existes  sur une ou plusieurs matières ',
                SUBJECT_CONTRACTS_EXIST_ERROR: 'Impossible de supprimer: un contrat d\'enseignement existe sur une ou plusieurs matières',
                SUBJECT_BILL_EXIST_ERROR: 'Impossible de supprimer: une  ou plusieurs factures   existent sur une ou plusieurs matières',
                SUBJECT_PROGRESSION_EXIST_ERROR: 'Impossible de supprimer: des cours ont été réalisés sur une ou plusieurs matières',
            } 
           
              if(response.data[0]!="DONE")
              {

                        $mdDialog.show(
                          $mdDialog.alert()
                            .parent(angular.element(document.body))
                            .clickOutsideToClose(true)
                            .title('Erreur')
                            .textContent(output[response.data[0]])
                            .ariaLabel('Alert Dialog Demo')
                            .ok('OK')
                            .targetEvent(ev)
                        );

                  
              }
              else
              {
              
                toastr.success("Opération effectuée avec succès");
              
              }

         },
        function errorCallback(response){
            toastr.error("Une erreure inattendue s'est produite")
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };
    /*--------------------------------------------------------------------------
     *--------------------------- updating subject -----------------------------
     *----------------------------------------------------------------------- */
    $ctrl.showSubject = function(subject,ev){
        $scope.isUpdate= true;
       
        $scope.subject.id = subject.id;
        $scope.subject.name = subject.subjectName;
        $scope.subject.code = subject.subjectCode; 
        $scope.subject.credits = subject.subjectCredits;
        $scope.subject.subjectWeight = subject.subjectWeight;
        $scope.subject.hours_vol = subject.subjectHours;
        $scope.subject.cm_hrs = subject.subjectCmHours;
        $scope.subject.td_hrs = subject.subjectTdHours;
        $scope.subject.tp_hrs = subject.subjectTpHours;
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/subjectformtpl.html',
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
      
    //$scope.subject = {code:'',name:'',credits: '',hours_vol:'',cm_hrs:'',tp_hrs:'',td_hrs:'',ue_classe_id: ue_class_id, ue_id:id};


 $scope.createSubject = function(){
     
     $http.post('subject',$scope.subject).then(function successCallback(response){
         //$scope.subject=response.data[0];
        $scope.subject.subjectName = $scope.subject.name;
        $scope.subject.subjectCode = $scope.subject.code; 
        $scope.subject.subjectCredits = $scope.subject.credits;
        $scope.subject.subjectHours = $scope.subject.hours_vol;
        $scope.subject.subjectCmHours = $scope.subject.cm_hrs;
        $scope.subject.subjectTdHours = $scope.subject.td_hrs;
        $scope.subject.subjectTpHours = $scope.subject.tp_hrs;
         
         $scope.subjects.push($scope.subject);
         toastr.success('Opération effectuée avec succès');
         $mdDialog.cancel();
         
     }, function errorCallback(response){
        toastr.error('Erreur', "Une erreur inattendue s'est produite");
     });
 };
 
 $scope.updateSubject = function(){
        $scope.subject.sem = $ctrl.ue.sem_id;
        var data = {id: $scope.subject.id,data:$scope.subject}; 
        var id = $scope.subject.id;
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
       
       $http.put('subject',data,config).then(
            function successCallback(response){
                //reinitialise form
                //$location.path("/degree");
                toastr.success("Opération effectuée avec succès")
                $mdDialog.cancel();
            },
            function errorCallback(response){
                toastr.error("Une erreur inattendue s'est produite");
                $mdDialog.cancel();
            });
     
 };
 

      

  }
  
      $scope.cancel = function() {
      //$scope.faculties=[];
      
      $scope.subject = {code:'',name:'',credits: '',hours_vol:'',class_id: '',cm_hrs:'',tp_hrs:'',td_hrs:''};
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };   
    
    
/**
     * Search for fielod of study ... use $timeout to simulate
     * remote dataservice call.
     */
    function querySearch (query) {
      
        
      var results = query ? $ctrl.classes.filter( createFilterFor(query) ) : $ctrl.classes,
          deferred;
      if ($ctrl.simulateQuery) {
        deferred = $q.defer();
        $timeout(function () { deferred.resolve( results ); }, Math.random() * 1000, false);
        return deferred.promise;
      } else {
        return results;
      }
    }

    /**
     * Create filter function for a query string
     */
    function createFilterFor(query) {
      var lowercaseQuery = angular.lowercase(query);

      return function filterFn(filiere) { 
        
        return (filiere.name.indexOf(lowercaseQuery) === 0);
      };

    }
};


