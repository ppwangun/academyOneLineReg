'use strict';

angular.module('myApp.acadyrList', ['ngRoute','datatables'])

.config(['$routeProvider','$mdDateLocaleProvider', function($routeProvider,$mdDateLocaleProvider) {

    $mdDateLocaleProvider.parseDate = function(dateString) {
      var m = moment(dateString, 'YYYY-MM-DD', true);
      return m.isValid() ? m.toDate() : new Date(NaN);
    };  
  
    $mdDateLocaleProvider.formatDate = function(date) {
      var m = moment(date);
      return m.isValid() ? m.format('YYYY-MM-DD') : '';
    };
  $routeProvider.when('/acadyr-list', {
    templateUrl: 'anneeacad',
    controller: 'AcadyrListCtrl'
  });
}])



.controller('AcadyrListCtrl', function($scope,$http,$location, DTOptionsBuilder, DTColumnDefBuilder,toastr) {
    
        $scope.dtOptions = DTOptionsBuilder.newOptions().withPaginationType('full_numbers');
        $scope.dtColumnDefs = [
        DTColumnDefBuilder.newColumnDef(0),
        DTColumnDefBuilder.newColumnDef(1),
        DTColumnDefBuilder.newColumnDef(2),
        DTColumnDefBuilder.newColumnDef(3),
        DTColumnDefBuilder.newColumnDef(4).notSortable()
    ];
        $scope.acadyrs = [];
        $scope.acadyr = {code:'',name:'',starting_date:'',ending_date:''};
       $scope.onlineRegistrationStatus = 117;
       
        $scope.formatDate = function(date){
          var dateOut = new Date(date);
          return dateOut;
        };
        $http({
            method: 'GET',
            url: 'acadyear'
            
        }).then(function successCallback(response){
            $scope.acadyrs = response.data.acadyrs;
        }, function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                  
        }).then(
        
            
            
            
            );
           var  dataString ,
           config = {
            //params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
            $http.get('onlineRegistrationDefault',config).then(function(response){
                   $scope.onlineRegistrationStatus = response.data[0];
                });
        
        $scope.redirect = function(id){
            $location.path("/updateacadyr/"+id);
        }
        

    $scope.changeOnlineRegistrationDefault = function(id)
    {
        $scope.onlineRegistrationStatus = id;
       var  dataString = {id: id},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };

            $http.get('changeOnlineRegistrationDefault',config).then(function(response){
                   toastr.success("Modification effectuée avec succès");
                });
     };       

})
.run(initDT);
function initDT(DTDefaultOptions) {
    DTDefaultOptions.setLoadingTemplate('<img src="images/loading.gif">');
}