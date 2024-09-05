function AffectTeachingUnitController($scope, $mdDialog, $http, teacherId, teacherName) {
    $scope.teacherId = teacherId;
    $scope.teacherName = teacherName;
    $scope.searchInputId = 'teaching_unit_code';

    $scope.suggestionsList = [];
    $scope.selectedTeachingUnits = [];
    $scope.partialAttribution = false;
    $scope.searchQuery = null;
    $scope.autocomplete = null;
    $scope.lastSearchedTime = 0;
    $scope.currentTeachingUnit = null;
    $scope.canAddCurrentTeachingUnit = false;
    $scope.showforceToProceeButton =false
    $scope.isProcessing = false;
    $scope.isProcessing = false;
    $scope.subjects = [];
    $scope.selectedSubject = null;
    

    
        //Loading subjects asynchronously
    $scope.query = function(subject)
    {
       var  dataString = {id: subject},
          config = {
            params: dataString,
            headers : {'Accept' : 'application/json; charset=utf-8'}
            };
    
            return  $http.get('searchAllSubjects',config).then(function(response){
                   return response.data[0];
                });
     };
     

//Select subjects to be add    
     $scope.addSubject = function()
     {
         //Cehck if the value exist in the array be for pushinng
         //Avoiding duplicates
         
         if ($scope.subjects.includes($scope.selectedSubject) === false) $scope.subjects.push($scope.selectedSubject);
         if ($scope.selectedTeachingUnits.includes($scope.selectedSubject) === false) $scope.selectedTeachingUnits.push($scope.selectedSubject);
         
         
     };    
//Delete selected subject
     $scope.removeSubject = function(sub){
         var index = $scope.subjects.indexOf(sub);
         $scope.subjects.splice(index,1);
     };
     
    

    $scope.getTeachingUnitLabelForAutocompletion = function($teachingUnit) {
        return $teachingUnit.code + (`&nbsp;&nbsp;&nbsp;<em>(${$teachingUnit.classe?.code}</em>)`)
    }

    $scope.checkButtonStatus = function () {
        const temp = $scope.selectedTeachingUnits.some(elt => elt.id === $scope.currentTeachingUnit?.id);
        $scope.canAddCurrentTeachingUnit = $scope.currentTeachingUnit && ($scope.currentTeachingUnit.code === $scope.searchQuery) && !temp;
    }

    $scope.addCurrentTeachingUnit = function () {
        if (!$scope.canAddCurrentTeachingUnit) {
            return ;
        }
        const shouldDisabledTeachingUnit = $scope.currentTeachingUnit.teachers?.some(elt => elt.id === $scope.teacherId);
        $scope.selectedTeachingUnits.unshift({...$scope.currentTeachingUnit, disabled: shouldDisabledTeachingUnit});
        $scope.currentTeachingUnit = null;
        $scope.searchQuery = null;
    }

    $scope.removeTeachingUnit = function (teachingUnitId) {
        $scope.selectedTeachingUnits = $scope.selectedTeachingUnits.filter(elt => elt.id !== teachingUnitId);
    }

    $scope.getTeachersListOfTeachingUnit = function (teachers) {
        return (teachers && teachers.length > 0) ? teachers.map(elt => elt.names).join(', ') : '---------------';
    }

    $scope.teachingUnitsToSave = function () {
        return $scope.selectedTeachingUnits.filter(elt => !elt.disabled);
    }



    $scope.onSearchValueChanged = function () {
        $scope.currentTeachingUnit = null;
        $scope.checkButtonStatus();
        console.log($scope.searchQuery);
        if ($scope.searchQuery.length < 2) {
            if (!$scope.suggestionsList.find(elt => elt.code.includes($scope.searchQuery))) {
                $scope.autocomplete.list = [];
                $scope.autocomplete.close();
            }
            return;
        }
        $scope.autocomplete.close();
        $http.get(`${API_URL}teaching-units/search?code=${$scope.searchQuery.replace('#', '')}`)
            .then(function (response) {
                console.log(response.data);
                $scope.autocomplete.list = response.data;
                $scope.suggestionsList = response.data;
                setTimeout(() => {
                    $scope.autocomplete.evaluate();
                });
            }, function (error) {
                console.error(error);
            });
    }

    $scope.saveChoices = (proceedByForce) => {
        var data = $scope.teachingUnitsToSave().map(elt => elt.id);
        if (data.length === 0 || $scope.isProcessing) return;
        
        data = {teacherid: $scope.teacherId,subjects : $scope.teachingUnitsToSave(),proceedByForce:proceedByForce,partialAttribution:$scope.partialAttribution}
        data = $.param(data)
        var config = {
            //params: {id: $scope.teacherId,subjects : JSON.stringify(data)},
            headers : {'Content-Type' : "application/x-www-form-urlencoded;charset=utf-8;"}
        };
        $scope.isProcessing = true;
        $http.post(`assignSubjectToTeacher`,data,config)
            .then(function (response) {
                if(response.data[0])
                    $mdDialog.hide($scope.teachingUnitsToSave());
                else{
                    $scope.showforceToProceeButton = true;
                }
               // alert('L\'enseignant a ete mis a jour avec succes !');
                $scope.isProcessing = false;
            }, function (error) {
                console.error(error);
                $scope.isProcessing = false;
                alert('Une erreur s\'est produite lors du traitement ! Veuillez reessayer !')
            });
    }

    $scope.hide = function () {
        if ($scope.isProcessing) {
            alert('Veuillez patientez un instant ...');
            return;
        }
        $mdDialog.hide();
     };

    $scope.cancel = function () {
        if ($scope.isProcessing) {
            alert('Veuillez patientez un instant ...');
            return;
        }
        $mdDialog.cancel();
    };
    
    
}