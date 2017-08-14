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
/**$sql2="SELECT exerciseId, sum(frustratedINT), sum(boredINT)
FROM `selfreportfrustration`
WHERE frustrated = 'Y' OR bored = 'Y'
GROUP BY exerciseId";


//Number of times of exercise are completed
$sql4=”SELECT exercisesId, count(complete)
FROM `exerciseprogress`
WHERE completeINT = '1'
GROUP BY exercisesId”;

 **/

//MySQL query end

//Result start
$result = mysqli_query($con,$sql) or die(mysqli_error($con));
$result1 = mysqli_query($con,$sql1) or die(mysqli_error($con));
/**$result2 = mysqli_query($con,$sql2) or die (mysqli_error($con));**/
//Result end

//Rows start
$rows = array();
$rows1 = array();
//$rows2 = array();
//Rows end

$flag = true;

//Table start
$table = array();
$table1 = array();
//$table2 = array();
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

/**$table2['cols'] = array(
array('label' => 'Exercise ID', 'type' => 'string'),
array('label' => 'Frustrated', 'type' => 'number'),
array('label' => 'Bored', 'type' => 'number')
);**/
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

/**$rows2 = array();
while($r2 = mysqli_fetch_assoc($result2)) {
$temp2 = array();
$temp2[] = array('v' => (string) $r2['exerciseId']);

$temp2[] = array('v' => (int) $r2['sum(frustratedINT)']);
$temp2[] = array('v' => (int) $r2['sum(boredINT)']);
$rows2[] = array('c' => $temp2);
}**/
//Table Row array end

//Table Row start
$table['rows'] = $rows;
$table1['rows'] = $rows1;
//$table2['rows'] = $rows2;
//Table Row end

//json start
$jsonTable = json_encode($table);

$jsonTable1 = json_encode($table1);
//$jsonTable2 = json_encode($table2);
//echo $jsonTable;
//json end

//header("refresh: 5;");
?>
