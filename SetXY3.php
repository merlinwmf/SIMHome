<?php
$x=$_POST['x'];
$y=$_POST['y'];

$x_file = "xy3.txt";
$fhx = fopen($x_file, 'w') or die("can't open file");
$stringData = $x."#".$y;
fwrite($fhx, $stringData);
fclose($fhx);

echo 1;
?>