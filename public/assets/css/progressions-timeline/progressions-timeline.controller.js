function ProgressionsTimelineController($scope, $mdDialog, $http, teachingUnitId, teachingUnitCode, teacherId) {
    $scope.teachingUnitId = teachingUnitId;
    $scope.teachingUnitCode = teachingUnitCode;

    $scope.progressions = [];
    $scope.hasLoadedProgressions = null;

    $scope.loadProgressions = function () {
        $scope.hasLoadedProgressions = null;
        $http.get(`${API_URL}teaching-units/${$scope.teachingUnitId}/progressions`)
            .then(function (response) {
                console.log(response.data);
                $scope.progressions = response.data;
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
            cm: 'Cours Magistral',
            tp: 'Travail Pratique',
            td: 'Travail Dirige'
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

    $scope.cancel = function () {
        $mdDialog.cancel();
    };
}