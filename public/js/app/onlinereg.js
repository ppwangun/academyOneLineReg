(function () {

    angular
    	.module('onlineReg', ['ngMaterial', 'ngAnimate'])
			.config(function($mdThemingProvider) {
		  		$mdThemingProvider.theme('default')
		    	.primaryPalette('pink')
		    	.accentPalette('blue');
		})

		.controller('formCtrl', ['$scope', '$http', function($scope, $http) {
                        var $ctrl = this;
                            $ctrl.student= null;
				$scope.formParams = {};
				$scope.stage = "";
				$scope.formValidation = false;
				$scope.toggleJSONView = false;
				$scope.toggleFormErrorsView = false;

				$scope.genders = ["male", "female", "other"];

				$scope.progressValue = 0;
			  
			/*
			|--------------------------------------------------------------------------
			|  Go NEXT
			|--------------------------------------------------------------------------
			*/
			$scope.next = function (stage) {
				$scope.formValidation = true;
			    
			    if ($scope.stdRegistrationForm.$valid) {
			    	$scope.direction = 1;
			    	$scope.stage = stage;
			    	$scope.formValidation = false;

			    	// increment progressbar
			    	$scope.progressValue += (100 / numCards);
			    }
			};

			/*
			|--------------------------------------------------------------------------
			|  GO BACK
			|--------------------------------------------------------------------------
			*/
			$scope.back = function (stage) {
				$scope.direction = 0;
			 	$scope.stage = stage;
			};
			  
			  
			/*
			|--------------------------------------------------------------------------
			|  Submit Form
			|--------------------------------------------------------------------------
			*/
			$scope.submitForm = function () {
			var wsUrl = "url";

			// Check form validity and submit data using $http
			if ($scope.multiStepForm.$valid) {
			  $scope.formValidation = false;

			  $http({
			    method: 'POST',
			    url: wsUrl,
			    data: JSON.stringify($scope.formParams)
			  }).then(function successCallback(response) {
			    if (response && response.data && response.data.status && response.data.status === 'success') {
			    	$scope.stage = "success";
			    } else {
			    	if (response && response.data && response.data.status && response.data.status === 'error') {
			        	$scope.stage = "error";
			      	}
			    }
			  }, function errorCallback(response) {
			    	$scope.stage = "error";
			  		console.log(response);
			  });
			}
			};
			  
			/*
			|--------------------------------------------------------------------------
			|  DESTROY
			|--------------------------------------------------------------------------
			*/
			$scope.reset = function() {
			    // Clean up scope before destorying
			    $scope.formParams = {};
			    $scope.stage = "";
			  }
			}]);


})();



