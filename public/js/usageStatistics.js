/**
 * Created by ybhavnasi on 6/18/15.
 */
var ctx = $("#myChart").get(0).getContext("2d");

var opt2 = {

    canvasBordersWidth: 3,
    canvasBordersColor: "#205081",
    legend: true,
    graphTitleFontSize: 18,
    logarithmic: true,
    responsive: true
};

var data = {

    labels: JSON.parse('<?php echo JSON_encode($months);?>'),
    datasets: [
        {
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            data: JSON.parse('<?php echo JSON_encode($usage);?>')
            //title:"No of Graphs"
        },
        {
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            data: JSON.parse('<?php echo JSON_encode($ip);?>')
            // title:"Unique IP's"
        }
    ]
};

var myBarChart = new Chart(ctx).Bar(data, opt2);

var ctx1 = $("#myChart1").get(0).getContext("2d");

var data1 = {
    labels: JSON.parse('<?php echo JSON_encode($bioc_months);?>'),
    datasets: [
        {
            fillColor: "rgba(220,220,220,0.5)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            data: JSON.parse('<?php echo JSON_encode($bioc_downloads);?>')
        },
        {
            fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            data: JSON.parse('<?php echo JSON_encode($bioc_ip);?>')
        }
    ]
};

var opt1 = {

    canvasBordersWidth: 3,
    canvasBordersColor: "#205081",
    scaleOverride: true,
    scaleSteps: 4,
    scaleStepWidth: 500,
    scaleStartValue: 0,
    scaleLabel: "<%=value%>",
    legend: true,
    graphTitleFontSize: 18,
    responsive: true


};
var myBarChart1 = new Chart(ctx1).Bar(data1, opt1);