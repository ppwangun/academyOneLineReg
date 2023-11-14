'use strict';

var studentApp = angular.module("student",["ngRoute",'ngMessages']).controller("preRegistrationCtrl",function($timeout,$http,$location,$mdDialog,$routeParams,toastr,$scope){
    var id;
    var $ctrl= this;
   // $scope.cycles = [];
    $ctrl.simulateQuery = false;
    $ctrl.isDisabled    = false;
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedItem= null;

    $ctrl.isCycleRequired= false;


    $scope.subjects = [];
   
    var id,ue_class_id;
   // $ctrl.selectedItemChange = selectedItemChange;
    

     //Loading classes of study asynchronously
    $ctrl.query = function(classe)
    {
       var  dataString = {id: classe},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('admittedStudent',config).then(function(response){
                   return response.data[0];
                });
     };

    $scope.confirmAdmission = function(data,ev){
        //var data = {id:data.id,nom:data.nom,prenom:data.prenom,phoneNumber:data.phoneNumber,classe:data.classe}
        $timeout(
         $http.post('admittedStudent',data).then(function(response){
             toastr.success("opération effectuée avec succès");
             
             //clear the form content
             $ctrl.selectedItem = null;
           // $scope.student = response.data[0];
               $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('')
                      .textContent('Votre numéro d\'admission est:'+response.data[0]+'<a href="newStudentLogin">Cliquer ici</a> pour poursuivre votre inscription')
                      .ariaLabel('Alert Dialog Demo')
                      .ok('Got it!')
                      .targetEvent(ev)
                  );

          }),500);

      };


    

 

      

  });
  
  
    



