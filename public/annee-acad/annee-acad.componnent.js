'use strict';

angular.module('anneeAcad',['ngRoute'])
.component('createAnneeAcad',{
    templateUrl : 'anneeacad',
    controller: ['$routeParams', function CreateAnneeAcadController($routeParams){
        self = this;
        self.anneeId = $routeParams.anneeId;
    }
        
    ]
    
});