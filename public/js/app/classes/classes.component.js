'use strict';

angular.module('classes')
        .component('classesDetails',{
            templateUrl: 'classetpl',
            controller: classesCtrl 
});
function classesCtrl($timeout,$http,$location){
    var $ctrl = this;
    
    $ctrl.init = function(){
     
    $timeout(
     $http.get('classes').then(function(response){
         $ctrl.classes = response.data[0];
     }),500);
 };
 
 
  $ctrl.redirect= function(id){
     $location.path("/newclasse/"+id)
     
 };
     
    
};


