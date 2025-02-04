'use strict';

angular.module('student')

        .component('pedagogicalReg',{
            controller: stdPedagogicalRegCtrl ,
            templateUrl: 'pedagogicalregtpl',

            
})

function stdPedagogicalRegCtrl($timeout,$http,$location,$mdDialog,$routeParams,$scope,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var id,exam_id;
    var $ctrl= this;
   
    $ctrl.isDisabled    = false;
    $ctrl.sem = null;
    $ctrl.selectedExamType = null;
    $scope.exams = [];
    $scope.studs = [];
    $scope.selectedStd = null;
    
 
    //$ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedClasse= null;
    $ctrl.isUpdate = false;
    //Making sure UE Select element is active only after semester is selested
    $ctrl.isActivatedUeSelect = false;
    $ctrl.isActivatedMatiereSelect = false;
    //controlling if mark edition is allow for the subject
    $ctrl.markCalculationStatus = 0;
    
    $scope.showButtonGroup = false;
    
    $scope.isAnonymatButtonActive = false;
    $scope.isReportNoteButtonActive= false;
    $scope.isNoteActive = false;
    $scope.isNoteButtonActive = false;
    $scope.isNoteValidationButtonActive = false;
    $scope.isNoteFinalisationButtonActive = false;
    $scope.isDisabledCheckbox = false;
    $scope.isAttendanceButtonActive = true;
    
    $scope.importButtonIsVisible = true;
    $scope.fileLoadButtonIsVisible = false;
    $scope.inportInputIsVisible = false;
    $scope.uploadSuccessIsVisible = false;

    $ctrl.ues = null;
    $ctrl.cycle = null;
    $ctrl.level = null;
    $ctrl.isCycleRequired= false;
    $ctrl.isMatiereRequired = false;
    $ctrl.classes = []; 
    $ctrl.dateExam = new Date();
    $ctrl.degrees = [];
    $ctrl.semesters = [];
    $ctrl.examtypes = [];
    $scope.sem = null;
    $scope.searchClasse = null;

    $ctrl.registeredStd = [];
    $ctrl.selectedSem = null;
    $ctrl.subjects = [];
    $ctrl.selectedUe = null;
    $ctrl.selectedSubject =null;
    $ctrl.examCode = null;
    $scope.currentExamCode = $ctrl.examCode;
    $ctrl.exam = null;
   
    var id,ue_class_id;
   // $ctrl.selectedItemChange = selectedItemChange;
   
   
  $scope.items = [];
  $scope.selected = [];
  
  $scope.toggle = function (item, list) {
    var idx = list.indexOf(item);
    if (idx > -1) {
      item.status = -1; 
      
      list.splice(idx, 1);
      var id = $ctrl.registeredStd.findIndex((obj => obj.id == item.id));
      $ctrl.registeredStd[id].status=-1;

    }
    else {
        item.status = 1;
      list.push(item);
      var id = $ctrl.registeredStd.findIndex((obj => obj.id == item.id));
      $ctrl.registeredStd[id].status=1;
    }
    
  };
  
  $scope.exists = function (item, list) {
    return list.indexOf(item) > -1;
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
      $ctrl.registeredStd.forEach(function(item) {  item.status = -1})
      
    } else if ($scope.selected.length === 0 || $scope.selected.length > 0) {
      $scope.selected = $scope.items.slice(0);
      $ctrl.registeredStd.forEach(function(item) {  item.status = 1})
    } 
  };   
 //collecte and load all the available classes of study  
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
     

    $ctrl.queryStudent = function(std)
    {
       var  dataString;
       if($ctrl.selectedAcadYr) dataString   = {matricule: std,acadYrId :$ctrl.selectedAcadYr.id,classeId: $ctrl.selectedClasse.id};
       else dataString   = {matricule: std};        
        var  config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('searchStudent',config).then(function(response){
                   return response.data[0];
                });
     };         

   
 };
 
 
$ctrl.activateUeSelect = function(){
   $ctrl.isActivatedUeSelect = true;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
};

$ctrl.selectedItemChange = function(classe){
    $ctrl.sem = null;
    $ctrl.selectedSem = null;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
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
                
    });
};

 

//If new student is registered to subject after exam already created,
//This function helps updatings the exam registration in such a way that
//The new registered student is as well registered to exam
$ctrl.updateExamRegistration = function(){
    var data = {semId: $ctrl.selectedSem.id ,ueId:$ctrl.selectedUe.id,examId: exam_id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };
    $http.get('updateexamregistraion',config).then(
    function(response){
        
       toastr.success("Mise à jeour effectuée avec succès");

        }).then(function(){
            getRegisteredStudent($ctrl.examCode);
        })
    
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

                            $http.get('stdregisteredtosubject',config).then(function(response){
                                $ctrl.registeredStd = response.data[0];
                                if($ctrl.registeredStd.length>0)
                                {

                                    
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                        //if($ctrl.registeredStd[i].isRepeated!=1)
                                        //{
                                            $ctrl.registeredStd[i].num = i+1;
                                            $scope.items.push($ctrl.registeredStd[i]);
                                        //}
                                            if($ctrl.registeredStd[i].status ===1 || $ctrl.registeredStd[i].status ===0 )
                                                $scope.selected.push($ctrl.registeredStd[i]);
                                        
                                    }
                                    for(i=0;i<$scope.items.length;i++)
                                    {
                                        $scope.items[i].num = i+1;
                                    }

                                }
                            }).then(function(){
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
  
                            });
                        
                           
                        };
                        
    // Saving choices made     
     $scope.saveChoices = function(selectedItems){
         if(selectedItems.length<=0) selectedItems = $ctrl.registeredStd;

        if(!$ctrl.selectedSubject)
            var data ={ueId: $ctrl.selectedUe.id,semId: $ctrl.selectedSem.id ,students : selectedItems};
        else var data ={ueId: $ctrl.selectedUe.id,subjectId: $ctrl.selectedSubject.id,semId: $ctrl.selectedSem.id,students : selectedItems};        
       /* var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };*/
         $timeout(
         $http.post('subjectregistration',data).then(
         function successCallback(response){
            
            response.data[0]?toastr.success('Informations sauvegardées avec succès'):toastr.error('Erreur lors de la sauvegarde des information', 'Erreur');
        
            $mdDialog.hide();

         },
         function errorCallback(response){

         }),1000);
    }


    /*--------------------------------------------------------------------------
     *---------------------------Loading subjects-------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.addStdToSubject = function(ev){
        $scope.isUpdate= true;

        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/addStudentForm.html',
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
    $scope.selectedStd;
    $scope.studs = [];
    $scope.selectedSubject = null;


//Select subjects to be add    
     $scope.addStudent = function()
     {
         
         //Cehck if the value exist in the array be for pushinng
         //Avoiding duplicates
         if ($scope.studs.includes($scope.selectedStd) === false) 
         {
             $scope.selectedStd.num = $scope.studs.length + 1;
             $scope.selectedStd.status = 1;
             $scope.studs.push($scope.selectedStd);
         }
         
         
     };
//Delete selected subject
     $scope.removeStudent = function(sub){
         var index = $scope.studs.indexOf(sub);
         $scope.studs.splice(index,1);
     };



  }    


   

  
      $scope.cancel = function() {
        $scope.importButtonIsVisible = true;
        $scope.uploadSuccessIsVisible = false;
        $scope.fileLoadButtonIsVisible = false;
        $scope.inportInputIsVisible = false;
        
      $mdDialog.cancel();
      
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    }; 
 



    
    
   
            


}
