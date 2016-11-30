<?php


$f = fopen('bin2.txt','w');

$data = 1;
$data2 = 1;

$packed =  pack('i',$data);
$packed2 =  pack('i',$data2);

fwrite($f,$packed);
fwrite($f,$packed2);
fclose($f);

//var_dump($read);

$f = fopen('bin2.txt','rb');
$r = fread($f,8);
//$r2 = fread($f,4);

$d = unpack('i',$r);
//$d2 = unpack('i',$r2);

fclose($f);
var_dump($r);

var_dump($d);

//var_dump($d2);



$v0 = '000101';
$f = fopen('v0','w');

?>


