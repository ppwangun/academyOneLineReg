angular.module('teachingunit')
        .component('teacherFollowUp',{
            templateUrl: 'teacherFollowUp',
            controller: teacherListController 
})        .component('teacherAssignedSubjects',{
            templateUrl: 'teacherAssignedSubjectsTpl',
            controller: teacherListController 
}).component('subjectBilling',{
            templateUrl: 'subjectBillingTpl',
            controller: teacherListController 
}).component('billDetails',{
            templateUrl: 'billDetails',
            controller: teacherListController 
});

function teacherListController($scope, $mdDialog, $http, $timeout,DTOptionsBuilder,DTColumnDefBuilder,$routeParams,toastr){
    // $scope.teachers = [
    //     {id: 1, names: 'Tiger Nixon', speciality: 'System Architect'},
    //     {id: 2, names: 'Garrett Winters', speciality: 'Accountant'},
    //     {id: 3, names: 'Ashton Cox', speciality: 'Junior Technical Author'},
    //     {id: 4, names: 'Cedric Kelly', speciality: 'Senior Javascript Developer'},
    //     {id: 5, names: 'Airi Satou', speciality: 'Accountant'},
    //     {id: 6, names: 'Brielle Williamson', speciality: 'Integration Specialist'},
    //     {id: 7, names: 'Herrod Chandler', speciality: 'Sales Assistant'},
    //     {id: 8, names: 'Rhona Davidson', speciality: 'Integration Specialist'},
    //     {id: 9, names: 'Colleen Hurst', speciality: 'Javascript Developer'},
    //     {id: 10, names: 'Sonya Frost', speciality: 'Software Engineer'},
    //     {id: 11, names: 'Jena Gaines', speciality: 'Office Manager'},
    //     {id: 12, names: 'Quinn Flynn', speciality: 'Support Lead'},
    //     {id: 13, names: 'Charde Marshall', speciality: 'Regional Director'},
    //     {id: 14, names: 'Haley Kennedy', speciality: 'Senior Marketing Designer'},
    //     {id: 15, names: 'Tatyana Fitzpatrick', speciality: 'Regional Director'},
    //     {id: 16, names: 'Michael Silva', speciality: 'Marketing Designer'},
    //     {id: 17, names: 'Paul Byrd', speciality: 'Chief Financial Officer (CFO)'},
    //     {id: 18, names: 'Gloria Little', speciality: 'Systems Administrator'},
    // ]
    // $scope.affectedTeachingUnits = [
    //     {id: 1, name: 'Mathematiques', classe: '6eme'},
    //     {id: 2, name: 'Physique', classe: '5eme'},
    //     {id: 3, name: 'Informatique', classe: '4eme'},
    //     {id: 4, name: 'Mathematiques', classe: '3eme'},
    //     {id: 5, name: 'Chimie', classe: '2nde'},
    //     {id: 6, name: 'Mathematiques', classe: '1ere'},
    //     {id: 7, name: 'Mathematiques', classe: 'Tle'},
    //     {id: 8, name: 'Physique', classe: '6eme'},
    //     {id: 9, name: 'Informatique', classe: '5eme'},
    //     {id: 10, name: 'Mathematiques', classe: '4eme'},
    //     {id: 11, name: 'Chimie', classe: '3eme'},
    // ]
     $scope.dtOptions = DTOptionsBuilder.newOptions()
     .withButtons([
            //'columnsToggle',
            //'colvis',
            //'copy',
           // 'print'

        ])
        .withPaginationType('full_numbers')
        .withDisplayLength(25)
        .withOption('lengthChange', false);
         /* .withFixedHeader({
    top: true
    info: false 
  })*/;
  
    $scope.dtColumnDefs = [
    DTColumnDefBuilder.newColumnDef(0),
    DTColumnDefBuilder.newColumnDef(1),

  ];     
    $scope.teachers = [];
    $scope.hasLoadedTeachers = null;
    $scope.currentTeacher = null;
    $scope.currentTeachingUnit = null;
    $scope.currentProgressionStats = null;
    $scope.hasLoadedCurrentTeacher = null;
    $scope.hasLoadedCurrentProgressionStats = null;

    $scope.isLoadingTeachers = function () {
        return $scope.hasLoadedTeachers === null;
    }
    $scope.hasFailedLoadingTeachers = function () {
        return $scope.hasLoadedTeachers === false;
    }
    $scope.hasSucceededLoadingTeachers = function () {
        return !$scope.isLoadingTeachers() && !$scope.hasFailedLoadingTeachers();
    }

    $scope.isLoadingCurrentTeacher = function () {
        return $scope.hasLoadedCurrentTeacher === null;
    }
    $scope.hasFailedLoadingCurrentTeacher = function () {
        return $scope.hasLoadedCurrentTeacher === false;
    }
    $scope.hasSucceededLoadingCurrentTeacher = function () {
        return !$scope.isLoadingCurrentTeacher() && !$scope.hasFailedLoadingCurrentTeacher();
    }

    $scope.isLoadingCurrentProgressionStats = function () {
        return $scope.hasLoadedCurrentProgressionStats === null;
    }
    $scope.hasFailedLoadingCurrentProgressionStats = function () {
        return $scope.hasLoadedCurrentProgressionStats === false;
    }
    $scope.hasSucceededLoadingCurrentProgressionStats = function () {
        return !$scope.isLoadingCurrentProgressionStats() && !$scope.hasFailedLoadingCurrentProgressionStats();
    }
    
    
    var $ctrl = this;
    $ctrl.searchTeacher = "";
    $ctrl.selectedTeacher = null;
    $scope.tableBillsShow = 0;
    
   $ctrl.billNumRef = null;

    $ctrl.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
    $ctrl.billNumRef =$routeParams.numRef;  

    $ctrl.init = function(){
     
    $timeout(
     $http.get('teacherAssignedSubjects').then(function(response){
         $ctrl.ue = response.data[0];
     }),500);
     
    //Loading classes of study asynchronously
    $ctrl.query = function(classe)
    {
       var  dataString = {id: classe},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('classes',config).then(function(response){
                   return response.data[0];
                });
     };
     
    //Loading classes of study asynchronously
    $ctrl.queryTeacher = function(teacher)
    {
       var  dataString = {id: teacher},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('searchTeacher',config).then(function(response){
                   return response.data[0];
                });
     };     
     
     $timeout(    
         $http.get('semester').then(function(response){
             //$ctrl.semesters = response.data[0];
         }).then(function(){
             $http.get('examtype').then(function(response){
                 $ctrl.examtypes = response.data[0];
                 
             });
         }),500); 
         
     
 };
 
 $ctrl.asignedSemToClasse = function(class_code){
    var data = {id: class_code};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('assignsemtoclass',config).then(function(response){
        $ctrl.semesters = response.data[0];
        $ctrl.isActivatedUeSelect = true;
    });
};

 $ctrl.selectedItemChange = function(teacher){
     if(teacher)     teacherID = teacher.id; else teacherId =-1;
     $ctrl.assignedSubjects = [];
     $ctrl.isActivatedUeSelect = false;
    //Loading selected class information for update
    $timeout(
     $http.get(`teachers/${teacherID}`).then(function (response) {
       
          $ctrl.assignedSubjects = response.data[0].teaching_units;
          $ctrl.isActivatedUeSelect = true;
     }),1000);
  };
  
  $ctrl.showbillDetails = false;
      //chech if numRef is setted
    if($ctrl.billNumRef)
    {
        var  dataString = {numRef: $ctrl.billNumRef},
        config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
        };
        $http.get('billDetails',config).then(function(response){
            $ctrl.billDetails = response.data[0];
            $ctrl.selectedTeacher = response.data[1];
            $ctrl.selectedUe = response.data[2];
            $ctrl.bill = response.data[3];
            $ctrl.showbillDetails = true;
            $ctrl.paymentRate = response.data[4];
            $ctrl.totalHrsAffected = response.data[5].totalHrsAffected;
            $ctrl.totalHrsPreviouslyBilled = response.data[5].totalHrsPreviouslyBilled
            $ctrl.totalHrsBilled = response.data[5].totalHrsDone
            $ctrl.vacationDeduction = response.data[5].vacationDeduction
            $ctrl.totalActuaBilledTime = response.data[5].totalHrsCurrentlyBilled
            $ctrl.overtime = response.data[5].overTime;
            
            
        });        
    }

  $ctrl.loadBills = function(selectectedTeacher,selectedUe)
  {
    var data = {teacherID: selectectedTeacher.id,contractID : selectedUe.id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('searchBill',config).then(function(response){
        $ctrl.bills = response.data[0];
        $scope.tableBillsShow = 1
       
    });    
  }
  
  $ctrl.generateBill = function(selectectedTeacher,selectedUe)
  {
    var data = {teacherID: selectectedTeacher.id,contractID : selectedUe.id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };      
    $http.get('generateBill',config).then(function(response){
        var resp = response;
        console.log(resp)
        if(resp.data.info.resultat==="echec") toastr.error("Une erreur inattendue s'est produite");
        else
        {
        

            var errorValue = response.data[0].error;
            
            if(errorValue===0)
            {
                alert("Facturation impossible: volume horaire en dépassement")
            }
            else if(errorValue===1)
            {
                alert("Absence d'heure de cours à facturer")
            }

        
        $ctrl.billDetails = response.data[0].paymentDetails;
        $ctrl.totalHrsBilled = response.data[0].totalBilledTime;
        $ctrl.totalHrsPreviouslyBilled = response.data[0].alreadyBilledTime;
        $ctrl.totalHrsAffected = response.data[0].totalHoursAffected;
        $ctrl.totalActuaBilledTime = response.data[0].totalActualBilledTime;
        $ctrl.overtime = response.data[0].overtime;
        $ctrl.paymentRate = response.data[0].paymentRate;
        $scope.tableBillsShow = 1;
        toastr.success("Opération effectuée avec succès");
    }
       
    });    
  }  

    $scope.loadTeachers = function () {
        $scope.hasLoadedTeachers = null;
        $http.get(`teachers`).then(function (response) {
            console.log(response)
            $scope.teachers = response.data[0];
            $scope.hasLoadedTeachers = true;
        }, function (error) {
            console.error(error);
            $scope.hasLoadedTeachers = false;
        });
    }

    $scope.loadCurrentTeacher = function () {
        $scope.hasLoadedCurrentTeacher = null;
        $http.get(`teachers/${$scope.selectedTeacherId}`).then(function (response) {
            console.log(response)
            const {documents, ...data} = response.data[0];
            $scope.currentTeacher = {
                ...data,
                identityDocumentFile: documents.find(document => document.type === 'identity_document'),
                coverLetterFile: documents.find(document => document.type === 'cover_letter'),
                resumeFile: documents.find(document => document.type === 'resume'),
                teacherRankFile: documents.find(document => document.type === 'teacher_rank'),
                highestDegreeFile: documents.find(document => document.type === 'highest_degree'),
                experienceReviewFile: documents.find(document => document.type === 'experience_review'),
                nominationActFile: documents.find(document => document.type === 'nomination_act'),
            }
          
            $scope.hasLoadedCurrentTeacher = true;
        }, function (error) {
            console.error(error);
            $scope.hasLoadedCurrentTeacher = false;
        });
    }
    
    $ctrl.printBill = function (ev) {
    $mdDialog.show({
      controller: DialogController,
      templateUrl: 'printBill/'+$ctrl.billNumRef,
      // Appending dialog to document.body to cover sidenav in docs app
      // Modal dialogs should fully cover application to prevent interaction outside of dialog
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose: true,
      fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
    }).then(function (answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function () {
      $scope.status = 'You cancelled the dialog.';
    });
  };
    $ctrl.printWorkloadFollowUp = function (ev) {
    $mdDialog.show({
      controller: DialogController,
      templateUrl: 'printWorkloadFollowUp',
      // Appending dialog to document.body to cover sidenav in docs app
      // Modal dialogs should fully cover application to prevent interaction outside of dialog
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose: true,
      fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
    }).then(function (answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function () {
      $scope.status = 'You cancelled the dialog.';
    });
  };  
  function DialogController($scope, $mdDialog) {
    $scope.hide = function () {
      $mdDialog.hide();
    };

    $scope.cancel = function () {
      $mdDialog.cancel();
    };

    $scope.answer = function (answer) {
      $mdDialog.hide(answer);
    };
  }    


    $scope.loadCurrentProgressionStats = function () {
        $scope.hasLoadedCurrentProgressionStats = null;
        data = {contractId: $scope.selectedContractId}
        data = $.param(data)
        var config = {
            //params: {id: $scope.teacherId,subjects : JSON.stringify(data)},
            headers : {'Content-Type' : "application/x-www-form-urlencoded;charset=utf-8;"}
        };        
        $http.post(`unitFollowUp`,data,config).then(function (response) {
            console.log(response)
            $scope.currentProgressionStats = response.data[0];
            $scope.hasLoadedCurrentProgressionStats = true;
        }, function (error) {
            console.error(error);
            $scope.hasLoadedCurrentProgressionStats = false;
        });
    }

    $scope.getRoundedPercentage = function (expectedPercentage) {
        const minPercentage = Math.max(expectedPercentage, 0);
        return Math.min(Math.round(minPercentage), 100);
    }

    $scope.loadTeachers();

    $scope.data={ "records": $scope.teachers};
    $scope.dataTableOpt = {
        //custom datatable options
        // or load data through ajax call also
        "aLengthMenu": [[10, 50, 100,-1], [10, 50, 100,'All']],
        "aoSearchCols": [
            null
        ],
    };

    $scope.selectedTeacherId = null;
    $scope.selectedTeachingUnitId = null;

    $scope.onSelectTeacher = function ($teacherId) {
        $scope.selectedTeacherId = $teacherId;
        $scope.selectedTeachingUnitId = null;
        $scope.currentProgressionStats = null;
        $scope.currentTeachingUnit = null;
        $scope.loadCurrentTeacher();
    }
    $scope.onSelectTeachingUnit = function ($teachingUnitId) {
        $scope.selectedTeachingUnitId = $teachingUnitId;
        $scope.currentTeachingUnit = $scope.currentTeacher?.teaching_units?.find(elt => elt.id === $teachingUnitId);
        $scope.currentProgressionStats = null;
        $scope.loadCurrentProgressionStats();
    }
    $scope.onSelectContract = function ($contractId) { 
        $scope.selectedContractId = $contractId;
        $scope.currentContract = $scope.currentTeacher?.teaching_units?.find(elt => elt.id === $contractId);
        $scope.currentTeachingUnit = $scope.currentTeacher?.teaching_units?.find(elt => elt.id === $contractId); 
        teachingUnitCode: $scope.currentTeachingUnit?.codeUe;
        $scope.currentProgressionStats = null;
        $scope.loadCurrentProgressionStats();
    }    
    $scope.unAssignSubjectToTeacher = function(teachingUnitId,ev)
    {
        data = {teacherId: $scope.selectedTeacherId,subject : teachingUnitId,contractId: $scope.selectedContractId} 
        data = $.param(data)
        var config = {
            //params: {id: $scope.teacherId,subjects : JSON.stringify(data)},
            headers : {'Content-Type' : "application/x-www-form-urlencoded;charset=utf-8;"}
        };
        $scope.isProcessing = true;
        
// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Annuler Attribution?')
            .textContent('VOus êtes sur le point d\'annuler l\'attribution de l\'unité d\'enseignement séléctionné Voulez vous continuer')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.post(`unAssignSubjectToTeacher`,data,config)
            .then(function (response) {

               // alert('L\'enseignant a ete mis a jour avec succes !');
                $scope.loadCurrentTeacher();
                $scope.isProcessing = false;
            }, function (error) {
                console.error(error);
                $scope.isProcessing = false;
                alert('Une erreur s\'est produite lors du traitement ! Veuillez reessayer !')
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    }); 
    
        
        
    }

    $scope.onViewDocument = function () {
        console.log("Viewing document")
    }
    $scope.progtimes =[{id:0,time:"07:30:00",name:"7h30"},{id:1,time:"08:00:00",name:"8h00"},{id:2,time:"08:30:00",name:"8h30"},{id:3,time:"09:00:00",name:"9h00"},{id:4,time:"09:30:00",name:"9h30"},
    {id:5,time:"10:00:00",name:"10h00"},{id:6,time:"10:30:00",name:"10h30"},{id:7,time:"11:00:00",name:"11h00"},{id:8,time:"11:30:00",name:"11h30"},{id:9,time:"12:00:00",name:"12h00"},
    {id:10,time:"12:30:00",name:"12h30"},{id:11,time:"13:00:00",name:"13h00"},{id:12,time:"13:30:00",name:"13h30"},{id:13,time:"14:00:00",name:"14h00"},{id:14,time:"14:30:00",name:"14h30"},
    {id:15,time:"15:00:00",name:"15h00"},{id:16,time:"15:30:00",name:"15h30"},{id:17,time:"16:00:00",name:"16h00"},{id:18,time:"16:30:00",name:"16h30"},{id:19,time:"17:00:00",name:"17h00"},
    {id:20,time:"17:30:00",name:"17h30"}]


    $scope.openNewProgressionDialog = function (ev) {
        $mdDialog.show({
            controller: NewTeachingUnitProgressionController,
            templateUrl: 'js/app/teachingunit/new-teaching-unit-progression.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: true,
            fullscreen: false, // Only for -xs, -sm breakpoints.
            locals: {teachingUnitId: $scope.currentTeachingUnit?.id, teachingUnitCode: $scope.currentTeachingUnit?.codeUe, teacherId: $scope.currentTeacher?.id,contractId:$scope.selectedContractId,times:$scope.progtimes }
        }).then(function (newProgressionResult) {
            if ($scope.selectedTeachingUnitId === newProgressionResult.teaching_unit_id) {
                const previousData = $scope.currentProgressionStats[newProgressionResult.target];
                const newProgress = Math.min((previousData.progress + newProgressionResult.duration), previousData.total);

                $scope.currentProgressionStats = {
                    ...$scope.currentProgressionStats,
                    [newProgressionResult.target]: {
                        ...previousData,
                        progress: formatDecimals(newProgress, 2),
                        percentage: formatDecimals((newProgress/previousData.total * 100))
                    }
                }
                console.log($scope.currentProgressionStats);
            }
        }, function () {
            // $scope.status = 'You cancelled the dialog.';
        });
    };

    $scope.openAffectTeachingUnitDialog = function (ev) {
        $mdDialog.show({
            controller: AffectTeachingUnitController,
            templateUrl: 'js/app/teachingunit/affect-teaching-unit.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: false,
            fullscreen: false, // Only for -xs, -sm breakpoints.
            locals: {teacherId: $scope.currentTeacher.id, teacherName: $scope.currentTeacher.names}
        }).then(function (newTeachingUnits) { console.log(newTeachingUnits)
            $scope.currentTeacher.teaching_units = newTeachingUnits.concat($scope.currentTeacher.teaching_units);
        }, function () {
            // $scope.status = 'You cancelled the dialog.';
        });
    };

    $scope.openProgressionsTimelineDialog = function (ev, teachingUnit) {
        $mdDialog.show({
            controller: ProgressionsTimelineController,
            templateUrl: 'js/app/teachingunit/progressions-timeline.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: false,
            fullscreen: false, // Only for -xs, -sm breakpoints.
            locals: { teacherId: $scope.currentTeacher.id,contractId:$scope.selectedContractId, teachingUnitCode: $scope.currentTeachingUnit?.codeUe}
        });
    };
    
    $scope.openSearchProgressionsTimelineDialog = function (ev, teachingUnit) {
        $mdDialog.show({
            controller: ProgressionsTimelineController,
            templateUrl: 'js/app/teachingunit/search-progressions-timeline.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: false,
            fullscreen: false, // Only for -xs, -sm breakpoints.
            locals: { teacherId: $scope.currentTeacher.id,contractId:$scope.selectedContractId, teachingUnitCode: $scope.currentTeachingUnit?.codeUe}
        });
    };   
};
