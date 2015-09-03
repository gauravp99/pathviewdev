/**
 * Created by ybhavnasi on 9/1/15.
 */

angular.module('mytodoApp')
    .controller('trendsCtrl', function ($scope,$rootScope,$http) {
       console.log("yearly trends");

        $http.get('/api/yearly').
            then(function(response) {
                $rootScope.analysis = response.data;
                var no_of_years = response.data.length;
                var analysis = [];

                var years = [];
                var i=0;
                $.each(response.data, function(value, key) {
                        years[i] = value;

                    var months = [];
                    var p_analysis_count = [];
                    var g_analysis_count = [];

                    var p_ex1_count= 0;
                    var p_ex2_count= 0;
                    var p_ex3_count= 0;
                    var p_new_count= 0;
                    var g_ex1_count= 0;
                    var g_ex2_count= 0;
                    var g_disc_count= 0;
                    var g_new_count= 0;


                    var users_count = [];
                    var k = 0;
                    $.each(response.data[years[i]], function(value, key) {
                        console.log(key);
                            console.log(key.date);
                        months[k] = key.month;
                        p_analysis_count[k]= key.pathwayAnalysisCount;
                        g_analysis_count[k] = key.GageAnalysisCount;
                        users_count[k] = key.usersCount;

                        $.each(key.pathwayDistribution,function(key,value){

                           if(key=="exampleAnalysis1")
                           {
                               p_ex1_count += parseInt(value);
                           }
                            if(key=="exampleAnalysis2")
                            {
                                p_ex2_count += parseInt(value);
                            }
                            if(key=="exampleAnalysis3")
                            {
                                p_ex3_count += parseInt(value);
                            }
                            if(key=="newAnalysis")
                            {
                                p_new_count += parseInt(value);
                            }

                        });
                        $.each(key.GageDistribution,function(key,value){
                            if(key=="exampleGageAnalysis1")
                            {
                                g_ex1_count += parseInt(value);
                            }
                            if(key=="exampleGageAnalysis2")
                            {
                                g_ex2_count += parseInt(value);
                            }
                            if(key=="DiscAnalysis")
                            {
                                g_disc_count += parseInt(value);
                            }
                            if(key=="newGageAnalysis1")
                            {
                                g_new_count += parseInt(value);
                            }
                            if(key=="gage")
                            {
                                g_new_count += parseInt(value);
                            }

                        });

                        k= k+1;

                    });

                    months = months.reverse();
                    var maxpathviewvalue = 0;
                    var minpathviewvalue =0;
                    var maxgagevalue = 0;
                    var maxusersvalue = 0;
                    var stepspathview = 10;
                    var stepsgage= 10;
                    var stepsUser= 10;
                    console.log(p_analysis_count);
                    maxpathviewvalue = _.max(p_analysis_count,function (months) {return parseInt(months)});

                    minpathviewvalue = _.min(p_analysis_count,function (months) {return parseInt(months)});

                    maxgagevalue = _.max(g_analysis_count,function (months) {return parseInt(months)});
                    maxusersvalue = _.max(users_count,function (months) {return parseInt(months)});
                    stepspathview =  Math.round(maxpathviewvalue/10) - Math.round(maxpathviewvalue % 10);
                    if(stepspathview <= 0)
                    {
                        stepspathview = Math.round(maxpathviewvalue/10);
                    }

                    stepsgage =  Math.round(maxgagevalue/10) - Math.round(maxgagevalue % 10);
                    if(stepsgage <= 0)
                    {
                        stepsgage =  Math.round(maxgagevalue/10);
                    }
                    stepsUser= Math.round(maxusersvalue/10) -  Math.round(maxusersvalue % 10);
                    if(stepsUser <= 0)
                    {
                        stepsUser =  Math.round(maxusersvalue/10);
                    }
                    var months_name = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                    var months_names = [];
                    $.each(months,function(index,value){
                        months_names.push(months_name[index]+'-'+years[i]);
                    });
                    analysis[years[i]] = {
                        'months':months_names,
                        'p_analysis_count':p_analysis_count.reverse(),
                        'g_analysis_count':g_analysis_count.reverse(),
                        'users_count':users_count.reverse(),
                        'stepspathview':stepspathview,
                        'stepsgage':stepsgage,
                        'stepsUser':stepsUser,
                        'p_ex1_count':p_ex1_count,
                        'p_ex2_count':p_ex2_count,
                        'p_ex3_count':p_ex3_count,
                        'p_new_count':p_new_count,
                        'g_ex1_count':g_ex1_count,
                        'g_ex2_count':g_ex2_count,
                        'g_disc_count':g_disc_count,
                        'g_new_count':g_new_count
                    };


                        i=i+1;
                });
                $scope.years = years;
                $scope.analysis = analysis;

                var pathview = $("#Pathview").get(0).getContext("2d");

                var opt2 = {

                    canvasBordersWidth: 3,
                    canvasBordersColor: "#205081",
                    legend: true,
                    graphTitleFontSize: 18,
                    logarithmic: true,
                    responsive: true,
                    scaleOverride: true,
                    scaleSteps: 10,
                    scaleStepWidth: $scope.analysis['2015'].stepsUser,
                    scaleStartValue: 0,
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
                };



                var opt_gage = {
                    scaleGridLineColor : "rgba(0,0,0,.05)",
                    canvasBordersWidth: 3,
                    canvasBordersColor: "#205081",
                    legend: true,
                    graphTitleFontSize: 18,
                    logarithmic: true,
                    responsive: true,
                    scaleOverride: true,
                    scaleSteps: 10,
                    scaleStepWidth: $scope.analysis['2015'].stepsgage,
                    scaleStartValue: 0,
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"

                };


                var opt_pathview = {
                    scaleGridLineColor : "rgba(0,0,0,.05)",
                    canvasBordersWidth: 3,
                    canvasBordersColor: "#205081",
                    legend: true,
                    graphTitleFontSize: 18,
                    logarithmic: true,
                    responsive: true,
                    scaleOverride: true,
                    scaleSteps: 10,
                    scaleStepWidth: $scope.analysis['2015'].stepspathview,
                    scaleStartValue: 0,
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"

                };

                var data = {

                    labels: $scope.analysis['2015'].months,
                    datasets: [
                        {
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            data: $scope.analysis['2015'].p_analysis_count
                            //title:"No of Graphs"
                        }
                    ]
                };
                var data_users = {

                    labels: $scope.analysis['2015'].months,
                    datasets: [
                        {
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            data: $scope.analysis['2015'].users_count
                            //title:"No of Graphs"
                        }
                    ]
                };


               new Chart(pathview).Bar(data, opt_pathview);


                var gage_analysis = $("#Gage").get(0).getContext("2d");
                var gage_users = $("#GageUsers").get(0).getContext("2d");
                var data1 = {
                    labels: $scope.analysis['2015'].months,
                    datasets: [
                        {
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            data: $scope.analysis['2015'].g_analysis_count
                        }
                    ]
                };

                new Chart(gage_analysis).Bar(data1, opt_gage);
                new Chart(gage_users).Line(data_users, opt2);
                console.log($scope);


                var pathviewpie = [
                    {
                        value: $scope.analysis['2015'].p_new_count,
                        color:"#F7464A",
                        highlight: "#FF5A5E",
                        label: "New Analysis"
                    },
                    {
                        value: $scope.analysis['2015'].p_ex1_count,
                        color: "#46BFBD",
                        highlight: "#5AD3D1",
                        label: "Example 1"
                    },
                    {
                        value: $scope.analysis['2015'].p_ex2_count,
                        color: "#FDB45C",
                        highlight: "#FFC870",
                        label: "Example 2"
                    },
                    {
                        value: $scope.analysis['2015'].p_ex3_count,
                        color: "#00B45C",
                        highlight: "#00C870",
                        label: "Example 3"
                    }
                ];
                var gagepie = [
                    {
                        value: $scope.analysis['2015'].g_new_count,
                        color:"#F7464A",
                        highlight: "#FF5A5E",
                        label: "New Analysis"
                    },
                    {
                        value: $scope.analysis['2015'].g_ex1_count,
                        color: "#46BFBD",
                        highlight: "#5AD3D1",
                        label: "Example 1"
                    },
                    {
                        value: $scope.analysis['2015'].g_ex2_count,
                        color: "#FDB45C",
                        highlight: "#FFC870",
                        label: "Example 2"
                    },
                    {
                        value: $scope.analysis['2015'].g_disc_count,
                        color: "#FDB45C",
                        highlight: "#00C870",
                        label: "Discrete Analysis"
                    }
                ];
                var ctxpieChart = $("#PathviewAnalysis").get(0).getContext("2d");
                var ctxpieChart1 = $("#gageAnalysis").get(0).getContext("2d");
                new Chart(ctxpieChart).Doughnut(pathviewpie, {
                    animateScale: true,
                    animationEasing : "easeInSine",
                    responsive: true,
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
                });
                new Chart(ctxpieChart1).Doughnut(gagepie, {
                    animateScale: true,
                    animationEasing : "easeInSine",
                    showScale: true,
                    scaleLabel: "<%=value%>",
                    scaleShowLabels: true,
                    responsive: true,
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
                });
            }, function(response) {
                alert("Alert!! Unable to get details from server.")
            });


    })
.controller('MonthlyTrendsCtrl', function ($scope) {
        console.log("monthly trends");
})
.controller('ManualTrendsCtrl', function ($scope) {
        console.log("manual trends");
});
