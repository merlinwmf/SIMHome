<?php
function GetXY(){
	$myFile = "XY1.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, 100);
	fclose($fh);
	return $theData;
}

$q=$_GET["q"];
if ($q == true)
$value = GetXY();
echo $value;
?>