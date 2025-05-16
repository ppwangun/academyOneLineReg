function NewTeachingUnitProgressionController($scope, $mdDialog, $http, teachingUnitId, teachingUnitCode, teacherId,contractId,times) {
    $scope.teachingUnitId = teachingUnitId;
    $scope.teachingUnitCode = teachingUnitCode;
    $scope.contractId= contractId;
    $scope.isProcessing = false;

    
    $scope.progtimes = times


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
       
        const startTime = new Date (dateInISO+" "+$scope.progression.start_time.time);
        const endTime = new Date(dateInISO+" "+$scope.progression.end_time.time);
        // startDate.setHours(startTime.getHours(), startTime.getMinutes(), startTime.getSeconds());
        // endDate.setHours(endTime.getHours(), endTime.getMinutes(), endTime.getSeconds());

        if (startTime.getTime() > endTime.getTime()) {
            alert('L\'heure de debut doit etre inferieure a celle de fin !');
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