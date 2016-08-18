(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject = ['$location', 'UserService', 'AuthenticationService', 'FlashService'];
    function LoginController($location, UserService, AuthenticationService, FlashService) {
        var vm = this;

        vm.login = login;
        vm.user = {};
        vm.user.username = "";
        vm.user.password = "";

        (function initController() {
            // reset login status
            AuthenticationService.ClearCredentials();
        })();

        vm.demo = function(){
            vm.user.username = "admin"; vm.user.password = "password";
            login();
        }

        function login() {
            vm.dataLoading = true;
            AuthenticationService.Login(vm.user, function (resp) {
                console.log("resp",resp);
                if (resp.success) {
                    AuthenticationService.SetCredentials(vm.user.username, vm.user.password);
                    vm.inUser = UserService.GetInUser();
                    if(vm.inUser.type == 'manager' )
                        $location.path('/manager');
                    else
                        $location.path('/employee/'+vm.inUser.md5_id);
                } else {
                    FlashService.Error(resp.message);
                    vm.dataLoading = false;
                }
            });
        };
    }

})();
