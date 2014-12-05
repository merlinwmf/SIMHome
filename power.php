<?php
/*require_once 'db_connect.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);

if (!$db_server) die ("Unable to connect to MySQL:".mysql_error());

mysql_select_db($db_database, $db_server)
 or die("Unable to select database:" . mysql_error());
*/
//total
/*function REST_request1($device_id)
{

	$request = "http://admin:admin@128.200.55.92:86/rest/nodes/".$device_id." 1";

	$response = simplexml_load_file($request);

    if ($response === False)
        return False;
    else
        return $response;
}


function REST_result1(){

	$result = REST_request1("19 87 B8"); // not installed

	$attribute = (string)$result->node->property->attributes()->value;
	
	return $attribute;

}
////////////////////////////////////////////////////////////////////////////////
//tv
*/
function REST_request2($device_id)
{

	$request = "http://admin:admin@128.200.55.92:86/rest/nodes/".$device_id." 1";

	$response = simplexml_load_file($request);

    if ($response === False)
        return False;
    else
        return $response;

}


function REST_result2(){

	$result = REST_request2("19 8A EE"); // TV-Meter

	$attribute = (string)$result->node->property->attributes()->value;
	
	return $attribute;

}
////////////////////////////////////////////////////////////////////////////////////
//lamp
function REST_request3($device_id)
{

	$request = "http://admin:admin@128.200.55.92:86/rest/nodes/".$device_id." 1";

	$response = simplexml_load_file($request);

    if ($response === False)
        return False;
    else
        return $response;

}


function REST_result3(){

	$result = REST_request3("19 88 73"); // Lamp-Meter

	$attribute = (string)$result->node->property->attributes()->value;
	
	return $attribute;

}
///////////////////////////////////////////////////////////////////////////////////////
//speaker
function REST_request16($device_id)
{

	$request = "http://admin:admin@192.168.1.110:80/rest/nodes/".$device_id." 1";

	$response = simplexml_load_file($request);

    if ($response === False)
        return False;
    else
        return $response;

}


function REST_result16(){

	$result = REST_request3("19 8A 1B"); // Speaker-Meter

	$attribute = (string)$result->node->property->attributes()->value;
	
	return $attribute;

}
/////////////////////////////////////////////////////////////////////////////////////
//vd_1
function vd1(){
	$myFile = "VD1_State.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, 1);
	fclose($fh);

// Let's make the swich dynamic each 30seconds on/off
/*	$today = getDate();
	$time = $today["seconds"];
	$theData = 0;
	if($time>=0 && $time<=30){
		$theData = 1;
	}
*/
// Finish of dynamics function

	return $theData;
}
		
		

/////////////////////////////////////////////////////////////////////////////////////
//vd_2
function vd2(){
	$myFile = "VD2_State.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, 1);
	fclose($fh);

	return $theData;
}
/////////////////////////////////////////////////////////////////////////////////////
//vd_3
function vd3(){
	$myFile = "VD3_State.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, 1);
	fclose($fh);
	
	// Let's make the swich dynamic each 30seconds on/off
	/*
	$today = getDate();
	$time = $today["seconds"];
	$theData = 0;
	if($time>=20 && $time<=59){
		$theData = 1;
	}
	*/
	// Finish of dynamics function

	return $theData;
}
/////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/*function REST_request($device_id)
{

	$request = "http://admin:admin@128.200.55.92:86/rest/nodes/".$device_id." 1";

	$response = simplexml_load_file($request);

    if ($response === False)
        return False;
    else
        return $response;

}
function REST_result(){

	$result = REST_request("19 87 B8"); // Not-Installed
	
	$properties = $result->properties;
	$property = $properties->property[1];

	$attribute = (string)$property->attributes()->value;
	
	//$attribute = (string)$result->properties->property->attributes()->value;
	
	return $attribute;

}
*/
/////////////////////////////////////////////////////////////////////////////////////
//UD1
function UD1(){
	$myFile = "UD1.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, 2);
	fclose($fh);
	return $theData;
}

////////////////////////////////////
function UD2(){
	$myFile = "UD2.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, 2);
	fclose($fh);
	return $theData;
}
/////////////////////////////////////////////////////////////////////////////////////
function UD3(){
	$myFile = "UD3.txt";
	$fh = fopen($myFile, 'r');
	$theData = fread($fh, 2);
	fclose($fh);
	return $theData;
}
////////////////////////////////////////////////////////////

$q=$_GET['q'];
if($q == 1){
	$value = REST_result1();
	echo $value;
}
else if($q == 2)
{
	$value = REST_result2();
	echo $value;
}
else if($q == 3)
{
	$value = REST_result3();
	echo $value;
}
else if($q == 16)
{
	$value = REST_result16();
	echo $value;
}
else if($q == 4)
{
	$value = vd1();
	echo $value;
}
else if($q == 17)
{
	$value = vd3();
	echo $value;
}
else if($q == 5)
{
	$value = vd2();
	echo $value;
}
else if($q == 6)
{
	$value = REST_result();
	echo $value;
}

else if($q == 7)
{
	$value = UD1();
	$query = "SELECT * FROM tv WHERE id = '$value'";
	if($value == "99")
		echo"99";
	else{
		$result_tv = mysql_query($query);
   	$row = mysql_fetch_row($result_tv);
    $tv_made = $row[1];
    $tv_model = $row[2];
    $tv_watt = $row[4];
    $sign = "#";
    $sign2 = "*";
    $display = $tv_made.$sign.$tv_model.$sign2.$tv_watt;
    echo "$display";
  }
}

else if($q == 8)
{
	$value = UD2();
	$query = "SELECT * FROM stb WHERE id = '$value'";
	if($value == "99")
		echo"99";
	else{
		$result_stb = mysql_query($query);
   	$row = mysql_fetch_row($result_stb);
    $stb_made = $row[1];
    $stb_model = $row[2];
    $stb_watt = $row[4];
    $sign = "#";
    $sign2 = "*";
    $display = $stb_made.$sign.$stb_model.$sign2.$stb_watt;
    echo "$display";
  }
}
else if($q == 9)
{
	$value = UD3();
	$query = "SELECT * FROM desktop WHERE id = '$value'";
	if($value == "99")
		echo"99";
	else{
		$result_desktop = mysql_query($query);
   	$row = mysql_fetch_row($result_desktop);
    $desktop_made = $row[1];
    $desktop_model = $row[2];
    $desktop_watt = $row[4];
    $sign = "#";
    $sign2 = "*";
    $display = $desktop_made.$sign.$desktop_model.$sign2.$desktop_watt;
    echo "$display";
  }
}
else if($q == 10) //vd1=1
{
	$myFile = "VD1_State.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = 1;
	fwrite($fh, $stringData);
	fclose($fh);
	echo true;	
}

else if($q == 11) //vb1=0
{
	$myFile = "VD1_State.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = 0;
	fwrite($fh, $stringData);
	fclose($fh);
	echo true;	
}
else if($q == 12) //vb2=1
{
	$myFile = "VD2_State.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = 1;
	fwrite($fh, $stringData);
	fclose($fh);
	echo true;	
}
else if($q == 13) //vb2=0
{
	$myFile = "VD2_State.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = 0;
	fwrite($fh, $stringData);
	fclose($fh);
	echo true;	
}
else if($q == 14) //vb3=1
{
	$myFile = "VD3_State.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = 1;
	fwrite($fh, $stringData);
	fclose($fh);
	echo true;	
}
else if($q == 15) //vb3=0
{
	$myFile = "VD3_State.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = 0;
	fwrite($fh, $stringData);
	fclose($fh);
	echo true;	
}

?>