'use strict';
angular.module("cycle",["ngRoute",'ngMessages','ui.bootstrap']);
angular.module('cycle')
        .component('cycleDetails',{
            templateUrl: 'cycletpl',
            controller: cycleCtrl  
});

angular.module('cycle')
        .component('newCycle',{
            templateUrl: 'newCycle',
            controller: cycleCtrl 
});

angular.module('cycle')
        .component('updateCycle',{
            templateUrl: 'updateCycle',
            controller: cycleCtrl 
});

function cycleCtrl($scope,$http,$timeout,$mdDialog,$location,toastr,$routeParams){
    var $ctrl=this;
    $ctrl.cpt =1;
    $scope.faculties=null;
    $scope.faculty={name:'',id:''};
    $scope.dpt = {name:'',id:''};
    $scope.cycle={name:'',code:'',status:true};
    $scope.cycles = [];
    $scope.dptId = null;
     /*--------------------------------------------------------------------------
     *--------------------------- create new department   ---------------------
     *----------------------------------------------------------------------- */    
    
      $ctrl.redirect= function(id){

     $location.path("/newCycle/"+id);
     $scope.dptId=id;

    };
    $ctrl.redirectUpdate= function(id){

     $location.path("/updateCycle/"+id);
     $scope.filId=id;

    };    
     $ctrl.init = function(){
    /*--------------------------------------------------------------------------
     *--------------------------- Load faculties--------------------------------
     *----------------------------------------------------------------------- */

   $timeout($http.get('faculty').then(
        function(response){
        $scope.faculties = response.data[0];
    }),1000);
    
    $timeout($http.get('department').then(
        function(response){
        $scope.dpts = response.data[0];
    }),1000); 
    
    $timeout($http.get('cycleFormation').then(
        function(response){
        $scope.cycles = response.data[0];
    }),1000);    
    
    if($routeParams.id && $routeParams.id !=-1)
    {
        var config = {
            params: {id : $routeParams.id },
            headers : {'Accept' : 'application/json'}
        };
        $timeout($http.get('cycleFormation',config).then(
        function(response){
        $scope.cycle = response.data[0];
        }),2500);
        
        
        
    }
        
    }
    
    
    /*--------------------------------------------------------------------------
     *--------------------------- New filiere--------------------------------
     *----------------------------------------------------------------------- */ 
    
    $ctrl.newCycle = function(cycle){
       

        
        $http.post('cycleFormation',cycle).then(
            function successCallback(response){
                $scope.cycles.push(response.data[0]);
                toastr.success("Opération effectuée avec succès")
                $scope.cycle = null;
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });    
    }
    
     /*-------------------------------------------------------------------------
     *--------------------------- deleting filiere------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteCycle= function(cycle,ev)
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
            $http.delete('cycleFormation',config).then(
              function successCallback(response){
                  //check the index of the current object in the array
                  var x;
                  var index = $scope.cycles.findIndex(x => x.id === cycle.id);
                  //remove the current object from the array
                  $scope.cycles.splice(index,1);
                  toastr.success("Opération effectuée avec succès")
             },
            function errorCallback(response){
                 toastr.error("une erreur inattendue s'est produite");
                });
        }, function() {
         // $scope.status = 'You decided to keep your debt.';
        });

      }; 
      
    $ctrl.updateCycle = function(cycle) {
        var data = {id: cycle.id,code:cycle.code,name:cycle.name,status:cycle.status}; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('cycleFormation',data,config)
            .then(function succesCallback(response)
            {
                 //$ctrl.school=response.data[0];
                toastr.success("operation effectuée avec succès");
                $mdDialog.cancel();
                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                 
                  alert(response.data);
                });       
    };      
 
     /*--------------------------------------------------------------------------
     *--------------------------- loading all filières   ---------------------
     *----------------------------------------------------------------------- */
    $ctrl.searchDptByFaculty = function(id){
      var data = {fac_id: id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchDptByFaculty',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $scope.dpts = response.data[0];
            
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });    
        
    }


     
    
     /*-------------------------------------------------------------------------
     *--------------------------- updating filiere------------------------------
     *----------------------------------------------------------------------- */
            $ctrl.updateCycle=function(cycle,ev){
                $scope.cycle= cycle;

            $mdDialog.show({
              controller: CycleController,
              templateUrl: 'js/my_js/globalconfig/cycleupdateformtpl.html',
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
        }
    

  //Dialog Controller
  function CycleController($scope, $mdDialog,toastr) {
      
        
    $scope.cyUpdate = function(cycle) {
        var data = {id: cycle.id,code:cycle.code,name:cycle.name,status:cycle.status}; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('cycleFormation',data,config)
            .then(function succesCallback(response)
            {
                 //$ctrl.school=response.data[0];
                toastr.success("operation effectuée avec succès");
                $mdDialog.cancel();
                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                 
                  alert(response.data);
                });       
    };

    $scope.cancel = function() {
      //$scope.faculties=[];
      
   
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {

      $mdDialog.hide(answer);
    };
    };
}