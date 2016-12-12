
<?php

// init
$graph = [
// 0 1 2 3 4 5 6 7
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
?>

<form>
  <label>Ввведите кол-во вершин:</label>
  <input type="number" name="vertex" min="0">
  <input type="submit" value="Set">
</form>


<?php
if(isset($_GET['vertex'])):
    $v = $_GET['vertex'];?>


<form method="post">
  <label>Введите список смежности графа <?php echo $v.'x'.$v; ?></label>
  <ol start="0">
    <?php
    for($i = 0;$i<$v;$i++){
      $value = isset($_POST['vertexConnections'][$i])? $_POST['vertexConnections'][$i]:"";
      echo '<li><input type="text" name="vertexConnections[]" value="'.$value.'" </li>';
    }?>
  </ol>
  <input type="submit" value="Get components">
</form>

<?php

$graph = init_graph($v);

if(isset($_POST['vertexConnections'])){
  $vertexConnections = $_POST['vertexConnections'];
  $graph = applyConnections($graph,$vertexConnections);
}

//var_dump($graph);

?>

<?php endif;

if(isset($graph)):

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
/*var_dump($data);
var_dump($sortedByTime);*/


foreach ($components as $key => $component) {
  echo "component: ".++$key.'<br>';
  foreach ($component as $vertex) {
    $links = $graph[$vertex];
    foreach ($component as $v) {
      if($links[$v]){
        echo($vertex.'->'.$v.'<br>');
        $edges[] = [$vertex => $v];
      }
    }
  }
  echo '<br>';
}


$sequence = getSequence($data);
var_dump($sequence);
for ($i = 0; $i < count($sequence); $i++){

}


foreach ($sequence as $orderN=>$vertexId){
    echo $vertexId.'->';
}


//var_dump($edges);

endif;

?>


<?php

/*
printMatrix($graph);
echo '<br>';
printMatrix($graphT);
var_dump($dataT);
*/

?>

<?php
//----------------------------------------------------------------


function getSequence ($data){
    $seq = array();
    foreach($data as $vertexId => $vertex){
        $seq[$vertex['timeStart']] = $vertexId;
        $seq[$vertex['timeFinish']] = $vertexId;
    }
    ksort($seq);
    return $seq;
}

function applyConnections($graph,$vertexConnections){
    foreach ($vertexConnections as $vertexId => $connections){
        $connections = explode(',',trim($connections));
        foreach ($connections as $c){
            if($c != ""){
                $graph[$vertexId][$c] = 1;
            }
        }
    }

    return $graph;
}

function init_graph($v){
    $graph = array();
    for ($i = 0; $i < $v; $i++){
        $links = array();
        for ($j = 0; $j< $v; $j++){
            $links[] = 0;
        }
        $graph[]=$links;
    }
    return $graph;
}




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
        $data[$i]['componentN'] = 1;
    }
    return $data;
}

function printMatrix($graph){
    $length = count($graph);
    for ($i = 0; $i < $length; $i++){
        for ($j = 0; $j < $length; $j++){
            echo $graph[$i][$j];
        }
        echo '<br>';
    }
}

$dict = ['a','b','c','d','e','f','g','h'];

?>
