'use strict';

angular.module('teachingunit')
        .component('courseProgramming',{
            controller: programmingCtrl,
            templateUrl: 'programmingtpl',
            
});
function programmingCtrl($timeout,$http,$location,$mdDialog,$scope,uiCalendarConfig,toastr){
    var $ctrl = this;
    
    $ctrl.time = [{id:0,time:"7:30:00",name:"7h30"},{id:1,time:"8:00:00",name:"8h00"},{id:2,time:"8:30:00",name:"8h30"},{id:3,time:"9:00:00",name:"9h00"},{id:4,time:"9:30:00",name:"9h30"},
    {id:5,time:"10:00:00",name:"10h00"},{id:6,time:"10:30:00",name:"10h30"},{id:7,time:"11:00:00",name:"11h00"},{id:8,time:"11:30:00",name:"11h30"},{id:9,time:"12:00:00",name:"12h00"},
    {id:10,time:"12:30:00",name:"12h30"},{id:11,time:"13:00:00",name:"13h00"},{id:12,time:"13:30:00",name:"13h30"},{id:13,time:"14:00:00",name:"14h00"},{id:14,time:"14:30:00",name:"14h30"},
    {id:15,time:"15:00:00",name:"15h00"},{id:16,time:"15:30:00",name:"15h30"},{id:17,time:"16:00:00",name:"16h00"},{id:18,time:"16:30:00",name:"16h30"},{id:19,time:"17:00:00",name:"17h00"},
    {id:20,time:"17:30:00",name:"17h30"}]

    $ctrl.startingTime = null;
    $ctrl.endingTime = null;
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
      console.log(info);
      alert("je suis dedans"+info.id)
  }  
    /* config object */
    $scope.uiConfig = {
      calendar:{
        //height: 450,
        height: 900,
        editable: true,
        header:{
          //left: 'month basicWeek basicDay agendaWeek agendaDay',
          left: ' month agendaWeek',
          center: 'title',
          right: 'today prev,next'
        },
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
          end    : event.endingTime.date
        })
        
      
    })
    $scope.eventSources[0] = events;
    });

 };
 
 $scope.addEvent = function(ev)
 {
     $ctrl.date = moment($ctrl.date).format("YYYY-MM-DD");
     if($ctrl.selectedSubject) var eventName= $ctrl.selectedSubject.code;
     else var eventName= $ctrl.selectedUe.code;
     
     if($ctrl.selectedSubject)         var data = {classe:$ctrl.selectedClasse.id,sem:$ctrl.selectedSem.id,ue:$ctrl.selectedUe.id,subject:$ctrl.selectedSubject.id,date:$ctrl.date,startingTime:$ctrl.startingTime.time,endingTime:$ctrl.endingTime.time}
     else var data = {classe:$ctrl.selectedClasse.id,sem:$ctrl.selectedSem.id,ue:$ctrl.selectedUe.id,date:$ctrl.date,startingTime:$ctrl.startingTime.time,endingTime:$ctrl.endingTime.time}
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
 $ctrl.loadPedagogicalRegStd = function(selectedUe,classe){
                            $ctrl.isActivatedMatiereSelect = false;
                            $ctrl.isMatiereRequired = false;
                            
                            $scope.items = [];
                            if($ctrl.selectedSubject) var data ={id: {ueId: $ctrl.selectedUe.id,subjectId: $ctrl.selectedSubject.id,classId : classe.id}};
                            else    var data ={id: {ueId: $ctrl.selectedUe.id,classId : classe.id}};
                             
                            var i;
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };


                                data ={id: $ctrl.selectedUe.id}
                                    var config = {
                                    params: data,
                                    headers : {'Accept' : 'application/json'}
                                    };   
                                    if($ctrl.subjects<=0)
                                        $http.get('subjectbyue',config).then(function(response){

                                            $ctrl.subjects = response.data[0];
 

                                        });
                                                $ctrl.isActivatedMatiereSelect = true;
                                                $ctrl.isMatiereRequired = true;                                    
  
                        
                           
                        };
                        

     
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
      
$scope.subject = {code:'',name:'',credits: '',hours_vol:'',cm_hrs:'',tp_hrs:'',td_hrs:'',ue_classe_id: '', ue_id:''};

$scope.uploadSubject = function(){
 console.log("je suis dednas")
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importSubject',
     data: fd,
     headers: {'Content-Type': undefined},
    }).then(function successCallback(response) { 
      // Store response data
      $scope.response = response.data[0];
      response.data[0]?toastr.success('Import effectué avec succès'):toastr.error('Type de fichier incorrect', 'Erreur');
      
    } ,function errorCallback(){
        toastr.error('Problème survenu lors de l\'import du fichier', 'Erreur');
    });
 };

 $scope.createSubject = function(){
     
     $http.post('subject',$scope.subject).then(function successCallback(response){
         $scope.subject=response.data[0];
         $mdDialog.cancel();
         
     }, function errorCallback(response){
        alert(" Une erreur inattendue s'est produite") ;
     });
 };
 
 $scope.updateCycle = function(){
        var data = {id: $scope.cycle.id,data:$scope.cycle}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };
       $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.put('cycle',data,config).then(
            function successCallback(response){
                //reinitialise form
                //$location.path("/degree");
                $mdDialog.cancel();
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
     
 };
 

      

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
    
       
};


