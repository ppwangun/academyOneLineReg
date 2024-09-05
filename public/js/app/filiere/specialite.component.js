'use strict';
angular.module("specialite",["ngRoute",'ngMessages','ui.bootstrap']);
angular.module('specialite')
        .component('specialityList',{
            templateUrl: 'specialitetpl',
            controller: specialiteCtrl  
});

angular.module('specialite')
        .component('specialityDetails',{
            templateUrl: 'newSpe',
            controller: specialiteCtrl 
});

angular.module('specialite')
        .component('updateSpe',{
            templateUrl: 'updateSpe',
            controller: specialiteCtrl 
});

function specialiteCtrl($scope,$http,$timeout,$mdDialog,$location,toastr,$routeParams){
    var $ctrl=this;
    $ctrl.cpt =1;
    $scope.faculties=null;
    $scope.faculty={name:'',id:''};
    $scope.fil={name:'',id:''};
    $scope.dpt = {name:'',id:''};
    $scope.specialite={name:'',code:'',dpt_id: $scope.dpt.id,fac_id:$scope.faculty.id,fil_id:$scope.fil.id,status:true};
    $scope.specailites = [];
    $scope.dptId = null;
     /*--------------------------------------------------------------------------
     *--------------------------- create new department   ---------------------
     *----------------------------------------------------------------------- */    
    
      $ctrl.redirect= function(id){

     $location.path("/newSpe/"+id);
     $scope.dptId=id;

    };
    $ctrl.redirectUpdate= function(id){

     $location.path("/updateSpe/"+id);
     $scope.filId=id;

    };    
     $ctrl.init = function(){
    /*--------------------------------------------------------------------------
     *--------------------------- Load faculties--------------------------------
     *----------------------------------------------------------------------- */

    $timeout(
        $http.get('faculty').then(function(response){
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
    
     $timeout($http.get('specialite').then(
        function(response){
        $scope.specialites = response.data[0];
    }),1000);   
    
    if($routeParams.id && $routeParams.id !=-1)
    {
        var config = {
            params: {id : $routeParams.id },
            headers : {'Accept' : 'application/json'}
        };
        
        $timeout($http.get('specialite',config).then(
        function(response){
        $scope.specialite = response.data[0];
        }),5000);
        var config = {
            params: {fil_id : $routeParams.id },
            headers : {'Accept' : 'application/json'}
        };        
        $timeout($http.get('searchSpeByFil',config).then(
        function(response){
        $scope.filieres = response.data[0];
        }),1000);        
        
    }
        
    }
    
    
    /*--------------------------------------------------------------------------
     *--------------------------- New filiere--------------------------------
     *----------------------------------------------------------------------- */ 
    
    $ctrl.newSpe = function(specialite){
       

        
        $http.post('specialite',specialite).then(
            function successCallback(response){
                $scope.specialites.push(specialite);
                toastr.success("Opération effectuée avec succès")
                $scope.specialite={dpt_id: -1,status:true};
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
      
    $ctrl.updateSpe = function(spe) {
        var data = {id: spe.id,code:spe.code,name:spe.name,status:spe.status,fil_id:spe.fil_id}; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('specialite',data,config)
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
     /*-------------------------------------------------------------------------
     *--------------------------- updating filiere------------------------------
     *----------------------------------------------------------------------- */
            $ctrl.updateSpecialite=function(spe,ev){
                $scope.specialite= spe;

            $mdDialog.show({
              controller: SpeController,
              templateUrl: 'js/my_js/globalconfig/speupdateformtpl.html',
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
  function SpeController($scope, $mdDialog,toastr) {
      
        
    $scope.speUpdate = function(spe) {
        var data = {id: spe.id,code:spe.code,name:spe.name,status:spe.status,fil_id:spe.fil_id}; 
        var config = {
        params :  data,
        headers : {'Accept' : 'application/json'}
        };
        $http.put('specialite',data,config)
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