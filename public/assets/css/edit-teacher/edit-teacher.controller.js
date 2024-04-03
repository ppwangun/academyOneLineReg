app.controller('editTeacherController', function($scope, $http, $location, $routeParams) {
    $scope.hasLoadedAssets = null;
    $scope.hasLoadedTeacher = null;
    $scope.teacherId = $routeParams.teacherId ?? null;
    $scope.teacherName = '-';

    $scope.teacher = {
        names: null,
        birthdate: null,
        birthplace: null,
        country_id: null,
        marital_status: null,
        phone: null,
        email: null,
        highest_degree_id: null,
        speciality_id: null,
        grade_id: null,
        actual_employer: null,
        requested_establishment_id: null,
        identity_document_type: 'nic'
    }

    $scope.maxFileSize = 1024 * 1024 * 5;
    $scope.maxFileSizeErrorMessage = "La taille maximale allouee pour un fichier est de 5 Mo";
    $scope.pdfMimeType = "application/pdf";
    $scope.pdfMimeTypeErrorMessage = "Le fichier doit etre de type PDF";
    $scope.isProcessing = false;

    $scope.identityDocumentFile = null;
    $scope.coverLetterFile = null;
    $scope.resumeFile = null;
    $scope.highestDegreeFile = null;
    $scope.teacherRankFile = null;
    $scope.experienceReviewFile = null;
    $scope.nominationActFile = null;
    $scope.syllabusFile = null;

    $scope.grades = [];
    $scope.countries = [];
    $scope.establishments = [];
    $scope.diplomas = [];
    $scope.specialities = [];

    $scope.loadAssets = function () {
        $scope.hasLoadedAssets = null;
        $http.get(`${API_URL}new-teacher-form-assets`)
            .then(function (response) {
                console.log(response.data);
                $scope.grades = response.data.grades;
                $scope.countries = response.data.countries;
                $scope.establishments = response.data.establishments;
                $scope.diplomas = response.data.diplomas;
                $scope.specialities = response.data.specialities;
                $scope.hasLoadedAssets = true;
            }, function (error) {
                console.error(error);
                $scope.hasLoadedAssets = false;
            });
    }

    $scope.loadTeacher = function () {
        $scope.hasLoadedTeacher = null;
        $http.get(`${API_URL}teachers/${$scope.teacherId}`)
            .then(function (response) {
                console.log(response.data);
                const {documents, grade, teaching_units, speciality, highest_degree, requested_establishment, created_at, updated_at, country, ...teacher} = response.data;
                $scope.teacher = {...teacher, birthdate: new Date(teacher.birthdate ?? 0)};
                console.log($scope.teacher);
                const documentsData = {
                    identityDocumentFile: documents.find(document => document.type === 'identity_document'),
                    coverLetterFile: documents.find(document => document.type === 'cover_letter'),
                    resumeFile: documents.find(document => document.type === 'resume'),
                    teacherRankFile: documents.find(document => document.type === 'teacher_rank'),
                    highestDegreeFile: documents.find(document => document.type === 'highest_degree'),
                    experienceReviewFile: documents.find(document => document.type === 'experience_review'),
                    nominationActFile: documents.find(document => document.type === 'nomination_act'),
                }
                $scope.identityDocumentFile = documentsData.identityDocumentFile ? ({exists: true, name: teacher.identity_document_type === 'passport' ? 'Passeport.pdf': 'Carte d\'identite.pdf'}) : null;
                $scope.coverLetterFile = documentsData.coverLetterFile ? ({exists: true, name: 'Lettre de motivation.pdf'}) : null;
                $scope.resumeFile = documentsData.resumeFile ? ({exists: true, name: 'Curriculum vitae.pdf'}) : null;
                $scope.teacherRankFile = documentsData.teacherRankFile ? ({exists: true, name: 'Attestation de grade.pdf'}) : null;
                $scope.highestDegreeFile = documentsData.highestDegreeFile ? ({exists: true, name: 'Diplome le plus eleve.pdf'}) : null;
                $scope.experienceReviewFile = documentsData.experienceReviewFile ? ({exists: true, name: 'Attestation dexperience.pdf'}) : null;
                $scope.nominationActFile = documentsData.nominationActFile ? ({exists: true, name: 'Acte de nomination.pdf'}) : null;

                $scope.teacherName = teacher.names;
                $scope.hasLoadedTeacher = true;
            }, function (error) {
                console.error(error);
                $scope.hasLoadedTeacher = false;
            });
    }

    $scope.reloadData = function () {
        if ($scope.hasLoadedTeacher === false) {
            $scope.loadTeacher();
        }
        if ($scope.hasLoadedAssets === false) {
            $scope.loadAssets();
        }
    }

    $scope.isLoading = function () {
        return $scope.hasLoadedAssets === null || $scope.hasLoadedTeacher === null;
    }

    $scope.hasFailedLoading = function () {
        return ($scope.hasLoadedAssets === false && $scope.hasLoadedTeacher !== null) ||
            ($scope.hasLoadedAssets !== null && $scope.hasLoadedTeacher === false);
    }

    $scope.canDisplayForm = function () {
        return !$scope.isLoading() && !$scope.hasFailedLoading();
    }

    $scope.loadAssets();
    $scope.loadTeacher();

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

    $scope.onSubmit = function (teacherForm) {
        if (!teacherForm.$valid) {
            alert('Formulaire invalide !');
            return;
        }

        $scope.isProcessing = true;
        let documents = [];

        if (!!$scope.identityDocumentFile && !$scope.identityDocumentFile?.exists) {
            documents.push({
                type: 'identity_document',
                file: $scope.identityDocumentFile
            });
        }
        if (!!$scope.coverLetterFile && !$scope.coverLetterFile?.exists) {
            documents.push({
                type: 'cover_letter',
                file: $scope.coverLetterFile
            });
        }
        if (!!$scope.resumeFile && !$scope.resumeFile?.exists) {
            documents.push({
                type: 'resume',
                file: $scope.resumeFile
            });
        }
        if (!!$scope.highestDegreeFile && !$scope.highestDegreeFile?.exists) {
            documents.push({
                type: 'highest_degree',
                file: $scope.highestDegreeFile,
            });
        }
        if (!!$scope.teacherRankFile && !$scope.teacherRankFile?.exists) {
            documents.push({
                type: 'teacher_rank',
                file: $scope.teacherRankFile
            });
        }
        if (!!$scope.experienceReviewFile && !$scope.experienceReviewFile?.exists) {
            documents.push({
                type: 'experience_review',
                file: $scope.experienceReviewFile
            });
        }
        if (!!$scope.nominationActFile && !$scope.nominationActFile?.exists) {
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

        $http.post(`${API_URL}teachers/${$scope.teacherId}?_method=PUT`, formData, {
            transformRequest: angular.identity,
            headers: { 'Content-Type': undefined }
        })
            .then(function (response) {
                alert('L\'enseignant a ete mis a jour avec succes !');
                $scope.isProcessing = false;
                $scope.teacher = {
                    identity_document_type: 'nic',
                }
                $location.path('/');
            }, function (error){
                console.error(error);
                alert('Une erreur est survenue lors de la mis a jour de l\'enseignant !');
                $scope.isProcessing = false;
            });
    }
});