'use strict';

angular.module('exam')
        .component('newExam',{
            templateUrl: 'newexam',
            controller: newexamCtrl ,
})
.directive('fileReaderDirective', function() {
    return {
        restrict: "A",
        scope: {
            fileReaderDirective: "=",
        },
        link: function(scope, element) {
            $(element).on('change', function(changeEvent) {
                var files = changeEvent.target.files;
                if (files.length) {
                    var r = new FileReader();
                    r.onload = function(e) {
                        var contents = e.target.result;
                        scope.$apply(function() {
                            scope.fileReaderDirective = contents;
                        });
                    };
                    r.readAsText(files[0]);
                }
            });
        }
    };
})
.factory('readFileData', function() {
    return {
        processData: function(csv_data) {
            var record = csv_data.split(/\r\n|\n/);
            var headers = record[0].split(',');
            var lines = [];
            var json = {};

            for (var i = 0; i < record.length; i++) {
                var data = record[i].split(',');
                if (data.length == headers.length) {
                    var tarr = [];
                    for (var j = 0; j < headers.length; j++) {
                        tarr.push(data[j]);
                    }
                    lines.push(tarr);
                }
            }
            
            for (var k = 0; k < lines.length; ++k){
              json[k] = lines[k];
            }
            return json;
        }
    };
});;
function newexamCtrl($timeout,$http,$location,$mdDialog,$routeParams,$scope,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var id,exam_id;
    var $ctrl= this;
   
    $ctrl.isDisabled    = false;
    $ctrl.sem = null;
    $ctrl.selectedExamType = null;
    $scope.exams = [];
    
 
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
    $ctrl.examCode = null;
    $scope.currentExamCode = $ctrl.examCode;
    $ctrl.exam = null;
   
    var id,ue_class_id;
   // $ctrl.selectedItemChange = selectedItemChange;
    
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
     
     $timeout(    
    
             $http.get('examtype').then(function(response){
                 $ctrl.examtypes = response.data[0];

         }),500); 
         
    //collectstudent exam id 
    exam_id =$routeParams.exam_id;
    //check  weither we are performing an update of exam or not
    if(exam_id)
    {

        $ctrl.isUpdate = true;
        $scope.showButtonGroup = true;
        

            var data = {id: exam_id};
            var config = {
            params: data,
            headers : {'Accept' : 'application/json'}
            };
             $timeout(
             //Collecting student credentials as well as all the payments associated with him        
             $http.get('exam',config).then(
             function successCallback(response){
                 
                 $scope.isMarkRegistered = response.data[0].is_registered_mark;
                 $scope.isMarkValidated = response.data[0].is_validated_mark;
                 $scope.isMarkConfirmed = response.data[0].is_confirmed_mark;
                 
                 
                        if(response.data[0].is_attendance_saved ===0)
                        {
                            $scope.isAttendanceButtonActive = true;
                            $scope.isAnonymatButtonActive = false;
                            $scope.isReportNoteButtonActive = false;
                        }
                        else if(response.data[0].is_anonymat_saved ===0){
                            $scope.isAttendanceButtonActive = false;
                            $scope.isAnonymatButtonActive = true;
                            $scope.isReportNoteButtonActive = false;
                        }
                        else if((response.data[0].is_registered_mark ===1&&response.data[0].is_validated_mark ===1&&response.data[0].is_confirmed_mark ===1))
                        {
                            $scope.isAttendanceButtonActive = false;
                            $scope.isAnonymatButtonActive = false;
                            $scope.isReportNoteButtonActive = false;
                            $scope.isDisabledCheckbox = true;
                        }
                        else{
                            $scope.isReportNoteButtonActive = true;
                            $scope.isAnonymatButtonActive = false;
                            $scope.isAttendanceButtonActive = false;
                            $scope.isDisabledCheckbox = true;
                       }

             
                       
                        //(response.data[0].is_attendance_saved ===1&&response.data[0].is_anonymat_saved ===1)?$scope.isAnonymatButtonActive = false:$scope.isAnonymatButtonActive = true;
                        //(response.data[0].is_anonymat_saved===1&&response.data[0].is_registered_mark ===1&&response.data[0].is_validated_mark ===1&&response.data[0].is_confirmed_mark ===1)?$scope.isReportNoteButtonActive = false:$scope.isReportNoteButtonActive = true;
                        $ctrl.examCode = response.data[0].exam_code;
                        $ctrl.exam = response.data[0];
                        var data = {id: response.data[0].classe_id};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };
                        $http.get('classes',config).then(
                        function(resp){
                            $ctrl.selectedClasse=resp.data[0];

                            }).then(function(){
                                $ctrl.asignedSemToClasse($ctrl.selectedClasse.code);
      
                        }).then(function(){

                        var data = {id: response.data[0].sem_id};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };                        
                        $http.get('semester',config).then(function(response){
                             $ctrl.selectedSem = response.data[0]; 
                              
                        })}).then(function(){

                        var data = {id: response.data[0].exam_type_code};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };                        
                        $http.get('examtype',config).then(
                        function(response){
                             $ctrl.selectedExam = response.data[0];
                                
                        });}).then(function(){ 
                        
                        var data = {id: response.data[0].ue_id};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };                        
                        $http.get('teachingunit',config).then(
                        function(response){
                             $ctrl.selectedUe = response.data[0];
                             $ctrl.isActivatedUeSelect = true; 
                             $ctrl.loadUE($ctrl.selectedClasse,$ctrl.exam.sem_id);
                             
                                
                        });}).then(function(){ 
                        
                        var data = {id: response.data[0].subject_id};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };                        
                        $http.get('subject',config).then(
                        function(response){
                             $ctrl.selectedSubject = response.data[0];
                             $ctrl.isActivatedMatiereSelect = true;
                            // $ctrl.loadUE($ctrl.selectedClasse);
                                
                        });
                        }).then(function(){
                                    var config = {
                                    params: {id:response.data[0].ue_id},
                                    headers : {'Accept' : 'application/json'}
                                    };   

                                    $http.get('subjectbyue',config).then(function(response){
                                        $ctrl.subjects = response.data[0];
                                        if($ctrl.subjects.length>0)
                                        {
                                            $ctrl.isActivatedMatiereSelect = true;
                                            $ctrl.isMatiereRequired = true;
                                        }
                                    });
                            }).then(function(){
                            
                        var data = {id: response.data[0].exam_code};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };                        
                        $http.get('stdregisteredtoexam',config).then(
                        function(response){
                        $ctrl.registeredStd = response.data[0];
                        if($scope.isMarkConfirmed===1)
                        {
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].confirmedMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                        }else if($scope.isMarkValidated===1)
                        {
                                $scope.isNoteActive = true;
                                $scope.isNoteFinalisationButtonActive = true;
                                $scope.isDisabledCheckbox = true;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].validatedMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }   
                        }else if($scope.isMarkRegistered===1)
                        {
                                $scope.isNoteActive = true;
                                $scope.isNoteValidationButtonActive = true;
                                //$scope.isDisabledCheckbox = false;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }                             
                        } else if(!$scope.isAttendanceButtonActive&&!$scope.isAnonymatButtonActive){
                                $scope.isNoteActive = true;
                                $scope.isNoteButtonActive = true;
                                $scope.isDisabledCheckbox = true;
                                
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                             
                        }
                        else if($scope.isAttendanceButtonActive)
                        {
                            $scope.isDisabledCheckbox = false;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                            
                        }
                        else{
                                $scope.isNoteActive = false;
                                $scope.isNoteButtonActive = false; 
                                $scope.isDisabledCheckbox = true;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                        }
                                

                            
                                
                        });                            
                            
                        }) ;
             },
             function errorCallback(response){

             }),1000);
        
    }
   
 };
 
 
$ctrl.activateUeSelect = function(){
   $ctrl.isActivatedUeSelect = true;
   $ctrl.selectedUe = null;
   $ctrl.selectedSubject = null;
};

$ctrl.selectedItemChange = function(classe){
    $ctrl.sem = null;
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

$ctrl.cancelExam = function(ev)

{
    var data = {id:$ctrl.examCode ,code: $ctrl.examCode,typeOperation:'CANCEL_EXAM'};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    }; 
            var confirm = $mdDialog.confirm()
            .title('Êtes vous sûre de vouloir annuler cet examen?')
            .textContent('??????')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
    $timeout(function(){  
        $http.put('exam',data,config).then(            
            function successCallback(response){
                $location.path("/examlist");


        },
         function errorCallback(response){
                alert("Une erreur inattendue s'est produite");

        });},1000);
        }, function() {
     // $scope.status = 'You decided to keep your debt.';
        });

};

      $ctrl.rollBack = function()
      {
            $scope.isAnonymatButtonActive = false;
            $scope.isReportNoteButtonActive= false;
            $scope.isNoteActive = false;
            $scope.isNoteButtonActive = false;
            $scope.isNoteValidationButtonActive = false;
            $scope.isNoteFinalisationButtonActive = false;
            $scope.isDisabledCheckbox = false; 
            $scope.isAttendanceButtonActive = true;
            
            $mdDialog.hide();
          
      }; 

//If new student is registered to subject after exam already created,
//This function helps updatings the exam registration in such a way that
//The new registered student is as well registered to exam
$ctrl.updateExamRegistration = function(){
    if($ctrl.selectedSubject)
        var data = {semId: $ctrl.selectedSem.id ,ueId:$ctrl.selectedUe.id,examId: exam_id,subjectId:$ctrl.selectedSubject.id};
    else var data = {semId: $ctrl.selectedSem.id ,ueId:$ctrl.selectedUe.id,examId: exam_id};
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
     
                $ctrl.ues = [];
                $ctrl.subjects= [];
                $ctrl.selectedSubject = null;
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
 $ctrl.loadStd = function(){
                            $ctrl.isActivatedMatiereSelect = false;
                            $ctrl.isMatiereRequired = false;
                            if($ctrl.selectedSubject) var data = {id: {ueId: $ctrl.selectedUe.id,subjectId: $ctrl.selectedSubject.id}};
                            else var data = {id : {ueId: $ctrl.selectedUe.id}};
                            var i;
                            var config = {
                            params: data,
                            headers : {'Accept' : 'application/json'}
                            };
                            if(!exam_id)
                            {
                            $http.get('stdregisteredtosubject',config).then(function(response){
                                $ctrl.registeredStd = response.data[0];
                                if($ctrl.registeredStd.length>0)
                                {
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].attendance = "P";
                                      $ctrl.registeredStd[i].anonymat = 0;
                                      $ctrl.registeredStd[i].note = 0;
                                    }
                                }
                            }).then(function(){
                                
                                    data = {id : $ctrl.selectedUe.id};
                                    var config = {
                                    params: data,
                                    headers : {'Accept' : 'application/json'}
                                    };   
                                    if($ctrl.subjects<=0)
                                    $http.get('subjectbyue',config).then(function(response){
                                        
                                        
                                          $ctrl.subjects = response.data[0];
                                      });

                            });
                                $ctrl.isActivatedMatiereSelect = true;
                                $ctrl.isMatiereRequired = true;
                        }
                          
                        };


// this function creates  a new exam
  $ctrl.newExam = function(){
      
      var examParams = {};
      examParams.typeOperation = "NEW_EXAM";
      examParams.date = $ctrl.dateExam;
      examParams.classe = $ctrl.selectedClasse.id;
      examParams.semester = $ctrl.selectedSem.id;
      examParams.examtype = $ctrl.selectedExam.code;
      examParams.ue_id = $ctrl.selectedUe.id;
      
      if($ctrl.selectedSubject)
        examParams.subject = $ctrl.selectedSubject.id;
      examParams.students = $ctrl.registeredStd;
      
        
     
      $timeout(
              $http.post('exam',examParams).then(function(response){
              // $location.path("/teachingunit") ; 
              $ctrl.examCode = response.data[0].code;
              exam_id = response.data[0].examId;
              $ctrl.isUpdate = true;
              $scope.showButtonGroup = true;
              toastr.success("opération effectuée avec succès");
          }),500);
      
  };  

    /*--------------------------------------------------------------------------
     *--------------------------- attendance validation---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.showUpdateAttendance= function(ev){


        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/exam/updateForm.html',
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
    /*--------------------------------------------------------------------------
     *--------------------------- Printing list of student participating to the exam ---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.showPrintList= function(ev){


        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printstudentlist/'+exam_id,
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
    /*--------------------------------------------------------------------------
     *--------------------------- Printing mark of student who have participated to exam ---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.showPrintNotes= function(ev){


        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printnotes/'+exam_id,
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
    /*--------------------------------------------------------------------------
     *--------------------------- Printing mark of student who have participated to exam ---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.showPrintExamEtiquette= function(ev){


        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'printexametiquette/'+exam_id,
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
    
      //This function reinitialises buttons in such a way tha modifiactions can be done on the exam registration process
   
    
 //Dialog Controller
  function DialogController($scope, $mdDialog,readFileData,toastr) {
    var i;
    $scope.students = $ctrl.registeredStd;
    for(i=0;i<$ctrl.registeredStd.length;i++)
    {
      $scope.students[i].attend = $ctrl.registeredStd[i].attendance;
      $scope.students[i].anonym= $ctrl.registeredStd[i].anonymat;
      $scope.students[i].mark= $ctrl.registeredStd[i].note;
      
     };
      //




      $scope.saveAttendance = function()
      {
          var queryString = [];
          $scope.isAnonymatButtonActive = true;
          $scope.isDisabledCheckbox = true;
          $scope.isAttendanceButtonActive = false;
          $ctrl.registeredStd = $scope.students;
          
            for(i=0;i<$ctrl.registeredStd.length;i++)
            {
               $ctrl.registeredStd[i].attendance = $scope.students[i].attend;
               $ctrl.registeredStd[i].anonymat = $scope.students[i].anonym;
               $ctrl.registeredStd[i].note = $scope.students[i].mark;
            } 
  
                var data = {id: $ctrl.examCode, data : $ctrl.registeredStd,"typeOperation":"SAVE_ATTENDANCE"}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $timeout(function(){ 
                    $http.post('exam',data).then(            
                        function successCallback(response){
                           toastr.success("opération effectuée avec succès");
                
                    },
                     function errorCallback(response){
                            alert("Une erreur inattendue s'est produite");
           
                    })},1000);

          $mdDialog.hide();
      };
      
      
      $scope.saveAnonymat= function(){
          $scope.isAnonymatButtonActive = false;
          $scope.isNoteButtonActive = true;
          $scope.isNoteActive = true;
          $scope.isReportNoteButtonActive= true;
          
                    $ctrl.registeredStd = $scope.students;
          
            for(i=0;i<$ctrl.registeredStd.length;i++)
            {
               $ctrl.registeredStd[i].attendance = $scope.students[i].attend;
              $ctrl.registeredStd[i].anonymat = $scope.students[i].anonym;
              $ctrl.registeredStd[i].note = $scope.students[i].mark;
            } 
            
                var data = {id: $ctrl.examCode, data : $ctrl.registeredStd,"typeOperation":"SAVE_ANONYMAT"}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $timeout(function(){  
                    $http.post('exam',data).then(            
                        function successCallback(response){
                           toastr.success("opération effectuée avec succès");
                
                    },
                     function errorCallback(response){
                            alert("Une erreur inattendue s'est produite");
           
                    })},1000);
          
            $mdDialog.hide();
      }
      
      $scope.saveNotes = function(){
            $ctrl.registeredStd = $scope.students;
          
            for(i=0;i<$ctrl.registeredStd.length;i++)
            {
               $ctrl.registeredStd[i].attendance = $scope.students[i].attend;
              $ctrl.registeredStd[i].anonymat = $scope.students[i].anonym;
              $ctrl.registeredStd[i].note = $scope.students[i].mark;
              $ctrl.registeredStd[i].registeredMark = $scope.students[i].mark;
            } 
            
                var data = {id: $ctrl.examCode, data : $ctrl.registeredStd,"typeOperation":"REGISTER_MARK"}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $timeout(function(){  
                    $http.post('exam',data).then(            
                        function successCallback(response){
                            
                            toastr.success("opération effectuée avec succès");
                    },
                     function errorCallback(response){
                            alert("Une erreur inattendue s'est produite");
           
                    })},1000);
            
            $scope.isNoteButtonActive = false;
            $scope.isNoteValidationButtonActive = true;
            $scope.isDisabledCheckbox = false;
            
            $mdDialog.hide();
      }

      $scope.validateNotes = function(){
            $ctrl.registeredStd = $scope.students;
          
            for(i=0;i<$ctrl.registeredStd.length;i++)
            {
               $ctrl.registeredStd[i].attendance = $scope.students[i].attend;
              $ctrl.registeredStd[i].anonymat = $scope.students[i].anonym;
              $ctrl.registeredStd[i].note = $scope.students[i].mark;
              $ctrl.registeredStd[i].validatedMark = $scope.students[i].mark;
             } 
            
                var data = {id: $ctrl.examCode, data : $ctrl.registeredStd,"typeOperation":"VALIDATE_MARK"}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $timeout(function(){  
                    $http.post('exam',data).then(            
                        function successCallback(response){
                        toastr.success("opération effectuée avec succès");    
                
                    },
                     function errorCallback(response){
                            alert("Une erreur inattendue s'est produite");
           
                    })},1000);
            
            $scope.isNoteValidationButtonActive = false;
            $scope.isNoteFinalisationButtonActive = true;
            
            $mdDialog.hide();
      }

      $scope.finaliseNotes = function(){
            $ctrl.registeredStd = $scope.students;
          
            for(i=0;i<$ctrl.registeredStd.length;i++)
            {
               $ctrl.registeredStd[i].attendance = $scope.students[i].attend;
              $ctrl.registeredStd[i].anonymat = $scope.students[i].anonym;
              $ctrl.registeredStd[i].note = $scope.students[i].mark;
              $ctrl.registeredStd[i].confirmedMark = $scope.students[i].mark;

            } 
                var data = {id: $ctrl.examCode, data : $ctrl.registeredStd,"typeOperation":"CONFIRM_MARK"}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $timeout(function(){  
                    $http.post('exam',data).then(            
                        function successCallback(response){
                        //$ctrl.selectedClasse.code = response.data[0].classe;    
                        toastr.success("opération effectuée avec succès");
                    },
                     function errorCallback(response){
                            alert("Une erreur inattendue s'est produite");
           
                    })},1000);
            
            $scope.isNoteFinalisationButtonActive = false;
            $scope.isReportNoteButtonActive= false;
            $mdDialog.hide();
      }
      
      $scope.printStudentList = function(){
          
      };
      
//Loading import form      
    $scope.loadImportForm = function(ev){
        $scope.importButtonIsVisible = false;
        $scope.fileLoadButtonIsVisible = true;
        $scope.inportInputIsVisible = true;

    };
    
  $scope.fileDataObj = {};
    
    $scope.uploadFile = function() {
      if ($scope.fileContent) {
        $scope.fileDataObj = readFileData.processData($scope.fileContent);
      var stds = $scope.fileDataObj;
        $scope.fileData = JSON.stringify($scope.fileDataObj);
          console.log(stds);
      }
      
      for (var i=0;i<$scope.students.length;i++)
      {
         
          for(var j=1;j<Object.keys(stds).length;j++)
          {
              
                var mat = stds[j][0].split(';')[0];
                var anonym = stds[j][0].split(';')[1];
                var note = stds[j][0].split(';')[2]; 

              if($scope.students[i]["matricule"] === mat || $scope.students[i]['anonym'] === anonym)
              {
                   // note = note.replace(/,/g, '.'); 
                   if($scope.students[i].attendance === "P")
                    $scope.students[i]['mark'] = parseFloat(note);
                  
                    
                }
                
          }
          
      }
        $scope.importButtonIsVisible = false;
        $scope.uploadSuccessIsVisible = true;
        $scope.fileLoadButtonIsVisible = false;
        $scope.inportInputIsVisible = false;
       

    }; 
  
  $scope.dtOptions = DTOptionsBuilder.newOptions()
     .withButtons([
            //'columnsToggle',
            //'colvis',
            {extend: 'copy', text:'Exporter'},
            {extend: 'print', text:'Imprimer'}

        ])
        .withPaginationType('full_numbers')
        .withDisplayLength(150)
        .withOption('paging', false)
         /* .withFixedHeader({
    top: true
  })*/;
  
    $scope.dtColumnDefs = [
    DTColumnDefBuilder.newColumnDef(0).notSortable(),
    DTColumnDefBuilder.newColumnDef(1).notSortable(),
    DTColumnDefBuilder.newColumnDef(2).notSortable(),
    DTColumnDefBuilder.newColumnDef(3).notSortable(),
    DTColumnDefBuilder.newColumnDef(4).notSortable(),
    DTColumnDefBuilder.newColumnDef(5).notSortable(),

  ];  
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
 



    
    
    var getRegisteredStudent = function(examCode)
    {
    
                            
                        //var data = {id: response.data[0].exam_code};
                        var data = {id: examCode};
                        var config = {
                        params: data,
                        headers : {'Accept' : 'application/json'}
                        };                        
                        $http.get('stdregisteredtoexam',config).then(
                        function(response){
                        $ctrl.registeredStd = response.data[0];
                        if($scope.isMarkConfirmed===1)
                        {
                            
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].confirmedMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                        }else if($scope.isMarkValidated===1)
                        {
                                $scope.isNoteActive = true;
                                $scope.isNoteFinalisationButtonActive = true;
                                $scope.isDisabledCheckbox = true;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].validatedMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }   
                        }else if($scope.isMarkRegistered===1)
                        {
                                $scope.isNoteActive = true;
                                $scope.isNoteValidationButtonActive = true;
                                $scope.isDisabledCheckbox = false;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }                             
                        } else if(!$scope.isAttendanceButtonActive&&!$scope.isAnonymatButtonActive){
                                $scope.isNoteActive = true;
                                $scope.isNoteButtonActive = true;
                                $scope.isDisabledCheckbox = true;
                                
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                             
                        }
                        else if($scope.isAttendanceButtonActive)
                        {
                            $scope.isDisabledCheckbox = false;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                            
                        }
                        else{
                                $scope.isNoteActive = false;
                                $scope.isNoteButtonActive = false; 
                                $scope.isDisabledCheckbox = true;
                                if($ctrl.registeredStd.length>0)
                                { 
                                    var i;
                                    for(i=0;i<$ctrl.registeredStd.length;i++)
                                    {
                                      $ctrl.registeredStd[i].num=i+1;
                                      $ctrl.registeredStd[i].note = $ctrl.registeredStd[i].registeredMark;

                                    }
                                    $scope.students = $ctrl.registeredStd;
                                }
                        }
                                

                            
                                
                        });                            
                            
            };
            
    /*--------------------------------------------------------------------------
     *---------------------------Loading subjects-------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.duplicateNotes = function(ev){
        $scope.isUpdate= true;

        $mdDialog.show({
          controller: DialogController2,
          templateUrl: 'js/app/exam/addExamForm.html',
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
$scope.examCodes = [];    
function DialogController2($scope, $mdDialog, $q,toastr) {
    $scope.selectedSubject= null;
    
    $scope.selectedSubject = null;
    $scope.currentExamCode = $ctrl.examCode;

    //Loading subjects asynchronously
    $scope.query = function(codeExam)
    {
       var  dataString = {id: codeExam},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('examSearch',config).then(function(response){
                   return response.data[0];
                });
     };
  
//Select subjects to be add    
     $scope.addExam = function()
     {
         //Cehck if the value exist in the array be for pushinng
         //Avoiding duplicates
         if ($scope.exams.includes($scope.selectedExam) === false) 
         {
             $scope.exams.push($scope.selectedExam);
             $scope.examCodes.push($scope.selectedExam.code);
         }
         
         
     };
//Delete selected subject
     $scope.removeExam = function(sub){
         var index = $scope.exams.indexOf(sub);
         $scope.exams.splice(index,1);
     };


    // Saving choices made     
     $scope.saveChoices = function(ev){
        var confirm = $mdDialog.confirm()
            .title('Duplication des notes')
            .textContent('Duplication des notes de  l\'évaluation'+$scope.currentExamCode+' vers '+$scope.examCodes.toString()+' voulez vous continuer?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {         

        var data = { data:{ fromExam: $scope.currentExamCode,toExams : $scope.examCodes},
            
        };
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
         $timeout(
         $http.get('noteDuplication',config).then(
         function successCallback(response){
            
            response.data[0]?toastr.success('OPération effectuée avec succès'):toastr.error('Verifier que les notes de la source existent','Erreur lors de la duplication des notes');
           //$ctrl.subjects =  $ctrl.subjects.concat($scope.subjects)
           // $mdDialog.hide();

         },
         function errorCallback(response){

         }),1000);
         
     }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });

 

  }    
}
}
