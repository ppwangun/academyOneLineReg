'use strict';

angular.module('users')
        .component('groupManagement',{
            templateUrl: 'grouplist',
            controller: usersCtrl,
});
function usersCtrl($timeout,$http,$location){
    var $ctrl = this;
    
    /*$ctrl.init = function(){
     
    $timeout(
     $http.get('classes').then(function(response){
         $ctrl.classes = response.data[0];
     }),500);
 };
 */
 
  $ctrl.redirect= function(id){
     $location.path("/newgroup/"+id)
     
 };
  $ctrl.groups = [];
 $ctrl.init = function(){

     $timeout(
     $http.get('groupoperation').then(function(response){
         //convert all loaded data to lower case
         var i = 0;
         $ctrl.groups = response.data[0];
        angular.forEach(response.data[0],function(item){

          item.num = i+1;
          i++;

        });
     }),500); 
 }
};


