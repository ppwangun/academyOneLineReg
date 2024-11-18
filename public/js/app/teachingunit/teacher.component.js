angular.module('teachingunit').component('manageTeacher',{
            templateUrl: 'teacherList',
            controller: newTeacherController 
});
angular.module('teachingunit').component('newTeacher',{
            templateUrl: 'newteachertpl',
            controller: newTeacherController 
});
angular.module('teachingunit')
        .component('acadRankConfig',{
            templateUrl: 'acadranktpl',
            controller: newTeacherController 
});
angular.module('teachingunit')
        .component('newAcadRank',{
            templateUrl: 'newAcadRank',
            controller: newTeacherController 
});

function parseObjectToFormData(object, formData = new FormData(), namespace = '') {
    for(let property in object) {
        if (!object.hasOwnProperty(property) || !object[property]) {
            continue;
        }
        let formKey = namespace ? `${namespace}[${property}]` : property;
        if (object[property] instanceof Date) {
            formData.append(formKey, object[property].toISOString());
        }
        else if (typeof object[property] === 'object' && !(object[property] instanceof File) && !(object[property] instanceof Blob)) {
            parseObjectToFormData(object[property], formData, formKey);
        }
        else {
            formData.append(formKey, object[property]);
        }
    }
    return formData;
}

function formatDecimals(value, n) {
    const shouldFixed = value > Math.floor(value);
    return shouldFixed ? parseFloat(parseFloat(value.toString()).toFixed(n)) : Math.floor(value);
}

function printReadableDate(date, lang, printMonth = false, printHours = false){
    const englishDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const frenchDays = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

    const englishMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const frenchMonths = ['Jan', 'FÃ©v', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aou', 'Sep', 'Oct', 'Nov', 'DÃ©c'];

    const days = lang === 'fr' ? frenchDays : englishDays;
    const months = lang === 'fr' ? frenchMonths : englishMonths;

    const hours = (lang === 'fr' ? ' Ã  ' : ' at ') + (date.toLocaleTimeString());

    return printMonth ? (days[date.getDay()] + ', ' + date.getDate() + ' '+ months[date.getMonth()] + ' '+ date.getFullYear() + (printHours ? hours : '')) : (days[date.getDay()] + ', '+ date.toLocaleDateString());
}
function newTeacherController($scope, $http, $location,$routeParams,$timeout,toastr,$mdDialog,DTOptionsBuilder,DTColumnDefBuilder) {
    $scope.hasLoadedAssets = null;

    $scope.teacher = {
        names: null,
        birthdate: null,
        birthplace: null,
        country_id: null,
        living_country: null,
        living_city: null,
        marital_status: null,
        phone: null,
        email: null,
        highest_degree: null,
        speciality: null,
        grade_id: null,
        actual_employer: null,
        requested_establishment_id: null,
        identity_document_type: 'nic',
        status: false
    }

    $scope.maxFileSize = 1024 * 1024 * 5;
    $scope.maxFileSizeErrorMessage = "La taille maximale allouee pour un fichier est de 5 Mo";
    $scope.pdfMimeType = "application/pdf";
    $scope.pdfMimeTypeErrorMessage = "Le fichier doit etre de type PDF";
    $scope.isProcessing = false;

    $scope.identityDocumentFile = null;
    $scope.teachers = [];
    $scope.coverLetterFile = null;
    $scope.resumeFile = null;
    $scope.highestDegreeFile = null;
    $scope.teacherRankFile = null;
    $scope.experienceReviewFile = null;
    $scope.nominationActFile = null;
    $scope.syllabusFile = null;
    $scope.grade = {name:null,code:null,paymentRate:null}
    $scope.grades = [];
    $scope.countries = [];
    $scope.establishments = [];
    $scope.diplomas = [];
    $scope.specialities = [];
    $scope.isUpdate = false;
    $scope.cpt = 1;
    
    $scope.isFormUpdate =false;
    

    
    $scope.redirect= function(id){

     $location.path("/new-teacher/"+id);
     $scope.dptId=id;

    };
    
    $scope.redirectBareme= function(id){

     $location.path("/newAcademicRank/"+id)
    $scope.isUpdate = true;
    console.log($scope.isUpdate)

    };    
    
    $scope.init = function(){
        
        $http.get(`teachers`).then(function (response) {

            $scope.teachers = response.data[0];
            $scope.hasLoadedTeachers = true;
        });       

            var id = $routeParams.id; 
            var teachId = $routeParams.teachId;
            if(id)
            {
                $scope.isUpdate = true;
                var data = {id: id};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                //Loading selected degree information for update

              
                    $http.get('teacherGrade',config).then(function(response){
                     $scope.grade = response.data[0];
                    })
           }
           if(teachId && teachId >0)
           {
               $scope.isFormUpdate =true;
                var data = {id: teachId};
                var config = {
                params: data,
                headers : {'Accept' : 'application/json'}
                };
                $http.get('teachers',config).then(function(response){
                     $scope.teacher = response.data[0];
                     console.log($scope.teacher);
                    })               
           }
//
    };    

    $scope.loadAssets = function () {
        $scope.hasLoadedAssets = null;
        
        $http.get(`new-teacher-form-assets`)
            .then(function (response) {
                console.log(response.data);
                $scope.grades = response.data.grades;
                $scope.countries = response.data.countries;
                $scope.establishments = response.data.establishments;
                $scope.diplomas = response.data.diplomas;
                //$scope.specialities = response[0].data.specialities;
                $scope.hasLoadedAssets = true;
            }, function (error) {
                console.error(error);
                $scope.hasLoadedAssets = false;
            });
    }
    
    $scope.newBareme = function(grd){
        $http.post('teacherGrade',grd)
            .then(function succesCallback(response)
            {
                $scope.grades.push(grd);
                $scope.grade = null;
                toastr.success("Opération effectuée avec succès")
                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                  alert(response.data);
                });         
    }
    $scope.updateBareme = function(grd){
        
        var data = grd;
        var config = {
        params: grd,
        headers : {'Accept' : 'application/json'}
      };        
        $http.put('teacherGrade',data,config)
            .then(function succesCallback(response)
            {
                toastr.success("Opération effectuée avec succès")
                
            },
            function errorCallback(response) {
                  // called asynchronously if an error occurs
                  // or server returns response with an error status.
                  alert(response.data);
                });         
    }    

    $scope.isLoading = function () {
        return $scope.hasLoadedAssets ;
    }

    $scope.hasFailedLoading = function () {
        return $scope.hasLoadedAssets === false;
    }

    $scope.canDisplayForm = function () {
        return $scope.hasLoadedAssets ;
    }

    $scope.loadAssets();
    
    
    $scope.getCities = function(countryId){
        
        var data = {id: countryId};
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
        };
        //Loading selected class information for update
        $timeout(

                $http.get('cities',config).then(

                function(response){
                    $scope.cities=response.data[0];

                }),1000);        
    }

    $scope.onUploadFile = function ($target, callback, mimeTypes = [$scope.pdfMimeType], maxSize = $scope.maxFileSize,) {
        const file = $target.files[0]; 
     
        if (!file) {
            alert("Aucun fichier selectionne");
            return ;
        }

        if (file.size > maxSize) { 
            alert($scope.maxFileSizeErrorMessage);
            return;
        }
        if (!mimeTypes.includes(file.type)) {
            alert($scope.pdfMimeTypeErrorMessage);
            return;
        }
        $target.value = null;
        $target.files = null;
        callback(file);
    }

    $scope.handleIdentityDocumentUploadedFile = function ($file) {
        $scope.identityDocumentFile = $file; 

    }
    $scope.removeIdentityDocumentFile = function () {
        $scope.identityDocumentFile = null;
    }

    $scope.handleCoverLetterUploadedFile = function ($file) {
        $scope.coverLetterFile = $file;
    }
    $scope.removeCoverLetterFile = function () {
        $scope.coverLetterFile = null;
    }

    $scope.handleResumeUploadedFile = function ($file) {
        $scope.resumeFile = $file;
    }
    $scope.removeResumeFile = function () {
        $scope.resumeFile = null;
    }

    $scope.handleHighestDegreeUploadedFile = function ($file) {
        $scope.highestDegreeFile = $file;
    }
    $scope.removeHighestDegreeFile = function () {
        $scope.highestDegreeFile = null;
    }

    $scope.handleTeachingRangUploadedFile = function ($file) {
        $scope.teacherRankFile = $file;
    }
    $scope.removeTeachingRangFile = function () {
        $scope.teacherRankFile = null;
    }

    $scope.handleExperienceReviewUploadedFile = function ($file) {
        $scope.experienceReviewFile = $file;
    }
    $scope.removeExperienceReviewFile = function () {
        $scope.experienceReviewFile = null;
    }

    $scope.handleNominationActUploadedFile = function ($file) {
        $scope.nominationActFile = $file;
    }
    $scope.removeNominationActFile = function () {
        $scope.nominationActFile = null;
    }

    $scope.handleSyllabusUploadedFile = function ($file) {
        $scope.syllabusFile = $file;
    }
    $scope.removeSyllabusFile = function () {
        $scope.syllabusFile = null;
    }

    $scope.onSubmit = function (teacherForm) {console.log("je suis dedans");
        if (!teacherForm.$valid) {
            alert('Formulaire invalide !');
            return;
        }

        $scope.isProcessing = true;
        let documents = [];

        if (!!$scope.identityDocumentFile) {
            documents.push({
                type: 'identity_document',
                file: $scope.identityDocumentFile,
                data: {
                    document_type: $scope.identity_document_type
                }
            });
        }
        if (!!$scope.coverLetterFile) {
            documents.push({
                type: 'cover_letter',
                file: $scope.coverLetterFile
            });
        }
        if (!!$scope.resumeFile) {
            documents.push({
                type: 'resume',
                file: $scope.resumeFile
            });
        }
        if (!!$scope.highestDegreeFile) {
            documents.push({
                type: 'highest_degree',
                file: $scope.highestDegreeFile,
            });
        }
        if (!!$scope.teacherRankFile) {
            documents.push({
                type: 'teacher_rank',
                file: $scope.teacherRankFile
            });
        }
        if (!!$scope.experienceReviewFile) {
            documents.push({
                type: 'experience_review',
                file: $scope.experienceReviewFile
            });
        }
        if (!!$scope.nominationActFile) {
            documents.push({
                type: 'nomination_act',
                file: $scope.nominationActFile
            });
        }

        let data = {...$scope.teacher, birthdate: $scope.teacher.birthdate?.toISOString()?.split('T')[0]};
        if (documents.length > 0) {
            data.documents = documents;
        }

        const formData = parseObjectToFormData(data);
        if(!$scope.isFormUpdate)
        {

        $http.post(`teachers`, formData, {
            transformRequest: angular.identity,
            headers: { 'Content-Type': undefined }
        }).then(function (response) {
                toastr.success("Opération effectuée avec succès")
                $scope.isProcessing = false;
                $location.path('/teacher-list');
               /* $scope.teacher = {
                    identity_document_type: 'nic',
                }*/
                //$location.path('/');
            }, function (error){
                console.error(error);
                alert('Une erreur est survenue lors de l\'enregistrement de l\'enseignant !');
                $scope.isProcessing = false;
            });
        }
        else{
            
        var data1 = {id: $scope.teacher.id,data}; 
        var config = {
        params: data,
        headers : {'Accept' : 'application/json'}
      };            
            $http.put(`teachers`, data1,config).then(function (response) {
                    alert('L\'enseignant a ete enregistre avec succes !');
                    $scope.isProcessing = false;
                   /* $scope.teacher = {
                        identity_document_type: 'nic',
                    }*/
                    //$location.path('/');
                    $location.path('/teacher-list');
                }, function (error){
                    console.error(error);
                    alert('Une erreur est survenue lors de l\'enregistrement de l\'enseignant !');
                    $scope.isProcessing = false;
                });
            }            
    }
    
    
    
     $scope.dtOptions = DTOptionsBuilder.newOptions()
     .withButtons([
            //'columnsToggle',
            //'colvis',
            'copy',
            'print'

        ])
        .withPaginationType('full_numbers')
        .withDisplayLength(100)
         /*.withFixedHeader({
    top: true
  })*/;
  
    $scope.dtColumnDefs = [
    DTColumnDefBuilder.newColumnDef(0),
    DTColumnDefBuilder.newColumnDef(1),
    DTColumnDefBuilder.newColumnDef(2),
    DTColumnDefBuilder.newColumnDef(3),

  ];    
    
  /*----------------------------------------------------------------------------
   * fonction for deleting grade
   ---------------------------------------------------------------------------*/
  $scope.deleteGrade = function(grd,ev)
  {
      var data = {id: grd.id}; 
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
        $http.delete('teacherGrade',config).then(
          function successCallback(response){
              //check the index of the current object in the array
              var index = $scope.grades.findIndex(x => x.id === grd.id)
              //remove the current object from the array

              $scope.grades.splice(index,1);
              toastr.success("Operation effectuée avec succès");

         },
        function errorCallback(response){
            toastr.error("Une erreur inattendue s'est produite");
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };    
  
 
    
  /*----------------------------------------------------------------------------
   * fonction for deleting teacher
   ---------------------------------------------------------------------------*/
  $scope.deleteTeacher = function(teacher,ev)
  {
      var data = {id: teacher.id}; 
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
        $http.delete('teachers',config).then(
          function successCallback(response){
              //check the index of the current object in the array
              var index = $scope.grades.findIndex(x => x.id === grd.id)
              //remove the current object from the array

              $scope.grades.splice(index,1);
              toastr.success("Operation effectuée avec succès");

         },
        function errorCallback(response){
            toastr.error("Une erreur inattendue s'est produite");
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });
     
  };    
};



