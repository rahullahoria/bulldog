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

        vm.champs = 0;
        vm.good = 0;
        vm.improve = 0;
        vm.bad = 0;

        vm.successFilter = true;
        vm.dangerFilter = true;
        vm.warningFilter = true;
        vm.primaryFilter = true;

        initController();

        function initController() {
          //  loadCurrentUser();
           // loadAllUsers();

            //loadUser();
            loadToCallCandidates();

        }

        vm.logout = function(){
            vm.inUser = null;
            $location.path('#/login');
        };

        function loadUser(){
            vm.inUser = UserService.GetInUser();
            console.log("in user",vm.inUser);


        }

        vm.filterIt = function(status){

            if(status == "all")  {

                vm.successFilter = true;
                vm.dangerFilter = true;
                vm.warningFilter = true;
                vm.primaryFilter = true;
                return;

            }

            if(status == "success")  {

                vm.successFilter = true;
                vm.dangerFilter = false;
                vm.warningFilter = false;
                vm.primaryFilter = false;
                return;

            }
            if(status == "danger")  {

                vm.successFilter = false;
                vm.dangerFilter = true;
                vm.warningFilter = false;
                vm.primaryFilter = false;
                return;

            }
            if(status == "warning")  {

                vm.successFilter = false;
                vm.dangerFilter = false;
                vm.warningFilter = true;
                vm.primaryFilter = false;
                return;

            }
            if(status == "primary")  {

                vm.successFilter = false;
                vm.dangerFilter = false;
                vm.warningFilter = false;
                vm.primaryFilter = true;
                return;

            }

        };

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

                    for(var i=0;i < vm.toCallCandidates.length ; i++){

                        vm.champs += (vm.getColor(vm.toCallCandidates[i].time) == "primary")?1:0;
                        vm.good += (vm.getColor(vm.toCallCandidates[i].time) == "success")?1:0;
                        vm.improve += (vm.getColor(vm.toCallCandidates[i].time) == "warning")?1:0;
                        vm.bad += (vm.getColor(vm.toCallCandidates[i].time) == "danger")?1:0;
                    }

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