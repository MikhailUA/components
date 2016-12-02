<!DOCTYPE>
<html>
<head>
  <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
  <script src="http://cytoscape.github.io/cytoscape.js/api/cytoscape.js-latest/cytoscape.min.js"></script>
  <style>
    #cy {
      width: 300px;
      height: 300px;
      display: block;
    }
  </style>
</head>
<body>
<?php
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
}?>
<form>
  <label>Ввведите кол-во вершин:</label>
  <input type="number" name="vertex" min="0">
  <input type="submit" value="Set">
</form>


<?php
if(isset($_GET['vertex'])){
  $v = $_GET['vertex'];
}
?>

<form method="post">
  <label>Введите матрицу смежности графа <?php echo $v.'x'.$v; ?></label>
  <ol start="0">
    <?php
    for($i = 0;$i<$v;$i++){
      echo '<li><input type="text" name="graph[]" minlength="'.$v.'" maxlength="'.$v.'" value="'.$_POST['graph'][$i].'" required></li>';
    }?>
  </ol>
  <input type="submit" value="Get components">
</form>


<?php
$data = array(
  'elements' => array(
    'nodes' => array( array( 'data' => array( 'id' => 'j', 'name' => 'Jerry' ) ) ),
    'edges' => array()
  )
);

$data->elements = new stdClass();

/*var cyJson =   {
      elements: {
        nodes: [
          { data: { id: 'j', name: 'Jerry' } },
          { data: { id: 'e', name: 'Elaine' } },
          { data: { id: 'k', name: 'Kramer' } },
          { data: { id: 'g', name: 'George' } }
        ],
        edges: [
          { data: { id: 'je', source: 'j', target: 'e' } },
          { data: { source: 'jk', target: 'k' } },
          { data: { source: 'j', target: 'g' } },
          { data: { source: 'e', target: 'j' } },
          { data: { source: 'e', target: 'k' } },
          { data: { source: 'k', target: 'j' } },
          { data: { source: 'k', target: 'e' } },
          { data: { source: 'k', target: 'g' } },
          { data: { source: 'g', target: 'j' } }
        ]
      }
    };*/

var_dump(json_encode($data));
exit;
?>

<?php

if(isset($_POST['graph'])):?>

  <?php $graph = [];
  $graphT = $_POST['graph'];
  foreach ($graphT as $key=>$v){
    // echo $key.' '.$v.'<br>';
    for ($i = 0; $i<strlen($v);$i++){
      $graph[$key][] = (int) $v[$i];
    }
  }
  //var_dump($graph);

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




?>

  <div id="cy"></div>

  <script>

    /*var cyJson =   {
      elements: {
        nodes: [
          { data: { id: 'j', name: 'Jerry' } },
          { data: { id: 'e', name: 'Elaine' } },
          { data: { id: 'k', name: 'Kramer' } },
          { data: { id: 'g', name: 'George' } }
        ],
        edges: [
          { data: { id: 'je', source: 'j', target: 'e' } },
          { data: { source: 'jk', target: 'k' } },
          { data: { source: 'j', target: 'g' } },
          { data: { source: 'e', target: 'j' } },
          { data: { source: 'e', target: 'k' } },
          { data: { source: 'k', target: 'j' } },
          { data: { source: 'k', target: 'e' } },
          { data: { source: 'k', target: 'g' } },
          { data: { source: 'g', target: 'j' } }
        ]
      }
    };*/
    var cyJson = <?php json_encode($components);?>;

    var cy = cytoscape(
      {
        container: document.getElementById('cy'), // container to render in

        style: [ // the stylesheet for the graph
          {
            selector: 'node',
            style: {
              'background-color': '#666',
              'label': 'data(id)'
            }
          },

          {
            selector: 'edge',
            style: {
              'width': 3,
              'line-color': '#ccc',
              'target-arrow-color': '#ccc',
              'target-arrow-shape': 'triangle'
            }
          }
        ]
      }
    );

    cy.json(cyJson);
    cy.layout({
      name: 'grid',
      padding: 10,
      directed: true,
    });

    /*
     var cy = cytoscape(
     {
     container: document.getElementById('cy'), // container to render in

     elements: [ // list of graph elements to start with
     { // node a
     data: { id: 'a' }
     },
     { // node b
     data: { id: 'b' }
     },
     { // edge ab
     data: { id: 'ab', source: 'a', target: 'b' }
     }
     ],

     style: [ // the stylesheet for the graph
     {
     selector: 'node',
     style: {
     'background-color': '#666',
     'label': 'data(id)'
     }
     },

     {
     selector: 'edge',
     style: {
     'width': 3,
     'line-color': '#ccc',
     'target-arrow-color': '#ccc',
     'target-arrow-shape': 'triangle'
     }
     }
     ],

     layout: {
     name: 'grid',
     rows: 1
     }
     }
     );
     */


  </script>


<?php endif; ?>

</body>
</html>