'use strict';

// Declare app level module which depends on views, and components
var app = angular.module('myApp', [
  'ngMaterial',
  'ngMessages',
  'ngAnimate',
  'ngImageCompress',
  'cgBusy',
  'onlineReg',
  'student'
  
  
 // 'ngRoute'

  //"ngResource"
  

]).
config(['$httpProvider', function($httpProvider) {

  
  $httpProvider.interceptors.push('myHttpInterceptor');          
  var spinnerFunction = function spinnerFunction(data, headersGetter) {
    $("#spinner").show();
    return data;
  };

  $httpProvider.defaults.transformRequest.push(spinnerFunction);          
                  
         
}]).factory('myHttpInterceptor', function ($q, $window) {
  return function (promise) {
    return promise.then(function (response) {
      $("#spinner").hide();
      return response;
    }, function (response) {
      $("#spinner").hide();
      return $q.reject(response);
    });
  };
});