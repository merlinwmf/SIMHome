<!doctype>
<html>
<head>
<meta content="ie=10.000" http-equiv="x-ua-compatible">
<meta http-equiv="content-type" content="text/html; charset=utf-8"> 

<title>Wall Of Power</title>

<link href="./resources/text/style.css" rel="stylesheet" type="text/css"> 
<link href="./resources/text/jquery-ui.css" rel="stylesheet" type="text/css"> 
<link href="./resources/text/ui.notify.css" rel="stylesheet" type="text/css">

<style type="text/css">form input { display:block; width:250px; margin-bottom:5px }</style>

<script src="./resources/js/raphael.2.1.0.min.js"></script>
<script src="./resources/js/justgage.1.0.1.min.js"></script>
<script src="./resources/js/dygraph-combined.js"></script>
<script src="./resources/js/jquery-1.8.2.js"></script>
<script src="./resources/js/highcharts.js"></script>
<script src="./resources/js/modules/exporting.js"></script>
<script src="./resources/js/jquery.js" type="text/javascript"></script>
<script src="./resources/js/jquery-ui.js" type="text/javascript"></script>
<script src="./resources/js/jquery.notify.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript" src="resources/js/jgauge-0.3.0.a3.js"></script> 
<script language="javascript" type="text/javascript" src="resources/js/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="resources/js/jQueryRotate.min.js"></script>
<script language="javascript" type="text/javascript" src="resources/js/jquery.flot.stack.js"></script>

<style type="text/css">
<!--
body {
	background-image: url(0.jpg);
	text-align: center;
}
#g1,#g2,#g3,#g4,#g5,#g6,#g7,#g8 {
        width:150px; height:120px;
        display: inline-block;
        margin: 1em;
      }
#Layer1 {
	position:absolute;
	width:200px;
	height:50px;
	z-index:1;
	left: 900px;
	top: 232px;
}
#Layer2 {
	position:absolute;
	width:516px;
	height:266px;
	z-index:2;
	left: 1358px;
	top: -64px;
}
#Layer3 {
	position:absolute;
	width:400px;
	height:115px;
	z-index:3;
	left: 1034px;
	top: 30px;
}
#Layer4 {
	position:absolute;
	width:300px;
	height:100px;
	z-index:20;
	left: 332px;
	top: 165px;
}
#Layer5 {
	position:absolute;
	width:200px;
	height:507px;
	z-index:5;
	left: 380px;
	top: 285px;
}

#Layer6 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:6;
	left: 0px;
	top: 642px;
}
#Layer7 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:7;
	left: 1126px;
	top: 134px;
	color:#F7FE2E;
	font-size:80px;
}
#Layer8 {
	position:absolute;
	width:200px;
	height:50px;
	z-index:8;
	left: 50px;
	top: 280px;
}
#Layer12 {
	position:absolute;
	width:200px;
	height:50px;
	z-index:12;
	left: 1000px;
	top: 652px;
}
#Layer13 {
	position:absolute;
	width:100px;
	height:100px;
	z-index:13;
	left: 132px;
	top: 514px;
}
#Layer14 {
	position:absolute;
	width:200px;
	height:50px;
	z-index:14;
	left: 1310px;
	top: 295px;
}
#Layer15 {
	position:absolute;
	width:50px;
	height:50px;
	z-index:0;
	left: 1580px;
	top: 320px;
}

-->
</style>

<script> <!-- Get Power -->
var TotalPower = 0;
var TvPower = 0;
var LampPower = 0;
var SpeakerPower = 0;
var VD1 = 0;
var VD2 = 0;
var VD3 = 0;
var all = 0;
var RestPower = 0;
var money = 0;
var time = 0;
      window.onload = function(){
        var g1 = new JustGage({
          id: "g1", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
		  // Virtual TV
          title: " ",
          titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
		  showMinMax: false,   
          shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,   
		});
	    
		var g2 = new JustGage({
          id: "g2", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
		  //Virtual STB
          title: " ",
		  titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
          shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,
		  showMinMax: false,  
        });
        
        var g3 = new JustGage({
          id: "g3", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
		  //Virtual Mac
          title: " ",
		  titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
          showMinMax: false, 
          shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,		  
        }); 
		    var g4 = new JustGage({
          id: "g4", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
          title: "TV",
          titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
          showMinMax: false, 
		  shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,     
        });
		    var g5 = new JustGage({
          id: "g5", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
          title: "Lamp",
          titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
          showMinMax: false,  
		  shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,   
        });
		    var g6 = new JustGage({
          id: "g6", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
          title: "Speaker",
          titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
          showMinMax: false,        
          shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,
		}); 
		var g7 = new JustGage({
          id: "g7", 
          // value: getRandomInt(0, 20), 
          value: 12,
		  min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
		  //Virtual Fridge
          title: " ",
          titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
          showMinMax: false,        
          shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,
		}); 
		var g8 = new JustGage({
          id: "g8", 
          // value: getRandomInt(0, 100), 
          value: 25,
		  min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
		  //Virtual Lamp
          title: " ",
          titleFontColor: "#FFFFFF",
		  label: "Watts",
		  labelFontColor: "#FFFFFF",
          showMinMax: false,        
          shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,
		}); 
		setInterval(function() {
		  GetVD1Power();
		  GetVD2Power();
		  GetVD3Power();
		  GetTvPower();
		  GetLampPower();
		  GetSpeakerPower();
		  //GetRestPower();
		  //GetTotalPower();

		  g1.refresh(VD1);
	      g2.refresh(VD2);
		  g3.refresh(VD3);
		  g4.refresh(TvPower);
		  g5.refresh(LampPower);
		  g6.refresh(SpeakerPower);
		  GetTime();
        }, 2500); //2.5 sec
		  
		setInterval(function() {

        }, 9000); //5 sec
	document.getElementById('Layer8').style.display='none'
		
      };
///////////////////
//Flow
$(function () {

    var data = [], totalPoints = 300;
    var data2 = [];
		var y = 0;

		var tmin, tmax;
	
    function getData() {
		if (data.length > 0)
            data = data.slice(1);
			
        // do a random walk

				PD_all=y;
       
        while (data.length < totalPoints) {
			data.push(all);
			console.log(all);
        }
		
        // zip the generated y values with the x values
        var res = [];
    for(var m = 0; m <= data.length; m++)
        data2 [m] = data[data.length-m-1];
		for (var i = 0; i <= data.length; ++i)		
        res.push([i, data2[i]]);
		return res;
    }

    // setup control widget
    var updateInterval = 50;//millisecond
    
    // setup plot
    var options = {
		series: { label: "Total Power (W)", color: "#FFFF00", lines: { show: true, fill: true }},
        yaxis: { min: 0, max: 300, color: "#FFFFFF"},
        xaxis: { show: false},

    };
    var plot = $.plot($("#placeholder_4"), [ getData() ], options);
	$("#placeholder_4 div.legend table").css("color", "#FFFFFF");
	$("#placeholder_4 div.legend table").css("background", "opacity", "0");
	
    function update() {
        plot.setData([ getData() ]);
        // since the axes don't change, we don't need to call plot.setupGrid()
        plot.draw();
        
        setTimeout(update, updateInterval);
		
    }
	update();
	
});
///////////////////////
//Get Total Power
function GetTime(){

		today = new Date();
		time = today.getHours();
		//alert(time);
		
		if(time>=10 && time<=18)
		{
		  document.getElementById('Layer7').innerHTML="Peak";
		  document.getElementById('Layer7').style.color = "#FFFF00";
		  document.getElementById('Layer7').style.fontSize="50px";
		  document.getElementById('Layer7').style.fontFamily= "Arial";
		  document.getElementById('Layer7').style.align= "right";
		  document.getElementById('MoneyPosition').innerHTML=money;
		}
		else
		{	
		  document.getElementById('Layer7').innerHTML="Off-Peak";
		  document.getElementById('Layer7').style.color = "#FFFF00";
		  document.getElementById('Layer7').style.fontSize="50px";
		  document.getElementById('Layer7').style.fontFamily= "Arial";
		  document.getElementById('Layer7').style.align= "right";
		  document.getElementById('MoneyPosition').innerHTML=money;	
		}
}
function GetTotalPower(){

	var xmlhttp;
	var str = 1;
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
			TotalPower = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","power.php?q="+str,true);
		xmlhttp.send();
}
function GetTvPower(){

	var xmlhttp;
	var str = 3;
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
			TvPower = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","power.php?q="+str,true);
		xmlhttp.send();
}
function GetLampPower(){

	var xmlhttp;
	var str = 3;
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
			LampPower = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","power.php?q="+str,true);
		xmlhttp.send();
}
function GetSpeakerPower(){

	var xmlhttp;
	var str = 16;
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
			SpeakerPower = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","power.php?q="+str,true);
		xmlhttp.send();
}
function GetRestPower(){

	var xmlhttp;
	var str = 6;
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
			RestPower = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","power.php?q="+str,true);
		xmlhttp.send();
}

/*
function GetVD1Power(){

	today = new Date();
	time = today.getSeconds();
	//alert(time);
		if(time>=0 && time<=30){
			VD1 = 60;
			document.getElementById('soare1').src= "tv_on.png";
		}
		if(time>1.5 && time<=59){	
			document.getElementById('soare1').src= "tv_off.png";	
		}
}
*/
function GetVD1Power(){

	var xmlhttp;
	var str = 4;
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
	xmlhttp.open("GET","power.php?q="+str,true);
	xmlhttp.send();
		if(VD1==1)
		{
			VD1 = 42;
	     	document.getElementById('soare1').src= "tv_on.png";
		}
		else
		{
			VD1 = 0;
			document.getElementById('soare1').src= "tv_off.png";
		}

}

function GetVD2Power(){

	var xmlhttp;
	var str = 5;
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
			VD2 = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","power.php?q="+str,true);
		xmlhttp.send();
		if(VD2==1){
			VD2 = 16;
			}
		else{
		VD2 = 0;
		}
		
}
function GetVD3Power(){

	var xmlhttp;
	var str = 17;
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
			VD3 = xmlhttp.responseText;
		}
	}
		xmlhttp.open("GET","power.php?q="+str,true);
		xmlhttp.send();
		if(VD3==1)
			{
				VD3 = 55;
				document.getElementById('soare3').src= "laptop_on.png";
			}
		else{
			VD3 = 0;
			document.getElementById('soare3').src= "laptop_off.png";
		}
		all = (parseInt(LampPower)+parseInt(TvPower)+parseInt(SpeakerPower)+parseInt(VD1)+parseInt(VD2)+parseInt(VD3)+12+25);
		console.log(all,LampPower,TvPower,SpeakerPower,parseInt(LampPower),parseInt(TvPower),parseInt(SpeakerPower),parseInt(VD1),parseInt(VD2),parseInt(VD3));
		//alert(RestPower);
		money = (parseInt(RestPower)/3600*0.18+parseInt(all)*30*(8*0.2+16*0.16)*0.25/1000).toFixed(2);
		//alert(money);
		//alert(all);
}

function  changeImagetv(){



	var xmlhttp;
	var strr = 4;
	var strw0 = 11;
	var strw1 = 10;
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
	xmlhttp.open("GET","power.php?q="+strr,true);
	xmlhttp.send();
		if(VD1==1)
		{
			//document.getElementById('soare1').src= "tv_off.png";
			VD1 = 0;
	     	
	     	xmlhttp.open("GET","power.php?q="+strw0,true);
			xmlhttp.send();
		}
		else
		{
			//document.getElementById('soare1').src= "tv_on.png";
			VD1 = 42;
			
			xmlhttp.open("GET","power.php?q="+strw1,true);
			xmlhttp.send();
		}




}

function  changeImagestb(){



	var xmlhttp;
	var strr = 5;
	var strw0 = 13;
	var strw1 = 12;
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
			VD2 = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","power.php?q="+strr,true);
	xmlhttp.send();
		if(VD2==1)
		{
			VD2 = 0;
	     	xmlhttp.open("GET","power.php?q="+strw0,true);
			xmlhttp.send();
		}
		else
		{
			VD2 = 16;
			xmlhttp.open("GET","power.php?q="+strw1,true);
			xmlhttp.send();
		}



	


}

function  changeImagelaptop(){
	

	var xmlhttp;
	var strr = 17;
	var strw0 = 15;
	var strw1 = 14;
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
			VD3 = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","power.php?q="+strr,true);
	xmlhttp.send();
		if(VD3==1)
		{

			VD3 = 0;
	     	//document.getElementById('soare3').src= "laptop_off.png"
	     	xmlhttp.open("GET","power.php?q="+strw0,true);
			xmlhttp.send();
		}
		else
		{
			VD3 = 55;
			//document.getElementById('soare3').src= "laptop_on.png";
			xmlhttp.open("GET","power.php?q="+strw1,true);
			xmlhttp.send();
		}




}
</script>

<script type="text/javascript"> // Make Drag-able
var makeDrag_flag = false;
var makeDrop_objs = [];
function makeDrop(obj){
makeDrop_objs.push(obj);	
}
function makeDrag(obj){
	obj.onmousedown = function (e)
	{
		if (!document.all) e.preventDefault();
		var oPos = getObjPos(obj);
		var cPos = getCurPos(e);
		makeDrag_flag = true;
		document.onmouseup = function (e){
			makeDrag_flag = false;
			document.onmousemove = null;
			document.onmouseup = null;			
			var nPos = getCurPos(e);			
			for(i = 0; i < makeDrop_objs.length; i++)
			{
				var tg = makeDrop_objs[i];
				var tPos = getObjPos(tg);
				var tg_w = tg.offsetWidth;
				var tg_h = tg.offsetHeight;
				if ((nPos.x > tPos.x) && (nPos.y > tPos.y) && (nPos.x < tPos.x + tg_w) && (nPos.y < tPos.y + tg_h))
				{
					tg.innerHTML += '<p>' + obj.innerHTML + 'ик?ии?</p>'
					obj.style.display = 'none';
				}
			}
	    };
		document.onmousemove = function (e)
		{
			if (makeDrag_flag)
			{
				obj.style.position = 'absolute';
				var Pos = getCurPos(e);
				obj.style.left = Pos.x - cPos.x + oPos.x + 'px';
				obj.style.top = Pos.y - cPos.y + oPos.y + 'px';
			}
			return false;
		}
	}
}
function getObjPos(obj){
	var x = y = 0;
	if (obj.getBoundingClientRect)
	{
		var box = obj.getBoundingClientRect();
		var D = document.documentElement;
		x = box.left + Math.max(D.scrollLeft, document.body.scrollLeft) - D.clientLeft;
		y = box.top + Math.max(D.scrollTop, document.body.scrollTop) - D.clientTop;		
	}
	else
	{
		for(; obj != document.body; x += obj.offsetLeft, y += obj.offsetTop, obj = obj.offsetParent );
	}
	return {'x':x, 'y':y};
}
function getCurPos(e){
	e = e || window.event;
	var D = document.documentElement;
	if (e.pageX) return {x: e.pageX, y: e.pageY};
	return {
		x: e.clientX + D.scrollLeft - D.clientLeft,
		y: e.clientY + D.scrollTop - D.clientTop	
	};
}
</script>


<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0

		document.getElementById('soare1').src= "tv_on.png";


//eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
//if (restore) selObj.selectedIndex=0;
}
//-->
</script>

</head>
<!--<body  onclick="SetPicture()" id="xxx">-->

<body  id="xxx">

<img src="./tv_off.png" id="soare1" onclick="changeImagetv()" style="position: absolute; top: 374px; left: 660px; z-index: 8;"><br>
<img src="./stb.png" id="soare2" onclick="changeImagestb()" style="position: absolute; top: 695px; left: 816px; z-index: 8;"><br>
<img src="./laptop_off.png" id="soare3" onclick="changeImagelaptop()" style="position: absolute; top: 649px; left: 103px; z-index: 8;"><br>


<img src="./resources/img/energy_star_logo.png" height="100" width="100" style="position: fixed; bottom: 10px; right: 10px; z-index: 8;"><br>
<img src="./resources/img/amazon_logo.png" height="100" width="220" style="position: fixed; bottom: 10px; right: 140px; z-index: 8;"><br>
<img src="./resources/img/cnet_logo.png" height="100" width="100" style="position: fixed; bottom: 10px; right: 780px; z-index: 8;"><br>


<!--
<img src="./resources/img/energy_star_logo.png" height="100" width="100" style="position: absolute; bottom: 10px; right: 10px; z-index: 8;"><br>
<img src="./resources/img/amazon_logo.png" height="100" width="220" style="position: absolute; bottom: 10px; right: 140px; z-index: 8;"><br>
<img src="./resources/img/cnet_logo.png" height="100" width="100" style="position: absolute; bottom: 10px; right: 780px; z-index: 8;"><br>
-->


<form action="" method="get" style="position: absolute; top: 340px; left: 580px; z-index: 8;"> 
<label><font color=white>TV</font></label> 
<select name="TVMenu" id="TVMenu"
 onchange="MM_jumpMenu('parent',this,0)"> 
<option value="http://www.DIVCSS5.com/">Model1</option> 
<option value="http://www.divcss5.com/">Model2</option> 
</select> 
</form> 


<form action="" method="get" style="position: absolute; top: 650px; left: 730px; z-index: 8;"> 
<label><font color=white>setbox</font></label> 
<select name="stbMenu" id="stbMenu"
 onchange="MM_jumpMenu('parent',this,0)"> 
<option value="http://www.DIVCSS5.com/">Model1</option> 
<option value="http://www.divcss5.com/">Model2</option> 
</select> 
</form> 

<form action="" method="get" style="position: absolute; top: 500px; left: 200px; z-index: 8;"> 
<label><font color=white>laptop</font></label> 
<select name="laptopMenu" id="laptopMenu"
 onchange="MM_jumpMenu('parent',this,0)"> 
<option value="http://www.DIVCSS5.com/">Model1</option> 
<option value="http://www.divcss5.com/">Model2</option> 
</select> 
</form> 
<!--
<img src="./resources/img/treeXmas_4.gif"  height="300" width="300" style="position: absolute; top: 500px; left: 1300px; z-index: 0;"><br>
<img src="./resources/img/Xmas_snowflake2.gif"  height="250" width="250" style="position: absolute; top: 10px; left: 900px; z-index: 8;"><br>
-->

<div id="Layer1">
  <table width="300" height="100" border="0">
    <tr align="center">
      <td width="45" height="60"><div id='g1' ></div></td>
    </tr>
  </table>
</div> 
<div id="Layer2">
  <table width="481" height="253" border="0" >
    <tr>
      <td width="800" height="400"><div id="placeholder_4" style="width:500px;height:200px;"  align="right"></div></td>
      
    </tr>
  </table>
</div>
<div id="Layer3">
<b style="color:#FFFF00; font-size: 60px; text-align:center;font-family: Arial;
	font-weight:bold;">$
	<b id="MoneyPosition" style="color:#FFFF00; font-size: 63px; text-align:center;font-family: Arial;
	font-weight:bold;">0.00</b> </b></div>
<div id="Layer4">
  <table width="300" height="100" border="0">
    <tr align="center">
      <td width="45"><div id='g8'></div></td>
    </tr>
  </table>
</div> 
<div id="Layer5"><img src="./resources/img/lamp_on.png"></div>
<div id="Layer6"><img src="./resources/img/couch.png"></div>
<div id="Layer7">
<b style="font-size: 50px; text-align:center;font-family: Arial;font-weight:bold;">Peak</b></div>
<div id="Layer8">
  <table width="300" height="100" border="0">
    <tr align="center">
      <td height="0"><div id='g4'></div></td>
      <td><div id='g5'></div></td>
      <td><div id='g6'></div></td>
    </tr>
  </table>
</div> 
<div id="Layer12">
  <table width="300" height="100" border="0">
    <tr align="center">
      <td width="45"><div id='g2' ></div></td>
    </tr>
  </table>
</div> 
<div id="Layer13">
  <table width="300" height="100" border="0">
    <tr align="center">
      <td width="45"><div id='g3'></div></td>
    </tr>
  </table>
</div> 
<div id="Layer14">
  <table width="300" height="100" border="0">
    <tr align="center">
      <td width="45"><div id='g7'></div></td>
    </tr>
  </table>
</div> 
<div id="Layer15"><img src="./resources/img/refrigerator.png"></div>

<script type="text/javascript"> //register drag events
var Drag_Gage1 = document.getElementById('Layer1');
var Drag_Gage2 = document.getElementById('Layer12');
var Drag_Gage3 = document.getElementById('Layer13');
var Drag_Gage7 = document.getElementById('Layer14');
var Drag_Gage8 = document.getElementById('Layer4');

var Drag_Chart = document.getElementById('Layer2');
var Drag_Fee = document.getElementById('Layer3');
var Drag_Lamp = document.getElementById('Layer5');
var Drag_Couch = document.getElementById('Layer6');
var Drag_Peak = document.getElementById('Layer7');

makeDrag(Drag_Gage1);
makeDrag(Drag_Gage2);
makeDrag(Drag_Gage3);
makeDrag(Drag_Gage7);
makeDrag(Drag_Gage8);

makeDrag(Drag_Chart);
makeDrag(Drag_Fee);
makeDrag(Drag_Lamp);
makeDrag(Drag_Couch);
makeDrag(Drag_Peak);

var dropItem = document.getElementById('drop');
makeDrop(dropItem);
</script>

<script type="text/javascript"> // Notification
function create( template, vars, opts ){
	return $container.notify("create", template, vars, opts);
}

$(function (){
	// initialize widget on a container, passing in all the defaults.
	// the defaults will apply to any notification created within this
	// container, but can be overwritten on notification-by-notification
	// basis.
	$container = $("#container").notify();
	
	//create two when the pg loads
    //create("default", { title:'Welcome to CalPlug', text:'Wall of Power'});
	//create("sticky", { title:'Sticky Notification', text:'Example of a "sticky" notification.  Click on the X above to close me.'},{ expires:false });
	
	// second---calls the tv notification function below
	setInterval(Product1Notification, 45000);
	setInterval(Product2Notification, 55000);
	setInterval(Product3Notification, 65000);
	setInterval(Product4Notification, 75000);
	
	// bindings for the examples
	$("#default").click(function(){
		create("default", { title:'Default Notification', text:'Example of a default notification.  I will fade out after 5 seconds'});
	});
	
	$("#sticky").click(function(){
		create("sticky", { title:'Sticky Notification', text:'Example of a "sticky" notification.  Click on the X above to close me.'},{ expires:false });
	});
	
	$("#warning").click(function(){
		create("withIcon", { title:'Warning!', text:'OMG the quick brown fox jumped over the lazy dog.  You\'ve been warned. <a href="#" class="ui-notify-close">Close me.</a>', icon:'alert.png' },{ 
			expires:false
		});
	});
	
	$("#themeroller").click(function(){
		create("themeroller", { title:'Warning!', text:'The <code>custom</code> option is set to false for this notification, which prevents the widget from imposing it\'s own coloring.  With this option off, you\'re free to style however you want without changing the original widget\'s CSS.' },{
			custom: true,
			expires: false
		});
	});
	
	$("#clickable").click(function(){
		create("default", { title:'Clickable Notification', text:'Click on me to fire a callback. Do it quick though because I will fade out after 5 seconds.'}, {
			click: function(e,instance){
				alert("Click triggered!\n\nTwo options are passed into the click callback: the original event obj and the instance object.");
			}
		});
	});
	
	$("#buttons").click(function(){
		var n = create("buttons", { title:'Confirm some action', text:'This template has a button.' },{ 
			expires:false
		});
		
		n.widget().delegate("input","click", function(){
			n.close();
		});
	});
	
	// second---calls the tv notification function below

	setInterval(startNotifications, 1000);
});	

function startNotifications(){
	today = new Date();
	time = today.getSeconds();
	//alert(time);
		if(time==0){
			tvNotification1();
			setTimeout(tvNotification2,5000);
			setTimeout(tvNotification3,8000);
			setTimeout(tvNotification2,16000);
			setTimeout(tvNotification3,19000);	
			
			setTimeout(rfNotification1,30000);
			setTimeout(rfNotification2,35000);
			setTimeout(rfNotification3,38000);
			setTimeout(rfNotification2,46000);
			setTimeout(rfNotification3,49000);
		}
}

function tvNotification1(){
	var container = $("#container-TV").notify({ stack:'above'});
	container.notify("create", { 
		title:'Look ma, two containers!', 
		text:'This container is positioned on the bottom of the screen.  Notifications will stack on top of each other with the <code>position</code> attribute set to <code>above</code>.' 
	},{ expires:30000 });

}

function tvNotification2(){
		var container = $("#container-TV").notify({ stack:'above'});
		container.notify("create", 1, { title:'Another Notification!', text:'The quick brown fox jumped over the lazy dog.' },{ expires:5000 });
}

function tvNotification3(){
		var container = $("#container-TV").notify({ stack:'above'});
		container.notify("create", 2, { title:'Another Notification!', text:'The quick brown fox jumped over the lazy dog.' },{ expires:5000 });
}
	
function rfNotification1()
{
	var container2 = $("#container-rf").notify({ stack:'below' });
	container2.notify("create", { 
		title:'Look ma, two containers!', 
		text:'This container is positioned on the bottom of the screen.  Notifications will stack on top of each other with the <code>position</code> attribute set to <code>above</code>.' 
	},{
  expires: 30000,
});
}

function rfNotification2(){
		var container = $("#container-rf").notify({ stack:'below'});
		container.notify("create", 1, { title:'Another Notification!', text:'The quick brown fox jumped over the lazy dog.' },{ expires:5000 });
}

function rfNotification3(){
		var container = $("#container-rf").notify({ stack:'below'});
		container.notify("create", 2, { title:'Another Notification!', text:'The quick brown fox jumped over the lazy dog.' },{ expires:5000 });
}


function Product1Notification()
{
	create("Product1", { title:'Product1!', text:'The <code>custom</code> option is set to false for this notification, which prevents the widget from imposing it\'s own coloring. With this option off, you\'re free to style however you want without changing the original widget\'s CSS.' },{
		custom: false,
		expires: 35000
	});
}

function Product2Notification()
{
	create("Product2", { title:'Product2!', text:'The <code>custom</code> option is set to false for this notification, which prevents the widget from imposing it\'s own coloring. With this option off, you\'re free to style however you want without changing the original widget\'s CSS.' },{
		custom: false,
		expires: 35000
	});
}

function Product3Notification()
{
	create("Product3", { title:'Product3!', text:'The <code>custom</code> option is set to false for this notification, which prevents the widget from imposing it\'s own coloring. With this option off, you\'re free to style however you want without changing the original widget\'s CSS.' },{
		custom: false,
		expires: 35000
	});
}

function Product4Notification()
{
	create("Product4", { title:'Product4!', text:'The <code>custom</code> option is set to false for this notification, which prevents the widget from imposing it\'s own coloring. With this option off, you\'re free to style however you want without changing the original widget\'s CSS.' },{
		custom: false,
		expires: 35000
	});
}
</script>

<div id="content">

	<!-- form style="margin: 20px 0px;"><input id="default" type="button" value="open with default configuration"><input id="sticky" type="button" value='create a "sticky" notification'><input id="warning" type="button" value="use icons in your templates"><input id="buttons" type="button" value="or buttons even"><input id="clickable" type="button" value="the entire notification can be clicked on"><input id="themeroller" type="button" value="themeroller support"></form -->
	
	<!--- container to hold notifications, and default templates --->
	<div id="container" style="margin: 0px 0px 10px 10px; left: 0px; top: 0px; bottom: auto; text-align:left">
		<div id="default">
		<h1>#{title}</h1>
		<p>#{text}</p></div>
		
		<div id="sticky"><a class="ui-notify-close ui-notify-cross" href="http://www.erichynds.com/examples/jquery-notify/index.htm#">x</a>
		<h1>#{title}</h1>
		<p>#{text}</p></div>
		
		<div class="ui-state-error" id="Product1" style="background-color:#086A87; border-color:#086A87"><a 
			class="ui-notify-close" href="http://www.erichynds.com/examples/jquery-notify/index.htm#">
			<span class="ui-icon ui-icon-close" style="float: right;"></span>
			</a>
			<span class="ui-icon ui-icon-alert" style="margin: 2px 5px 0px 0px; float: left;"></span>
			<h1 style="font-size: 22px;"> Hewlett-Packard ENVYx2</h1>
			<p style="font-size: 17px;">Sleep power use (watts): 1.1 <br />
			Annual Energy Use: 4.3 kWh/year <br />
			Lifetime Energy Saving: 4.3 kWh/year <br />
			Price: $499.00   /  Rating: 4.2/5.0 <br />
			<!-- <p style="text-align: center;"><a class="ui-notify-close" href="http://www.erichynds.com/examples/jquery-notify/index.htm#">close me</a></p> -->
		</div>
		
		<div class="ui-state-error" id="Product2" style="background-color:#088A88; border-color:#088A88; font-color:#FFFFFF"><a 
			class="ui-notify-close" href="http://www.erichynds.com/examples/jquery-notify/index.htm#">
			<span class="ui-icon ui-icon-close" style="float: right;"></span>
			</a>
			<span class="ui-icon ui-icon-alert" style="margin: 2px 5px 0px 0px; float: left;"></span>
			<h1 style="font-size: 22px;">Asko D5644</h1>
			<p style="font-size: 17px;">   Top Efficient Dishwasher<br />
			Annual Energy Use: 212 kilowatt hours <br />
			Lifetime Cost Savings: $80-$179 <br />
			Lifetime Energy Saving: 996 kWh <br />
			Price: $1,699  /  Rating: 3.2/5.0 <br />

		</div>
		
		<div class="ui-state-error" id="Product3" style="background-color:#8A4B08; border-color:#8A4B08"><a 
			class="ui-notify-close" href="http://www.erichynds.com/examples/jquery-notify/index.htm#">
			<span class="ui-icon ui-icon-close" style="float: right;"></span>
			</a>
			<span class="ui-icon ui-icon-alert" style="margin: 2px 5px 0px 0px; float: left;"></span>
			<h1 style="font-size: 22px;">LG	60LA7400</h1>
			<p style="font-size: 17px;">   Top Efficient Large TV<br />
			Annual Energy Use: 106 kWh/yr <br />
			Lifetime Cost Savings: $31-$70 <br />
			Lifetime Energy Saving: 389.7 kWh <br />
			Price: $1,799   /   Rating: 4.6/5.0 <br />

		</div>
		
		<div class="ui-state-error" id="Product4" style="background-color:#585858; border-color:#585858"><a 
			class="ui-notify-close" href="http://www.erichynds.com/examples/jquery-notify/index.htm#">
			<span class="ui-icon ui-icon-close" style="float: right;"></span>
			</a>
			<span class="ui-icon ui-icon-alert" style="margin: 2px 5px 0px 0px; float: left;"></span>
			<h1 style="font-size: 22px;">Samsung WF455AGS</h1>
			<p style="font-size: 17px;">   Top Efficient Clothes Washer<br />
			Annual Energy Use: 90 kWh <br />	
			Lifetime Cost Savings: $153-$346 <br />
			Lifetime Energy Saving: 1,920 kWh <br />	
			Price: $1,049.00   /   Rating: 3.1/5.0 <br />


		</div>
				
		<div id="withicon"><a class="ui-notify-close ui-notify-cross" href="http://www.erichynds.com/examples/jquery-notify/index.htm#">x</a> 
		<!--<div style="margin: 0px 10px 0px 0px; float: left;"><img alt="warning" src="./resources/index.htm"></div>-->
		<h1>#{title}</h1>
		<p>#{text}</p></div>
		
		<div id="buttons">
		<h1>#{title}</h1>
		<p>#{text}</p>
		<p style="text-align: center; margin-top: 10px;"><input class="confirm" type="button" value="close dialog">
		</p></div>
	</div>
	
	<!--- second container -  bottom notifications --->	 
	<div id="container-TV" style="margin: 0px 0px 0px 0px; left: 645px; top: auto; bottom: 730px; background-color:#000000; text-align:left">
		<div>
			<h1 style="font-size: 22px;">Westinghouse UW32S3PW</h1>
			<p style="font-size: 18px;">Annual Energy Use: 42.7 kWh/yr <br />	
			Lifetime Cost Savings: $17.1-$38.5 <br />
			Lifetime Energy Saving: 207.2 kWh <br />	
		</div>
		<div>
			<h1 style="font-size: 18px;">Amazon Price: $499.00</h1>
		</div>
		<div>
			<h1 style="font-size: 18px;">CNET Rating: 4.2/5.0</h1>
		</div>
	</div>

	
</div>

<div id="content2">

	<div id="container-rf" style="margin: 0px 0px 10px 10px; left: 1170px; top: 420px; bottom: auto; text-align:right">
		<div>
			<h1 style="font-size: 22px;">Whirlpool WRT771RWY</h1>
			<p style="font-size: 18px;">Annual Energy Use: 364 kWh/yr <br />	
			Lifetime Cost Savings: $151.7-$341.3 <br />
			Lifetime Energy Saving: 1,896 kWh <br />	
		</div>
		<div>
			<h1 style="font-size: 18px;">Amazon Price: $559.00</h1>
		</div>
		<div>
			<h1 style="font-size: 18px;">CNET Rating: 4.4