﻿(function () {
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
                    drawGraph();
                });


        }

        function drawGraph(){
            var labels = [] ;
            var expectedHrs = [];
            var fun= [];
            var workingHrs = [];
            var totalWork = 0;
            for(var i=0;i< vm.employee.length; i++ ){
                console.log(vm.employee[i].date);
                var t = vm.employee[i].date;
                var date = new Date(t);
                labels.push(date.getDate());
                expectedHrs.push(8);
                fun.push(Math.floor((Math.random() * (vm.employee[i].time/60/60)) + 1));
                workingHrs.push(vm.employee[i].time/60/60);
                totalWork += vm.employee[i].time/60/60;


            };
            vm.performance = (totalWork/(new Date().getDate()*6.85))*100;
            var data = {
                    labels: labels ,
            datasets: [
                {
                    label: "Expected working hours",
                    backgroundColor: "rgba(0,220,0,0.2)",


                    data: expectedHrs
        },
            {
                label: "Fun Time",
                    backgroundColor: "rgba(173,216,230,0.8)",
                data: fun
            },
            {
                label: "Working Hours",
                    backgroundColor: "rgba(151,187,0,0.4)",
                data: workingHrs
            }
        ]
        };



            var options = {

                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Hours'
                        }
                    }]
                },

                ///Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,

                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",

                //Number - Width of the grid lines
                scaleGridLineWidth: 1,

                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,

                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,

                //Boolean - Whether the line is curved between points
                bezierCurve: true,

                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.4,

                //Boolean - Whether to show a dot for each point
                pointDot: true,

                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,

                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,

                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,

                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,

                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,

                //Boolean - Whether to fill the dataset with a colour
                datasetFill: true,

                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"

            };


            var ctx = document.getElementById("canvas").getContext("2d");
            new Chart(ctx, {type: 'line', data, options});

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