
<?php
		$q=$_GET["q"];
	if($q == 1) //lamp on
		{
			$fp = fopen('http://admin:admin@128.200.55.92:86/rest/nodes/1B%207B%207%201/cmd/DFON', 'r');
			echo 1;
		}
	
	else if($q == 2)//lamp off
	{
		$fp = fopen('http://admin:admin@128.200.55.92:86/rest/nodes/1B%207B%207%201/cmd/DOF', 'r');
		echo 0;
	}
	else if($q == 3)//speaker on
	{
		$fp = fopen('http://admin:admin@128.200.55.92:86/rest/nodes/19%207C%202C%201/cmd/DFON', 'r');
		echo 1;
	}
	else if($q == 4)//speaker off
	{
			$fp = fopen('http://admin:admin@128.200.55.92:86/rest/nodes/19%207C%202C%201/cmd/DOF', 'r');
			echo 0;
	}
	else if($q == 5)//tv on
	{
			$fp = fopen('http://admin:admin@128.200.55.92:86/rest/nodes/1B%2077%206D%201/cmd/DFON', 'r');
			echo 1;
	}

	else if($q == 6)//tv off
	{
			$fp = fopen('http://admin:admin@128.200.55.92:86/rest/nodes/1B%2077%206D%201/cmd/DOF', 'r');
	  	echo 0;
	}      

?>
	