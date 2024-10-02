'use strict';
angular.module("department",["ngRoute",'ngMessages','ui.bootstrap']);
angular.module('department')
        .component('departmentList',{
            templateUrl: 'departmentpl',
            controller: departmentCtrl 
});

angular.module('department')
        .component('departmentDetails',{
            templateUrl: 'newDept',
            controller: departmentCtrl 
});

angular.module('department')
        .component('updateDpt',{
            templateUrl: 'updateDpt',
            controller: departmentCtrl 
});

function departmentCtrl($scope,$http,$timeout,$mdDialog,$location,toastr,$routeParams){
    var $ctrl=this;
    $scope.faculties=null;
    $scope.faculty={name:'',id:''};
    $scope.dpt={name:'',code:'',fac_id:$scope.faculty.id,status:true};
    $scope.dpts = [];
    $scope.dptId = null;
     /*--------------------------------------------------------------------------
     *--------------------------- create new department   ---------------------
     *----------------------------------------------------------------------- */    
    
      $ctrl.redirect= function(id){

     $location.path("/newDpt/"+id);
     $scope.dptId=id;

    };
    $ctrl.redirectUpdate= function(id){

     $location.path("/updateDpt/"+id);
     $scope.dptId=id;

    };    
     $ctrl.init = function(){
    /*--------------------------------------------------------------------------
     *--------------------------- Load faculties--------------------------------
     *----------------------------------------------------------------------- */

    $http.get('faculty').then(
        function(response){
        $scope.faculties = response.data[0];
    });
    
    $http.get('department').then(
        function(response){
        $scope.dpts = response.data[0];
    });   
    
    if($routeParams.id && $routeParams.id !=-1)
    {
        var config = {
            params: {id : $routeParams.id },
            headers : {'Accept' : 'application/json'}
        };
        $http.get('department',config).then(
        function(response){
        $scope.dpt = response.data[0];
        });
        
        
        var config = {
            params: {dpt_id : $routeParams.id },
            headers : {'Accept' : 'application/json'}
        };        
        $http.get('searchFilByDpt',config).then(
        function(response){
        $scope.filieres = response.data[0];
        });        
        
    }
        
    }
    
    
    /*--------------------------------------------------------------------------
     *--------------------------- New department--------------------------------
     *----------------------------------------------------------------------- */ 
    
    $ctrl.newDpt = function(dep){
       

        
        $http.post('department',dep).then(
            function successCallback(response){
                $scope.dpts.push(dep);
                toastr.success("Opération effectuée avec succès")
                $scope.dpt = null;
            },
            function errorCallback(response){
                toastr.error("une erreur inattendue s'est produite");
            });    
    }
    
     /*-------------------------------------------------------------------------
     *--------------------------- deleting filiere------------------------------
     *----------------------------------------------------------------------- */
    
      $ctrl.deleteDpt= function(dep,ev)
      {
            var data = {id: dep.id}; 
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
            $http.delete('department',config).then(
              function successCallback(response){
                  //check the index of the current object in the array
                  var x;
                  var index = $scope.dpts.findIndex(x => x.id === dep.id);
                  //remove the current object from the array
                  $scope.dpts.splice(index,1);
                  toastr.success("Opération effectuée avec succès")
             },
            function errorCallback(response){
                 toastr.error("une erreur inattendue s'est produite");
                });
        }, function() {
         // $scope.status = 'You decided to keep your debt.';
        });

      }; 
      
    $ctrl.updateDept = function(dep) {
        var data = dep; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('department',data,config)
            .then(function succesCallback(response)
            {
                 //$ctrl.school=response.data[0];
                toastr.success("operation effectuée avec succès");

                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                 
                  alert(response.data);
                });       
    };   
    
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
                  var index = $scope.dpts.findIndex(x => x.id === fil.id);
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
                toastr.success("Opération effectuée avec succès")
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
     *--------------------------- updating filiere------------------------------
     *----------------------------------------------------------------------- */
            $ctrl.updateDpt=function(dep,ev){
                $scope.dpt= dep;

            $mdDialog.show({
              controller: DptController,
              templateUrl: 'js/my_js/globalconfig/dptupdateformtpl.html',
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
  function DptController($scope, $mdDialog,toastr) {
      
        
    $scope.dptUpdate = function(dep) {
        var data = {id: dep.id,code:dep.code,name:dep.name,status:dep.status,fac_id:dep.fac_id}; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('department',data,config)
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