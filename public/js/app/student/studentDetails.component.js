'use strict';

var studentApp = angular.module('student')

        .component('studentDetails',{
            controller: studentDetailsCtrl ,
            templateUrl: 'studentinfostpl',

            
}).config(['$routeProvider','$mdDateLocaleProvider', function($routeProvider,$mdDateLocaleProvider) {

    $mdDateLocaleProvider.parseDate = function(dateString) {
      var m = moment(dateString, 'YYYY-MM-DD', true);
      return m.isValid() ? m.toDate() : new Date(NaN);
    };  
  
    $mdDateLocaleProvider.formatDate = function(date) {
      var m = moment(date);
      return m.isValid() ? m.format('YYYY-MM-DD') : '';
    };

}]);

//Student controller
function studentDetailsCtrl($timeout,$http,$location,$mdDialog,$scope,$routeParams,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var $ctrl = this;
    $ctrl.imgUrl;
     $ctrl.students=[];
     $ctrl.stds = [];
     $ctrl.subjects=[];
     $ctrl.classes = [];
     $ctrl.payments = [];
     $ctrl.student= {};
     $ctrl.sponsor = "PERE";
     $ctrl.querySearch   = querySearch;
     var temp =[];
     var std_id;
     var data; 
     
      //collectstudent registered subject
    std_id =$routeParams.id;
    var data = {id: std_id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };     
    $ctrl.init = function(){
    
    $ctrl.id = $routeParams.id;
    
    $ctrl.imgUrl = 'zip\\photo'+std_id+'.png';
    
     
        
    $timeout($http.get('studentInfoDetails',config).then(function(response){
     $ctrl.student = response.data[0];
     
     $ctrl.student.imageSrc = 'data:image/png;base64,'+$ctrl.student.photo;
          
    }),1000); 
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
    
     
        
    
     $timeout(
     $http.get('subjectregistration',config).then(
     function successCallback(response){
        
        $ctrl.subjects = response.data[0];
       
       
     },
     function errorCallback(response){
         
     }),1000);
     
     //collect and load all available classes
     $timeout(
     $http.get('classes').then(function(response){
         //convert all loaded data to lower case
        angular.forEach(response.data[0],function(item){

          $ctrl.classes.push({id:item.id,name:item.code, value:item.code.toLowerCase()}); 

        });
     }),1000);
     
      
}; //END inti function

//Function for deleting a subject
     $ctrl.deleteSubject = function(subject,ev)
      {
      
      var data = {id: subject.id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

       // Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer Cette UE?')
            .textContent('Elle sera enlevée de la liste des UE que cet étudiant devra suivre au cours de cette année académique')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Supprimer')
            .cancel('Annuler');
    //open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('subjectregistration',config).then(
          function successCallback(response){
              
              response.data[0]?toastr.success('Succès'):toastr.error('Erreur', 'Erreur');
              //check the index of the current object in the array
              var index = $ctrl.subjects.findIndex(x => x.id === subject.id)
              //remove the current object from the array
              $ctrl.subjects.splice(index,1);

         },
        function errorCallback(response){
            toastr.error('Erreur', 'Erreur');
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  }; //END delete
     
 //Collection student payment details       
    $timeout( $http.get('journaltier',config).then(
            function successCallback(response){

               $ctrl.payments = response.data[0];
               $ctrl.totalPayments = 0;
                for(var i=0;i<$ctrl.payments.length;i++)
                {
                    $ctrl.payments[i].num = i+1;
                    $ctrl.payments[i].dateTransaction = $ctrl.payments[i].dateTransaction.date;
                    
                    $ctrl.totalPayments = $ctrl.totalPayments + parseInt($ctrl.payments[i].amount);
                    
                }
            },
            function errorCallback(response){

            }),1000);     
 
  //reset pedagogic inscription
  $ctrl.resetPedagogicRegistration= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription administrative')
            .textContent('Cette opération va réinitialiser le processus d\'inscription administrative. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('resetPedagogicRegistration',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 };

  //reset pedagogic inscription
  $ctrl.resetRegistration= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription académique')
            .textContent('Cette opération va réinitialiser le processus d\'inscription académique. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('resetAdministrativeRegistration',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 }; 
 
  //suspend registration
  $ctrl.suspendRegistration= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription académique')
            .textContent('Cette opération va réinitialiser le processus d\'inscription académique. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('suspendRegistration',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 }; 
 
   //reset pedagogic inscription
  $ctrl.leaveTraining= function(ev){
      
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Réinitialisation de l\'inscription académique')
            .textContent('Cette opération va réinitialiser le processus d\'inscription académique. voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        
        var data = {matricule: std_id};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };         
        $timeout(
         $http.get('leaveTraining',config).then(
         function successCallback(response){
           // response.data[0]; 
            toastr.success("Opération effectué aavec succès");

         },
         function errorCallback(response){
             toastr.error("Une erreure inattendue s'est produite");
         }),1000);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 }; 
 
 $ctrl.printRegistrationFile = function(ev){
    
          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printRegistrationFile/'+$ctrl.student.matricule+'/'+$ctrl.student.classe,
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        }); 
        
         //Dialog Controller
        function DialogController($scope, $mdDialog,readFileData) {
            
            
        $scope.cancel = function() {
            $mdDialog.cancel();
        };

        $scope.answer = function(answer) {
          $mdDialog.hide(answer);
        };   

        };

    };

$ctrl.printStudentCard = function(ev){
    
          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printStudentCard/'+$ctrl.student.matricule+'/'+$ctrl.student.classe,
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        }); 
        
         //Dialog Controller
        function DialogController($scope, $mdDialog,readFileData) {
            
            
        $scope.cancel = function() {
            $mdDialog.cancel();
        };

        $scope.answer = function(answer) {
          $mdDialog.hide(answer);
        };   

        };

    };    
    
    
 $ctrl.printScholarshipCertificate = function(ev){
      
          $mdDialog.show({
          controller: DialogController,
          templateUrl: 'home',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        }); 
        
         //Dialog Controller
        function DialogController($scope, $mdDialog,readFileData) {
            
            
        $scope.cancel = function() {
            $mdDialog.cancel();
        };

        $scope.answer = function(answer) {
          $mdDialog.hide(answer);
        };   

        };
    

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
    DTColumnDefBuilder.newColumnDef(5)
  ];

    $ctrl.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
 
 
 
    /*--------------------------------------------------------------------------
     *---------------------------Loading subjects-------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.addSubject = function(ev){
        $scope.isUpdate= true;

        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/addSubjectForm.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true // Only for -xs, -sm breakpoints.
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    

    
    
    //Dialog Controller
function DialogController($scope, $mdDialog, $q,toastr) {
    $scope.selectedSubject= null;
    $scope.subjects = [];
    $scope.selectedSubject = null;

    //Loading subjects asynchronously
    $scope.query = function(subject)
    {
       var  dataString = {id: subject},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('subjectsearch',config).then(function(response){
                   return response.data[0];
                });
     };
  
//Select subjects to be add    
     $scope.addSubject = function()
     {
         //Cehck if the value exist in the array be for pushinng
         //Avoiding duplicates
         if ($scope.subjects.includes($scope.selectedSubject) === false) $scope.subjects.push($scope.selectedSubject);
         
         
     };
//Delete selected subject
     $scope.removeSubject = function(sub){
         var index = $scope.subjects.indexOf(sub);
         $scope.subjects.splice(index,1);
     };


    // Saving choices made     
     $scope.saveChoices = function(){

        var data = {id: std_id,
            subjects : $scope.subjects
        };
        var config = {
        //params: data,
        headers : {'Content-Type' : 'application/json'}
        };
         $timeout(
         $http.post('subjectregistration',JSON.stringify(data)).then(
         function successCallback(response){
            
            response.data[0]?toastr.success('Informations sauvegardées avec succès'):toastr.error('Erreur lors de la sauvegarde des information', 'Erreur');
           $ctrl.subjects =  $ctrl.subjects.concat($scope.subjects)
            $mdDialog.hide();

         },
         function errorCallback(response){

         }),1000);
         
     }

 

  }
  
      $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };   
     
/**
     * Search for fielod of study ... use $timeout to simulate
     * remote dataservice call.
     */
    function querySearch (query) {
      var results = query ? $ctrl.classes.filter( createFilterFor(query) ) : $ctrl.classes,
          deferred;
      if ($ctrl.simulateQuery) {
        deferred = $q.defer();
        $timeout(function () { deferred.resolve( results ); }, Math.random() * 1000, false);
        return deferred.promise;
      } else {
        return results;
      }
    }

    /**
     * Create filter function for a query string
     */
    function createFilterFor(query) {
      var lowercaseQuery = query.toLowerCase();

      return function filterFn(filiere) { 
        
        return (filiere.value.indexOf(lowercaseQuery) === 0);
      };

    }

 
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
};

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
            if(ext==='') return;
            if(validFormats.indexOf(ext) ===-1){
                return value;
            }
            validImage(true);
            return value;
        });
    }
  };
});