angular.module('userPopup', ['ui.bootstrap']);
angular.module('userPopup').controller('popupController', function($scope, $uibModal,$compile, $log) {
        $scope.usrs = [];
        $scope.usr = {code: '', name: '', date_debut: '', date_fin: ''};
        $scope.addUser = function(){
         var dialogInst = $uibModal.open({
            
                          animation: this.animationsEnabled,
    			  templateUrl: 'js/my_js/globalconfig/periodform.html',
    			  controller: 'DialogInstCtrl',
    			  size: 'md',
    			  resolve: {
    				selectedUsr: function () {
    				  return $scope.usr;
    				}
    			  }
			    });
			    dialogInst.result.then(function (newusr) {
			    $scope.usrs.push(newusr);
			    $scope.usr = {code: '', name: '', date_debut: '', date_fin: ''};
			}, function () {
			  $log.info('Modal dismissed at: ' + new Date());
			});
                        
        };
        
        $scope.create = function(){
            
            // event.preventDefault();
             
             $.ajax({
                 dataType: 'html',
                 url : "newacadyr",
                 //data : $this.serialize(),
                 success: function( data){
                    //$scope.$apply();
                         $compile($('#page-wrapper').html(data))($scope);
                         $scope.$digest();
                        //$scope.templateUrl = data;
                         
                    
                }
             });        
        };
});
angular.module('userPopup').controller('DialogInstCtrl', function($scope, $uibModalInstance, selectedUsr, $log) {
$scope.usr = selectedUsr;

           
		  $scope.submitUser = function () {
			$uibModalInstance.close($scope.usr);
		//	$scope.usr = {name: '', job: '', age: '', sal: '', addr:''};
  		};
		$scope.cancel = function () {
		$uibModalInstance.dismiss('cancel');
		  };

var today = new Date();
        $scope.AvailableDate = new Date();
        $scope.ExpireDate = new Date();
        $scope.dateFormat = 'yyyy-MM-dd';
        $scope.availableDateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            minDate: today,
            maxDate: new Date(2030, 5, 22)}
         $scope.expireDateOptions = {
            formatYear: 'yy',
            startingDay: 1,
            minDate: today,
            maxDate: new Date(2030, 5, 22)
        };
        $scope.availableDatePopup = {
            opened: false
        };
        $scope.expireDatePopup = {
            opened: false
        };
        $scope.ChangeExpiryMinDate = function(availableDate) {
            if (availableDate !== null) {
                var expiryMinDate = new Date(availableDate);
                $scope.expireDateOptions.minDate = expiryMinDate;
                $scope.ExpireDate = expiryMinDate;
            }
        };
        $scope.ChangeExpiryMinDate();
        $scope.OpenAvailableDate = function() {
            $scope.availableDatePopup.opened = !$scope.availableDatePopup.opened;
        };
        $scope.OpenExpireDate = function() {
            $scope.expireDatePopup.opened = !$scope.expireDatePopup.opened;
        };
});