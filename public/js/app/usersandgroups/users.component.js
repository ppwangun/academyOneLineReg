'use strict';

angular.module('users')
        .component('userManagement',{
            templateUrl: 'usermanagementpl',
            controller: usersCtrl 
})
function usersCtrl($timeout,$http,$location){
    var $ctrl = this;
    
$ctrl.formatDate = function(date){
  var dateOut = new Date(date);
  return dateOut;
};     
    
    /*$ctrl.init = function(){
     
    $timeout(
     $http.get('classes').then(function(response){
         $ctrl.classes = response.data[0];
     }),500);
 };
 */
 
  $ctrl.redirect= function(id){
     $location.path("/newuser/"+id)
     
 };
  $ctrl.users = [];
 $ctrl.init = function(){

     $timeout(
     $http.get('adduser').then(function(response){
         //convert all loaded data to lower case
         var i = 0;
         $ctrl.users = response.data[0];
        angular.forEach(response.data[0],function(item){

          item.num = i+1;
          i++;

        });
     }),500); 
 }
};


