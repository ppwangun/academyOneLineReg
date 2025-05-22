angular.module("degree").component("newDegree",{
    templateUrl:"newdegreetpl",
    controller: degreeCtrl
});

function degreeCtrl($http,$q,$timeout,$routeParams,$mdDialog,$location,$scope,toastr)
{
    var id;
    var $ctrl= this;
    $scope.cycles = [];
    $ctrl.simulateQuery = false;
    $ctrl.isDisabled    = false;
   // $ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedItem= null;
    $ctrl.selectedItem2= null;
    $ctrl.isUpdate = false;
    $ctrl.degree = {code:'',name:'',status: 1,isCoreCurriculum: false,filiere_id:''};
    $ctrl.degrees = []; 
    $ctrl.filieres = [];
    $ctrl.querySearch   = querySearch;
    $ctrl.querySearch2 = querySearch2;
    $ctrl.selectedItemChange = selectedItemChange;
    $ctrl.searchTextChange   = searchTextChange;
    $scope.cycle = {classesGenerationStatus: true};
    


    //$ctrl.newState = newState;

    $ctrl.init = function(){
//loading filed of study information to fill autocomplete field
            $http.get('filiere').then(function(response){
                angular.forEach(response.data[0],function(item){

                  $ctrl.filieres.push({id:item.id,code:item.code, name:item.name.toLowerCase()+"["+item.code+"]"}); 

                });
            });
            
//loading degrees information to fill autocomplete field
            $http.get('degree').then(function(response){
                angular.forEach(response.data[0],function(item){

                  $ctrl.degrees.push({id:item.id,code:item.code, name:item.name.toLowerCase()+"["+item.code+"]"}); 

                });
            });  
            
            
            $timeout($http.get('cycleFormation').then(
                function(response){
                $scope.cyclesFormation = response.data[0];
            }),1000);  

       
            
            id = $routeParams.id; 
            if(id)
            {
                $ctrl.isUpdate = true;
                var data = {id: id};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading selected degree information for update

              
                    $http.get('degree',config).then(function(response){
                        $ctrl.degree = response.data[0]
                
                        var data = {id: $ctrl.degree.fil_id};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };      
                        $timeout(
                            $http.get('filiere',config).then(function(response){
                                  $ctrl.fil = response.data[0];
                                  $ctrl.selectedItem={id:$ctrl.fil.id,code:$ctrl.fil.code,name:$ctrl.fil.name};
                        }),3000);                    
                    })
                    
                 
                    $http.get('cyclebydegree',config).then(function(response){
                    $scope.cycles = response.data[0]; 
                    })                    
                    

              
        $scope.cycle={name:"",code:"",duration:"",degree_id:id};
 
       
          

            }
              
        $scope.isUpdate = false;
//
    };
    /*--------------------------------------------------------------------------
     *----------------------------- adding degree-------------------------------
     *----------------------------------------------------------------------- */    
   $ctrl.addDegree = function(){
       $ctrl.degree.fil_id=$ctrl.selectedItem.id;
       $http.post('degree',$ctrl.degree).then(
            function successCallback(response){
                //reinitialise form
                $ctrl.selectedItem = null;
                
                $ctrl.degrees = response.data[0];
                $location.path("/degree");
            },
            function errorCallback(response){
               toastr.error("une erreur inattendue s'est produite")
           
            });
   };
    /*--------------------------------------------------------------------------
     *--------------------------- updating degree-------------------------------
     *----------------------------------------------------------------------- */    
   $ctrl.updateDegree = function(){
        var data = {id: id,data:$ctrl.degree}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
       $ctrl.degree.fil_id=$ctrl.selectedItem.id;
       $http.put('degree',data,config).then(
            function successCallback(response){
                //reinitialise form
                $location.path("/degree");
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
   };   
    /*-------------------------------------------------------------------------
     *--------------------------- deleting degree-------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteDegree= function(ev)
      {
        var data = {id: id}; 
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
        $http.delete('degree',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
              var index = $ctrl.degrees.findIndex(x=>x.id === id);
              //remove the current object from the array
              $ctrl.degrees.splice(index,1);
              $location.path("/degree");

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };

    /*--------------------------------------------------------------------------
     *--------------------------- Adding training curriculum--------------------
     *----------------------------------------------------------------------- */
    $ctrl.addCurriculum = function(ev) {
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/curriculumformtpl.html',
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
          templateUrl: 'js/my_js/globalconfig/curriculumformtpl.html',
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
    // ******************************
    // Internal methods
    // ******************************

    /**
     * Search for fielod of study ... use $timeout to simulate
     * remote dataservice call.
     */
    function querySearch (query) {
      
        
      var results = query ? $ctrl.filieres.filter( createFilterFor(query) ) : $ctrl.filieres,
          deferred;
      if ($ctrl.simulateQuery) {
        deferred = $q.defer();
        $timeout(function () { deferred.resolve( results ); }, Math.random() * 1000, false);
        return deferred.promise;
      } else {
        return results;
      }
    }
    function querySearch2 (query) {
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
    
    function searchTextChange(text) {
      $log.info('Text changed to ' + text);
    }

    function selectedItemChange(item) {
      $log.info('Item changed to ' + JSON.stringify(item));
    }

    /**
     * Build `states` list of key/value pairs
     */



    /**
     * Create filter function for a query string
     */
    function createFilterFor(query) {
      var lowercaseQuery = angular.lowercase(query);

      return function filterFn(filiere) { 
        
        return (filiere.name.indexOf(lowercaseQuery) === 0);
      };

    }
    
      /*--------------------------------------------------------------------------
     *--------------------------- loading all specilaities  by field of study   ---------------------
     *----------------------------------------------------------------------- */
    $ctrl.searchSpeByFil = function(id){
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
//Dialog Controller
  function DialogController($scope, $mdDialog) {
      
      $vm = this;
 //$scope.cycle = {...classesGenerationStatus};
 $scope.cycle.classesGenerationStatus = true;
 $scope.createCycle = function(){
     
     $http.post('cycle',$scope.cycle).then(function successCallback(response){
         $scope.cycles=response.data[0];
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
 

      
   /* $scope.faculty = {name:'', code:'',respo:'',created_date:'',school_id: $ctrl.school.id};       
    $scope.createFaculty = function() {
        $http.post('faculty',$scope.faculty)
            .then(function succesCallback(response)
            {
                 //$ctrl.school=response.data[0];
                console.log(response.data[0]);
                $ctrl.faculties= response.data[0];
                $mdDialog.cancel();
                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                 
                  alert(response.data);
                });       
    };*/
  }
  
      $scope.cancel = function() {
      //$scope.faculties=[];
      
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };
    
    
    

  
}





