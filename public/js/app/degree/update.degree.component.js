angular.module("degree").component("newDegree",{
    templateUrl:"newdegreetpl",
    controller: degreeCtrl
});

function degreeCtrl($http,$q,$timeout,$routeParams)
{
    var $ctrl= this;
    $ctrl.simulateQuery = false;
    $ctrl.isDisabled    = false;
    $ctrl.noCache = false;

    // list of `state` value/display objects
    $ctrl.selectedItem= null;
    $ctrl.degree = {id:'',code:'',name:'',status: 1,filiere_id:''}
    $ctrl.degrees = []; 
    $ctrl.filieres = [];
    $ctrl.querySearch   = querySearch;
    $ctrl.selectedItemChange = selectedItemChange;
    $ctrl.searchTextChange   = searchTextChange;

   

    $ctrl.init = function(){
        $http.get('filiere').then(function(response){
            angular.forEach(response.data[0],function(item){
                
              $ctrl.filieres.push({id:item.id,code:item.code, name:item.name.toLowerCase()+"["+item.code+"]"}); 
              
            });
            
        });
        
         $ctrl.degree.id = $routeParams.id; 
         var fil_id = $routeParams.fil_id;
         if($ctrl.degree.id)
         {
             alert($ctrl.degree.id);
         }

         
    };
    
   $ctrl.addDegree = function(){
       $ctrl.degree.filiere_id=$ctrl.selectedItem.id;
       $http.post('degree',$ctrl.degree).then(
            function successCallback(response){
                //reinitialise form
                $ctrl.selectedItem = null;
                $ctrl.degree = {code:'',name:'',status: 1,filiere_id:''}
                $ctrl.degrees = response.data[0];
            },
            function errorCallback(response){
                alert("Une erreur inattendue s'est produite");
           
            });
   };

    function newState(state) {
      alert("Sorry! You'll need to create a Constitution for " + state + " first!");
    }

    // ******************************
    // Internal methods
    // ******************************

    /**
     * Search for states... use $timeout to simulate
     * remote dataservice call.
     */
    function querySearch (query) {
       
      var results = query ? $ctrl.filieres.filter( createFilterFor(query) ) : $ctrl.filieres,
          deferred;
      if ($ctrl.simulateQuery) {
        deferred = $q.defer();
        $timeout(function () { deferred.resolve( results ); }, Math.random() * 1000, false);
        return deferred.promise;
      } else {
        return results;
      }
    }

    function searchTextChange(text) {
      $log.info('Text changed to ' + text);
    }

    function selectedItemChange(item) {
      $log.info('Item changed to ' + JSON.stringify(item));
    }

    /**
     * Build `states` list of key/value pairs
     */



    /**
     * Create filter function for a query string
     */
    function createFilterFor(query) {
      var lowercaseQuery = angular.lowercase(query);

      return function filterFn(filiere) { 
        
        return (filiere.name.indexOf(lowercaseQuery) === 0);
      };

    }    
}


