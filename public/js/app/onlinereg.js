(function () {

    angular.module('onlineReg', ['ngMaterial', 'ngAnimate'])
			.config(function($mdThemingProvider) {
		  		$mdThemingProvider.theme('default')
		    	.primaryPalette('blue')
		    	.accentPalette('blue');
		}).controller('formCtrl', ['$scope', '$http','$timeout','$mdDialog', function($scope, $http, $timeout,$mdDialog,$window,$location) {
                        var $ctrl = this;
                            $ctrl.student= {fac_id:-1,fil_id:-1};
				$scope.formParams = {};
				$scope.stage = "";
                                $scope.deg = null
				$scope.formValidation = false;
				$scope.toggleJSONView = false;
				$scope.toggleFormErrorsView = false;
                                $ctrl.student.uploadFinancialProof = [];
                                $ctrl.student.uploadCv = [];
$scope.baccalaureat = [
 {
  "code": "A4",
  "name": "BAC A4"
 },
 {
  "code": "AAT",
  "name": "BAC AAT"
 },
 {
  "code": "ACA",
  "name": "BAC ACA"
 },
 {
  "code": "ACC",
  "name": "BAC ACC"
 },
 {
  "code": "AV",
  "name": "BAC AV"
 },
 {
  "code": "AC",
  "name": "BAC AC"
 },
 {
  "code": "AF1",
  "name": "BAC AF1"
 },
 {
  "code": "AF2",
  "name": "BAC AF2"
 },
 {
  "code": "AF3",
  "name": "BAC AF3"
 },
 {
  "code": "BIJO",
  "name": "BAC BIJO"
 },
 {
  "code": "F6-BIPE",
  "name": "BAC F6-BIPE"
 },
 {
  "code": "B-PA",
  "name": "BAC B-PA"
 },
 {
  "code": "C",
  "name": "BAC C"
 },
 {
  "code": "CH-TI",
  "name": "BAC CH-TI"
 },
 {
  "code": "CI",
  "name": "BAC CI"
 },
 {
  "code": "CG",
  "name": "BAC CG"
 },
 {
  "code": "CMA-MVT",
  "name": "BAC CMA-MVT"
 },
 {
  "code": "CMA-MVPL",
  "name": "BAC CMA-MVPL"
 },
 {
  "code": "F6-COPH",
  "name": "BAC F6-COPH"
 },
 {
  "code": "CU",
  "name": "BAC CU"
 },
 {
  "code": "D",
  "name": "BAC D"
 },
 {
  "code": "E",
  "name": "BAC E"
 },
 {
  "code": "ESF",
  "name": "BAC ESF"
 },
 {
  "code": "F2",
  "name": "BAC F2"
 },
 {
  "code": "F3",
  "name": "BAC F3"
 },
 {
  "code": "F1",
  "name": "BAC F1"
 },
 {
  "code": "ABI",
  "name": "BAC ABI"
 },
 {
  "code": "FIG",
  "name": "BAC FIG"
 },
 {
  "code": "F5",
  "name": "BAC F5"
 },
 {
  "code": "F4-BA",
  "name": "BAC F4-BA"
 },
 {
  "code": "F4-BE",
  "name": "BAC F4-BE"
 },
 {
  "code": "F4-TP",
  "name": "BAC F4-TP"
 },
 {
  "code": "GT\/TOPO ou GTTO",
  "name": "BAC GT\/TOPO ou GTTO"
 },
 {
  "code": "IH",
  "name": "BAC IH"
 },
 {
  "code": "IB",
  "name": "BAC IB"
 },
 {
  "code": "IS",
  "name": "BAC IS"
 },
 {
  "code": "MEA",
  "name": "BAC MEA"
 },
 {
  "code": "MEM",
  "name": "BAC MEM"
 },
 {
  "code": "MISE",
  "name": "BAC MISE"
 },
 {
  "code": "MHB",
  "name": "BAC MHB"
 },
 {
  "code": "MA",
  "name": "BAC MA"
 },
 {
  "code": "MEB",
  "name": "BAC MEB"
 },
 {
  "code": "F6-MIPE",
  "name": "BAC F6-MIPE"
 },
 {
  "code": "AG-PA",
  "name": "BAC AG-PA"
 },
 {
  "code": "AG-PV",
  "name": "BAC AG-PV"
 },
 {
  "code": "RB",
  "name": "BAC RB"
 },
 {
  "code": "SES",
  "name": "BAC SES"
 },
 {
  "code": "F7-BIOLAP",
  "name": "BAC F7-BIOLAP"
 },
 {
  "code": "F7",
  "name": "BAC F7"
 },
 {
  "code": "F8",
  "name": "BAC F8"
 },
 {
  "code": "SH",
  "name": "BAC SH"
 },
 {
  "code": "TGF",
  "name": "BAC TGF"
 },
 {
  "code": "TI",
  "name": "BAC TI"
 },
 {
  "code": "AG-TP",
  "name": "BAC AG-TP"
 }
]

$scope.annee = [
 {
  "annee": "2020/2021"
 },
 {
  "annee": "2021/2022"
 },
 {
  "annee": "2022/2023"
 },
 {
  "annee": "2023/2024"
 },
 {
  "annee": "2024/2025"
 },
 {
  "annee": "2025/2026"
 },
 {
  "annee": "2026/2027"
 },
 {
  "annee": "2028/2029"
 },
 {
  "annee": "2030/2031"
 },
 {
  "annee": "2031/2032"
 },
 {
  "annee": "2032/2033"
 },
 {
  "annee": "2033/2034"
 }
]
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
    $ctrl.searchDegree = function(cycle_id){
      var data = {cycle_id:cycle_id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };
        $http.get('searchDegree',config).then(
            function successCallback(response){
                $ctrl.cpt =1;
                $ctrl.student.degree_id = null;
                $ctrl.student.filiere= "";
                $ctrl.student.faculty = "";
                $scope.degrees = response.data[0];
                $ctrl.student.filiere = $scope.degrees.filiere;
                $ctrl.student.faculty = $scope.degrees.faculty;

                
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
   
        angular.forEach($ctrl.student.uploadFinancialProof,function(file){
          fd.append('file[]',file); 
          });
        angular.forEach($ctrl.student.uploadCv,function(file){
          fd.append('file1[]',file); 
          });  
        /*angular.forEach($ctrl.student.uploadCv,function(file){
          fd.append('file[]',file);
          });  */         
        angular.forEach($ctrl.student.uploadLastDegree,function(file){
          fd.append('file2[]',file);
          });           
        angular.forEach($ctrl.student.uploadTranscript,function(file){
          fd.append('file3[]',file);
          });
        angular.forEach($ctrl.student.uploadBirthCertificate,function(file){
          fd.append('file4[]',file);
          });
        angular.forEach($ctrl.student.uploadIdentity,function(file){
          fd.append('file5[]',file);
          });          
         
       // });
        angular.forEach($scope.image1.compressed.dataURL,function(file){
          fd.append('img_file[]',file);
        });       
        var config  = {
          params: {fd:fd,student:$ctrl.student},
          headers: {'Content-Type': undefined}
        }

        $scope.myPromise = $http.post("submitRegistrationForm",fd,config).then(
            function successCallback(response){
               
               //window.location.href="endApplication"
                
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
                                var validFileSize = true;
                                var fd = new FormData();
                                
                        angular.forEach($ctrl.student.uploadFiles,function(file,ev){
                            var size = file.size / 1024 / 1024;
                        if(parseFloat(size) > 2) 
                        {
                            validFileSize = false;
                            $mdDialog.show(
                                $mdDialog.alert()
                                  .parent(angular.element(document.querySelector('#popupContainer')))
                                  .clickOutsideToClose(true)
                                  .title('Attention!!!!!!!')
                                  .textContent('Assurez vous  que la taille des fichiers importés n\'est pas supérieur à 2MB')
                                  .ariaLabel('Alert Dialog Demo')
                                  .ok('OK')
                                  .targetEvent(ev)
                              );
                            console.log(file); 
                            
                        }else fd.append('file[]',file);
                        
                        
                        });

                            if(!validFileSize) return;    
			    
			    if ($scope.stdRegistrationForm.$valid) {
			    	$scope.direction = 1;
			    	$scope.stage = stage;
			    	$scope.formValidation = false;

			    	// increment progressbar
			    	$scope.progressValue += (100 / 8);
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
    }]).directive('validFile', function () {
    return {
      restrict: "A",
      require: "ngModel",
      link: function (scope,elem,attrs,ngModel) {
            
        elem.bind("change", function(e) {
          console.log("change");
          scope.$apply(function(){
              ngModel.$valid=true;
              ngModel.$invalid=false;
          });
        });
      }
    };
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



