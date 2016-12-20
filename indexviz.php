<!DOCTYPE>
<html>

<head>
  <title>cytoscape-dagre.js demo</title>

  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

  <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
  <script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>

  <!-- for testing with local version of cytoscape.js -->
  <!--<script src="../cytoscape.js/build/cytoscape.js"></script>-->

  <script src="https://cdn.rawgit.com/cpettitt/dagre/v0.7.4/dist/dagre.min.js"></script>
  <script src="https://cdn.rawgit.com/cytoscape/cytoscape.js-dagre/1.1.2/cytoscape-dagre.js"></script>

  <style>
    body {
      font-family: helvetica;
      font-size: 14px;
    }

    #cy {
      width: 100%;
      height: 100%;
      position: absolute;
      left: 0;
      top: 0;
      z-index: 999;
    }

    h1 {
      opacity: 0.5;
      font-size: 1em;
    }
  </style>


</head>

<body>
<h3>Курсовая работа по теории алгоритмов.</h3>
<h4>Тема: Cильно связанные компоненты в ориентированном невзвешенном графе.</h4>


<form>
  <label>Введите кол-во вершин:</label>
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

  if(isset($_POST['vertexConnections'])):
    $vertexConnections = $_POST['vertexConnections'];
    $graph = applyConnections($graph,$vertexConnections);

    /*
        $graph = [
    // 0 1 2 3 4 5 6 7
          [0,1,0,0,0,0,0,0], //0
          [0,0,1,0,1,1,0,0], //1
          [0,0,0,1,0,0,1,0], //2
          [0,0,1,0,0,0,0,1], //3
          [1,0,0,0,0,1,0,0], //4
          [0,0,0,0,0,0,1,0], //5
          [0,0,0,0,0,1,0,1], //6
          [0,0,0,1,0,0,0,0]  //7
        ];*/

    if(isset($graph)):
      $graphT = trans($graph); // транспорнированный граф
      $sortedByTime = null;
      $data = init_data($graph);
      $dataT = $data;
      $sortedByTime = getFinishTime($data);
      $comp = [];
      //-----------------------------------------

      // Search strongly connected components
      DFS($graph,$data,$sortedByTime);
      $sortedByTime = getFinishTime($data);
      DFS($graphT,$dataT,$sortedByTime);
      printResult($dataT);
      $edges = getEdges($graph);
      $JsonElements = createElements($edges[0],$edges[1]);


      //-----------------------------------------
    endif;
  endif;
endif;
//----------------------------------------------------------------
?>

<div id="cy" style="width: 300px; height: 300px; display: block; position: relative;"></div>

<script>

  $(function(){
    var cy = window.cy = cytoscape({
      container: document.getElementById('cy'),

      boxSelectionEnabled: false,
      autounselectify: true,

      layout: {
        name: 'grid',
        rows: 2
      },

      style: [
        {
          selector: 'node',
          style: {
            'content': 'data(id)',
            'text-opacity': 0.5,
            'text-valign': 'center',
            'text-halign': 'right',
            'background-color': '#11479e'
          }
        },

        {
          selector: 'edge',
          style: {
            'width': 4,
            'target-arrow-shape': 'triangle',
            'line-color': '#9dbaea',
            'target-arrow-color': '#9dbaea',
            'curve-style': 'bezier'
          }
        }
      ],
      <?php echo $JsonElements?>
    });

  });

</script>


</body>

</html>


<?php

function printResult($dataT){
  echo "Сильно связанные компоненты: ".'<br><br>';

  $t = 0;
  $sequence = getSequence($dataT);

  for ($i = 1; $i < count($sequence)+1; $i++){

    $compN = $dataT[$sequence[$i]]['componentN'];

    $br = ($i != 1 ) ? '<br><br>' : '';
    $arrow = ($t==$compN) ? '->' : '';

    if ($t<>$compN){
      echo $br.'component '.$compN.': <br>';
    }

    echo $arrow.$sequence[$i];

    $t = $compN;
  }
}

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
  $compN = 1;
  $time = 0;
  for ($el = 0; $el < count($elements); $el++){
    $i = $elements[$el];
    if ($data[$i]['color'] == 'white'){
      $comp = [];
      DFS_visit($graph, $data, $i, $time, $comp, $compN);
      $linked[] = $comp;
      $compN++;
    }
  }
  return $linked;
}

// visit
function DFS_visit($graph,&$data, $i, &$time, &$comp, &$compN){
  $comp[] = $i;
  $data[$i]['color'] = 'grey';
  $time = $time + 1;
  $data[$i]['timeStart'] = $time;
  $data[$i]['componentN'] = $compN;
  foreach ($Adj = $graph[$i] as $key => $v) {
    if ($v != 0 && $data[$key]['color'] == 'white'){
      $data[$key]['parent'] = $i;
      DFS_visit($graph,$data,$key, $time, $comp, $compN);
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
    $data[$i]['id']  = $i;
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
function getEdges($graph){
  $edges = [];
  $nodes = [];
  foreach ($graph as $source=>$linkedList){
    $nodes[] = $source;
    foreach ($linkedList as $target => $vertex) {
      if ($vertex == 1){
        $edges[] = ['source' => $source, 'target' => $target];
      }
    }
  }

  $rs = array($nodes,$edges);
  return $rs;

}

function createElements($nodes,$edges){
  $source = null;
  $target = null;

  $JsonNodes = '';
  $JsonEdges = '';


  foreach ($nodes as $source){
    $JsonNodes .= '{ data: { id: '.$source.' } },';
  }

  foreach ($edges as $edge){

    $source = $edge['source'];
    $target = $edge['target'];

    $JsonEdges .=  '{ data: { source: '.$source.', target: '.$target.'} },';
  }


  $JsonElements = 'elements: {
                    nodes: ['.$JsonNodes.'],
                    edges: ['.$JsonEdges.']
                   },';

  return $JsonElements;

}

?>
