<?php
$con=mysqli_connect("localhost","root","IT1506") or die("Failed to connect with database");
mysqli_select_db($con, "affecttutor");

//MySQL query start

//Number of times which exercise was asked for hints
$sql="SELECT topicString, count(exerciseId) 
FROM `generated_hint` 
WHERE self_requested = 'Y'
GROUP BY topicString";

//Total Frustration and Bored value of specific student
$sql1="SELECT username, sum(frustratedINT), sum(boredINT)
FROM `selfreportfrustration` 
WHERE frustrated = 'Y' OR bored = 'Y'
GROUP BY username";

//Frustrated by what exercise
$sql2="SELECT exerciseId, sum(frustratedINT), sum(boredINT)
FROM `selfreportfrustration`
WHERE frustrated = 'Y' OR bored = 'Y'
GROUP BY exerciseId";

//Number of times of exercise are completed
$sql3="SELECT exercisesId, count(complete)
FROM `exerciseprogress`
WHERE completeINT = '1'
GROUP BY exercisesId";

//How many exercises did a student complete
$sql4="SELECT username, count(complete)
FROM `exerciseprogress`
WHERE completeINT = '1'
GROUP BY username";

//MySQL query end

//Result start
$result = mysqli_query($con,$sql) or die(mysqli_error($con));
$result1 = mysqli_query($con,$sql1) or die(mysqli_error($con));
$result2 = mysqli_query($con,$sql2) or die (mysqli_error($con));
$result3 = mysqli_query($con,$sql3) or die (mysqli_error($con));
$result4 = mysqli_query($con,$sql4) or die (mysqli_error($con));
//Result end

//Rows start
$rows = array();
$rows1 = array();
$rows2 = array();
$rows3 = array();
$rows4 = array();
//Rows end

$flag = true;

//Table start
$table = array();
$table1 = array();
$table2 = array();
$table3 = array();
$table4 = array();
//Table end

//Table Column array start
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'Topics', 'type' => 'string'),
    array('label' => 'Number of Hints', 'type' => 'number')

);

$table1['cols'] = array(
    array('label' => 'User', 'type' => 'string'),
    array('label' => 'Frustrated', 'type' => 'number'),
    array('label' => 'Bored', 'type' => 'number')
);

$table2['cols'] = array(
    array('label' => 'Exercise ID', 'type' => 'string'),
    array('label' => 'Frustrated', 'type' => 'number'),
    array('label' => 'Bored', 'type' => 'number')
);

$table3['cols'] = array(
    array('label' => 'Exercise ID', 'type' => 'string'),
    array('label' => 'Completion', 'type' => 'number')
);

$table4['cols'] = array(
    array('label' => 'Username', 'type' => 'string'),
    array('label' => 'Completion', 'type' => 'number')
);
//Table Column array end

//Table Row array start
$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $temp = array();
    // the following line will be used to slice the Pie chart
    $temp[] = array('v' => (string) $r['topicString']);

    // Values of each slice
    $temp[] = array('v' => (int) $r['count(exerciseId)']);
    $rows[] = array('c' => $temp);
}

$rows1 = array();
while($r1 = mysqli_fetch_assoc($result1)) {
    $temp1 = array();
    $temp1[] = array('v' => (string) $r1['username']);

    $temp1[] = array('v' => (int) $r1['sum(frustratedINT)']);
    $temp1[] = array('v' => (int) $r1['sum(boredINT)']);
    $rows1[] = array('c' => $temp1);
}

$rows2 = array();
while($r2 = mysqli_fetch_assoc($result2)) {
    $temp2 = array();
    $temp2[] = array('v' => (string) $r2['exerciseId']);

    $temp2[] = array('v' => (int) $r2['sum(frustratedINT)']);
    $temp2[] = array('v' => (int) $r2['sum(boredINT)']);
    $rows2[] = array('c' => $temp2);
}

$rows3 = array();
while($r3 = mysqli_fetch_assoc($result3)) {
    $temp3 = array();
    $temp3[] = array('v' => (string) $r3['exercisesId']);

    $temp3[] = array('v' => (int) $r3['count(complete)']);
    $rows3[] = array('c' => $temp3);
}

$rows4 = array();
while($r4 = mysqli_fetch_assoc($result4)) {
    $temp4 = array();
    $temp4[] = array('v' => (string) $r4['username']);

    $temp4[] = array('v' => (int) $r4['count(complete)']);
    $rows4[] = array('c' => $temp4);
}
//Table Row array end

//Table Row start
$table['rows'] = $rows;
$table1['rows'] = $rows1;
$table2['rows'] = $rows2;
$table3['rows'] = $rows3;
$table4['rows'] = $rows4;
//Table Row end

//json start
$jsonTable = json_encode($table);
$jsonTable1 = json_encode($table1);
$jsonTable2 = json_encode($table2);
$jsonTable3 = json_encode($table3);
$jsonTable4 = json_encode($table4);
//json end
?>

<script type="text/javascript">

    google.charts.load('current', {'packages': ['corechart','controls']});

    //Pie Chart (Number of times which exercise was asked for hints)
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {

        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(<?=$jsonTable?>);
        var dashboard = new google.visualization.Dashboard(
            document.getElementById('dashboard_div'));

        var donutRangeSlider = new google.visualization.ControlWrapper({
            'controlType':'NumberRangeFilter',
            'containerId':'filter_div',
            'options':{
                'filterColumnLabel':'Number of Hints'
            }
        });

        var pieChart = new google.visualization.ChartWrapper({
            'chartType':'PieChart',
            'containerId':'chart_div',
            'options':{
                'width' : 500,
                'height' : 500,
                'pieSliceText' : 'value',
                'legend' : 'right'
            }
        });


        dashboard.bind(donutRangeSlider, pieChart);
        dashboard.draw(data);
    }

    //Bar Chart (Total Value of Students Frustration and Boredness)
    google.charts.setOnLoadCallback(drawChart1);
    function drawChart1() {

        // Create our data table out of JSON data loaded from server.
        var data1 = new google.visualization.DataTable(<?=$jsonTable1?>);
        var dashboard1 = new google.visualization.Dashboard(
            document.getElementById('dashboard_div1'));

        var stringFilter = new google.visualization.ControlWrapper({
            'controlType': 'StringFilter',
            'containerId': 'filter_div1',
            'options':{
                'filterColumnLabel': 'User'
            }
        });

        var barChart = new google.visualization.ChartWrapper({
            'chartType': 'BarChart',
            'containerId':'chart_div1',
            'options':{
                'width': 500,
                'height': 500,
                'legend': 'right'
            }
        });
        dashboard1.bind(stringFilter, barChart);
        dashboard1.draw(data1);
    }

    //Column Chart (Exercises w/ Frustration Value)
    google.charts.setOnLoadCallback(drawChart2);
    function drawChart2() {

        // Create our data table out of JSON data loaded from server.
        var data2 = new google.visualization.DataTable(<?=$jsonTable2?>);
        var dashboard2 = new google.visualization.Dashboard(
            document.getElementById('dashboard_div2'));

        var numberRangeFilterColumnChart = new google.visualization.ControlWrapper({
            'controlType': 'StringFilter',
            'containerId': 'filter_div2',
            'options':{
                'filterColumnLabel': 'Exercise ID'
            }
        });

        var columnChart = new google.visualization.ChartWrapper({
            'chartType': 'ColumnChart',
            'containerId':'chart_div2',
            'options':{
                'width': 500,
                'height': 500,
                'legend': 'right'
            }
        });
        dashboard2.bind(numberRangeFilterColumnChart, columnChart);
        dashboard2.draw(data2);
    }

    //Number of times of exercise are completed
    google.charts.setOnLoadCallback(drawChart3);
    function drawChart3() {

        // Create our data table out of JSON data loaded from server.
        var data3 = new google.visualization.DataTable(<?=$jsonTable3?>);
        var dashboard3 = new google.visualization.Dashboard(
            document.getElementById('dashboard_div3'));

        var numberRangeFilterColumnChart = new google.visualization.ControlWrapper({
            'controlType': 'StringFilter',
            'containerId': 'filter_div3',
            'options':{
                'filterColumnLabel': 'Exercise ID'
            }
        });

        var columnChart = new google.visualization.ChartWrapper({
            'chartType': 'ColumnChart',
            'containerId':'chart_div3',
            'options':{
                'width': 500,
                'height': 500,
                'legend': 'right'
            }
        });
        dashboard3.bind(numberRangeFilterColumnChart, columnChart);
        dashboard3.draw(data3);
    }

    //How many exercises did a student complete
    google.charts.setOnLoadCallback(drawChart4);
    function drawChart4() {

        // Create our data table out of JSON data loaded from server.
        var data4 = new google.visualization.DataTable(<?=$jsonTable4?>);
        var dashboard4 = new google.visualization.Dashboard(
            document.getElementById('dashboard_div4'));

        var numberRangeFilterColumnChart = new google.visualization.ControlWrapper({
            'controlType': 'StringFilter',
            'containerId': 'filter_div4',
            'options':{
                'filterColumnLabel': 'Username'
            }
        });

        var columnChart = new google.visualization.ChartWrapper({
            'chartType': 'ColumnChart',
            'containerId':'chart_div4',
            'options':{
                'width': 1100,
                'height': 500,
                'legend': 'right'
            }
        });
        dashboard4.bind(numberRangeFilterColumnChart, columnChart);
        dashboard4.draw(data4);
    }
</script>