'use strict';
angular.module("filiere",["ngRoute",'ngMessages','ui.bootstrap']);
angular.module('filiere')
        .component('filiereList',{
            templateUrl: 'filieretpl',
            controller: filiereCtrl  
});

angular.module('filiere')
        .component('filiereDetails',{
            templateUrl: 'newFil',
            controller: filiereCtrl 
});

angular.module('filiere')
        .component('updateFil',{
            templateUrl: 'updateFil',
            controller: filiereCtrl 
});

function filiereCtrl($scope,$http,$timeout,$mdDialog,$location,toastr,$routeParams){
    var $ctrl=this;
    $ctrl.cpt =1;
    $scope.faculties=null;
    $scope.faculty={name:'',id:''};
    $scope.dpt = {name:'',id:''};
    $scope.filiere={name:'',code:'',dpt_id: $scope.dpt.id,fac_id:$scope.faculty.id,status:true};
    $scope.filieres = [];
    $scope.dptId = null;
     /*--------------------------------------------------------------------------
     *--------------------------- create new department   ---------------------
     *----------------------------------------------------------------------- */    
    
      $ctrl.redirect= function(id){

     $location.path("/newFil/"+id);
     $scope.dptId=id;

    };
    $ctrl.redirectUpdate= function(id){

     $location.path("/updateFil/"+id);
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
    
    $timeout($http.get('filiere').then(
        function(response){
        $scope.filieres = response.data[0];
    }),1000);    
    
    if($routeParams.id && $routeParams.id !=-1)
    {
        var config = {
            params: {id : $routeParams.id },
            headers : {'Accept' : 'application/json'}
        };
        $timeout($http.get('filiere',config).then(
        function(response){
        $scope.filiere = response.data[0];
        }),2500);
       var config = {
            params: {fil_id : $routeParams.id },
            headers : {'Accept' : 'application/json'}
        };        
        $timeout($http.get('searchSpeByFil',config).then(
        function(response){
        $scope.specialites = response.data[0];
        }),1000);        
        
    }
        
    }
    
    
    /*--------------------------------------------------------------------------
     *--------------------------- New filiere--------------------------------
     *----------------------------------------------------------------------- */ 
    
    $ctrl.newFil = function(filiere){
       

        
        $http.post('filiere',filiere).then(
            function successCallback(response){
                $scope.filieres.push(filiere);
                toastr.success("Opération effectuée avec succès")
                $scope.filiere = null;
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });    
    }
    
     /*-------------------------------------------------------------------------
     *--------------------------- deleting filiere------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteFil= function(fil,ev)
      {
            var data = {id: fil.id}; 
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
            $http.delete('filiere',config).then(
              function successCallback(response){
                  //check the index of the current object in the array
                  var x;
                  var index = $scope.filieres.findIndex(x => x.id === fil.id);
                  //remove the current object from the array
                  $scope.filieres.splice(index,1);
                  toastr.success("Opération effectuée avec succès")
             },
            function errorCallback(response){
                 toastr.error("une erreur inattendue s'est produite");
                });
        }, function() {
         // $scope.status = 'You decided to keep your debt.';
        });

      }; 
      
    $ctrl.updateFil = function(fil) {
        var data = {id: fil.id,code:fil.code,name:fil.name,status:fil.status,fac_id:fil.fac_id,dpt_id:fil.dpt_id}; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('filiere',data,config)
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

     /*--------------------------------------------------------------------------
     *--------------------------- loading all filières by faculty   ---------------------
     *----------------------------------------------------------------------- */
    $ctrl.searchFilByFaculty = function(id){
      var data = {fac_id: id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchFilByFaculty',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $scope.filieres = response.data[0];
                
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });    
        
    }
    
      /*--------------------------------------------------------------------------
     *--------------------------- loading all filières  by departement   ---------------------
     *----------------------------------------------------------------------- */
    $ctrl.searchFilByDpt = function(id){
      var data = {dpt_id: id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchFilByDpt',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $scope.filieres = response.data[0];
            
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });    
        
    } 
    
     /*-------------------------------------------------------------------------
     *--------------------------- deleting specialite------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteSpe= function(spe,ev)
      {
            var data = {id: spe.id}; 
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
            $http.delete('specialite',config).then(
              function successCallback(response){
                  //check the index of the current object in the array
                  var x;
                  var index = $scope.specialites.findIndex(x => x.id === spe.id);
                  //remove the current object from the array
                  $scope.specialites.splice(index,1);
                  toastr.success("Opération effectuée avec succès")
             },
            function errorCallback(response){
                 toastr.error("une erreur inattendue s'est produite");
                });
        }, function() {
         // $scope.status = 'You decided to keep your debt.';
        });

      };     
    
     /*-------------------------------------------------------------------------
     *--------------------------- updating filiere------------------------------
     *----------------------------------------------------------------------- */
            $ctrl.updateFiliere=function(fil,ev){
                $scope.filiere= fil;

            $mdDialog.show({
              controller: FilController,
              templateUrl: 'js/my_js/globalconfig/filupdateformtpl.html',
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
  function FilController($scope, $mdDialog,toastr) {
      
        
    $scope.filUpdate = function(fil) {
        var data = {id: fil.id,code:fil.code,name:fil.name,status:fil.status,fac_id:fil.fac_id,dpt_id:fil.dpt_id}; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('filiere',data,config)
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