'use strict';

var studentApp = angular.module("student",["ngRoute",'ngMessages']).config(['$routeProvider','$mdDateLocaleProvider', function($routeProvider,$mdDateLocaleProvider) {

    $mdDateLocaleProvider.parseDate = function(dateString) {
      var m = moment(dateString, 'YYYY-MM-DD', true);
      return m.isValid() ? m.toDate() : new Date(NaN);
    };  
  
    $mdDateLocaleProvider.formatDate = function(date) {
      var m = moment(date);
      return m.isValid() ? m.format('YYYY-MM-DD') : '';
    };

}]).controller("studentDetailsCtrl",function($timeout,$http,$scope,fileReader,toastr,$window,$httpParamSerializer){    
    var $ctrl = this;
     $ctrl.student= {};
     //$ctrl.student.classe ;
     $ctrl.student.matricule; 
     $ctrl.countries = [];
     $ctrl.stds = [];
     $scope.sem1,$scope.sem2;
     $scope.nbreCredit1,$scope.nbreCredit2;
     $scope.items = [];
     $scope.items2 = [];
     $scope.selected1 = [],$scope.selected2 = [];
     $scope.currentYrSubjects = [];
     $scope.backlogs = [];
     $scope.semesters = [];
     $scope.totalCredits1 = 0,$scope.totalCredits2 = 0,$scope.totalCredits3 = 0,$scope.totalCredits4 = 0;
     $scope.disableSendButton1=true,$scope.disableSendButton2=true;
     $scope.subjectList1 = [],$scope.subjectList2 = [];
     $scope.isChecked=true;
     $ctrl.sponsor;
     $ctrl.submitInscriptionButtonDisable = false;
     
     
   $scope.dateChanged = function() {
    $ctrl.student.birthdate = moment($ctrl.student.birthdate).format('YYYY-MM-DD');
   
}  

    var std_id =$routeParams.id;
    $scope.init = function(){ 
    //$ctrl.chkSponsor($ctrl.sponsor);
    //Colecting student details based on matricule    
    var data = {id: std_id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };        
    $timeout($http.get('studentInfoDetails',config).then(function(response){
     $ctrl.student = response.data[0];
     
     
    })); 
    //End student

    //Collecting all countries
    $timeout(
        $http.get('countries').then(
        function successCallback(response){
            $ctrl.countries = response.data[0]; 
            $ctrl.countries_1 = response.data[0];
            $ctrl.countries_2 = response.data[0];
        },
        function errorCallback(response){
    }),1000); 
    //End Countries 
     
        $ctrl.sponsor = "PERE";
        var data = {classe: $ctrl.student.classe};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout($http.get('semesterByClasse',config).then(
                function(response){
                    $scope.semesters=response.data[0];
                    angular.forEach($scope.semesters,function(value,key){
                        if(value.ranking%2===1)
                            $scope.sem1 = value.code;
                        else
                            $scope.sem2 = value.code;
                    })
                    
            $http.get('subjectsByClasse',config).then(
                function(response){
                    $scope.currentYrSubjects = response.data[0];}

                ).then(function(){
            $http.get('registeredSubjectsByStudent').then(
                function(resp){
                    $scope.backlogs = resp.data[0];
                    
                    angular.forEach($scope.semesters,function(value,key){
                        angular.forEach($scope.backlogs,function(val,keys){
                            if((value.ranking%2===val.semRanking%2)&&(value.ranking%2===1))
                            {
                                $scope.selected1.push(val);
                            }else{
                                if((value.ranking%2===val.semRanking%2)&&(value.ranking%2===0))
                                {
                                   $scope.selected2.push(val);
                                }                                
                            }
                            
                        })
                        
                    });
                
                    $scope.subjectList1 = $scope.selected1;  
                    $scope.subjectList2 = $scope.selected2;                    
                    
                    })

                })                    

                }),1000)
  
    };
 
 $scope.calculateSum1= function (item) { 
   $scope.totalCredits1 += item.credits;
 } 
  $scope.calculateSum2= function (item) { 
   $scope.totalCredits2 += item.credits;
 } 
 $scope.calculateSum3= function (item) { 
   $scope.totalCredits3 += item.credits;
 }
  $scope.calculateSum4= function (item) { 
   $scope.totalCredits4 += item.credits;
 } 
$scope.resetTotalAmt = function () {
   $scope.totalCredits =0;
 }
    
$scope.toggleSelection1 = function toggleSelection1(sub) {
  var idx = $scope.subjectList1.indexOf(sub);
  
if ($scope.semesters[0].ranking >=13)
{
	$scope.disableSendButton1=false;
	$scope.disableSendButton2=false;
        $scope.subjectList1.push(sub);
	return;
}

  if (idx > -1) {
    // is currently selected
    $scope.subjectList1.splice(idx, 1);
     if($scope.chkCreditsExceeded($scope.subjectList1))
     {
          $scope.disableSendButton1=true;
          return ;
     }
      $scope.disableSendButton1=false;            
   }
   else {
     // is newly selected
     $scope.subjectList1.push(sub);
     if($scope.chkCreditsExceeded($scope.subjectList1))
     {
          $scope.disableSendButton1=true;
          return ;
     }
      $scope.disableSendButton1=false;    
   }

};

$scope.toggleSelection2 = function toggleSelection2(sub) {
  var idx = $scope.subjectList2.indexOf(sub);
  
if ($scope.semesters[0].ranking >=13)
{
	$scope.disableSendButton2=false;
	$scope.disableSendButton1=false;
        $scope.subjectList2.push(sub);
	return;
}

  if (idx > -1) {
    // is currently selected
    $scope.subjectList2.splice(idx, 1);
     if($scope.chkCreditsExceeded($scope.subjectList2))
     {
          $scope.disableSendButton2=true;
          return ;
     }
      $scope.disableSendButton2=false;            
   }
   else {
     // is newly selected
     $scope.subjectList2.push(sub);
     if($scope.chkCreditsExceeded($scope.subjectList2))
     {
          $scope.disableSendButton2=true;
          return ;
     }
      $scope.disableSendButton2=false;    
   }

};
    $scope.exist1 = function (item) {
      return ($scope.subjectList1.indexOf(item) > -1)
    };
    
    $scope.exist2 = function (item) {
      return ($scope.subjectList2.indexOf(item) > -1)
    };
 
    
    $scope.chkCreditsExceeded = function(list)
    {
        var credits=0; 
       if ($scope.semesters[0].ranking >=13)
		   return false;
	   
        angular.forEach(list,function(value,key){ 
            credits = credits + value.credits;
        });
        
         if(credits>36){
             
             alert("Vous ne devez pas excéder 36 Credits par semestre");
             return true;
         }
         
         return false;
        
    };
    
     $scope.nbreCreditInscrit = function(list)
    {
        var credits=0; 
      
        angular.forEach(list,function(value,key){ 
            credits = credits + value.credits;
        });
        return credits;
    };   

     var temp =[];
     var std_id;
     var data; 
     
     
 
    $ctrl.getRegions = function(countryCode){
        
        var data = {code: countryCode};
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
    };
 
     $ctrl.getCities = function(countryId){
        
        var data = {id: countryId};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.get('cities',config).then(

                function(response){
                    $ctrl.cities=response.data[0];

                }),1000);        
    }

     $ctrl.getCities_1 = function(countryId){
        
        var data = {id: countryId};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.get('cities',config).then(

                function(response){
                    $ctrl.cities_1=response.data[0];

                }),1000);        
    }
     $ctrl.getCities_2 = function(countryId){
        
        var data = {id: countryId};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.get('cities',config).then(

                function(response){
                    $ctrl.cities_2=response.data[0];

                }),1000);        
    }
  
$ctrl.isBaccalaureat = function(typeCertificate){
   
    if(typeCertificate === "BACCALAUREAT" || typeCertificate ==="GCE/A" || typeCertificate ==="BREVET_DE_TECHNICIEN")
        return true;
    return false;
}

$ctrl.chkHandicap = function(handicap){
    if(handicap==="OUI")
        return true
    return false;
}

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

$ctrl.submitRegistrationForm = function(){
        
        var data = $.param($ctrl.student);
        var config = {
        
        headers : {'Content-Type' : "application/x-www-form-urlencoded;charset=utf-8;"}
        };
        //Loading selected class information for update //
         $("#spinner").show();
        $timeout(
               
        $http.post('saveRegistration',data,config).then(
            function successCallback(response){
                //$window.location.href = "insPedagogique";
          toastr.error("le type du fichier image non supporté");
             }, function errorCallback(response){
            alert(" Une erreur inattendue s'est produite") ;
        }),1000);      
};
$ctrl.submitNewStdRegistrationForm = function(){

        var data = $.param($ctrl.student);
        var config = {
        
        headers : {'Content-Type' : "application/x-www-form-urlencoded;charset=utf-8;"}
        };
        //Loading selected class information for update //
        $timeout(

        $http.post('saveNewStudentRegistration',data,config).then(
            function successCallback(response){
                if(!response.data)
                {
                 toastr.error("le type du fichier image non supporté");
                 return;
                }
                toastr.success("Vos informations sont enrégistréesavec suscess")
                $window.location.href = "insPedagogique";
         
             }, function errorCallback(response){
            alert(" Une erreur inattendue s'est produite") ;
        }),1000);      
};

$ctrl.submitInscriptionPedagogique = function(){

        var subjects = $scope.subjectList1;
        var i=0,sub={subjects:[]};
        subjects = subjects.concat($scope.subjectList2);
        $ctrl.submitInscriptionButtonDisabled= true;
       
        angular.forEach(subjects,function(value,key){
            if(value.idUe)
            {
            sub.subjects[i]={idUe:value.idUe,sem:value.semester,semID:value.semID}; 
            
            i++;
        }
        else
        {
            sub.subjects[i]={idUe:value.id,sem:value.semester,semID:value.semID}; 
            i++;            
        }
            
        });
       
       // subjects.matricule = $ctrl.student.matricule;
        var data = {subjects:sub};
        var config = {
        params: data,
        headers : {'Content-Type' : "application/x-www-form-urlencoded;charset=utf-8;"}
        };
        //Loading selected class information for update //
        $timeout(

        $http.get('saveInsPedagogique',config).then(
            function successCallback(response){
                
history.pushState(null, null, $(location).attr('href'));
    window.addEventListener('popstate', function () {
        history.pushState(null, null, $(location).attr('href'));
    });               
                $window.location.href = "endRegistration";
         
             }, function errorCallback(response){
            alert(" Une erreur inattendue s'est produite") ;
        }),1000);      
};

 
 
 
 
 


});

studentApp.directive("ngFileSelect", function(fileReader, $timeout) {
    return {
      scope: {
        ngModel: '='
      },
      link: function($scope, el) {
        function getFile(file) {
          fileReader.readAsDataUrl(file, $scope)
            .then(function(result) {
              $timeout(function() {
                $scope.ngModel = result;
              });
            });
        }

        el.bind("change", function(e) {
          var file = (e.srcElement || e.target).files[0];
          getFile(file);
        });
      }
    };
  });

studentApp.factory("fileReader", function($q, $log) {
  var onLoad = function(reader, deferred, scope) {
    return function() {
      scope.$apply(function() {
        deferred.resolve(reader.result);
      });
    };
  };

  var onError = function(reader, deferred, scope) {
    return function() {
      scope.$apply(function() {
        deferred.reject(reader.result);
      });
    };
  };

  var onProgress = function(reader, scope) {
    return function(event) {
      scope.$broadcast("fileProgress", {
        total: event.total,
        loaded: event.loaded
      });
    };
  };

  var getReader = function(deferred, scope) {
    var reader = new FileReader();
    reader.onload = onLoad(reader, deferred, scope);
    reader.onerror = onError(reader, deferred, scope);
    reader.onprogress = onProgress(reader, scope);
    return reader;
  };

  var readAsDataURL = function(file, scope) {
    var deferred = $q.defer();

    var reader = getReader(deferred, scope);
    reader.readAsDataURL(file);

    return deferred.promise;
  };

  return {
    readAsDataUrl: readAsDataURL
  };
});

studentApp.directive('validFile', function () {
return {
    require: 'ngModel',
    link: function (scope, elem, attrs, ngModel) {
        var validFormats = ['jpg','jpeg','png'];
        elem.bind('change', function () {
            validImage(false);
           /* scope.$apply(function () {
                ngModel.$render();
            });*/
        });
        ngModel.$render = function () {
            ngModel.$setViewValue(elem.val());
        };
        function validImage(bool) {
            ngModel.$setValidity('extension', bool);
        }
        ngModel.$parsers.push(function(value) {
            var ext = value.substr(value.lastIndexOf('.')+1);
            if(ext=='') return;
            if(validFormats.indexOf(ext) == -1){
                return value;
            }
            validImage(true);
            return value;
        });
    }
  };
});