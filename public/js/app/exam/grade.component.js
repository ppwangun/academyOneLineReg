'use strict';

angular.module('exam')
        .component('gradeConfig',{
            templateUrl: 'gradelist',
            controller: gradeCtrl 
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

function gradeCtrl($timeout,$http,$location,$mdDialog,$scope,DTOptionsBuilder,DTColumnDefBuilder){
    var $ctrl = this;
    $ctrl.grades = [];
    
    $ctrl.init = function(){

        //Collect all the availaible grade
    $timeout(
     $http.get('gradeconfig').then(function(response){
         $ctrl.grades = response.data[0];
                for(var i=0;i<$ctrl.grades.length;i++)
                {
                    $ctrl.grades[i].num = i+1;
                   

                }
     }),500);
 };
 
$ctrl.formatDate = function(date){
  var dateOut = new Date(date);
  return dateOut;
};
    
  $ctrl.redirect= function(id){
   
     $location.path("/newgrade/"+id);
     
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
    DTColumnDefBuilder.newColumnDef(0).notSortable(),
    DTColumnDefBuilder.newColumnDef(1),
    DTColumnDefBuilder.newColumnDef(2),
    DTColumnDefBuilder.newColumnDef(3),
    DTColumnDefBuilder.newColumnDef(4),
    DTColumnDefBuilder.newColumnDef(5),
    DTColumnDefBuilder.newColumnDef(6)
  ];
     
    /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/loadteachingunit.html',
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
  function DialogController($scope, $mdDialog) {
      
$scope.subject = {code:'',name:'',credits: '',hours_vol:'',cm_hrs:'',tp_hrs:'',td_hrs:'',ue_classe_id: '', ue_id:''};

  
 $scope.createSubject = function(){
     
     $http.post('subject',$scope.subject).then(function successCallback(response){
         $scope.subject=response.data[0];
         $mdDialog.cancel();
         
     }, function errorCallback(response){
        alert(" Une erreur inattendue s'est produite") ;
     });
 };
 
 $scope.updateCycle = function(){
        var data = {id: $scope.cycle.id,data:$scope.cycle}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
       $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('cycle',data,config).then(
            function successCallback(response){
                //reinitialise form
                //$location.path("/degree");
                $mdDialog.cancel();
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
     
 };
 

      

  }
  
      $scope.cancel = function() {
      //$scope.faculties=[];
      
      $scope.subject = {code:'',name:'',credits: '',hours_vol:'',class_id: '',cm_hrs:'',tp_hrs:'',td_hrs:''};
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };   
    
       
};


