'use strict';

angular.module('teachingunit')
        .component('newTeachingunit',{
            templateUrl: 'newteachingunitpl',
            controller: newteachingunitCtrl 
});
function newteachingunitCtrl($timeout,$http,$location,$mdDialog,$routeParams,$scope){
    var id;
    var $ctrl= this;
   // $scope.cycles = [];
    $ctrl.simulateQuery = false;
    $ctrl.isDisabled    = false;
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedItem= null;
    $ctrl.isUpdate = false;
    $ctrl.ue = {code:'',name:'',credits: '',hours_vol:'',class_id: '',cm_hrs:'',tp_hrs:'',td_hrs:''};
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

     $timeout(

         $http.get('semester').then(function(response){
             $ctrl.semesters = response.data[0];
        }),500); 
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
                        $http.get('teachingunit',config).then(
                        function(response){
                            $ctrl.ue=response.data[0];
                            
                        }).then(function(){
                            var data = {id: $ctrl.ue.class_id};
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };      
                            $http.get('classes',config).then(function(response){
                                $ctrl.selectedItem = response.data[0];
                            });
                        }),1000);
                      
        var data = {ue_class_id: ue_class_id,ue_id:id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
         $http.get('subject',config).then(function(response){
                            $scope.subjects=response.data[0];
                        });

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
 
 //Load corresponding training cycle when degree is selected
 /*    function selectedItemChange(item) {
         if(item)
         {
         var data={id:item.id}
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        
        $http.get('cyclebydegree',config).then(function(response){
            $ctrl.trainingCycles=response.data[0];
           (response.data[0].length>0 && $crtl.cycle.cycle_id===null)? $ctrl.isCycleRequired=true :$ctrl.isCycleRequired=false ;
          

        });
    };
    }*/

  $ctrl.addUe = function(){
      $ctrl.ue.class_id = $ctrl.selectedItem.id;
      $timeout(
              $http.post('teachingunit',$ctrl.ue).then(function(){
               $location.path("/teachingunit") ;  
              }),500);
      
  };  
  
  
  
      /*-------------------------------------------------------------------------
     *--------------------------- deleting degree-------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteUe= function(ev)
      {
       
        var data = {id: ue_class_id}; 
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
        $http.delete('teachingunit',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
            /*  var index = $ctrl.classes.findIndex(x=>x.id === id);
              //remove the current object from the array
              $ctrl.classes.splice(index,1);*/
              $location.path("/teachingunit");

         },
        function errorCallback(response){

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
        var data = {id: $ctrl.ue.id,data:$ctrl.ue}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
      // $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('teachingunit',data,config).then(
            function successCallback(response){
                //reinitialise form
                $location.path("/teachingunit");
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
   };
    
    
    /*--------------------------------------------------------------------------
     *--------------------------- Adding subject--------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.addSubject= function(ev) {
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
   $scope.deleteCycle = function(cycle,ev)
      {
      
      var data = {id: cycle.id}; 
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
        $http.delete('cycle',config).then(
          function successCallback(response){
              //check the index of the current object in the array
              
              var index = $scope.cycles.findIndex(x => x.id === cycle.id)
              //remove the current object from the array
              $scope.cycles.splice(index,1);

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };
    /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    $scope.showCurriculum = function(cycle,ev){
        $scope.isUpdate= true;
       
        $scope.cycle = cycle;
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
      
$scope.subject = {code:'',name:'',credits: '',hours_vol:'',cm_hrs:'',tp_hrs:'',td_hrs:'',ue_classe_id: ue_class_id, ue_id:id};


 $scope.createSubject = function(){
     
     $http.post('subject',$scope.subject).then(function successCallback(response){
         $scope.subject=response.data[0];
         $mdDialog.cancel();
         
     }, function errorCallback(response){
        alert(" Une erreur inattendue s'est produite") ;
     });
 };
 
 $scope.updateCycle = function(){
        var data = {id: $scope.cycle.id,data:$scope.cycle}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
       $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('cycle',data,config).then(
            function successCallback(response){
                //reinitialise form
                //$location.path("/degree");
                $mdDialog.cancel();
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
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
      var lowercaseQuery = query.toLowerCase();;

      return function filterFn(filiere) { 
        
        return (filiere.name.indexOf(lowercaseQuery) === 0);
      };

    }
};


