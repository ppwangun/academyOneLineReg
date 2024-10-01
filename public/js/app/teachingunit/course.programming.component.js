'use strict';

angular.module('teachingunit')
        .component('courseProgramming',{
            controller: programmingCtrl,
            templateUrl: 'programmingtpl',
            
});
function programmingCtrl($timeout,$http,$location,$mdDialog,$scope,uiCalendarConfig,toastr){
    var $ctrl = this;
    
    $ctrl.time = [{id:0,time:"07:30:00",name:"7h30"},{id:1,time:"08:00:00",name:"8h00"},{id:2,time:"08:30:00",name:"8h30"},{id:3,time:"09:00:00",name:"9h00"},{id:4,time:"09:30:00",name:"9h30"},
    {id:5,time:"10:00:00",name:"10h00"},{id:6,time:"10:30:00",name:"10h30"},{id:7,time:"11:00:00",name:"11h00"},{id:8,time:"11:30:00",name:"11h30"},{id:9,time:"12:00:00",name:"12h00"},
    {id:10,time:"12:30:00",name:"12h30"},{id:11,time:"13:00:00",name:"13h00"},{id:12,time:"13:30:00",name:"13h30"},{id:13,time:"14:00:00",name:"14h00"},{id:14,time:"14:30:00",name:"14h30"},
    {id:15,time:"15:00:00",name:"15h00"},{id:16,time:"15:30:00",name:"15h30"},{id:17,time:"16:00:00",name:"16h00"},{id:18,time:"16:30:00",name:"16h30"},{id:19,time:"17:00:00",name:"17h00"},
    {id:20,time:"17:30:00",name:"17h30"}]

    $ctrl.startingTime = null;
    $ctrl.endingTime = null;

    $ctrl.schedlingUpdate = false;
    $ctrl.isActivatedUeSelect = false;
    $scope.eventSources = [ ];
        
    /*    {
           
      events: [ // put the array in the `events` property
        {
            id : 1,
            resourceId: 'a',
          title  : 'event1',
          start  : '2024-02-09T08:30:00',
          end    : '2024-02-09T12:30:00',
          color: 'black',
        },
        {
            id : 3,
          title  : 'event2 \n Campus B A001',
          start  : '2024-02-09T12:30:00',
          end    : '2024-02-09T14:30:00'
        },
        {
          id : 3,
          title  : 'event3',
          start  : '2024-02-08T12:30:00',
        }
      ],
      //color: 'black',     // an option!
     //textColor: 'yellow' // an option!

    },
];*/
    
    $scope.alertEventOnClick = function(info)
  {
        $ctrl.schedlingUpdate = true;
     
       var  dataString = {id: info.id},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('getScheduledCourse',config).then(function(response){
                
                   $ctrl.startingTime = $ctrl.time.filter(function(item) { return item.time === response.data[0].startingTime; }); 
                   $ctrl.startingTime = $ctrl.startingTime[0];
                   $ctrl.endingTime = $ctrl.time.filter(function(item) { return item.time === response.data[0].endingTime; }); 
                   $ctrl.endingTime = $ctrl.endingTime[0]; 
                   
                   $ctrl.date = response.data[0].dateScheduled.date;
                   
                   $ctrl.selectedSem = response.data[0].semester;
                   
                   //**************************************************
                    $ctrl.loadUE($ctrl.selectedClasse,$ctrl.selectedSem.id)

    
                   $ctrl.selectedUe =  response.data[0].teachingUnit;
                   
                   $ctrl.loadSubjects($ctrl.selectedUe)
                   
                   $ctrl.selectedSubject =  response.data[0].subject;
                   
                   $ctrl.scheduleType = response.data[0].scheduleType;
                });
     };


    $scope.uiConfig = {
      calendar:{
        //height: 450,
        height: 900,
        editable: true,
        lang: 'fr',
        header:{
          //left: 'month basicWeek basicDay agendaWeek agendaDay',
          left: ' month agendaWeek',
          center: 'title',
          right: 'today prev,next'
        },
        selectable: true,
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		dayNamesShort: ["Dim", "Lun", "Mar", "Merc", "Jeudi", "Vend", "Sam"],  
                timeFormat:'HH:mm', // Month 24 hour timeformat
                axisFormat: 'HH:mm', // Week & Day 24 hour timeformat
        eventClick: $scope.alertEventOnClick,
        eventDrop: $scope.alertOnDrop,
        eventResize: $scope.alertOnResize,
        
      }
    };    

 $ctrl.init = function(){

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
     
    
     
         

   
 };

 $ctrl.selectedItemChange= function(item)
 {
     // remove all existing events
     

   //   angular.forEach($scope.eventSources[0].events,function(value, key){
          //$scope.eventSources[0].events.splice(key,1);
     // }); 

                   $ctrl.startingTime = null;
                   $ctrl.endingTime = null; 
                   $ctrl.date = null;
                   $ctrl.selectedSem = null;
                   $ctrl.selectedUe =  null;
                   $ctrl.selectedSubject =  null;     
    
      if(item)
       var data = {classe:item.id}
       else var data = {classe:-1}
       var events = []
       var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };      
        $http.get('getSchedulingCourses',config).then(function(response){
            toastr.success("Opération effectuée avec succès");
 
            angular.forEach(response.data[0], function(event, key) { 
            events.push({
              id : event.id,
          title  : event.eventName,
          start  : event.startingTime.date,
          end    : event.endingTime.date,
        })
        
      
    })
   
    //$scope.eventSources[0] = events;
$scope.eventSources[0] = {color:"black",textColor:"yellow",events:events}
    
    console.log( $scope.eventSources)
    });
    
    if($ctrl.selectedClasse)
    {
        $ctrl.semesters = [];
        var data = {id: $ctrl.selectedClasse.code};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };      
        $http.get('assignsemtoclass',config).then(function(response){
            $ctrl.semesters = response.data[0];
            $ctrl.isActivatedUeSelect = true;

        });
    }
    


 };
 
 $scope.addEvent = function(ev)
 {console.log($ctrl.scheduleType)
     $ctrl.date = moment($ctrl.date).format("YYYY-MM-DD");
     if($ctrl.selectedSubject) var eventName= $ctrl.selectedSubject.code;
     else var eventName= $ctrl.selectedUe.code;
     
     if($ctrl.selectedSubject)         var data = {classe:$ctrl.selectedClasse.id,sem:$ctrl.selectedSem.id,ue:$ctrl.selectedUe.id,subject:$ctrl.selectedSubject.id,date:$ctrl.date,startingTime:$ctrl.startingTime.time,endingTime:$ctrl.endingTime.time,scheduleType:$ctrl.scheduleType}
     else var data = {classe:$ctrl.selectedClasse.id,sem:$ctrl.selectedSem.id,ue:$ctrl.selectedUe.id,date:$ctrl.date,startingTime:$ctrl.startingTime.time,endingTime:$ctrl.endingTime.time,scheduleType:$ctrl.scheduleType}
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };      
        $http.get('schedulingCourse',config).then(function(response){
     
            var timeConflict = response.data.timeConflict;
            if(timeConflict)
            {
                    $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('Erreur ')
                      .textContent("Conflit sur l'heure de planification  ")
                      .ariaLabel('Alert Dialog Demo')
                      .ok('Fermer!')
                      .targetEvent(ev)
                  );
                
                return;    
            }
            
            
            toastr.success("Opération effectuée avec succès");
            event = response.data[0];
            $scope.eventSources[0].push({
                id : event.id,
                title  : event.eventName,
                start  : event.startingTime.date,
                end    : event.endingTime.date
            })

        });     
     
 }
 
 $ctrl.asignedSemToClasse = function(class_code){
    $ctrl.semesters = [];
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
 
 $ctrl.loadUE = function(classe,sem_id){
                $ctrl.selectedSubject =null;
                $ctrl.subjects = [];
                $ctrl.ues = [];
                var data = {id: {classe_id:classe.id,sem_id:sem_id}};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading selected class information for update
                $timeout(
                        
                        $http.get('teachingunit',config).then(
     
                        function(response){
                                $ctrl.ues=response.data[0];
                                $ctrl.isActivatedMatiereSelect = true;
                      

                        }),1000);

 };
 
 
 //Looad all student who are registered to the subject
 //Load all subjects associated with the UE as well
 $ctrl.loadSubjects = function(selectedUe){
     
                               var  data ={id:selectedUe.id}
                                    var config = {
                                    params: data,
                                    headers : {'Accept' : 'application/json'}
                                    };   
                                   
                                        $http.get('subjectbyue',config).then(function(response){

                                            $ctrl.subjects = response.data[0];
 

                                        });
                                 
                       };

                $ctrl.loadForValidation = function(ev)
                {
                         $mdDialog.show({
                          controller: DialogController,
                          templateUrl: 'js/app/teachingUnit/tabDialog.tmpl.html',
                          // Appending dialog to document.body to cover sidenav in docs app
                          // Modal dialogs should fully cover application to prevent interaction outside of dialog
                          parent: angular.element(document.body),
                          targetEvent: ev,
                          clickOutsideToClose: true
                        }).then(function (answer) {
                          $scope.status = 'You said the information was "' + answer + '".';
                        }, function () {
                          $scope.status = 'You cancelled the dialog.';
                        });

                    /* config object */
                }
     
    /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/my_js/globalconfig/loadteachingunit.html',
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
  function DialogController($scope, $mdDialog) {
      


    $scope.teachingUnitId = teachingUnitId;
    $scope.teachingUnitCode = teachingUnitCode;
    $scope.contractId= contractId;
    $scope.isProcessing = false;

    $scope.progression = {
        // date: null,
        // start_time: null,
        // end_time: null,
        // description: null,
        date: new Date(),
        start_time: new Date(),
        end_time: new Date(),
        description: null,
        target: 'cm',
        teaching_unit_id: teachingUnitId,
        teacher_id: teacherId,
        contract_id: contractId
    }

    $scope.saveProgression = function(progressionForm) {
        if (!progressionForm.$valid) {
            alert('Formulaire invalide !');
            return;
        }

        if ($scope.isProcessing) return;

        const dateInISO = $scope.progression.date.toISOString()?.split('T')[0];
        // const startDate = $scope.progression.date;
        // const endDate = $scope.progression.date;
        const startTime = $scope.progression.start_time;
        const endTime = $scope.progression.end_time;
        // startDate.setHours(startTime.getHours(), startTime.getMinutes(), startTime.getSeconds());
        // endDate.setHours(endTime.getHours(), endTime.getMinutes(), endTime.getSeconds());

        if (startTime.getTime() > endTime.getTime()) {
            alert('L\'heure de fin doit etre inferieure a celle de fin !');
            return;
        }

        const data = {
            ...$scope.progression,
            date: dateInISO,
            start_time: startTime.getHours() + ':' + startTime.getMinutes() + ':' + startTime.getSeconds(),
            end_time: endTime.getHours() + ':' + endTime.getMinutes() + ':' + endTime.getSeconds(),
        }
        
        var config  = {
          params: data,
          headers: {'Accept': 'application/json'}
        }

        $scope.isProcessing = true;
        $http.post("unitProgression", data,config)
            .then(function (response) {
                $mdDialog.hide({teaching_unit_id: $scope.teachingUnitId, target: $scope.progression.target, duration: ((endTime.getTime() - startTime.getTime())/3600000)});
                alert('La progression a ete ajoutee avec succes !');
                $scope.isProcessing = false;
            }, function (error) {
                console.error(error);
                $scope.isProcessing = false;
                alert('Une erreur s\'est produite lors l\'ajout de la progression ! Veuillez reessayer !')
            });
    }
 

      

  }
  
      $scope.cancel = function() {
      //$scope.faculties=[];
    
      $scope.subject = {code:'',name:'',credits: '',hours_vol:'',class_id: '',cm_hrs:'',tp_hrs:'',td_hrs:''};
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
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
};


