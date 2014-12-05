<!doctype>
<html>
<head>
<title>Wall Of Power</title>
</head>
<body>

1234456
<?php

$mysqli = new mysqli("localhost", "root", "mysql01A01", "wop_booth", "3306");

if($mysqli->connect_errno){
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "\n";

echo "connected";

$query = "SELECT * FROM tvinfo ORDER BY price DESC LIMIT 1";//INSERT QUERY HERE
//$query = "SELECT * from productinfo order by price desc limit 3";//INSERT QUERY HERE
//echo "query: " . $query . "<br>";

$result = $mysqli->query($query);
echo $result;
echo "query executed.<br><br>";

for ($row_no = 0; $row_no <= $result->num_rows - 1; $row_no++) 
{
	$result->data_seek($row_no);
	$row = $result->fetch_assoc();
	$output[] = $row;
}

$respo = json_encode($output);
echo $respo;

$mysqli->close();
//echo "<br>Connection closed.<br>";

/*
Sample from earlier

$row1 = array(
     "var11" => "value11",
     "var12" => "1"
);

$row2 = array(
     "var21" => "value21",
     "var22" => "2"
);

$output = array(
     0 => $row1,
     1 => $row2
);

$jsonobject = json_encode($output);
echo $jsonobject;

$output2 = json_decode($jsonobject, true);

echo $output2[0]["var11"];

*/
?>

</body>
</html>