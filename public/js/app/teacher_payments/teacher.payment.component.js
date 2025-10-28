/******************************************************************************
 * ****************************************************************************
 *  This module allows the creation of academic year with de period of the ****
 *  year.
 */
'use strict';

angular.module('teacher.payment', ['ngRoute','ui.bootstrap'])

.config(['$routeProvider','$mdDateLocaleProvider', function($routeProvider,$mdDateLocaleProvider) {

    $mdDateLocaleProvider.parseDate = function(dateString) {
      var m = moment(dateString, 'YYYY-MM-DD', true);
      return m.isValid() ? m.toDate() : new Date(NaN);
    };  
  
    $mdDateLocaleProvider.formatDate = function(date) {
      var m = moment(date);
      return m.isValid() ? m.format('YYYY-MM-DD') : '';
    };
  $routeProvider.when('/paymentsgrid', {
    templateUrl: 'paymentsgridlist',
    controller: 'TeacherPymtCtrl'
  }).when('/newPaymentGrid/:id', {
    templateUrl: 'newPaymentGrid',
    controller: 'TeacherPymtCtrl'
  }).when('/updateacadyr/:id', {
    templateUrl: 'newacadyr',
    controller: 'AcadYrCtrl'
  });
}])
.controller('TeacherPymtCtrl', function($scope,$uibModal,$log,$http,$location,$routeParams,$timeout,toastr,$mdDialog) {
 
    //Array that will record the list of created semesters(period of year)
    $scope.semesters = [];
    $scope.teachersRanks = [];
    $scope.paymentGrid = [];
    $scope.pymtGridName;
    $scope.isUpdate = false;
    $scope.itemAlreadyExistsError = false;

    //object storing academic year detalis
    $scope.acadyr = {code: '', name: '', startingDate: '', endingDate: '',admissionStartingDate:'',
        admissionEndingDate: '',adminRegistrationStartingDate:'',adminRegistrationEndingDate: '',isDefault:false,status:0};
    //object storing  semster details
    $scope.sem = {code:'',name:'',startingDate:'',endingDate:'',acad_id:''};
    var id =$routeParams.id;

    $scope.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
    
    
        
    $scope.redirect= function(id){
        $location.path("/newPaymentGrid/"+id);
       
    };
 
    //Delete item from payment grid
    $scope.removePymtItem = function(pymt){
       const  index = $scope.paymentGrid.findIndex(item=>item.id === pymt.id)
        if(index !== -1)
            $scope.paymentGrid.splice(index,1)


    }
    
    $scope.changeDefautlPymtGrid = function(defaultPymtGridId){
        $http.post('setDefaultPymtGrid',{id:defaultPymtGridId}).then(function(response){ 

                 toastr.success("Opération effectuée avec succès");
                 

         })         
    }
    
    //creatae a new payment grid
    $scope.createPymentGrid = function(){
        $http({
                method:'POST', url:'createPymtGrid',data: {title:$scope.pymtGridName,pymtGridDetails : $scope.paymentGrid,isUpdate:$scope.isUpdate}
            }).then(function successCallBack(response){

                
                toastr.success('Vos informationis ont été enrégistrées correctement', 'Succès!');
                $location.path("/paymentsgrid");
               


            },function errorCallback(response){
                toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:error")
            });         
    }
    
    //update a new payment grid
    $scope.updatePymentGrid = function(){
        $http({
                method:'POST', url:'createPymtGrid',data: {id : $routeParams.id,title:$scope.pymtGridName,pymtGridDetails : $scope.paymentGrid,isUpdate:$scope.isUpdate}
            }).then(function successCallBack(response){

                
                toastr.success('Vos informationis ont été enrégistrées correctement', 'Succès!');
                $location.path("/paymentsgrid");
               


            },function errorCallback(response){
                toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:error")
            });         
    }   
    
//deleting payment grid
      $scope.deletePymentGrid= function(id,ev)
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
        $http.post('deletePymtGrid',{id:id}).then(
          function successCallback(response){
              //check the index of the current object in the array
             
            /*  var index = $ctrl.classes.findIndex(x=>x.id === id);
              //remove the current object from the array
              $ctrl.classes.splice(index,1);*/
              toastr.success("Opération effectuée avec succès");
              $location.path("/paymentsgrid");

         },
        function errorCallback(response){
            toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:error")

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
      };    
    
 
        
    $scope.init = function(){
         //$scope.acadyr.inscription_ending_date= moment($scope.acadyr.inscription_ending_date).format("YYYY-MM-DD");
         
         var id =$routeParams.id; 

        //Collect teacher rank
        $http.get('teacherGrade').then(function(response){
                 $scope.teachersRanks = response.data[0];

         })
        $http.post('allPaymentTypes',{id:id}).then(function(response){ 
                 $scope.allPaymentTypes = response.data.allPaymentRate;
                 $scope.pymtGridName = response.data.paymentRate.description;
                 $scope.paymentGrid = response.data.paymentGrid
                 $scope.defaultPymtGridId = $scope.allPaymentTypes.find((item) => item.isDefaultPayment === true)
                 if($scope.defaultPymtGridId) $scope.defaultPymtGridId= $scope.defaultPymtGridId.id;

         }) 
         
        


        if(id)
        if(id != -1)
        {
            $scope.isUpdate =true;
        }

    };
        
        //function for adding a new semester
        $scope.saveSemester = function(){
            //opening a modal form for registering semester details

                  //check if the form is on update mode
                  $scope.semesters.push($scope.sem);
                  if(id)
                  {
                      $scope.sem.acad_id = id;
                        $http({
                                method:'POST', url:'semester',data: $scope.sem
                            }).then(function successCallBack(response){
                                
                                $scope.sem = {code:'',name:'',starting_date:'',ending_date:''};
                                toastr.success('Vos informationis ont été enrégistrées correctement', 'Succès!');
                                $mdDialog.cancel();
                  

                            },function errorCallback(response){
                                toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:error")
                            });                        
  
                  }
                  
                        
        };
        //!End addSemster
        
        
        //funtion for recording academic year details with associated perio (semster)
        $scope.saveAcadYear = function(){

// send a firts request for creating the academic year
            $http({
                    method: 'POST',
                    url: 'acadyear',
                    data: $scope.acadyr
                    //headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).then(function successCallback(response) {

//Check if therr are semesters associated to the academic year previously created
//In case semsters exist, send request to create each semester
                        if($scope.semesters.length !== 0)
                        {
                            angular.forEach($scope.semesters,function(value,key){
                                angular.merge(value,{acad_id :response.data.acadyear.acad_id});
                                        
                                $http({
                                    method:'POST', url:'semester',data: value
                                }).then(function successCallBack(response){

                                },function errorCallback(response){

                                } );                        
                            });

                        }
                        $location.path('/acadyr-list');
                    }, function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                  toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:error")
                  
                });
            
        };
        //!End SaveYear()
        
        $scope.updateAcadYear = function(){
            var data = {id: id,data:$scope.acadyr}; 
            var config = {
            params: data,
            headers : {'Accept' : 'application/json'}
            };
            $timeout(
               $http.put('acadyear',data,config).then(
               function successCallback(){
                   toastr.success("Mise à jour effectuée avec succès");
                   $location.path("/acadyr-list")
                   
               },
               function erroCallback(response)
               {
                toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:")   
               })     
                    ,
            500);
            
        };


 
    
//Migrating data from active year to the current year
      $scope.activeYrDataMigration= function(ev)
      {
       
        var data = {id: id}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Migration des données')
            .textContent('Cette opération va faire migrer toutes les données de l\'année académique')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $scope.showMigrationProgress(ev);
        $http.get('academicYrDataMigration',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
            /*  var index = $ctrl.classes.findIndex(x=>x.id === id);
              //remove the current object from the array
              $ctrl.classes.splice(index,1);*/
              $mdDialog.cancel();
              toastr.success("Opération effectuée avec succès");
              //$location.path("/acadyr-list");

         },
        function errorCallback(response){
            toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:error")

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
      };//deleting academic year
      $scope.deleteAcadYear= function(ev)
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
        $http.delete('acadyear',config).then(
          function successCallback(response){
              //check the index of the current object in the array
             
            /*  var index = $ctrl.classes.findIndex(x=>x.id === id);
              //remove the current object from the array
              $ctrl.classes.splice(index,1);*/
              toastr.success("Opération effectuée avec succès");
              $location.path("/acadyr-list");

         },
        function errorCallback(response){
            toastr.error("Un problème est inattendu survenu lors de l'enrégistrement des informations:error")

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
      };
      
      
      
 /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    $scope.showSemester = function(ev){
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/periodform.html',
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
          
          //$ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          //$ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
     $scope.showMigrationProgress = function(ev){
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/migrationProgress.html',
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
          
          //$ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          //$ctrl.status = 'You cancelled the dialog.';
        });        
    };  
 /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    $scope.showUpdateSemester = function(sem,ev){
        $scope.isUpdate= true;

        $scope.sem = sem;
        
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/periodform.html',
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
          
          //$ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          //$ctrl.status = 'You cancelled the dialog.';
        });        
    };
    
 /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    $scope.showGradeFeesForm = function(ev){
        
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/gradefeesform.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true, // Only for -xs, -sm breakpoints.
          locals: {teachersGrades : $scope.teachersRanks,
              paymentGrid : $scope.paymentGrid,
              itemAlreadyExistsError : $scope.itemAlreadyExistsError
              
            }
        })
        .then(function(answer) {
          
          //$ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          //$ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
    
    //Dialog Controller
    function DialogController($scope, $mdDialog,teachersGrades,paymentGrid,itemAlreadyExistsError) {

        $scope.teachersGrades = teachersGrades;
        $scope.teacherPayment = {};

        $scope.addPaymentLine = function(pymnt)
        {
            //Parse string to objet
            $scope.teacherPayment.grade = JSON.parse($scope.teacherPayment.grade)
            //push the element into array

              //very the item not already exists
            if (!paymentGrid.find(item=>item.grade.id === pymnt.grade.id))
                paymentGrid.push(pymnt);
            else itemAlreadyExistsError = true;

            //reset from values
            $scope.teacherPayment = {};
            $mdDialog.hide();

        }


    }
  
    $scope.cancel = function() {
          $scope.sem = {code:'',name:'',startingDate:'',endingDate:'',acad_id:''};
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {

      $mdDialog.hide(answer);
    };  
   
 
        
});