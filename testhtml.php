<!doctype>
<html>
<head>

<title>Wall Of Power</title>
<script type="text/javascript">
function GetVD1Power(){

	var xmlhttp;
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			VD1 = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","power.php",true);
	xmlhttp.send();
		
}
</script>

</head>
<!--<body  onclick="SetPicture()" id="xxx">-->

<body>
<?php
echo $
?>

</body>

</html>
