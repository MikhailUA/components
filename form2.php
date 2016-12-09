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
  <label>Введите список смежности графа <?php echo $v.'x'.$v; ?></label>
  <ol start="0">
    <?php
    for($i = 0;$i<$v;$i++){
      echo '<li><input type="text" name="vertexConnections[]" value="'.$_POST['vertexConnections'][$i].'" </li>';
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

var_dump($graph);


function applyConnections($graph,$vertexConnections){
  foreach ($vertexConnections as $vertexId => $connections){
    $connections = explode(',',trim($connections));
    foreach ($connections as $c){
      if(!empty($c)){
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