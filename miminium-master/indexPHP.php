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
    array('label' => 'Completed', 'type' => 'number')
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

<html lang="en">

<!-- Head start -->
<head>

    <meta charset="utf-8">
    <meta name="description" content="NYP FYPJ Interactive Dashboard">
    <meta name="author" content="Tang Kin Leung">
    <meta name="keyword" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NYP FYPJ Interactive Dashboard</title>

    <!-- start: Css -->
    <link rel="stylesheet" type="text/css" href="asset/css/bootstrap.min.css">

    <!-- plugins -->
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/font-awesome.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/simple-line-icons.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="asset/css/plugins/fullcalendar.min.css"/>
    <link href="asset/css/style.css" rel="stylesheet">
    <!-- end: Css -->

    <link rel="shortcut icon" href="asset/img/logomi.png">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Chart -->
    <!--<script type="text/javascript" src="https://www.google.com/jsapi"></script>-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>

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

            changeOptions = function() {
                pieChart.setOption('is3D', true);
                pieChart.draw();
            }
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
                    'height': 550,
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

    <script type="text/javascript">
        function refreshPHP(){
            $('#chart_div').load('MySQLGoogleChart.php', function(){
            });

            $('#chart_div1').load('MySQLGoogleChart.php', function(){
            });

            $('#chart_div2').load('MySQLGoogleChart.php', function(){
            });

            $('#chart_div3').load('MySQLGoogleChart.php', function(){
            });

            $('#chart_div4').load('MySQLGoogleChart.php', function(){
            });
        };

        $(document).ready(function(){
            refreshPHP();
            setInterval(refreshPHP, 15000);
        });


    </script>
    <!-- Google Chart end-->
</head>

<!-- Head end -->


<body id="mimin" class="dashboard">
<!-- start: Header -->
<nav class="navbar navbar-default header navbar-fixed-top">
    <div class="col-md-12 nav-wrapper">
        <div class="navbar-header" style="width:100%;">
            <div class="opener-left-menu is-open">
                <span class="top"></span>
                <span class="middle"></span>
                <span class="bottom"></span>
            </div>
            <a href="index.html" class="navbar-brand">
                <b></b>
            </a>


            <ul class="nav navbar-nav navbar-right user-nav">
                <li class="user-name"><span>Tang Kin Leung</span></li>
                <li class="dropdown avatar-dropdown">
                    <img src="asset/img/avatar.jpg" class="img-circle avatar" alt="user name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"/>
                </li>
                <!--<li ><a href="#" class="opener-right-menu"><span class="fa fa-coffee"></span></a></li>-->
            </ul>
        </div>
    </div>
</nav>
<!-- end: Header -->

<div class="container-fluid mimin-wrapper">

    <!-- start:Left Menu -->
    <div id="left-menu">
        <div class="sub-left-menu scroll">
            <ul class="nav nav-list">
                <li><div class="left-bg"></div></li>
                <li class="time">
                    <h1 class="animated fadeInLeft">21:00</h1>
                    <p class="animated fadeInRight">Sat,October 1st 2029</p>
                </li>

            </ul>
        </div>
    </div>
    <!-- end: Left Menu -->


    <!-- start: content page -->
    <div id="content">
        <!-- start: header -->
        <div class="panel">
            <div class="panel-body">
                <div class="col-md-6 col-sm-12">
                    <h3 class="animated fadeInLeft">Affect Tutor Web Dashboard</h3>
                    <p class="animated fadeInDown"><span class="fa  fa-map-marker"></span> Singapore, Singapore</p>

                    <ul class="nav navbar-nav">
                    </ul>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="col-md-6 col-sm-6 text-right" style="padding-left:10px;">
                        <h3 style="color:#DDDDDE;"><span class="fa  fa-map-marker"></span> Singapore</h3>
                        <h1 style="margin-top: -10px;color: #ddd;">30<sup>o</sup></h1>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="wheather">
                            <div class="stormy rainy animated pulse infinite">
                                <div class="shadow">

                                </div>
                            </div>
                            <div class="sub-wheather">
                                <div class="thunder">

                                </div>
                                <div class="rain">
                                    <div class="droplet droplet1"></div>
                                    <div class="droplet droplet2"></div>
                                    <div class="droplet droplet3"></div>
                                    <div class="droplet droplet4"></div>
                                    <div class="droplet droplet5"></div>
                                    <div class="droplet droplet6"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: header -->

        <!-- Place where you put your contents  -->
        <div class="col-md-12" style="padding:20px;">
            <!-- Place where you put your contents end -->

            <div class="col-md-12 padding-0">

                <!-- Row 1 -->
                <div class="col-md-12 padding-0">

                    <!-- Side by Side -->
                    <div class="col-md-12 padding-0">

                        <!-- Pie Chart (Hints Requested)-->
                        <div class="col-md-6">
                            <div class="panel box-v1">
                                <div class="panel-heading bg-white border-none">
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                                        <h4 class="text-left">Number of Hints Requested</h4>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <h4>
                                            <span class="icon-book-open icons icon text-right"></span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="panel-body text-center">
                                    <hr/>
                                        <div id="dashboard_div">
                                            <div id="filter_div"></div>
                                            <div id="chart_div"></div>
                                            <button style="margin: 1em 1em 1em 2em" onclick="changeOptions();">
                                                Make the pie chart 3D
                                            </button>
                                            <script type="text/javascript">
                                                function changeOptions() {
                                                    pieChart.setOption('is3D', true);
                                                    pieChart.draw();
                                                }
                                            </script>
                                        </div>
                                    <hr/>
                                </div>
                            </div>
                        </div>

                        <!-- Bar Chart (Students Frustration and Boredness value) -->
                        <div class="col-md-6">
                            <div class="panel box-v1">
                                <div class="panel-heading bg-white border-none">
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                                        <h4 class="text-left">Affecive State of Students</h4>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                        <h4>
                                            <span class="icon-people icons icon text-right"></span>
                                        </h4>
                                    </div>
                                </div>
                                <div class="panel-body text-center">
                                    <hr/>
                                        <div id="dashboard_div1">
                                            <div id="filter_div1"></div>
                                            <div id="chart_div1"></div>
                                        </div>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <!-- Row 1 end-->

            <div class="col-md-12 card-wrap padding-0">
                <div class="col-md-6">
                    <div class="panel box-v1">
                        <div class="panel-heading bg-white border-none">
                            <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                                <h4 class="text-left">Frustration Value of Exercises</h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                <h4>
                                    <span class="icon-doc icons icon text-right"></span>
                                </h4>
                            </div>
                        </div>
                        <div class="panel-body text-center">
                            <hr/>
                            <div id="dashboard_div2">
                                <div id="filter_div2"></div>
                                <div id="chart_div2"></div>
                            </div>
                            <hr/>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel box-v1">
                        <div class="panel-heading bg-white border-none">
                            <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                                <h4 class="text-left">Times of Exercises Completed</h4>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                                <h4>
                                    <span class="icon-question icons icon text-right"></span>
                                </h4>
                            </div>
                        </div>
                        <div class="panel-body text-center">
                            <hr/>
                            <div id="dashboard_div3">
                                <div id="filter_div3"></div>
                                <div id="chart_div3"></div>
                            </div>
                            <hr/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="panel box-v1">
                    <div class="panel-heading bg-white border-none">
                        <div class="col-md-6 col-sm-6 col-xs-6 text-left padding-0">
                            <h4 class="text-left">Students Number of Exercises Completed</h4>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 text-right">
                            <h4>
                                <span class="icon-graduation icons icon text-right"></span>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-body text-center">
                        <hr/>
                        <div id="dashboard_div4">
                            <div id="filter_div4"></div>
                            <div id="chart_div4"></div>
                        </div>
                        <hr/>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Place where you put your contents end  -->
    <!-- end: content page -->

</div>

<!-- start: Javascript -->
<script src="asset/js/jquery.min.js"></script>
<script src="asset/js/jquery.ui.min.js"></script>
<script src="asset/js/bootstrap.min.js"></script>


<!-- plugins -->
<script src="asset/js/plugins/moment.min.js"></script>
<script src="asset/js/plugins/fullcalendar.min.js"></script>
<script src="asset/js/plugins/jquery.nicescroll.js"></script>
<script src="asset/js/plugins/jquery.vmap.min.js"></script>
<script src="asset/js/plugins/maps/jquery.vmap.world.js"></script>
<script src="asset/js/plugins/jquery.vmap.sampledata.js"></script>
<script src="asset/js/plugins/chart.min.js"></script>


<!-- custom -->
<script src="asset/js/main.js"></script>

<!-- end: Javascript -->
</body>
</html>


