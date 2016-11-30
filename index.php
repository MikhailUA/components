<?php

// init
$graph = [
//   0 1 2 3 4 5 6 7
  [0,1,0,0,0,0,0,0], //0
  [0,0,1,0,1,1,0,0], //1
  [0,0,0,1,0,0,1,0], //2
  [0,0,1,0,0,0,0,1], //3
  [1,0,0,0,0,1,0,0], //4
  [0,0,0,0,0,0,1,0], //5
  [0,0,0,0,0,1,0,1], //6
  [0,0,0,0,0,0,0,1]  //7
];

$graph11 = [
//   0 1 2 3 4 5 6 7
  [0,1,0], //0
  [1,0,0], //1
  [0,0,0], //2
 /* [0,0,1,0,0,0,0,1], //3
  [1,0,0,0,0,1,0,0], //4
  [0,0,0,0,0,0,1,0], //5
  [0,0,0,0,0,1,0,1], //6
  [0,0,0,0,0,0,0,1]  //7*/
];



$graphT = trans($graph); // транспорнированный граф
$time = 0;
$sortedByTime = null;
$data = init_data($graph);
$dataT = $data;
$sortedByTime = getFinishTime($data);
$comp = [];
//-----------------------------------------

// Search strongly connected components
DFS($graph,$data,$sortedByTime);
$sortedByTime = getFinishTime($data);
$components = DFS($graphT,$dataT,$sortedByTime);
//-----------------------------------------
//
var_dump($components); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!















/*
printMatrix($graph);
echo '<br>';
printMatrix($graphT);
var_dump($dataT);
*/









//----------------------------------------------------------------
// main cycle
function DFS($graph,&$data,$sortedByTime){
    $elements = $sortedByTime;
    $linked = [];
    for ($el = 0; $el < count($elements); $el++){
        $i = $elements[$el];
        if ($data[$i]['color'] == 'white'){
            $comp = [];
            DFS_visit($graph, $data, $i, $time, $comp);
            $linked[] = $comp;
        }
    }
    return $linked;
}

// visit
function DFS_visit($graph,&$data, $i, &$time, &$comp){
    $comp[] = $i;
    $data[$i]['color'] = 'grey';
    $time = $time + 1;
    $data[$i]['timeStart'] = $time;
    foreach ($Adj = $graph[$i] as $key => $v) {
        if ($v != 0 && $data[$key]['color'] == 'white'){
            $data[$key]['parent'] = $i;
            DFS_visit($graph,$data,$key, $time, $comp);
        }
    }
    $data[$i]['color'] = 'black';
    $time = $time + 1;
    $data[$i]['timeFinish'] = $time;
}

function trans ($graph){
    $graphT = [];
    $length = count($graph);
    for ($i = 0; $i < $length; $i++){
        for ($j = 0; $j < $length;$j++){
            $graphT[$j][$i] = $graph[$i][$j];
        }
    }
    return $graphT;
}
function getFinishTime ($data){
    $finishTime = [];
    for ($i = 0; $i < count($data); $i++){
        $finishTime[$i] = $data[$i]['timeFinish'];
    }
    arsort($finishTime);
    return array_keys($finishTime);
}
function init_data($graph){
    $data = [];
    for ($i = 0; $i < count($graph); $i++)
    {
        $data[$i]['color']  = 'white';
        $data[$i]['parent'] = null;
        $data[$i]['timeStart'] = null;
        $data[$i]['timeFinish'] = null;
    }
    return $data;
}
$dict = ['a','b','c','d','e','f','g','h'];
function printMatrix($graph){
    $length = count($graph);
    for ($i = 0; $i < $length; $i++){
        for ($j = 0; $j < $length; $j++){
            echo $graph[$i][$j];
        }
        echo '<br>';
    }
}

