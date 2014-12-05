   <?php
	
	/*connect test
    $myFile = "test ok.txt";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = "connect successfully username = ". $un. " password = ". $pw;
	fwrite($fh, $stringData);
	fclose($fh);
	//test over*/


    //connect to the db  
    $count2 = 0;//Defult Result to Echo.
    $host="localhost";
	$user = 'root';  
    $pswd = 'mysql01A01';  
    $db = 'wop_booth';
    //Connection	
  	 $conn = mysql_connect($host, $user, $pswd);
		if (!$conn)
		//change into create a database
		{
		 	if (mysql_query("CREATE DATABASE wop_booth",$conn))
		 	{
  				echo "Database created";
			}
			else{
				echo "Error creating database: " . mysql_error();
				}
 		}
		//mysql_close($conn);





/*
$con = mysql_connect("localhost","peter","abc123");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

if (mysql_query("CREATE DATABASE my_db",$con))
  {
  echo "Database created";
  }
else
  {
  echo "Error creating database: " . mysql_error();
  }

mysql_close($con);
*/
 //$conn = mysql_connect('localhost', $user, $pswd);  
    

    
	// make foo the current db


	$db_selected = mysql_select_db($db, $conn) or die ('Can\'t find database ' . mysql_error());

//add test value
	//mysql_query("INSERT INTO zach_usertable (User_Name, User_Password) VALUES ('abc123', '123')") or die ('Can\'t create value ' . mysql_error());


    //run the query to search for the username and password the match  
    $query = "SELECT * from tvinfo order by price desc limit 3";
	
    $result = mysql_query($query) or die("Unable to verify user because : " . mysql_error()); 

    
		$rows = array();
		while($r = mysql_fetch_assoc($result)) {
 		   $rows[] = $r;
		}
		$jresult = json_encode($rows);
		echo $jresult;
		/*print $jresult."\n";


			mysql_close($conn);

		$resp = json_decode($jresult,true);
		var_dump($resp);
		echo 1;
		$seg = $resp[0]["model"];
		echo $seg;*/

    ?>