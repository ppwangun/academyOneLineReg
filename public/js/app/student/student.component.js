'use strict';

angular.module('student')
        .component('studentList',{
            templateUrl: 'students',
            controller: studentCtrl 
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
function studentCtrl($timeout,$http,$location,$mdDialog,$scope,$log,$mdSidenav,toastr,DTOptionsBuilder,DTColumnDefBuilder){
    var $ctrl = this;
     $ctrl.students=[];
     $ctrl.stds = [];
     $ctrl.classes = [];
     
     $ctrl.faculties =[];
     $ctrl.filieres =[];
     $ctrl.specialite =[];
     
     $ctrl.school={id:'',name:'',code:'',logo:''};

     $http.get('school')
            .then(
            function (response){
               response.data[0].length<=0?$ctrl.isSchoolEmpty=true: $ctrl.isSchoolEmpty= false;
               $ctrl.isSchoolEmpty?$ctrl.school={id:'',name:'',code:'',logo:''} :$ctrl.school=response.data[0];
               
            },
            function(response)
            {
                
            }).then( function()
            {
                var data = {id: $ctrl.school.id}; 
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $http.get('faculty',config)
                         .then(
                         function(response){
                            $ctrl.faculties=response.data[0];
                });                     
            });     
     
    $scope.toggleLeft = buildDelayedToggler('left');
    $scope.toggleRight = buildToggler('right');
    $scope.isOpenRight = function(){
      return $mdSidenav('right').isOpen();
    };
    
     /**
     * Supplies a function that will continue to operate until the
     * time is up.
     */
    function debounce(func, wait, context) {
      var timer;

      return function debounced() {
        var context = $scope,
            args = Array.prototype.slice.call(arguments);
        $timeout.cancel(timer);
        timer = $timeout(function() {
          timer = undefined;
          func.apply(context, args);
        }, wait || 10);
      };
    }

    /**
     * Build handler to open/close a SideNav; when animation finishes
     * report completion in console
     */
    function buildDelayedToggler(navID) {
      return debounce(function() {
        // Component lookup should always be available since we are not using `ng-if`
        $mdSidenav(navID)
          .toggle()
          .then(function () {
            $log.debug("toggle " + navID + " is done");
          });
      }, 200);
    }

    function buildToggler(navID) {
      return function() {
        // Component lookup should always be available since we are not using `ng-if`
        $mdSidenav(navID)
          .toggle()
          .then(function () {
            $log.debug("toggle " + navID + " is done");
          });
      };
    }
    
    $scope.close = function () {
      // Component lookup should always be available since we are not using `ng-if`
      $mdSidenav('right').close()
        .then(function () {
          $log.debug("close RIGHT is done");
        });
    };

     var temp =[];
     var std_id;
     var data; 
     
     
    $ctrl.init = function(){
        
        
    /*--------------------------------------------------------------------------
     *--------------------------- Load faculties--------------------------------
     *----------------------------------------------------------------------- */
    $http.get('faculty').then(
        function(response){
        $scope.faculties = response.data[0];
    });  
    
    /*--------------------------------------------------------------------------
     *--------------------------- Load filieres --------------------------------
     *----------------------------------------------------------------------- */
     $http.get('filiere').then(function(response){
        $scope.filieres=response.data[0];  
     });    
    
    $timeout(
     $http.get('stdFromPv').then(
     function successCallback(response){
        $ctrl.students = response.data[0]; 
        //$ctrl.students= response.data;
        
        
        for(var i=0;i<$ctrl.students.length;i++)
        {
            $ctrl.students[i].dateInscription = $ctrl.students[i].dateInscription.date;
            $ctrl.students[i].dateNaissance = $ctrl.students[i].dateNaissance.date;
        }
        
       //$ctrl.students=response.data.data;
     },
     function errorCallback(response){
         
     }),1000);
    };
 
 

    $ctrl.dtOptions = DTOptionsBuilder.newOptions()
         .withButtons([
            //'columnsToggle',
            //'colvis',
            {extend:'copy',text:'Copier dans le presse papier',className: 'btn btn-inverse'},
            {extend:'print',text:"Imprimer",className: 'btn btn-inverse'}

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
    DTColumnDefBuilder.newColumnDef(5).notSortable(),
    DTColumnDefBuilder.newColumnDef(6)
  ];

    $ctrl.formatDate = function(date){
      var dateOut = new Date(date);
      return dateOut;
    };
 
 //Redirect user when double click
  $ctrl.redirect= function(id){

     $location.path("/studentinfos/"+id);
     std_id=id;

      //collect student registered subject
    
    /*var data = {id: std_id};
    var config = {
    params: data,
    headers : {'Accept' : 'application/json'}
    };
     $timeout(
     $http.get('subjectregistration',config).then(function(response){
      
        $ctrl.subjects = response.data[0];
       
       
     }),1000);
     
     //Collect all the laible classes
      $timeout(
     $http.get('classes').then(function(response){
         //convert all loaded data to lower case
        angular.forEach(response.data[0],function(item){

          $ctrl.classes.push({id:item.id,code:item.code, name:item.name.toLowerCase()+"["+item.code+"]"}); 

        });
     }).then(function(){
         $http.get('semester').then(function(response){
             $ctrl.semesters = response.data[0];
         });
     }),500); */
 };
 
 //clearing unit_registration duplicates
  $ctrl.clearDuplicates= function(){

    $timeout(
     $http.get('clearUnitRegistrationDuplicates').then(
     function successCallback(response){
       // response.data[0]; 
        toastr.success("Opération effectué aavec succès");

     },
     function errorCallback(response){
         toastr.error("Une erreure inattendue s'est produite");
     }),1000);

 };
 
  //Generate picture png files from base64
  $ctrl.picturesGenerator= function(ev){
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Génération des photos')
            .textContent('Cette opération va générer toutes les photos de la base de données vers un dossier compressé')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $scope.showGenerateButton = true;
        $ctrl.generatePicturesPopupWindow(ev);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 };
 
   //Generate students data
  $ctrl.updateStdMpc= function(ev){
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Mise à jour MPC')
            .textContent('Cette opération vous permets de mettre à jour les MPC ainsi que les nombre de crédits validés des étudiants')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
       
        $ctrl.loadStdMpcData(ev);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 };
 
  //Generate students data
  $ctrl.studentsDataGenerator= function(ev){
        // Preparing the confirm windows
        var confirm = $mdDialog.confirm()
            .title('Génération des données d\'étudiants')
            .textContent('Cette opération va générer les données de tous les étudiants dans un fichier de type csv')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
        //open de confirm window
        $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $scope.showGenerateButton = true;
        $ctrl.generateStudentDataPopupWindow(ev);

    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });      
 }; 
    /*--------------------------------------------------------------------------
     *---------------------------Loading Student--------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStdData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/uploadStdForm.html',
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
     *---------------------------Loading Student PV------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStdPvData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/uploadStdPvForm.html',
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
     *---------------------------Loading Student PVPMC--------------------------- */
    $ctrl.loadStdMpcData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/uploadStdMpcForm.html',
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
     *---------------------------Loading Student financial data-----------------
     *----------------------------------------------------------------------- */
    $ctrl.loadStdFinancialData = function(ev){
        $scope.isUpdate= true;
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/uploadStdFinanceForm.html',
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
     *---------------------------Loading Student PV------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.generatePicturesPopupWindow= function(ev){
       
       
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/generatePhoto.html',
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
            $scope.showProgress = false;
            $scope.showDownloadZipLink =false;
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    }; 
    
    /*--------------------------------------------------------------------------
     *---------------------------Loading Student PV------------------------------
     *----------------------------------------------------------------------- */
    $ctrl.generateStudentDataPopupWindow= function(ev){
       
        $mdDialog.show({
          controller: DialogController,
          templateUrl: 'js/app/student/generateStudentsData.html',
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
            $scope.showProgress = false;
            $scope.showDownloadZipLink =false;
          
          $ctrl.status = 'You said the information was "' + answer + '".';
        }, function() {
          $ctrl.status = 'You cancelled the dialog.';
        });        
    };     
    
 //Dialog Controller
  function DialogController($scope, $mdDialog) {
      
$scope.subject = {code:'',name:'',credits: '',hours_vol:'',cm_hrs:'',tp_hrs:'',td_hrs:'',ue_classe_id: '', ue_id:''};


 $scope.createSubject = function(){
     
     $http.post('subject',$scope.subject).then(function successCallback(response){
         $scope.subject=response.data[0];
         $mdDialog.cancel();
         
     }, function errorCallback(response){
        alert(" Une erreur inattendue s'est produite") ;
     });
 };
 
$scope.uploadStd = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importstudents',
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
 
$scope.uploadStdPv = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importStudentPv',
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
 
 $scope.uploadStdMpc = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importStudentMpc',
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
 
 $scope.updateMpc = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importStudentPv',
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
 
 $scope.uploadStdFinance = function(){
 
    var fd = new FormData();
    var files = document.getElementById('file').files[0];
    fd.append('file',files);

    // AJAX request
    $http({
     method: 'post',
     url: 'importStudentFinance',
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
 
    $scope.generatePictures = function(){
        $scope.showProgress = true;
        $scope.showGenerateButton = false;
        $scope.showCloseButton =false;
        $timeout(
            $http.get('picturesGenerator').then(
            function successCallback(response){
              // response.data[0]; 
              $scope.showProgress = false;
              $scope.showDownloadZipLink =true;
              $scope.showCloseButton = true;
              toastr.success("Opération effectué aavec succès");

            },
            function errorCallback(response){
                toastr.error("Une erreure inattendue s'est produite");
        }),1000);
    }
    
    $scope.generateData = function(){
        $scope.showProgress = true;
        $scope.showGenerateButton = false;
        $scope.showCloseButton =false;
        $timeout(
            $http.get('studentsDataGenerator').then(
            function successCallback(response){
              // response.data[0]; 
              $scope.showProgress = false;
              $scope.showDownloadLink =true;
              $scope.showCloseButton = true;
              toastr.success("Opération effectué aavec succès");

            },
            function errorCallback(response){
                toastr.error("Une erreure inattendue s'est produite");
        }),1000);
    }    

      $scope.cancel = function() {
      $mdDialog.cancel();
    };

  }
  


    $scope.answer = function(answer) {
      //$scope.faculties=[];
      //$scope.filiere={nom:'',code:'',fac_id:$scope.faculty.id,status:''};
      $mdDialog.hide(answer);
    };   


};