'use strict';

angular.module('users')
        .component('newGroup',{
            templateUrl: 'newgrouptpl',
            controller: newuserCtrl 
});
function newuserCtrl($timeout,$http,$location,$mdDialog,$routeParams,toastr){
    var id;
    var $ctrl= this;
   // $scope.cycles = [];

    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.permissions= [];
    $ctrl.selectedSem= null;
    $ctrl.isUpdate = false;
    $ctrl.classe = {code:'',name:'',studyLevel: '',degreeId:'',cycleId: null,isEndCycle:0,isEndDegreeTraining:0};
    $ctrl.cycle = null;
    $ctrl.level = null;
    $ctrl.isCycleRequired= false;
    $ctrl.classes = []; 
    $ctrl.loadedPermissions = [];
    $ctrl.users = [];
    $ctrl.group = null;
    $ctrl.selected = [];

  
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
     
        $timeout(
               $http.get('permissions',config).then(
               function(response){
                   $ctrl.permissions = response.data[0];

               }),1000);
     var id = $routeParams.group_id;
     if(id)
     {
                
                $ctrl.isUpdate = true;
                var data = {id: id};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading all available permissions
                $timeout(
                        $http.get('permissions',config).then(
                        function(response){
                            $ctrl.group=response.data[0];
                            $ctrl.loadedPermissions = response.data[0]["permissions"];
                            angular.forEach($ctrl.loadedPermissions, function(value, key) {
                              //Frome loaded permissions, we fing permission object based on id.
                                var item = $ctrl.permissions.find(item=>item.id==value.id)
                                $ctrl.selected.push(item);
                                console.log($ctrl.selected);
                            });
                            
                            
                        }),1000);

     }
     
 };

     
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


  $ctrl.addGroup= function(){
      var data = {"group":$ctrl.group,"permissions":$ctrl.permissions};
      $timeout(
              $http.post('groupoperation',data).then(
              function successCallback(response){
                  $ctrl.group.id = response.data[0];
                  $ctrl.isUpdate = true;
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
   $ctrl.updateGroup= function(){
       $ctrl.group["permissions"] = $ctrl.permissions;
        var data = {id: $ctrl.group.id,data:$ctrl.group}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
      // $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('groupoperation',data,config).then(
            function successCallback(response){
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
    
      $ctrl.deleteGroup= function(ev)
      {
        var data = {id: $ctrl.group.id}; 
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
        $http.delete('groupoperation',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
 
              $location.path("/groupmanagement");

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };
    

};


