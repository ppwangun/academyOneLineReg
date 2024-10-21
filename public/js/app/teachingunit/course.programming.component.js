'use strict';

angular.module('teachingunit')
        .component('courseProgramming',{
            controller: programmingCtrl,
            templateUrl: 'programmingtpl',
            
});
function programmingCtrl($timeout,$http,$location,$mdDialog,$scope,uiCalendarConfig,toastr){
    var $ctrl = this;
    
    $ctrl.times = [{id:0,time:"07:30:00",name:"7h30"},{id:1,time:"08:00:00",name:"8h00"},{id:2,time:"08:30:00",name:"8h30"},{id:3,time:"09:00:00",name:"9h00"},{id:4,time:"09:30:00",name:"9h30"},
    {id:5,time:"10:00:00",name:"10h00"},{id:6,time:"10:30:00",name:"10h30"},{id:7,time:"11:00:00",name:"11h00"},{id:8,time:"11:30:00",name:"11h30"},{id:9,time:"12:00:00",name:"12h00"},
    {id:10,time:"12:30:00",name:"12h30"},{id:11,time:"13:00:00",name:"13h00"},{id:12,time:"13:30:00",name:"13h30"},{id:13,time:"14:00:00",name:"14h00"},{id:14,time:"14:30:00",name:"14h30"},
    {id:15,time:"15:00:00",name:"15h00"},{id:16,time:"15:30:00",name:"15h30"},{id:17,time:"16:00:00",name:"16h00"},{id:18,time:"16:30:00",name:"16h30"},{id:19,time:"17:00:00",name:"17h00"},
    {id:20,time:"17:30:00",name:"17h30"}]

    $ctrl.startingTime = null;
    $ctrl.endingTime = null;

    $ctrl.schedlingUpdate = false;
    $ctrl.isActivatedUeSelect = false;
    $ctrl.isScheduleValidated = false
    $scope.eventSources = [ ];
    
    $ctrl.resetSchedule = function(){
                  
                   $ctrl.startingTime = null;

                   $ctrl.endingTime = null; 
                   
                   $ctrl.date = null;
                   
                   $ctrl.selectedSem = null;

    
                   $ctrl.selectedUe =  null;
                   
                   $ctrl.selectedClasse = null;
                   
                   $ctrl.selectedSubject =  null;
                   
                   $ctrl.scheduleType = null;
                   $ctrl.schedlingUpdate = false;
                   $ctrl.progressionData = null;
                  
                   
                   
                   

        
    }
        
    $scope.eventSources = [     {
           
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
];
     $scope.alertEventOnClick = function(info)
  {
        $ctrl.schedlingUpdate = true;
     
       var  dataString = {id: info.id},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('getScheduledCourse',config).then(function(response){
                
                   $ctrl.startingTime = $ctrl.times.filter(function(item) { return item.time === response.data[0].startingTime; }); 
                   $ctrl.startingTime = $ctrl.startingTime[0];
                   $ctrl.endingTime = $ctrl.times.filter(function(item) { return item.time === response.data[0].endingTime; }); 
                   $ctrl.endingTime = $ctrl.endingTime[0]; 
                   
                   $ctrl.date = response.data[0].dateScheduled.date;
                   
                   $ctrl.selectedSem = response.data[0].semester;
                   
                   //**************************************************
                    $ctrl.loadUE($ctrl.selectedClasse,$ctrl.selectedSem.id)

    
                   $ctrl.selectedUe =  response.data[0].teachingUnit;
                   
                   $ctrl.loadSubjects($ctrl.selectedUe)
                   
                   $ctrl.selectedSubject =  response.data[0].subject;
                   
                   $ctrl.scheduleType = response.data[0].scheduleType;
                   
                   $ctrl.isScheduleValidated = response.data[0].isScheduleValidated;
                  
                 
                   
                   $ctrl.progressionData ={scheduledId:response.data[0].scheduledId,contractId:response.data[0].contractId,classe:$ctrl.selectedClasse,sem:$ctrl.selectedSem,
                       ue:$ctrl.selectedUe,subject:$ctrl.selectedSubject,
                       date:$ctrl.date,startingTime:$ctrl.startingTime,endingTime:$ctrl.endingTime,scheduleType:$ctrl.scheduleType,description:response.data[0].description,
                       isScheduleValidated:$ctrl.isScheduleValidated,students:response.data[0].students}                   
                  
                //  console.log($ctrl.progressionData.scheduledId)
               /*   $scope.eventSources[0].events.filter(item=>{ return item.id === response.data[0].id}).map(
                           (item, idx) => {

                            item.color="black";
                    item.textColor = "white";
                    
                           });*/
                           

                    $scope.eventSources[0].events.forEach(event => {  
                        event.backgroundColor= '#fff'; 
                        $('#calendar').fullCalendar('rerenderEvents');
                       
                    });          
                          
                   // $scope.eventSources = createCalendarEventsFromCoreEvents(filterEvents());
                 
                   // uiCalendarConfig.calendars['calendar'].fullCalendar('removeEvents'); 
                    // uiCalendarConfig.calendars.calendar.fullCalendar('addEventSource', $scope.eventSources);
                    info.backgroundColor = '#2bbbad';
                    $('#calendar').fullCalendar('rerenderEvents');
                   // $('#calendar').fullCalendar('refetchEvents')
                   // console.log($scope.eventSources)
                    
                    $(this).css("background-color", "#2bbbad"); 
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
        eventColor: '#378006'
        
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
     

      angular.forEach($scope.eventSources[0].events,function(value, key){
          $scope.eventSources[0].events.splice(key);
      }); 

                   $ctrl.startingTime = null;
                   $ctrl.endingTime = null; 
                   $ctrl.date = null;
                   $ctrl.selectedSem = null;
                   $ctrl.selectedUe =  null;
                   $ctrl.selectedSubject =  null;   
                //   $scope.eventSources[0].events =[];
    
      if(item)
       var data = {classe:item.id}
       else var data = {classe:-1}
       var events = []
       var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };      
        $http.get('getSchedulingCourses',config).then(function(response){
         
 
            angular.forEach(response.data[0], function(event, key) { 
            events.push({
                id : event.id,
                title  : event.eventName,
                start  : event.startingTime.date,
                end    : event.endingTime.date,
                color: (event.isValidated)?'#C0C0C0' : ((event.scheduleType === "CC" || event.scheduleType=="EXAM")?"red":(event.scheduleType === "STAGE" || event.scheduleType=="ECN")?"#082567":''),
                textColor: (event.isValidated)?'grey' : ((event.scheduleType === "CC" || event.scheduleType=="EXAM")?"white":(event.scheduleType === "STAGE" || event.scheduleType=="ECN")?"red":'')
                
               
            })
        
      
    })
   
    //$scope.eventSources[0] = events;
    $scope.eventSources[0] = {
        events:events,
        
        render: function(event, element) {
            if (event.isValidated) {
                element.css({
                    'background-color': '#333333',
                    'border-color': '#333333'
                });
            }
        }    
}
    

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
 {
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
            var contractNotFound = response.data.contractNotFound;
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
            
            if(contractNotFound)
            {
                    $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('Erreur ')
                      .textContent("Cours non affecté, ne peut être programmé ")
                      .ariaLabel('Alert Dialog Demo')
                      .ok('Fermer!')
                      .targetEvent(ev)
                  );
                
                return;    
            }            
            
            
            toastr.success("Opération effectuée avec succès");
            event = response.data[0];
            $scope.eventSources[0].events.push({
                id : event.id,
                title  : event.eventName,
                start  : event.startingTime.date,
                end    : event.endingTime.date
            })

        });     
     
 }
 
  $scope.updateSchedule = function(ev,idEvent)
 {console.log(idEvent)
     $ctrl.date = moment($ctrl.date).format("YYYY-MM-DD");
     if($ctrl.selectedSubject) var eventName= $ctrl.selectedSubject.code;
     else var eventName= $ctrl.selectedUe.code;
     
     if($ctrl.selectedSubject)         var data = {idScheduledCourse:idEvent,classe:$ctrl.selectedClasse.id,sem:$ctrl.selectedSem.id,ue:$ctrl.selectedUe.id,subject:$ctrl.selectedSubject.id,date:$ctrl.date,startingTime:$ctrl.startingTime.time,endingTime:$ctrl.endingTime.time,scheduleType:$ctrl.scheduleType}
     else var data = {idScheduledCourse:idEvent,classe:$ctrl.selectedClasse.id,sem:$ctrl.selectedSem.id,ue:$ctrl.selectedUe.id,date:$ctrl.date,startingTime:$ctrl.startingTime.time,endingTime:$ctrl.endingTime.time,scheduleType:$ctrl.scheduleType}
      console.log(data);  
      var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };      
        $http.get('updateScheduledCourse',config).then(function(response){
     
            var timeConflict = response.data.timeConflict;
            var contractNotFound = response.data.contractNotFound;
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
            
            if(contractNotFound)
            {
                    $mdDialog.show(
                    $mdDialog.alert()
                      .parent(angular.element(document.querySelector('#popupContainer')))
                      .clickOutsideToClose(true)
                      .title('Erreur ')
                      .textContent("Cours non affecté, ne peut être programmé ")
                      .ariaLabel('Alert Dialog Demo')
                      .ok('Fermer!')
                      .targetEvent(ev)
                  );
                
                return;    
            }            
            
            
            toastr.success("Opération effectuée avec succès");
            event = response.data[0];


        });     
     
 }
 
  $scope.deleteSchedule = function(idEvent)
 { 
        var config = {
        params: {id:idEvent},
        headers : {'Accept' : 'application/json'}
        };      
        $http.get('deleteScheduledCourse',config).then(function(response){
         
            
            
            toastr.success("Opération effectuée avec succès");
            $ctrl.resetSchedule()


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
          locals:{progr:$ctrl.progressionData,times:$ctrl.times},
          templateUrl: 'js/app/teachingUnit/tabDialog.tmpl.html',
          // Appending dialog to document.body to cover sidenav in docs app
          // Modal dialogs should fully cover application to prevent interaction outside of dialog
          parent: angular.element(document.body),
          targetEvent: ev,
          clickOutsideToClose: true,
          fullscreen: true
        }).then(function (answer) {
          $scope.status = 'You said the information was "' + answer + '".';
        }, function () {
          $scope.status = 'You cancelled the dialog.';
        });
       // $ctrl.resetSchedule();
    /* config object */
}
     
    /*--------------------------------------------------------------------------
     *--------------------------- updating curriculum---------------------------
     *----------------------------------------------------------------------- */
    
    $ctrl.loadData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          
          controller: DialogController,
          controllerAs: 'ctrl',
          templateUrl: 'js/my_js/globalconfig/loadteachingunit.html',
          parent: angular.element(document.body),
         // parent: angular.element(document.querySelector('#component-tpl')),
          scope: $scope,
          preserveScope: true,
          autoWrap: false,
          targetEvent: ev,
          clickOutsideToClose:false,
          fullscreen: true, // Only for -xs, -sm breakpoints.
          
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
  function convertStringToTime(timeString)
  {
         
        let [hours, minutes, seconds] = timeString.split(':').map(Number);  
        let date = new Date();  
        date.setHours(hours);  
        date.setMinutes(minutes);  
        date.setSeconds(seconds);  

        return date;       
  }
  
  
  
 //Dialog Controller
  function DialogController($scope, $mdDialog,progr,times) {
      $scope.times = times;
    $scope.progression = {
        // date: null,
        // start_time: null,
        // end_time: null,
        // description: null,
        date: new Date(),
        start_time: new Date(),
        end_time: new Date(),
        description: null,
        target: 'CM',
      //  teaching_unit_id: teachingUnitId,
       // teacher_id: teacherId,
       // contract_id: contractId
    }      
   $scope.progr = progr;
   $scope.progression.date =  new Date (progr.date);
   $scope.progression.start_time = progr.startingTime;
   $scope.progression.end_time = progr.endingTime;
   $scope.progression.target = progr.scheduleType;
   $scope.progression.contract_id = progr.contractId;
   $scope.progression.scheduled_id = progr.scheduledId;
   $scope.progression.description = progr.description;
   $scope.progression.isScheduleValidated = progr.isScheduleValidated;
   $scope.stdRegisteredToClass = progr.students;
   
   
   $scope.items = progr.students;
   $scope.selected = [];
   
    progr.students.forEach(function(item) {
      if(item.attendance) $scope.selected.push(item)
   });


    $scope.saveProgression = function(progressionForm) {
        if (!progressionForm.$valid) {
            alert('Formulaire invalide !');
            return;
        }

        if ($scope.isProcessing) return;

        const dateInISO = $scope.progression.date.toISOString()?.split('T')[0];
        // const startDate = $scope.progression.date;
        // const endDate = $scope.progression.date;
        const startTime = convertStringToTime($scope.progression.start_time.time);
        const endTime = convertStringToTime($scope.progression.end_time.time);
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
            contract_id: progr.contractId,
            scheduled_id: progr.scheduledId,
            students: $scope.stdRegisteredToClass,
            fromSchedule: true
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
  /*  $scope.stdRegisteredToClass = [];
    $scope.selected = [];
    
    $scope.loadStudentAdmittedToclasse = function(classe){
        
        var config = {
        params: {classe:classe},
        headers : {'Accept' : 'application/json'}
        };      
        $http.get('studentsByClasse',config).then(function(response){
         
           // $scope.stdRegisteredToClass = response.data[0];
           // $scope.selected = $scope.stdRegisteredToClass;
            
            


        }); 
        
    }*/
    
    
    
 
        $scope.toggle = function (item, list) {
        var idx = list.indexOf(item);
        if (idx > -1) {
          list.splice(idx, 1);

           var id = $scope.stdRegisteredToClass.indexOf(item)
           $scope.stdRegisteredToClass[id].attendance = 0;
          
        }
        else {
          list.push(item);
          
          var id = $scope.stdRegisteredToClass.indexOf(item)
          $scope.stdRegisteredToClass[id].attendance = 1;
          
        }
      };

      $scope.exists = function (item, list) {
        return (list.indexOf(item) > -1);
      };

      $scope.isIndeterminate = function() {
        return ($scope.selected.length !== 0 &&
            $scope.selected.length !== $scope.items.length);
      };

      $scope.isChecked = function() {
        return $scope.selected.length === $scope.items.length;
      };

      $scope.toggleAll = function() {
        if ($scope.selected.length === $scope.items.length) {
          $scope.selected = [];
          $scope.stdRegisteredToClass.forEach(function(item) {  item.attendance = 0})
        } else if ($scope.selected.length === 0 || $scope.selected.length > 0) {
          $scope.selected = $scope.items.slice(0);
          $scope.stdRegisteredToClass.forEach(function(item) {  item.attendance = 1})
        }

      };
      

 
  
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
    
 }       



};


