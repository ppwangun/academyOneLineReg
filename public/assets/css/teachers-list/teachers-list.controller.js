app.controller('teacherListController',function($scope, $mdDialog, $http, $timeout){
    // $scope.teachers = [
    //     {id: 1, names: 'Tiger Nixon', speciality: 'System Architect'},
    //     {id: 2, names: 'Garrett Winters', speciality: 'Accountant'},
    //     {id: 3, names: 'Ashton Cox', speciality: 'Junior Technical Author'},
    //     {id: 4, names: 'Cedric Kelly', speciality: 'Senior Javascript Developer'},
    //     {id: 5, names: 'Airi Satou', speciality: 'Accountant'},
    //     {id: 6, names: 'Brielle Williamson', speciality: 'Integration Specialist'},
    //     {id: 7, names: 'Herrod Chandler', speciality: 'Sales Assistant'},
    //     {id: 8, names: 'Rhona Davidson', speciality: 'Integration Specialist'},
    //     {id: 9, names: 'Colleen Hurst', speciality: 'Javascript Developer'},
    //     {id: 10, names: 'Sonya Frost', speciality: 'Software Engineer'},
    //     {id: 11, names: 'Jena Gaines', speciality: 'Office Manager'},
    //     {id: 12, names: 'Quinn Flynn', speciality: 'Support Lead'},
    //     {id: 13, names: 'Charde Marshall', speciality: 'Regional Director'},
    //     {id: 14, names: 'Haley Kennedy', speciality: 'Senior Marketing Designer'},
    //     {id: 15, names: 'Tatyana Fitzpatrick', speciality: 'Regional Director'},
    //     {id: 16, names: 'Michael Silva', speciality: 'Marketing Designer'},
    //     {id: 17, names: 'Paul Byrd', speciality: 'Chief Financial Officer (CFO)'},
    //     {id: 18, names: 'Gloria Little', speciality: 'Systems Administrator'},
    // ]
    // $scope.affectedTeachingUnits = [
    //     {id: 1, name: 'Mathematiques', classe: '6eme'},
    //     {id: 2, name: 'Physique', classe: '5eme'},
    //     {id: 3, name: 'Informatique', classe: '4eme'},
    //     {id: 4, name: 'Mathematiques', classe: '3eme'},
    //     {id: 5, name: 'Chimie', classe: '2nde'},
    //     {id: 6, name: 'Mathematiques', classe: '1ere'},
    //     {id: 7, name: 'Mathematiques', classe: 'Tle'},
    //     {id: 8, name: 'Physique', classe: '6eme'},
    //     {id: 9, name: 'Informatique', classe: '5eme'},
    //     {id: 10, name: 'Mathematiques', classe: '4eme'},
    //     {id: 11, name: 'Chimie', classe: '3eme'},
    // ]
    $scope.teachers = [];
    $scope.hasLoadedTeachers = null;
    $scope.currentTeacher = null;
    $scope.currentTeachingUnit = null;
    $scope.currentProgressionStats = null;
    $scope.hasLoadedCurrentTeacher = null;
    $scope.hasLoadedCurrentProgressionStats = null;

    $scope.isLoadingTeachers = function () {
        return $scope.hasLoadedTeachers === null;
    }
    $scope.hasFailedLoadingTeachers = function () {
        return $scope.hasLoadedTeachers === false;
    }
    $scope.hasSucceededLoadingTeachers = function () {
        return !$scope.isLoadingTeachers() && !$scope.hasFailedLoadingTeachers();
    }

    $scope.isLoadingCurrentTeacher = function () {
        return $scope.hasLoadedCurrentTeacher === null;
    }
    $scope.hasFailedLoadingCurrentTeacher = function () {
        return $scope.hasLoadedCurrentTeacher === false;
    }
    $scope.hasSucceededLoadingCurrentTeacher = function () {
        return !$scope.isLoadingCurrentTeacher() && !$scope.hasFailedLoadingCurrentTeacher();
    }

    $scope.isLoadingCurrentProgressionStats = function () {
        return $scope.hasLoadedCurrentProgressionStats === null;
    }
    $scope.hasFailedLoadingCurrentProgressionStats = function () {
        return $scope.hasLoadedCurrentProgressionStats === false;
    }
    $scope.hasSucceededLoadingCurrentProgressionStats = function () {
        return !$scope.isLoadingCurrentProgressionStats() && !$scope.hasFailedLoadingCurrentProgressionStats();
    }

    $scope.loadTeachers = function () {
        $scope.hasLoadedTeachers = null;
        $http.get(`${API_URL}teachers`).then(function (response) {
            console.log(response)
            $scope.teachers = response.data;
            $scope.hasLoadedTeachers = true;
        }, function (error) {
            console.error(error);
            $scope.hasLoadedTeachers = false;
        });
    }

    $scope.loadCurrentTeacher = function () {
        $scope.hasLoadedCurrentTeacher = null;
        $http.get(`${API_URL}teachers/${$scope.selectedTeacherId}`).then(function (response) {
            console.log(response)
            const {documents, ...data} = response.data;
            $scope.currentTeacher = {
                ...data,
                identityDocumentFile: documents.find(document => document.type === 'identity_document'),
                coverLetterFile: documents.find(document => document.type === 'cover_letter'),
                resumeFile: documents.find(document => document.type === 'resume'),
                teacherRankFile: documents.find(document => document.type === 'teacher_rank'),
                highestDegreeFile: documents.find(document => document.type === 'highest_degree'),
                experienceReviewFile: documents.find(document => document.type === 'experience_review'),
                nominationActFile: documents.find(document => document.type === 'nomination_act'),
            }
            $scope.hasLoadedCurrentTeacher = true;
        }, function (error) {
            console.error(error);
            $scope.hasLoadedCurrentTeacher = false;
        });
    }

    $scope.loadCurrentProgressionStats = function () {
        $scope.hasLoadedCurrentProgressionStats = null;
        $http.get(`${API_URL}teaching-units/${$scope.selectedTeachingUnitId}/progression-stats`).then(function (response) {
            console.log(response)
            $scope.currentProgressionStats = response.data;
            $scope.hasLoadedCurrentProgressionStats = true;
        }, function (error) {
            console.error(error);
            $scope.hasLoadedCurrentProgressionStats = false;
        });
    }

    $scope.getRoundedPercentage = function (expectedPercentage) {
        const minPercentage = Math.max(expectedPercentage, 0);
        return Math.min(Math.round(minPercentage), 100);
    }

    $scope.loadTeachers();

    $scope.data={ "records": $scope.teachers};
    $scope.dataTableOpt = {
        //custom datatable options
        // or load data through ajax call also
        "aLengthMenu": [[10, 50, 100,-1], [10, 50, 100,'All']],
        "aoSearchCols": [
            null
        ],
    };

    $scope.selectedTeacherId = null;
    $scope.selectedTeachingUnitId = null;

    $scope.onSelectTeacher = function ($teacherId) {
        $scope.selectedTeacherId = $teacherId;
        $scope.selectedTeachingUnitId = null;
        $scope.currentProgressionStats = null;
        $scope.currentTeachingUnit = null;
        $scope.loadCurrentTeacher();
    }
    $scope.onSelectTeachingUnit = function ($teachingUnitId) {
        $scope.selectedTeachingUnitId = $teachingUnitId;
        $scope.currentTeachingUnit = $scope.currentTeacher?.teaching_units?.find(elt => elt.id === $teachingUnitId);
        $scope.currentProgressionStats = null;
        $scope.loadCurrentProgressionStats();
    }

    $scope.onViewDocument = function () {
        console.log("Viewing document")
    }

    $scope.openNewProgressionDialog = function (ev) {
        $mdDialog.show({
            controller: NewTeachingUnitProgressionController,
            templateUrl: '/app/components/new-teaching-unit-progression/new-teaching-unit-progression.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: true,
            fullscreen: false, // Only for -xs, -sm breakpoints.
            locals: {teachingUnitId: $scope.currentTeachingUnit?.id, teachingUnitCode: $scope.currentTeachingUnit?.code, teacherId: $scope.currentTeacher?.id}
        }).then(function (newProgressionResult) {
            if ($scope.selectedTeachingUnitId === newProgressionResult.teaching_unit_id) {
                const previousData = $scope.currentProgressionStats[newProgressionResult.target];
                const newProgress = Math.min((previousData.progress + newProgressionResult.duration), previousData.total);
                console.log(newProgress);
                console.log(previousData);
                console.log(newProgressionResult);
                console.log($scope.currentProgressionStats);
                $scope.currentProgressionStats = {
                    ...$scope.currentProgressionStats,
                    [newProgressionResult.target]: {
                        ...previousData,
                        progress: formatDecimals(newProgress, 2),
                        percentage: formatDecimals((newProgress/previousData.total * 100))
                    }
                }
                console.log($scope.currentProgressionStats);
            }
        }, function () {
            // $scope.status = 'You cancelled the dialog.';
        });
    };

    $scope.openAffectTeachingUnitDialog = function (ev) {
        $mdDialog.show({
            controller: AffectTeachingUnitController,
            templateUrl: '/app/components/affect-teaching-unit/affect-teaching-unit.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: false,
            fullscreen: false, // Only for -xs, -sm breakpoints.
            locals: {teacherId: $scope.currentTeacher.id, teacherName: $scope.currentTeacher.names}
        }).then(function (newTeachingUnits) {
            $scope.currentTeacher.teaching_units = newTeachingUnits.concat($scope.currentTeacher.teaching_units);
        }, function () {
            // $scope.status = 'You cancelled the dialog.';
        });
    };

    $scope.openProgressionsTimelineDialog = function (ev, teachingUnit) {
        $mdDialog.show({
            controller: ProgressionsTimelineController,
            templateUrl: '/app/components/progressions-timeline/progressions-timeline.html',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: false,
            fullscreen: false, // Only for -xs, -sm breakpoints.
            locals: {teachingUnitId: $scope.currentTeachingUnit.id, teachingUnitCode: $scope.currentTeachingUnit.code, teacherId: $scope.currentTeacher.id}
        });
    };
});
