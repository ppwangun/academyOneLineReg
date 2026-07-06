'use strict';

angular.module('exam')
        .component('evalWeightMgt',{
            templateUrl: 'evalWeightMgt',
            controller: evalweightCtrl 
});
function evalweightCtrl ($timeout,$http,$location,$mdDialog,$routeParams,$scope,$filter,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var id,exam_id;
    var $ctrl= this;
   
    $ctrl.selectedCriteria = null;
    $ctrl.showClasseElement = false;
    $ctrl.showCycleElement = false;
    $ctrl.gradeName = null;
    $ctrl.selectedCycle = null;
    $ctrl.selectedClasse;
    $ctrl.classes = [];
    $ctrl.rules = []
    $ctrl.ruleName ="";
    $ctrl.gradeRanges = [];
    $ctrl.weightDetails = [{"examType":'',"weightValue":0}];
    
    $ctrl.setRuleName = function()
    {
       var orderBy = $filter('orderBy');

        $ctrl.weightDetails =  $ctrl.weightDetails.filter(function(weight,index, self){
            return index === self.findIndex(function(u){
                return u.examType === weight.examType;
            })

        }); 
        $ctrl.ruleName = $ctrl.weightDetails.map(item=>item.examType)
        $ctrl.ruleName = orderBy($ctrl.ruleName); 
        $ctrl.ruleName = $ctrl.ruleName.join('+')       
    }
     
    $ctrl.sumUpWeight = function(){  
        $ctrl.totalWeight = $ctrl.weightDetails.reduce((accumulator, currentValue) => {return accumulator + currentValue.weightValue; },0);
    }
     
     $ctrl.addWeight = function(){
         $ctrl.weightDetails.push({"examType":'',"weightValue":''});
         
     }
     

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
           }).then(function(){
             $http.get('getRules').then(function(response){
                 $ctrl.rules = response.data['rules'];

            });
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
    $scope.loadSessionsBySem = function(semId)
    {
        var config = {
            params :  semId,
            headers : {'Accept' : 'application/json'}
        };
        var data = {semId:semId}
        $http.post('loadSessionsBySem',data,config).then(function successCallback(response){

            $scope.selectedSessions = response.data[0]; 
            
        })       
    } 
$ctrl.saveRule = function(){
    
    var data = {ruleName:$ctrl.ruleName,weights:$ctrl.weightDetails}
    var config = {
        params :  data ,
        headers : {'Accept' : 'application/json'}
    };
    //var data = {weightDetails:$ctrl.weightDetails}
          $timeout(
              $http.post('addRule',data,config).then(function(response){
                  $ctrl.rules = response.data;
              toastr.success("Opéraction effectuée avec succès")
             // $ctrl.isUpdate = true;
             // $scope.showButtonGroup = true;
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

$ctrl.deleteRule= function(ev,id){
      var data = {id: id}; 
      var config = {
      params: data,
      headers : {'Accept' : 'application/json'}
      };

// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Voulez vous vraiment supprimer cette règle?')
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
             // $location.path("/gradeconfig") ; 

         },
        function errorCallback(response){

            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });    
}



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
          fullscreen: true, // Only for -xs, -sm breakpoints.
          locals: {grade_id:grade_id,gradeRanges:$ctrl.gradeRanges}
        })
        .then(function(answer) {
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
 //Dialog Controller
  function DialogController($scope, $mdDialog,grade_id,gradeRanges) {
      $scope.selectedItem = null;
      $scope.grade = {};
      $scope.gradeRanges = gradeRanges;
      
      $scope.selectItem = function(item){
         $scope.selectedItem = item; 
         $scope.loadGradeData (item)
      }

      //
$scope.activateRangeUpdate = false;
    $scope.grade.min20 = null;
    $scope.grade.max20 = null;
    $scope.grade.min100 = null;
    $scope.grade.max100 = null;
    $scope.grade.value = null;
    $scope.grade.points = null; 
    $scope.grade.resultStatus =null;
$scope.loadGradeData = function(data)
{
    $scope.grade.id = data.id;
    $scope.grade.min20 = data.minsur20;
    $scope.grade.max20 = data.maxsur20;
    $scope.grade.min100 = data.minsur100;
    $scope.grade.max100 = data.maxsur100;
    $scope.grade.value = data.gradeValue;
    $scope.grade.points = data.gradePoints; console.log($scope.grade.points)
    $scope.grade.resultStatus =data.resultStatus;
    $scope.activateRangeUpdate = true;
}

$scope.addGradeRange = function(){
       
        $scope.grade.grade_id = grade_id;
         
          $timeout(
              $http.post('graderangeconfig',$scope.grade).then(function(response){
             toastr.success("Opéraction effectuée avec succès");
              
              $ctrl.gradeRanges.push(response.data[0]);
          }),500);    
};

$scope.updateGradeRange = function(){
    
        var data ={
            grade: $scope.grade,
            id : $scope.grade.id
        };
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };    
          $timeout(
              $http.put('graderangeconfig',data,config).then(function(response){
              toastr.success("Opéraction effectuée avec succès")

          }).then(function(){
                var data = {id: grade_id}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
              
             $http.get('graderangeconfig',config).then(
             function successCallback(response){
                $ctrl.gradeRanges = response.data[0];
                $scope.gradeRanges = response.data[0];
             })             
          })),500;    
};

$scope.deleteGradeRange = function(id,ev){
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


