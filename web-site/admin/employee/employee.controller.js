(function () {
    'use strict';

    angular
        .module('app')
        .controller('EmployeeController', EmployeeController);

    EmployeeController.$inject = ['UserService',  'CandidateService', '$rootScope','$routeParams', 'FlashService'];
    function EmployeeController(UserService, CandidateService,  $rootScope, $routeParams, FlashService) {
        var vm = this;

        console.log($routeParams.emp);

        vm.user = null;
        vm.inUser = null;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;

        initController();

        function initController() {
          //  loadCurrentUser();
           // loadAllUsers();

            loadUser($routeParams.emp);
            //loadToCallCandidates();

        }

        function loadUser(emp){
            CandidateService.GetByManagerEmployeeId(emp)
                .then(function (response) {
                    vm.employee = response.employee;
                    console.log(vm.employee);
                });


        }

        vm.loadToCallCandidates = loadToCallCandidates;



        function loadToCallCandidates(){
            vm.search = false;
            CandidateService.GetAll()
                .then(function (response) {
                    vm.toCallCandidates = response.employees;
                    console.log(vm.toCallCandidates[1].name);
                });

        }

        /*function loadCurrentUser() {
            UserService.GetByUsername($rootScope.globals.currentUser.username)
                .then(function (user) {
                    vm.user = user;
                });
        }*/

        function loadAllUsers() {
            UserService.GetAll()
                .then(function (users) {
                    vm.allUsers = users;
                });
        }

        function deleteUser(id) {
            UserService.Delete(id)
            .then(function () {
                loadAllUsers();
            });
        }





    }

})();