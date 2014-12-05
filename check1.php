   <?php
	//Description:Android Login   
    $un=htmlspecialchars($_POST['username']);  //Get username
    $pass=$_POST['password'];  //Get password


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

//for debug
	/////////////////////////////////////////////echo  "参数为". $db."和". $conn ."结束";



    $pass = stripslashes($pass);
    $pass = mysql_real_escape_string($pass);
    $pw = md5($pass);


    	//echo $un;
    	//echo $pw;




	$db_selected = mysql_select_db($db, $conn) or die ('Can\'t find database ' . mysql_error());

//add test value
	//mysql_query("INSERT INTO zach_usertable (User_Name, User_Password) VALUES ('abc123', '123')") or die ('Can\'t create value ' . mysql_error());


    //run the query to search for the username and password the match  
    $query = "SELECT * FROM zach_usertable WHERE User_Email = '$un' AND User_Password = '$pw'";
	
    $result = mysql_query($query) or die("Unable to verify user because : " . mysql_error()); 

    //echo $result . "end";
	
	//$row = mysql_fetch_array($result);
	/*$user_id=$row[0];
	$query3 = "SELECT * FROM zach_userprofile WHERE user_id = '$user_id'";
	$result3 = mysql_query($query3);
	$rows = mysql_num_rows($result3);
   
          $row2 = mysql_fetch_row($result3);
				$TV_num = $row2[4];
 			   $Fridge_num = $row2[5];
			   $DestTop_num = $row2[6];


				$myFile = "UD1.txt";
				$fh = fopen($myFile, 'w') or die("can't open file");
				$stringData = $TV_num;
				fwrite($fh, $stringData);
				fclose($fh);
				
				$myFile = "UD2.txt";
				$fh = fopen($myFile, 'w') or die("can't open file");
				$stringData = $Fridge_num;
				fwrite($fh, $stringData);
				fclose($fh);
				
				$myFile = "UD3.txt";
				$fh = fopen($myFile, 'w') or die("can't open file");
				$stringData = $DestTop_num;
				fwrite($fh, $stringData);
				fclose($fh);*/


    //If no user in the DB, check and login
    if(mysql_num_rows($result) > 0){  
    echo 1;  // for correct login response
	}	
    else{
    echo 0; // for incorrect login response
	}

	mysql_close($conn);
    ?>