(function () {
    'use strict';

    angular
        .module('app')
        .controller('ManagerController', ManagerController);

    ManagerController.$inject = ['UserService',  'CandidateService', '$rootScope', 'FlashService'];
    function ManagerController(UserService, CandidateService,  $rootScope, FlashService) {
        var vm = this;

        vm.user = null;
        vm.inUser = null;
        vm.allUsers = [];
        vm.deleteUser = deleteUser;
        vm.loadUser = loadUser;

        initController();

        function initController() {
          //  loadCurrentUser();
           // loadAllUsers();

            //loadUser();
            loadToCallCandidates();

        }

        function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);


        }

        vm.loadToCallCandidates = loadToCallCandidates;

        vm.date1 = new Date().getDate();
        vm.getFun = function(work){
           return Math.floor((Math.random() * (work/60/60)) + (work/60/60/4));
        };

        vm.getColor = function(eff){

            eff = (eff/60/60/(vm.date1*6.85))*100;

            //return "danger";

            if(eff <= 30){
                return "danger";
            }
            if(eff <= 60){
                return "warning";
            }
            if(eff <= 80){
                return "success";
            }
            if(eff >= 80){
                return "primary";
            }

        };

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