'use strict';

angular.module('users')
        .component('newUser',{
            templateUrl: 'newusertpl',
            controller: newuserCtrl 
});
function newuserCtrl($timeout,$http,$location,$mdDialog,$routeParams,toastr){
    var id;
    var $ctrl= this;
   // $scope.cycles = [];

    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedItem= null;
    $ctrl.selected= [];
    $ctrl.isUpdate = false;
    $ctrl.classe = {code:'',name:'',studyLevel: '',degreeId:'',cycleId: null,isEndCycle:0,isEndDegreeTraining:0};
    $ctrl.cycle = null;
    $ctrl.level = null;
    $ctrl.isCycleRequired= false;
    $ctrl.classes = []; 
    $ctrl.groups = [];
    $ctrl.users = [];
    $ctrl.errorMsg = {showAlert:false,error:false,msg:""};
    $ctrl.user = {email:'',status:1};

    $ctrl.querySearch   = querySearch;
   
  $ctrl.exists = function (item,list) {
    //var item = $ctrl.loadedPermissions.find(item=>item.id==item1.id)
    return list.indexOf(item) > -1;
  }; 
  $ctrl.toggle = function (item,list) {
    //var item = $ctrl.loadedPermissions.find(item=>item.id==item1.id)
    var idx = list.indexOf(item);
    if (idx > -1) {
      list.splice(idx, 1);
    }
    else {
      list.push(item);
    }
  };  
    
 //collecte and load all the available classes of study  
 $ctrl.init = function(){
                 //Loading all available groups(roles)
                $timeout(
                    $http.get('groupoperation').then(
                    function(response){
                        $ctrl.groups=response.data[0];
                            
                }),1000);                            

 
     var id = $routeParams.user_id;
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
                        $http.get('adduser',config).then(
                        function(response){
                            $ctrl.user=response.data[0];

                            
                            
                        }).then(function(){
                            
                            var data = {id: $ctrl.user.id};
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };      
                            $http.get('assignclasstouser',config).then(function(response){
                                $ctrl.classes = response.data[0];
                            });
                        }).then(function(){

                        //Loading all available permissions
                        $timeout(
                            $http.get('groupoperation',config).then(
                            function(response){
                                //Loadding groups associated with the user
                                $ctrl.loadedGroups = response.data[0];
                                angular.forEach($ctrl.loadedGroups, function(value, key) {
                                  //From loaded permissions, we fing permission object based on id.
                                    var item = $ctrl.groups.find(item=>item.id==value.id)
                                    $ctrl.selected.push(item);
                                    console.log($ctrl.selected);
                                });

                            
                        }),1000);                            
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
     
     $ctrl.associateClasseToUser= function()
     {
               var data = {class_id:$ctrl.selectedClasse.id,user_id:$ctrl.user.id};
               
     
        $timeout(
              $http.post('assignclasstouser',data).then(function(response){

                    toastr.success("Opération effectuée avec succès");
                    if ($ctrl.classes.includes($ctrl.selectedClasse) === false ) $ctrl.classes.push($ctrl.selectedClasse);

              }),500);
      
        
         
     }
     
   $ctrl.removeClasseTouser = function(classe_id,user_id,ev)
   {
        var data = {id: {classe_id: classe_id,user_id: user_id}}; 
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
        $http.delete('assignclasstouser',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
              var index = $ctrl.classes.findIndex(x=>x.id=== classe_id);
              //remove the current object from the array
              $ctrl.classes.splice(index,1);
              

         },
        function errorCallback(response){

            });
        }, function() {
     // $scope.status = 'You decided to keep your debt.';
        });
       
   }


  $ctrl.addUser= function(){
      $ctrl.user["roles"] = $ctrl.groups;
      var data = {"data":$ctrl.user}
      $timeout(
              $http.post('adduser',$ctrl.user).then(
              function successCallback(response){
                  $ctrl.user.id = response.data[0];
                  toastr.success("Opération effectuée avec succès");
               //$location.path("/usermanagement") ;  
              },
             function errorCallback(response){
                 toastr.error("Une erreure inattendue s'est produite: l'adresse email existe");
            }    ),500);
      
  }; 
  
      /*--------------------------------------------------------------------------
     *--------------------------- updating group-------------------------------
     *----------------------------------------------------------------------- */    
   $ctrl.updateUser= function(){
       $ctrl.user["roles"] = $ctrl.groups;
       
        var data = {id: $ctrl.user.id,data:$ctrl.user}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
      // $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('adduser',data,config).then(
            function successCallback(response){
                $ctrl.errorMsg=response.data[0];
                if($ctrl.errorMsg.error) toastr.error("Une erreur inattendue est survenue");
                else toastr.success("Opération effectuée avec succès")
                console.log($ctrl.errorMsg)
                //reinitialise form
               // $location.path("/classes");
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
   };
   
   
  $ctrl.validateEqual =function (value) {
          var valid = (value === scope.$eval(attrs.validateEquals));
          ngModelCtrl.$setValidity('equal', valid);
          return valid ? value: 'undefined';
      }
  
  
  
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


