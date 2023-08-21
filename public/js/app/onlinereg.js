(function () {

    angular.module('onlineReg', ['ngMaterial', 'ngAnimate'])
			.config(function($mdThemingProvider) {
		  		$mdThemingProvider.theme('default')
		    	.primaryPalette('blue')
		    	.accentPalette('blue');
		}).controller('formCtrl', ['$scope', '$http','$timeout', function($scope, $http, $timeout,$window,$location) {
                        var $ctrl = this;
                            $ctrl.student= {fac_id:-1,fil_id:-1};
				$scope.formParams = {};
				$scope.stage = "";
                                
				$scope.formValidation = false;
				$scope.toggleJSONView = false;
				$scope.toggleFormErrorsView = false;

				$scope.genders = ["male", "female", "other"];

				$scope.progressValue = 0;
                                
     $scope.image1  ={compressed: {dataURL: "https://placehold.it/80x80"}};
     $ctrl.student.cursus = [{"annee":'',"Etablissement":'',"diplome":'',"mention":''}];
     
     $ctrl.addCursus = function(){
         $ctrl.student.cursus.push({"annee":'',"Etablissement":'',"diplome":'',"mention":''});
         
     }
     
    $ctrl.student.uploadFile = [];                          
     $scope.init = function(){ 
    //$ctrl.chkSponsor($ctrl.sponsor);
    //Colecting student details based on matricule  
  
  


    //Collecting all countries
    //Collecting all countries
    $timeout(
        $http.get('countries').then(
        function successCallback(response){
            $ctrl.countries = response.data[0]; 
            $ctrl.countries_1 = response.data[0];
            $ctrl.countries_2 = response.data[0];
        },
        function errorCallback(response){
    }).then(function(response){
        var data = {code: "CM"};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.get('regions',config).then(

                function(response){
                    $ctrl.regions=response.data[0];

                }),1000);        
    }).then(function(response){
        var data = {id: "CM"};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.get('cities',config).then(

                function(response){
                    $ctrl.cities=response.data[0];
                    $ctrl.cities_1=response.data[0];

                }),1000);        
    }),1000);
    
     $http.get('faculty')
            .then(
            function (response){
               $scope.faculties = response.data[0]
               
            })
    
//loading filed of study information to fill autocomplete field
           /* $http.get('filiere').then(function(response){
                  $scope.filieres = response.data[0]; 

               
            });*/
    $timeout($http.get('searchCycleFormation').then(
        function(response){
        $scope.cycles = response.data[0];
    }),1000);         
            
//loading degrees information to fill autocomplete field
            $http.get('degree').then(function(response){
     
                    $scope.degrees = response.data[0];
            });    
    } 
    
    
     /*--------------------------------------------------------------------------
     *--------------------------- loading all filières by faculty   ---------------------
     *----------------------------------------------------------------------- */
    $ctrl.searchFilByFaculty = function(id){
      var data = {fac_id: id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchFilByFaculty',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $scope.filieres = response.data[0];
                $ctrl.student.fil_id = -1;
                
            },
            function errorCallback(response){
             
            });    
        
    }
    
    /*--------------------------------------------------------------------------
     *--------------------------- loading all filières by faculty   ---------------------
     *----------------------------------------------------------------------- */
    $ctrl.searchDegree = function(cycle_id,fil_id){
      var data = {fil_id: fil_id,cycle_id:cycle_id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchDegree',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $scope.degrees = response.data[0];

                
            },
            function errorCallback(response){
             
            });    
        
    }
    
        /*--------------------------------------------------------------------------
     *--------------------------- Uploading form  ---------------------
     *----------------------------------------------------------------------- */
    
    $scope.submitRegistrationForm = function()
    {
        
        var fd = new FormData();
   
        angular.forEach($ctrl.student.uploadFiles,function(file){
          fd.append('file[]',file);
        });
        angular.forEach($scope.image1.compressed.dataURL,function(file){
          fd.append('img_file[]',file);
        });       
        var config  = {
          params: {fd:fd,student:$ctrl.student},
          headers: {'Content-Type': undefined}
        }

        $scope.myPromise = $http.post("submitRegistrationForm",fd,config).then(
            function successCallback(response){
               
               window.location.href="endApplication"
                
            },
            function errorCallback(response){
             
            }        
        )

   /*$scope.myPromise =$http({
     method: 'post',
     url: 'submitRegistrationForm',
     data: fd,
     headers: {'Content-Type': undefined},
   }).then(function successCallback(response) {  
     // Store response data
     $scope.response = response.data;
   });*/

      
    }
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
			    	$scope.progressValue += (100 / 6);
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
                          
                        $ctrl.sponsor = "PERE";

                        $ctrl.chkSponsor = function(typeSponsor)
                        {

                            if(typeSponsor==="PERE")
                            {
                                $ctrl.student.sponsorName = $ctrl.student.fatherName;
                                $ctrl.student.sponsorProfession = $ctrl.student.fatherProfession;
                                $ctrl.student.sponsorCountry = $ctrl.student.fatherCountry;
                                $ctrl.student.sponsorCity = $ctrl.student.fatherCity;
                                $ctrl.student.sponsorPhoneNumber = $ctrl.student.fatherPhoneNumber;
                                $ctrl.student.sponsorEmail = $ctrl.student.fatherEmail;
                                return true;
                            }
                            if(typeSponsor==="MERE")
                            {
                                $ctrl.student.sponsorName = $ctrl.student.motherName;
                                $ctrl.student.sponsorProfession = $ctrl.student.motherProfession;
                                $ctrl.student.sponsorCountry = $ctrl.student.motherCountry;
                                $ctrl.student.sponsorCity = $ctrl.student.motherCity;
                                $ctrl.student.sponsorPhoneNumber = $ctrl.student.motherPhoneNumber;
                                $ctrl.student.sponsorEmail = $ctrl.student.motherEmail;        
                                return true;
                            }

                            if(typeSponsor==="AUTRE")
                            {

                                return false;
                            }

                            return true;
                        }                            
                }]).directive('validFile',function(){
                    return {
                        require:'ngModel',
                        link:function(scope,el,attrs,ctrl){
                            ctrl.$setValidity('validFile', el.val() != '');
                            //change event is fired when file is selected
                            el.bind('change',function(){
                                ctrl.$setValidity('validFile', el.val() != '');
                                scope.$apply(function(){
                                    ctrl.$setViewValue(el.val());
                                    ctrl.$render();
                                });
                            });
                        }
                    }
}).directive('ngFile', ['$parse', function ($parse) {
  return {
   restrict: 'A',
   link: function(scope, element, attrs) {
     element.bind('change', function(){

     $parse(attrs.ngFile).assign(scope,element[0].files)
     scope.$apply();
   });
  }
 };
}]);
                    
                  


})();



