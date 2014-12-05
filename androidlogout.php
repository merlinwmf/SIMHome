   <?php
	//Description:Android Logout   
    //connect to the db  
    $count2 = 0;//Defult Result to Echo.
	$user = 'root';  
    $pswd = 'calplug2012';  
    $db = 'wop_booth';
    //Connection	
    $conn = mysql_connect('127.0.0.1:3306', $user, $pswd);  
    mysql_select_db($db, $conn);
	
	$query2 = "delete from user_current";
	$result2 = mysql_query($query2);
	//$count2 = mysql_num_rows($result2);
	
	echo 1;//Delete the current users.
	mysql_close($con);
    ?>