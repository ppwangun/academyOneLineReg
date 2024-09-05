'use strict';

angular.module('exam')
        .component('newDeliberation',{
            templateUrl: 'newDeliberation',
            controller: newDeliberationCtrl 
});
function newDeliberationCtrl($timeout,$http,$location,$mdDialog,$routeParams,$scope,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var id,exam_id;
    var $ctrl= this;
   
    $ctrl.selectedCriteria = null;
    $ctrl.showClasseElement = false;
    $ctrl.showCycleElement = false;
    $ctrl.gradeName = null;
    $ctrl.selectedCycle = null;
    $ctrl.selectedClasse;
    $ctrl.classes = [];
    $ctrl.delib = {};
    $ctrl.delibContions = "";



    $ctrl.classes = []; 

    $scope.searchClasse = null;


    $ctrl.showElement = function(){
        if($ctrl.selectedCriteria === "classe")
        {
            $ctrl.showClasseElement = true;
            $ctrl.showCycleElement = false;
            $ctrl.selectedClasse = null;
            $ctrl.classes = [];
            
        }
        else{
            $ctrl.showClasseElement = false;
            $ctrl.showCycleElement = true;
            $ctrl.selectedCycle = null;
            $ctrl.classes = [];
            
            
        }

    }; 
 var grade_id =$routeParams.id;
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
         $http.get('semester').then(function(response){
             $ctrl.semesters = response.data[0];
         }).then(function(){
             $http.get('examtype').then(function(response){
                 $ctrl.examtypes = response.data[0];
                 
             });
         }),500); 
         
         
         
         if(grade_id)
         {
            $ctrl.isUpdate = true;
            var data = {id: grade_id};
            var config = {
            params: data,
            headers : {'Accept' : 'application/json'}
            };
             $timeout(
             //Collecting student credentials as well as all the payments associated with him        
             $http.get('gradeconfig',config).then(
             function successCallback(response){
                $ctrl.gradeName = response.data[0].name;
             },
             function errorCallback(){
                 
             }).then(function(){
                var data = {id: grade_id};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
             $http.get('graderangeconfig',config).then(
             function successCallback(response){
                $ctrl.gradeRanges = response.data[0];
             },
             function errorCallback(){
                 
             })}),1000);
             
         }

 };
 
 
$ctrl.addClasse = function(){
    //Cehck if the value exist in the array be for pushinng
    //Avoiding duplicates
    if ($ctrl.classes.includes($ctrl.selectedClasse.code) === false) $ctrl.classes.push($ctrl.selectedClasse.code);

};

$ctrl.removeClasse = function(cl){
         var index = $ctrl.classes.indexOf(cl);
         $ctrl.classes.splice(index,1);
};
 
$ctrl.saveGrade = function(){
    var gradeParams= {};
    gradeParams.gradeName = $ctrl.gradeName;
    gradeParams.cycle = $ctrl.selectedCycle;
    gradeParams.classes = $ctrl.classes;
    
          $timeout(
              $http.post('gradeconfig',gradeParams).then(function(response){
              toastr.success("Opéraction effectuée avec succès")
              $ctrl.isUpdate = true;
              $scope.showButtonGroup = true;
          }),500);
    
};

$ctrl.updateGrade = function(){
        var data ={
            gradeName : $ctrl.gradeName,
            cycle : $ctrl.selectedCycle,
            classes : $ctrl.classes,
            id : grade_id
        };
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };    
          $timeout(
              $http.put('gradeconfig',data,config).then(function(response){
              toastr.success("Opéraction effectuée avec succès")

          }),500);    
};

$ctrl.deleteGrade= function(ev){
      var data = {id: grade_id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer?')
            .textContent('Toutes les données associées à cette information seront perdues')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Supprimer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('gradeconfig',config).then(
          function successCallback(response){
              toastr.success("Opéraction effectuée avec succès");
              $location.path("/gradeconfig") ; 

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });    
}

$ctrl.addDelibCondition = function(){
       
        $ctrl.grade.grade_id =grade_id;
         
          $timeout(
              $http.post('delibConfig',$ctrl.delibContions).then(function(response){
             toastr.success("Opéraction effectuée avec succès");
              
              $ctrl.gradeRanges.push(response.data[0]);
          }),500);    
};

$ctrl.deleteGradeRange = function(id,ev){
      var data = {id: id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer?')
            .textContent('Toutes les données associées à cette information seront perdues')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Supprimer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('graderangeconfig',config).then(
          function successCallback(response){
              toastr.success("Opéraction effectuée avec succès");
              //check the index of the current object in the array
              var x;
              var index = $ctrl.gradeRanges.findIndex(x => x.id === id);
              //remove the current object from the array
              $ctrl.gradeRanges.splice(index,1);

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });    
}
    
 




    /*--------------------------------------------------------------------------
     *--------------------------- attendance validation---------------------------
     *----------------------------------------------------------------------- */
    $ctrl.showCreateRangeOfValues= function(ev){


        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/exam/gradeRange.html',
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
                    $http.put('exam',data,config).then(            
                        function successCallback(response){
                            
                
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
                    $http.put('exam',data,config).then(            
                        function successCallback(response){
                            
                
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
                    $http.put('exam',data,config).then(            
                        function successCallback(response){
                            
                
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
                    $http.put('exam',data,config).then(            
                        function successCallback(response){
                            
                
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
                    $http.put('exam',data,config).then(            
                        function successCallback(response){
                        //$ctrl.selectedClasse.code = response.data[0].classe;    
                
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

 
  
  $scope.dtOptions = DTOptionsBuilder.newOptions()
     .withButtons([
            //'columnsToggle',
            //'colvis',
            'copy',
            'print'

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
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };   

};


