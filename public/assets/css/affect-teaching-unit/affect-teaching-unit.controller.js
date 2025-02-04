function AffectTeachingUnitController($scope, $mdDialog, $http, teacherId, teacherName) {
    $scope.teacherId = teacherId;
    $scope.teacherName = teacherName;
    $scope.searchInputId = 'teaching_unit_code';

    $scope.suggestionsList = [];
    $scope.selectedTeachingUnits = [];
    $scope.searchQuery = null;
    $scope.autocomplete = null;
    $scope.lastSearchedTime = 0;
    $scope.currentTeachingUnit = null;
    $scope.canAddCurrentTeachingUnit = false;
    $scope.isProcessing = false;
    $scope.isProcessing = false;

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

    setTimeout(() => {
        $scope.autocomplete = new Awesomplete(document.getElementById($scope.searchInputId), {
            tabSelect: true
        });
        $scope.autocomplete.data = function (item, input) {
            return {label: $scope.getTeachingUnitLabelForAutocompletion(item), value: item.code}
        }
        $scope.autocomplete.list = []

        document.getElementById($scope.searchInputId)
            .addEventListener('awesomplete-selectcomplete', function (event) {
                const value = event.text.value;
                const label = event.text.label;
                $scope.searchQuery = value;
                $scope.currentTeachingUnit = $scope.suggestionsList.find((elt) => {
                    return $scope.getTeachingUnitLabelForAutocompletion(elt) === label;
                });
                $scope.checkButtonStatus();
            });
    });

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

    $scope.saveSelection = () => {
        const data = $scope.teachingUnitsToSave().map(elt => elt.id);
        if (data.length === 0 || $scope.isProcessing) return;

        $scope.isProcessing = true;
        $http.post(`${API_URL}teachers/${$scope.teacherId}/teaching-units`, {teaching_units_ids: data})
            .then(function (response) {
                $mdDialog.hide($scope.teachingUnitsToSave());
                alert('L\'enseignant a ete mis a jour avec succes !');
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