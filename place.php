<body onload="SetPicture()"></body>
<img src="one.gif" id="soare1" style="position:absolute;top:100px;left:100px; "><br>
<img src="one.gif" id="soare2" style="position:absolute;top:200px;left:100px; "><br>
<img src="one.gif" id="soare3" style="position:absolute;top:300px;left:100px; "><br>
<script type="text/javascript">
	
var y = 0;
var y1 = 0;
var y2 = 0;
var userWidth = window.screen.width;
var tv_x = 0;
var tv_y = 0;
var stb_x = 0;
var stb_y = 0;
var lt_x = 0;
var lt_y = 0;


function SetPicture(){
	var time1 = setInterval("movePicture1()",50);
	var time2 = setInterval("GetPoision1()",50);
	var time3 = setInterval("movePicture2()",50);
	var time4 = setInterval("GetPoision2()",50);
	var time5 = setInterval("movePicture3()",50);
	var time6 = setInterval("GetPoision3()",50);
}
////////////////////////////////////////////
//picture1
function GetPoision1(){

	var xmlhttp;
	var str = true;
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
			y = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","XY1.php?q="+str,true);
		xmlhttp.send();   
		var count1 = y.indexOf("#");
		tv_x = parseInt(y.substr(0,count1));
		tv_y = parseInt(y.substr(count1+1));

}

function movePicture1() {
var pp = document.getElementById("soare1");
var lft = parseInt(pp.style.left);
lft = (tv_x+0)*16/9; 
to = (tv_y+0)*16/9;
pp.style.left = lft+"px";
pp.style.top = to+"px";
}
///////////////////////////////////////////////////
//picture2
function GetPoision2(){

	var xmlhttp;
	var str = true;
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
			y1 = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","XY2.php?q="+str,true);
		xmlhttp.send();   
		var count1 = y1.indexOf("#");
		stb_x = parseInt(y1.substr(0,count1));
		stb_y = parseInt(y1.substr(count1+1));

}

function movePicture2() {
var pp = document.getElementById("soare2");
var lft = parseInt(pp.style.left);
lft = (stb_x+0)*16/9; 
to = (stb_y+0)*16/9;
pp.style.left = lft+"px";
pp.style.top = to+"px";
}
////////////////////////////////////////////
//picture3

function GetPoision3(){

	var xmlhttp;
	var str = true;
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
			y2 = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","XY3.php?q="+str,true);
		xmlhttp.send();   
		var count1 = y2.indexOf("#");
		lt_x = parseInt(y2.substr(0,count1));
		lt_y = parseInt(y2.substr(count1+1));
}

function movePicture3() {
var pp = document.getElementById("soare3");
var lft = parseInt(pp.style.left);
lft = (lt_x+0)*16/9; 
to = (lt_y+0)*16/9;
pp.style.left = lft+"px";
pp.style.top = to+"px";
}
/////////////////////////////////////
</script>