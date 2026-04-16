'use strict';

angular.module('users')
        .component('settings',{
            templateUrl: 'settingstpl',
            controller: settingsCtrl 
})
function settingsCtrl($timeout,$http,$location,toastr,$scope){
    var $ctrl = this;
    $scope.odooSettings = [{status:0}]
    
    $ctrl.tax = {isRefundable: false};
    $ctrl.taxes = [];
    
$ctrl.formatDate = function(date){
  var dateOut = new Date(date);
  return dateOut;
}; 

$ctrl.updateTax = function(tax)
{
    $ctrl.tax  = tax;
    $ctrl.tax.rate = tax.value;
    $ctrl.tax.isRefundable = tax.refundable
    
}
    
    $ctrl.init = function(){
     
    $timeout(
     $http.get('allTaxesAndWithholdings').then(function(response){
         $ctrl.taxes = response.data[0];
     }),500);
 };
 
 
  $ctrl.redirect= function(id){
     $location.path("/newuser/"+id)
     
 };
  $ctrl.users = [];
$ctrl.init = function(){

     $timeout(
     $http.get('getOdooSettings').then(function(response){
         //convert all loaded data to lower case
         var i = 0;
         $scope.odooSettings = response.data[0];

     }),500).then(
            $timeout($http.get('allTaxesAndWithholdings').then(function(response){
                $ctrl.taxes = response.data[0]})),1000); 
}
 
 $ctrl.updateOdooSettings = function(settings){
        var data = {settings: settings};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.get('updateOdooSettings',config).then(

                function(response){

                    
                toastr.success("Opération effectuée avec succès");

                }),1000);      
 }
 
  $ctrl.createTaxesOrWithholdings = function(settings){
        var data = {settings: settings};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.post('createTaxesOrWithholdings',data,config).then(

                function(response){

                $ctrl.taxes = response.data[0];   
                toastr.success("Opération effectuée avec succès");

                }),1000);      
 }
};


