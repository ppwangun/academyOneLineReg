'use strict';

angular.module('payment')
        .component('newmoratorium',{
            templateUrl: 'newmoratorium',
            controller: moratoriumCtrl 
});
function moratoriumCtrl($timeout,$http,$location,DTOptionsBuilder,DTColumnDefBuilder){
    var $ctrl = this;
        $ctrl.moratoriums = [];
    
    $ctrl.init = function(){

    $timeout(
     $http.get('moratorium').then(function(response){
         
         $ctrl.moratoriums= response.data[0];
     }),500);
 };
 
 
  $ctrl.redirect= function(id){
     $location.path("/paymentdetails/"+id)
     
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
    DTColumnDefBuilder.newColumnDef(5),
    DTColumnDefBuilder.newColumnDef(6)
  ];
     
    
};


