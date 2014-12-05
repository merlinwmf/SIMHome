   <?php
    ob_start();
    $host="localhost"; 
    $username="root"; 
    $password="mysql01A01"; 
    $db_name="wop_booth";

    echo  $password;

   //Connection 
     $conn = mysql_connect($host, $username, $password);
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

    $db_selected = mysql_select_db($db_name, $conn) or die ('Can\'t find database ' . mysql_error());

    $userFirstName=$_POST['user_firstName'];
    $userFirstName = stripslashes($userFirstName);
    $userFirstName = mysql_real_escape_string($userFirstName);

    $userLastName=$_POST['user_lastName'];
    $userLastName = stripslashes($userLastName);
    $userLastName = mysql_real_escape_string($userLastName);
  
    $userEmailAdd=$_POST['user_emailAdd'];
    $userEmailAdd = stripslashes($userEmailAdd);
    $userEmailAdd = mysql_real_escape_string($userEmailAdd);

    $pass=$_POST['user_password'];
    $pass = stripslashes($pass);
    $pass = mysql_real_escape_string($pass);
    $pass = md5($pass);
	

	
    $sql = "INSERT INTO zach_usertable (`User_FirstName`, `User_LastName`, `User_Email`, `User_Password`) VALUES ('$userFirstName', '$userLastName', '$userEmailAdd', '$pass');";
	$result=mysql_query($sql) or die("INSERT failed: " . mysql_error());
	if (mysql_error() === 1022) {
	echo 0;}  //If the username already exists
	else{
	echo 1;
		}
	ob_end_flush();
    ?>