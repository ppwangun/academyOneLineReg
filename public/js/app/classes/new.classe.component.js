'use strict';

angular.module('classes')
        .component('newClasse',{
            templateUrl: 'newclassetpl',
            controller: newclassesCtrl 
});
function newclassesCtrl($timeout,$http,$scope,$location,$mdDialog,$routeParams,toastr){
    var id;
    var $ctrl= this;
   // $scope.cycles = [];
    $ctrl.simulateQuery = false;
    $ctrl.isDisabled    = false;
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedItem= null;
    $ctrl.selectedDegrees = [];
    $ctrl.selectedSem= null;
    $ctrl.isUpdate = false;
    $ctrl.classe = {code:'',name:'',studyLevel: '',degreeId:'',cycleId: null,isCommonCore:false,isEndCycle:0,isEndDegreeTraining:0,isEndCommonCOre:0};
    $ctrl.cycle = null;
    $ctrl.level = null;
    $ctrl.isCycleRequired= false;
    $ctrl.classes = []; 
    $ctrl.trainingCycles = [];
    $ctrl.degrees = [];
    $ctrl.troncCommuns = [];
    $ctrl.semesters = [];
    $ctrl.querySearch   = querySearch;
    $ctrl.selectedItemChange = selectedItemChange;
  
    
 //collecte and load all the available classes of study  
 $ctrl.init = function(){

     $timeout(
     $http.get('degree').then(function(response){
         //convert all loaded data to lower case
        angular.forEach(response.data[0],function(item){

          $ctrl.degrees.push({id:item.id,code:item.code,value:item.name.toLowerCase(), name:item.name+"["+item.code+"]"}); 

        });
     }),500); 
     
    $timeout($http.get('searchCommonCoreTraining').then(
        function(response){
        $ctrl.troncCommuns = response.data[0];
    }),1000);  
            
     var id = $routeParams.id;
     if(id)
     {
                
                $ctrl.isUpdate = true;
                var data = {id: id};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading selected class information for update
                $timeout(
                        $http.get('classes',config).then(
                        function(response){
                            $ctrl.classe=response.data[0];
                            
                        }).then(function(){
                            
                            var data = {id: $ctrl.classe.degreeId};
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };      
                            $http.get('degree',config).then(function(response){
                                $ctrl.selectedItem = response.data[0];
                            });
                        }).then(function(){
                            
                            var data = {id: $ctrl.classe.code};
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };      
                            $http.get('assignsemtoclass',config).then(function(response){
                                $ctrl.semesters = response.data[0];
                            });
                        }),1000);

     }
     
 };
    //Loading classes of study asynchronously
    $ctrl.query = function(sem)
    {
       var  dataString = {id: sem},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('findsemester',config).then(function(response){
                   return response.data[0];
                });
     };
     
     $ctrl.associateSemToclasse = function()
     {
               var data = {class_id:$ctrl.classe.code,sem_id:$ctrl.selectedSem.id};
               
     
        $timeout(
              $http.post('assignsemtoclass',data).then(function(response){
               //$location.path("/classes") ; 
               if(response.data[0]==="DATA_SAVED") 
               {
                    toastr.success("Opération effectuée avec succès");
                    if ($ctrl.semesters.includes($ctrl.selectedSem) === false ) $ctrl.semesters.push($ctrl.selectedSem);
                }
               console.log($ctrl.semesters);
              }),500);
      
        
         
     }
     
   $ctrl.removeSemToClasse = function(sem_code,ev)
   {
        var data = {id: {sem:sem_code,class_id: $ctrl.classe.id }}; 
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
        $http.delete('assignsemtoclass',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
              var index = $ctrl.semesters.findIndex(x=>x.code=== sem_code);
              //remove the current object from the array
              $ctrl.semesters.splice(index,1);
              

         },
        function errorCallback(response){

            });
        }, function() {
     // $scope.status = 'You decided to keep your debt.';
        });
       
   }
 //classes level of study
 $ctrl.levelsStudy =[{id:'1',name:'Niveau 1'},{id:'2',name:'Niveau 2'},{id:'3',name:'Niveau 3'},{id:'4',name:'Niveau 4'}
 ,{id:'5',name:'Niveau 5'},{id:'6',name:'Niveau 6'},{id:'7',name:'Niveau 7'}]
 
 //Load corresponding training cycle when degree is selected
     function selectedItemChange(item) {
         if(item)
         {
             if(!$ctrl.classe.isCommonCore){ $ctrl.selectedDegrees[0]  = item; $ctrl.classe.coreDegree = item.id;}
        var data={id:item.id}
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        
        $http.get('cyclebydegree',config).then(function(response){
            $ctrl.trainingCycles=response.data[0]; 
           ($ctrl.trainingCycles.length >0 && $ctrl.classe.cycleId === null)? $ctrl.isCycleRequired=true :$ctrl.isCycleRequired=false ;
          

        });
    };
    }

  $ctrl.addCommonCoreDegree = function(degree){
      var index = $ctrl.selectedDegrees.findIndex(x => x.id === degree.id); console.log(index);
      if(index===-1)       $ctrl.selectedDegrees.push(degree);
  }
  
  $ctrl.removeDegreeFromCommnCore = function(degree){
 
    var index = $ctrl.selectedDegrees.findIndex(x => x.id === degree.id);
    //remove the current object from the array
    $ctrl.selectedDegrees.splice(index,1);      
  }
  
  $ctrl.resetCommonCoreDegree = function(){ 
      $ctrl.selectedDegrees = [];
  }
  
  $ctrl.addClasse = function(){
      $ctrl.classe.degrees = [];
      angular.forEach($ctrl.selectedDegrees, function(value,key){
       $ctrl.classe.degrees.push(value.id);   
      })
     // $ctrl.classe.degreeId = $ctrl.selectedDegrees; 
     
      $timeout(
              $http.post('classes',$ctrl.classe).then(function(){
               $location.path("/classes") ;  
              }),500);
      
  };  
  
  
  
      /*-------------------------------------------------------------------------
     *--------------------------- deleting degree-------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteDegree= function(ev)
      {
        var data = {id: $ctrl.classe.id}; 
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
        $http.delete('classes',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
              var index = $ctrl.classes.findIndex(x=>x.id === id);
              //remove the current object from the array
              $ctrl.classes.splice(index,1);
              $location.path("/classes");

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };
    
    
     /*--------------------------------------------------------------------------
     *--------------------------- updating degree-------------------------------
     *----------------------------------------------------------------------- */    
   $ctrl.updateClasse = function(){
        var data = {id: $ctrl.classe.id,data:$ctrl.classe}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
      // $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('classes',data,config).then(
            function successCallback(response){
                //reinitialise form
                $location.path("/classes");
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
   };
    
    
    
    
    
    
/**
     * Search for fielod of study ... use $timeout to simulate
     * remote dataservice call.
     */
    function querySearch (query) {
      
        
      var results = query ? $ctrl.degrees.filter( createFilterFor(query) ) : $ctrl.degrees,
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
        
        return (filiere.value.indexOf(lowercaseQuery) === 0);
      };

    }
};


