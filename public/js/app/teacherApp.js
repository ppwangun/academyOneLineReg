'use strict';

// Declare app level module which depends on views, and components
var app = angular.module('teacherApp', [
  'ngMaterial',
  'ngMessages',
  'ngAnimate',
  'ngImageCompress',
  'cgBusy',
  'lecturer'

  
  
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
}).filter('sumByKey', function() {
        return function(data, key) {
            if (typeof(data) === 'undefined' || typeof(key) === 'undefined') {
                return 0;
            }

            var sum = 0;
            for (var i = data.length - 1; i >= 0; i--) {
                sum += parseInt(data[i][key]);
            }

            return sum;
        };
    });;