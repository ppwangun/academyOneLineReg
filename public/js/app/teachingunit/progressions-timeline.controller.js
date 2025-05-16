function ProgressionsTimelineController($scope, $mdDialog, $http, teacherId,contractId,teachingUnitCode,toastr) {
   // $scope.teachingUnitId = teachingUnitId;
    $scope.teachingUnitCode = teachingUnitCode;

    $scope.progressions = [];
    $scope.hasLoadedProgressions = null;

    $scope.loadProgressions = function () {
        $scope.hasLoadedProgressions = null;
        var data = {"id": contractId}
        var config  = {
          params: data,
          headers: {'Accept': 'application/json'}
        }
        $http.get("unitProgression",config)
            .then(function (response) {
                console.log(response.data);
                $scope.progressions = response.data[0];
                $scope.progressions.sort((a, b) => {
                   const baseDate = new Date(a.date);
                   const aDate = baseDate;
                   const bDate = baseDate;
                   aDate.setHours(parseInt(a.end_time.split(':')[0]), parseInt(a.end_time.split(':')[1]));
                   bDate.setHours(parseInt(b.end_time.split(':')[0]), parseInt(b.end_time.split(':')[1]));
                   return bDate.getTime() - aDate.getTime();
                });
                $scope.hasLoadedProgressions = true;
            }, function (error) {
                console.error(error);
                $scope.hasLoadedProgressions = false;
            });
    }

    $scope.loadProgressions();

    $scope.isLoading = function () {
        return $scope.hasLoadedProgressions === null;
    }

    $scope.hasFailedLoading = function () {
        return $scope.hasLoadedProgressions === false;
    }

    $scope.canDisplayTimeline = function () {
        return $scope.hasLoadedProgressions === true;
    }

    $scope.getProgressionLabel = function (progressionTarget) {
        const data = {
            CM: 'Cours Magistral',
            cm: 'Cours Magistral',
            TP: 'Travail Pratique',
            tp: 'Travail Pratique',
            TD: 'Travail Dirige',
            td: 'Travail Dirige',
            CC: 'Control Continue',
            EXAM: 'Examen'
        }

        return data[progressionTarget] ?? '-';
    }

    $scope.getProgressionDateString = function (progressionDate, progressionStartTime, progressionEndTime) {
       
        let result = printReadableDate(new Date(progressionDate), 'fr', true);
        const startTime = progressionStartTime.split(':')[0]+'h'+progressionStartTime.split(':')[1];
        const endTime = progressionEndTime.split(':')[0]+'h'+progressionEndTime.split(':')[1];
        result += ` (De ${startTime} a ${endTime})`;

        return result;
    }
    
    $scope.deleteProgression = function(id,ev){
        data = {id: id} 
        console.log(id);
        //data = $.param(data)
        var config = {
            params: data,
            headers : {'Accept' : 'application/json'}
        };
        $scope.isProcessing = true;
        
// Preparing the confirm windows
      var confirm = $mdDialog.confirm()
            .title('Annuler la pogression?')
            .textContent('VOus êtes sur le point d\'annuler une progression Voulez vous continuer ?')
             // .ariaLabel('Lucky day')
            .targetEvent(ev)
            .ok('Confirmer')
            .cancel('Annuler');
//open de confirm window
    $mdDialog.show(confirm).then(function() {
        //in case delete is pressee excute  the delete backend 
        $http.delete('unitProgression',config)
            .then(function (response) {
                toastr.success("Operation effectuée avec succès")
               // alert('L\'enseignant a ete mis a jour avec succes !');
                //$scope.loadCurrentTeacher();
                $scope.isProcessing = false;
            }, function (error) {
                console.error(error);
                $scope.isProcessing = false;
                alert('Une erreur s\'est produite lors du traitement ! Veuillez reessayer !')
            });
    }, function() {
     // $scope.status = 'You decided to keep your debt.';
    });         
    }
    
    $scope.searchPlanning = function(subjectId,fromDate,toDate)
    {
        var data = {subjectId:subjectId,fromDate:fromDate,toDate:toDate}
        var config = {
            params: data,
            headers : {'Accept' : 'application/json'}
        };
        
        $http.delete('searchPlanning',config)
            .then(function (response) {
                toastr.success("Operation effectuée avec succès")
               // alert('L\'enseignant a ete mis a jour avec succes !');
                //$scope.loadCurrentTeacher();
                $scope.isProcessing = false;
            }, function (error) {
                console.error(error);
                $scope.isProcessing = false;
                alert('Une erreur s\'est produite lors du traitement ! Veuillez reessayer !')
            });        
    }

    $scope.cancel = function () {
        $mdDialog.cancel();
    };
}