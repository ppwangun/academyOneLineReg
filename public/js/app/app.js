'use strict';

// Declare app level module which depends on views, and components
angular.module('myApp', [
  'ngMaterial',
  'ngMessages',
  'ngRoute',
  'toastr',
  'angular-loading-bar',
  'dashboard',
  'myApp.acadyrList',
  'app.acadyr',
  'faculty',
  'department',
  'cycle',
  'filiere',
  'specialite',
  'degree',
  'classes',
  'student',
  'payment',
  'exam',
  'users',
  'teachingunit',
  'myApp.version',
  'datatables',
  'datatables.buttons',
  'datatables.fixedheader',
  'ui.calendar', 

]).
config(['$locationProvider', '$routeProvider', function($locationProvider, $routeProvider) {
  $locationProvider.hashPrefix('!');

  $routeProvider
          .when('/prospects',{
              template: '<prospective-std></prospective-std>'
          }).when('/prospectDetails/:id',{
              template: '<prospective-details></prospective-details>'
          })
          .when('/dashboard',{
              templateUrl: 'dashboard'
          })
          .when('/admission',{
              templateUrl: 'admission'
          })  
          .when('/newStdRegistrationFeesMgt',{
              templateUrl: 'newStdRegistrationFeesMgt'
          })          
          .when('/faculty',{
              template: '<faculty-list></faculty-list>'
          })
          .when('/department',{
              template: '<department-list></department-list>'
          })   
          .when('/newDpt/:id',{
              template: '<department-details></department-details>'
          })    
          .when('/updateDpt/:id',{
              template: '<update-dpt></update-dpt>'
          })   
          .when('/newFil/:id',{
              template: '<filiere-details></filiere-details>'
          })    
          .when('/updateFil/:id',{
              template: '<update-fil></update-fil>'
          })          
          .when('/filiere',{
              template: '<filiere-list></filiere-list>'
          })
          .when('/newSpe/:id',{
              template: '<speciality-details></speciality-details>'
          })    
          .when('/updateSpe/:id',{
              template: '<update-spe></update-spe>'
          })          
          .when('/specialite',{
              template: '<speciality-list></speciality-list>'
          }) 
          .when('/cycleFormation',{
              template: '<cycle-details></cycle-details>'
          })
          .when('/newCycle/:id',{
              template: '<new-cycle></new-cycle>'
          }) 
          .when('/updateCycle/:id',{
              template: '<update-cycle></update-cycle>'
          })          
          .when('/degree',{
              template: '<degree-details></degree-details>'
          })
          .when('/newdegree',{
              template: '<new-degree></new-degree>'
          })
          .when('/updatedegree/:id',{
              template: '<new-degree></new-degree>'
          })
          .when('/classes',{
              template: '<classes-details></classes-details>'
          })
          .when('/newclasse',{
              template: '<new-classe></new-classe>'
          }).when('/newclasse/:id',{
              template: '<new-classe></new-classe>'
          }).when('/assignedteachingunit',{
              template: '<assignedteachingunit-details></assignedteachingunit-details>'
          }).when('/assignnewteachingunit/:id/:ue_class_id/:ue_sem_id',{
              template: '<assignnewteachingunit-details></assignnewteachingunit-details>'
          }).when('/assignnewteachingunit',{
              template: '<assignnewteachingunit-details></assignnewteachingunit-details>'
          })
          .when('/teachingunit',{
              template: '<teachingunit-details></teachingunit-details>'
          })
          .when('/courseProgramming',{
              template: '<course-programming></course-programming>'
          }) 
          .when('/classroom',{
              template: '<classroom></classroom>'
          })          
          .when('/newteachingunit',{
              template: '<new-teachingunit></new-teachingunit>'
          }).when('/newteachingunit/:id/:ue_class_id',{
              template: '<new-teachingunit></new-teachingunit>'
          }).when('/students',{
              template: '<student-list></student-list>'
          }).when('/studentinfos/:id', {
              template: '<student-details></student-details>',
              resolve:{
                    "check":function(accessFac,$location,$route){
                        var access = false;
                        var perm = '';
               
                        accessFac.checkClassePermission(perm,$route.current.params.id).then(function(data){
                           access = data; 
                        
                        //function to be resolved, accessFac and $location Injected
                        if(access){    //check if the user has permission -- This happens before the page loads
                       
                        }else{
                            $location.path('/');                //redirect user to home if it does not have permission.
                            alert("Accès non autorisé");
                        }
                        });

                    }
                },
              
          })
          .when('/stdList',{
              templateUrl: 'stdList'
          })
          .when('/pedagogicalReg',{
              template: '<pedagogical-reg></pedagogical-reg>'
          })          
          .when('/transcripts',{
              templateUrl: 'transcripts'
          })
          .when('/scholarshipCertificates',{
              templateUrl: 'scholarshipCertificates'
          }) 
          .when('/studentCards',{
              templateUrl: 'studentCards'
          })          
          .when('/registrationStat',{
              templateUrl: 'registrationStat'
          })          
          .when('/payments', {
              template: '<payment-list></payment-list>'
              
          })
          .when('/paymentdetails/:id', {
              template: '<payment-details></payment-details>'
              
          })
          .when('/moratorium', {
              template: '<moratorium-list></moratorium-list>'
              
          }).when('/newmoratorium',{
              template: '<new-moratorium></new-moratorium>'
          }).when('/examlist',{
              template: '<exam-list></exam-list>'
          }).when('/newexam',{
              template: '<new-exam></new-exam>'
          }).when('/calculnotes',{
              template: '<calcul-notes></calcul-notes>'
          }).when('/calculmps',{
              template: '<calcul-mps></calcul-mps>'
          }).when('/newexam/:exam_id', {
              template: '<new-exam></new-exam>'
              
          }).when('/gradeconfig',{
              template: '<grade-config></grade-config>'
          }).when('/newgrade',{
              template: '<new-grade></new-grade>'
          }).when('/newgrade/:id',{
              template: '<new-grade></new-grade>'
          }).when('/settings',{
              template: '<settings></settings>'
          }).when('/deliberation',{
              template: '<delib-config></delib-config>'
          }).when('/deliberation/:id',{
              templateUrl: 'admission'
          }).when('/newDelibCondition',{
              template: '<newdelib-config></newdelib-config>'
          }).when('/newDeliberation/:id',{
              template: '<newdelib-config></newdelib-config>'
          }).when('/suiviparcourt',{
              template: '<suivi-parcourt></suivi-parcourt>'
          }).when('/usermanagement',{
              template: '<user-management></user-management>'
          }).when('/newuser',{
              template: '<new-user></new-user>'
          }).when('/newuser/:user_id', {
              template: '<new-user></new-user>'
              
          }).when('/groupmanagement', {
              template: '<group-management></group-management>',
                  resolve:{
                    "check":function(accessFac,$location){
                        var access = false;
                        var perm = 'manage.groups';
                        
                        accessFac.checkPermission(perm).then(function(data){
                           access = data; 
                        
                        //function to be resolved, accessFac and $location Injected
                        if(access){    //check if the user has permission -- This happens before the page loads

                        }else{
                            $location.path('/');                //redirect user to home if it does not have permission.
                            alert("Vous n'ets pas autoriser à accèder à l'objet: "+perm);
                        }
                        });

                    }
                },
              
          }).when('/newgroup/', {
              template: '<new-group></new-group>'
              
          }).when('/newgroup/:group_id', {
              template: '<new-group></new-group>'
              
          }).when("/teacher-list", {
            template: "<manage-teacher></manage-teacher>",
            
        }).when("/new-teacher/:teachId", {
            template: "<new-teacher></new-teacher>",
            
        }).when("/teacher-follow-up", {
            template: "<teacher-follow-up></teacher-follow-up>",
        }).when("/teacherAssignedSubjectsTpl", {
            template: "<teacher-assigned-subjects></teacher-assigned-subjects>",            
        }).when("/academicRankConfig", {
            template: "<acad-rank-config></acad-rank-config>",
        }).when("/subjectBilling", {
            template: "<subject-billing></subject-billing>",
        }).when("/vacationPaymentMethod", {
            template: "<vacation-payment-method></vacation-payment-method>",
        }).when("/subjectBilling/:numRef", {
            template: "<subject-billing></subject-billing>",
                       
        }).when("/newAcademicRank/", {
            template: "<new-acad-rank></new-acad-rank>",
            
        }).when("/newAcademicRank/:id", {
            template: "<new-acad-rank></new-acad-rank>",
            
        }).otherwise({redirectTo: '/dashboard'});
          
}]).factory('accessFac',function($http){
    var obj = {}
    this.access = false;
    obj.getPermission = function(){    //set the permission to true
        this.access = true;
    }
    obj.checkPermission = function(perm){
        var data = {permission_type:perm};
        return  $http.post('checkpermission',data).then(function(response){
          return response.data[0];
        })           //returns the users permission level 
    }
    obj.checkClassePermission = function(perm,id){
        var data = {permission_type:perm,std_id:id};
        return  $http.post('checkpermission',data).then(function(response){
          return response.data[0];
        })           //returns the users permission level 
    }
    return obj;
}).controller('testCtrl',function($scope,accessFac,$interval,$timeout,$http,toastr){
    var controller = this;
    var $ctrl = this;
    $ctrl.selectedAcadYr = null;

      controller.date = new Date();

      $interval(function() {
        controller.date = new Date();
      }, 1000);
      
      
             $timeout(
              $http.get('currentAcademicYear').then(
              function successCallback(response){
                  $scope.acadyr = response.data[0];
                  //toastr.success('Année académique cangée avec succès');

              },
              function errorCallbacl(response){
                   toastr.error('Problème survenu lors du chargement des informations', 'Erreur');
                  
              }),1000);
              
    $scope.getAccess = function(){
        accessFac.getPermission();       //call the method in acccessFac to allow the user permission.
    }
    
    
    $ctrl.init1 = function(){

        $ctrl.queryAcademicYear = function(academicYear)
        { 
           var  dataString = {id: academicYear},
              config = {
                params: dataString,
                headers : {'Accept' : 'application/json; charset=utf-8'}
                };

                return  $http.get('searchAcademicYear',config).then(function(response){
                       return response.data[0];
                    });
         }; 
     }
     
    $ctrl.selectedYearChange = function(acadYr){ 
        $scope.acadyr = acadYr;

        var  dataString = {id: acadYr.id},
           config = {
             params: dataString,
             headers : {'Accept' : 'application/json; charset=utf-8'}
             };

             return  $http.get('switchAcadYr',config).then(function(response){
                 toastr.success("Année académique changé avec succès")
                    return response.data[0];
                 });

        $ctrl.selectedClasse = null;
        $ctrl.selectedSem = null;
        $ctrl.disabledSelect = true;
    }     
     
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
    });