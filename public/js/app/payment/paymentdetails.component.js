'use strict';

angular.module('payment')
        .component('paymentDetails',{
            templateUrl: 'paymentdetailstpl',
            controller: paymentCtrl 
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

//Payment controller
function paymentCtrl($timeout,$http,$location,$mdDialog,$scope,$routeParams,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var $ctrl = this;
     $ctrl.payments=[];
     $ctrl.student = [];
     $ctrl.totalPayments = 0;
     var std_id;
     var data; 
    
    // Formatting date function
    $ctrl.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
    $ctrl.init = function(){
    

      //collectstudent registered subject
    std_id =$routeParams.id;
    var data = {id: std_id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };
     $timeout(
     //Collecting student credentials as well as all the payments associated with him        
     $http.get('api/payment',config).then(
     function successCallback(response){

        $ctrl.student = response.data[0];
     },
     function errorCallback(response){
         
     }).then(function(){
         $http.get('journaltier',config).then(
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

            }
     )}),1000);
     
     //collect and load all available classes
    /* $timeout(
     $http.get('classes').then(function(response){
         //convert all loaded data to lower case
        angular.forEach(response.data[0],function(item){

          $ctrl.classes.push({id:item.id,name:item.code, value:item.code.toLowerCase()}); 

        });
     }),1000);*/
     
     
 }


 
 

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
     *---------------------------Loading files ---------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/uploadform.html',
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
 
$scope.upload = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importstudents',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 }
 

      

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