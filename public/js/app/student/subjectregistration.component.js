'use strict';

angular.module('student')

        .component('registeredSubjects',{
            controller: studentCtrl ,
            templateUrl: 'studentinfostpl',
            
}).config(['$routeProvider','$mdDateLocaleProvider', function($routeProvider,$mdDateLocaleProvider) {

    $mdDateLocaleProvider.parseDate = function(dateString) {
      var m = moment(dateString, 'YYYY-MM-DD', true);
      return m.isValid() ? m.toDate() : new Date(NaN);
    };  
  
    $mdDateLocaleProvider.formatDate = function(date) {
      var m = moment(date);
      return m.isValid() ? m.format('YYYY-MM-DD') : '';
    };

}]);

//Student controller
function studentCtrl($timeout,$http,$location,$mdDialog,$scope,$routeParams,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var $ctrl = this;
     $ctrl.students=[];
     $ctrl.stds = [];
     $ctrl.subjects=[];
     $ctrl.classes = [];
     $ctrl.payments = [];
     $ctrl.querySearch   = querySearch;
     var temp =[];
     var std_id;
     var data; 
     
      //collectstudent registered subject
    std_id =$routeParams.id;
    var data = {id: std_id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };     
    $ctrl.init = function(){
    
    $ctrl.id = $routeParams.id;

     $timeout(
     $http.get('subjectregistration',config).then(
     function successCallback(response){
        
        $ctrl.subjects = response.data[0];
       
       
     },
     function errorCallback(response){
         
     }),1000);
     
     //collect and load all available classes
     $timeout(
     $http.get('classes').then(function(response){
         //convert all loaded data to lower case
        angular.forEach(response.data[0],function(item){

          $ctrl.classes.push({id:item.id,name:item.code, value:item.code.toLowerCase()}); 

        });
     }),1000);
}; //END inti function

//Function for deleting a subject
     $ctrl.deleteSubject = function(subject,ev)
      {
      
      var data = {id: subject.id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

       // Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer Cette UE?')
            .textContent('Elle sera enlevée de la liste des UE que cet étudiant devra suivre au cours de cette année académique')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Supprimer')
            .cancel('Annuler');
    //open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('subjectregistration',config).then(
          function successCallback(response){
              
              response.data[0]?toastr.success('Succès'):toastr.error('Erreur', 'Erreur');
              //check the index of the current object in the array
              var index = $ctrl.subjects.findIndex(x => x.id === subject.id)
              //remove the current object from the array
              $ctrl.subjects.splice(index,1);

         },
        function errorCallback(response){
            toastr.error('Erreur', 'Erreur');
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  }; //END delete
     
 //Collection student payment details       
    $timeout( $http.get('journaltier',config).then(
            function successCallback(response){

               $ctrl.payments = response.data[0];
               $ctrl.totalPayments = 0;
                for(var i=0;i<$ctrl.payments.length;i++)
                {
                    $ctrl.payments[i].num = i+1;
                    $ctrl.payments[i].dateTransaction = $ctrl.payments[i].dateTransaction.date;
                    
                    $ctrl.totalPayments = $ctrl.totalPayments + parseInt($ctrl.payments[i].amount);
                    
                }
            },
            function errorCallback(response){

            }),1000);     
 
  //reset pedagogic inscription
  $ctrl.resetPedagogicRegistration= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription administrative')
            .textContent('Cette opération va réinitialiser le processus d\'inscription administrative. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('resetPedagogicRegistration',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 };

  //reset pedagogic inscription
  $ctrl.resetRegistration= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription académique')
            .textContent('Cette opération va réinitialiser le processus d\'inscription académique. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('resetAdministrativeRegistration',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 }; 
 
  //suspend registration
  $ctrl.suspendRegistration= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription académique')
            .textContent('Cette opération va réinitialiser le processus d\'inscription académique. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('suspendRegistration',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 }; 
 
   //reset pedagogic inscription
  $ctrl.leaveTraining= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription académique')
            .textContent('Cette opération va réinitialiser le processus d\'inscription académique. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('leaveTraining',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 }; 
    $ctrl.dtOptions = DTOptionsBuilder.newOptions()
     .withButtons([
            //'columnsToggle',
            //'colvis',
            'copy',
            'print'

        ])
        .withPaginationType('full_numbers')
        .withDisplayLength(100)
         /* .withFixedHeader({
    top: true
  })*/;
  
    $ctrl.dtColumnDefs = [
    DTColumnDefBuilder.newColumnDef(0),
    DTColumnDefBuilder.newColumnDef(1),
    DTColumnDefBuilder.newColumnDef(2),
    DTColumnDefBuilder.newColumnDef(3),
    DTColumnDefBuilder.newColumnDef(4).notSortable(),
    DTColumnDefBuilder.newColumnDef(5)
  ];

    $ctrl.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
 
 
 
    /*--------------------------------------------------------------------------
     *---------------------------Loading subjects-------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.addSubject = function(ev){
        $scope.isUpdate= true;

        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/addSubjectForm.html',
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
    

    
    
    //Dialog Controller
function DialogController($scope, $mdDialog, $q,toastr) {
    $scope.selectedSubject= null;
    $scope.subjects = [];
    $scope.selectedSubject = null;

    //Loading subjects asynchronously
    $scope.query = function(subject)
    {
       var  dataString = {id: subject},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('subjectsearch',config).then(function(response){
                   return response.data[0];
                });
     };
  
//Select subjects to be add    
     $scope.addSubject = function()
     {
         //Cehck if the value exist in the array be for pushinng
         //Avoiding duplicates
         if ($scope.subjects.includes($scope.selectedSubject) === false) $scope.subjects.push($scope.selectedSubject);
         
         
     };
//Delete selected subject
     $scope.removeSubject = function(sub){
         var index = $scope.subjects.indexOf(sub);
         $scope.subjects.splice(index,1);
     };


    // Saving choices made     
     $scope.saveChoices = function(){

        var data = {id: std_id,
            subjects : $scope.subjects
        };
       /* var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };*/
         $timeout(
         $http.post('subjectregistration',data).then(
         function successCallback(response){
            
            response.data[0]?toastr.success('Informations sauvegardées avec succès'):toastr.error('Erreur lors de la sauvegarde des information', 'Erreur');
           $ctrl.subjects =  $ctrl.subjects.concat($scope.subjects)
            $mdDialog.hide();

         },
         function errorCallback(response){

         }),1000);
         
     }

 

  }
  
      $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };   
     
/**
     * Search for fielod of study ... use $timeout to simulate
     * remote dataservice call.
     */
    function querySearch (query) {
      var results = query ? $ctrl.classes.filter( createFilterFor(query) ) : $ctrl.classes,
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
      var lowercaseQuery = query.toLowerCase();

      return function filterFn(filiere) { 
        
        return (filiere.value.indexOf(lowercaseQuery) === 0);
      };

    }

  
};