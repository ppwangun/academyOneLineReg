'use strict';

angular.module('faculty')
        .component('facultyList',{
            templateUrl: 'facultytpl',
            controller: facultyCtrl 
});
function facultyCtrl($scope,$http,$mdDialog,toastr){
     var $ctrl = this;
     $ctrl.faculties =[];
     $ctrl.school={id:'',name:'',code:'',logo:''};

     $http.get('school')
            .then(
            function (response){
               response.data[0].length<=0?$ctrl.isSchoolEmpty=true: $ctrl.isSchoolEmpty= false;
               $ctrl.isSchoolEmpty?$ctrl.school={id:'',name:'',code:'',logo:''} :$ctrl.school=response.data[0];
               
            },
            function(response)
            {
                
            }).then( function()
            {
                var data = {id: $ctrl.school.id}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $http.get('faculty',config)
                         .then(
                         function(response){
                            $ctrl.faculties=response.data[0];
                });                     
            });

           
    $ctrl.createSchool = function()
    {
       
        $http.post('school',$ctrl.school)
            .then(function succesCallback(response)
            {
                 $ctrl.school=response.data[0];
                console.log(response.data[0]);
                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                  alert(response.data);
                });        
    };
    
    $ctrl.addFaculty = function(ev) {
    $mdDialog.show({
      controller: DialogController,
      templateUrl: 'js/my_js/globalconfig/facultyformtpl.html',
      parent: angular.element(document.body),
     // parent: angular.element(document.querySelector('#component-tpl')),
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
  
   $ctrl.addResponsable = function(ev) {
    $mdDialog.show({
      controller: RespoController,
      templateUrl: 'js/my_js/globalconfig/facultyformtpl.html',
      parent: angular.element(document.body),
     // parent: angular.element(document.querySelector('#component-tpl')),
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
  /*----------------------------------------------------------------------------
   * fonction for deleting faculty
   ---------------------------------------------------------------------------*/
  $ctrl.deleteFaculty = function(fac,ev)
  {
      var data = {id: fac.id}; 
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
        $http.delete('faculty',config).then(
          function successCallback(response){
              //check the index of the current object in the array
              var index = $ctrl.faculties.findIndex(x => x.id === fac.id)
              //remove the current object from the array
              console.log("yes i am in")
              $ctrl.faculties.splice(index,1);
              toastr.success("Operation effectuée avec succès");

         },
        function errorCallback(response){
            toastr.error("Une erreur inattendue s'est produite");
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };
  
  
  //Dialog Controller
  function DialogController($scope, $mdDialog) {
      
    $scope.faculty = {name:'', code:'',respo:'',created_date:'',school_id: $ctrl.school.id};       
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
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
    };
    
  //Dialog Controller
  function RespoController($scope, $mdDialog) {
      
    $scope.faculty = {name:'', code:'',respo:'',created_date:'',school_id: $ctrl.school.id};       
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
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
    };    
    
}