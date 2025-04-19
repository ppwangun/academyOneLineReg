
angular.module("degree").component("degreeDetails",{
    templateUrl:"degreetpl",
    controller: degreeCtrl
});

function degreeCtrl($http,$location)
{
    var $ctrl = this;
    $ctrl.degrees = [];
    $ctrl.degree = null;
    $ctrl.fil;
    
    
    $ctrl.init= function(){
        
        $http.get('degree').then(function(response){
            $ctrl.degrees=response.data[0];
            
        });
    };

    $ctrl.redirect = function(id){
            var route="/updatedegree/"+id;
            $location.path(route);
    };
 
    


    
}



