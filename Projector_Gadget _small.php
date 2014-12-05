<!DOCTYPE>

<head>
<?php
function GetAllData() {
$user = 'root';  
	$pswd = 'mysql01A01';  
	$db = 'smart_meter_reading';  
	$conn = mysql_connect('localhost', $user, $pswd);  
	mysql_select_db($db, $conn);
	$query = "select Consumption from smart_meter_reading.housemeterdemo order by Timestamp ASC";
	$result = mysql_query($query);
	while($row = mysql_fetch_array($result))
	{
		echo round($row[0]);
		echo ",";
	}	
}
function GetConsumptionData(){
	$user = 'root';  
	$pswd = 'calplug2012';  
	$db = 'smart_meter_reading';  
	$conn = mysql_connect('localhost', $user, $pswd);  
	mysql_select_db($db, $conn);
	$query = "select Consumption from smart_meter_reading.housemeterdemo order by Timestamp ASC";
	$result = mysql_query($query);
	$prev = 0;
	$counter = 0;
	while($row = mysql_fetch_array($result))
	{
		if ($counter <= 1){ // ignore the first element and use the second element as bases
			$prev = intval($row[0]) / 1000;
			$counter++;
		}
		else {
			$value = intval($row[0]) / 1000 - $prev;
			if ($value < 0) {
				$value = -$value;
			}
			if ($value > 10) {
				$value = 0;
			}
			echo round($value,4);
			echo ",";
			$prev = intval($row[0]) / 1000;
		}
	}
}
function GetDateData(){
	$user = 'root';  
	$pswd = 'calplug2012';  
	$db = 'smart_meter_reading';  
	$conn = mysql_connect('localhost', $user, $pswd);  
	mysql_select_db($db, $conn);
	$query = "select TimeStamp from smart_meter_reading.housemeterdemo order by Timestamp ASC";
	$result = mysql_query($query);
	$prev = 0;
	$counter = 0;
	while($row = mysql_fetch_array($result))
	{
		if ($counter <= 1){ // ignore the first element (which is empty) and use the second element as bases
			$counter++;
		}
		else {
			$val = $row[0];
			$tok = strtok($val, "- :");
			while ($tok !== false) {
    			echo intval($tok);
				echo ",";
    			$tok = strtok("- :");
			}
		}
	}		
}
?>

<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>Wall Of Power</title>

<script src="./resources/js/raphael.2.1.0.min.js"></script>
<script src="./resources/js/justgage.1.0.1.min.js"></script>
<script src="./resources/js/dygraph-combined.js"></script>
<script src="./resources/js/jquery-1.8.2.js"></script>
<script src="./resources/js/highcharts.js"></script>
<script src="./resources/js/modules/exporting.js"></script>

<script language="javascript" type="text/javascript" src="./resources/js/jgauge-0.3.0.a3.js"></script> 
<script language="javascript" type="text/javascript" src="./resources/js/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="./resources/js/jQueryRotate.min.js"></script>
<script language="javascript" type="text/javascript" src="./resources/js/jquery.flot.stack.js"></script>
		
<style type="text/css">
<!--
body {
	background-image: url(black.jpg);
	text-align: center;
}
#g1,#g2,#g3,#g4,#g5,#g6 {
        width:250px; height:120px;
        display: inline-block;
        margin: 1em;
      }
#Layer1 {
	position:absolute;
	width:250px;
	height:50px;
	z-index:5;
	left: 50px;
	top: 115px;
}
#Layer2 {
	position:absolute;
	width:50px;
	height:50px;
	z-index:0;
	left: 1100px;
	top: 400px;
}
#Layer3 {
	position:absolute;
	width:214px;
	height:389px;
	z-index:1;
	left: 1620px;
	top: 528px;
}

#Layer4 {
	position:absolute;
	width:214px;
	height:389px;
	z-index:1;
	left: 1220px;
	top: 375px;
}
#Layer5 {
	position:absolute;
	width:50px;
	height:50px;
	z-index:0;
	left: 430px;
	top: 375px;
}

#Layer6 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:7;
	left: 136px;
	top: 250px;
}
#Layer7 {
	position:absolute;
	width:200px;
	height:115px;
	z-index:7;
	left: 1317px;
	top: 31px;
	color:#F7FE2E;
	font-size:80px;
}
-->
</style>

<script>

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
/*         var g1 = new JustGage({
          id: "g1", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
          title: "Virtual TV",
          titleFontColor: "#000000",
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
          title: "Virtual STB",
            shadowOpacity: 1,
          shadowSize: 0,
          shadowVerticalOffset: 5,
		  showMinMax: false,  
        });
        */ 
        var g3 = new JustGage({
          id: "g3", 
          value: getRandomInt(0, 100), 
          min: 0,
          max: 100,
		  valueFontColor: "#FFFFFF",
          title: "Set Top Box",
          titleFontColor: "#FFFFFF",
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
		  setInterval(function() {
/* 		  GetVD1Power();
		  GetVD2Power();
		  GetVD3Power(); */
		  GetTvPower();
		  GetLampPower();
		  GetSpeakerPower();
		  GetRestPower();
		  GetTotalPower();

/* 		  g1.refresh(VD1);
		  g2.refresh(VD2);*/
		  g3.refresh(16); 
		  g4.refresh(TvPower);
		  g5.refresh(LampPower);
		  g6.refresh(SpeakerPower);
		  GetTime();
        }, 2500); //2.5 sec
		
      };
///////////////////
//flow

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
		yaxis: { min: 0, max: 300 },
        xaxis: { show: false},

    };
    var plot = $.plot($("#placeholder_4"), [ getData() ], options);

    function update() {
        plot.setData([ getData() ]);
        // since the axes don't change, we don't need to call plot.setupGrid()
        plot.draw();
        
        setTimeout(update, updateInterval);
		
    }
	update();
	
});
///////////////////////
//total
function GetTime(){

		today = new Date();
		time = today.getHours();
		//alert(time);
		
		if(time>=10 && time<=18)
		{
		 document.getElementById('Layer7').innerHTML="PEAK";
		 document.getElementById('Layer7').style.color = "#0C4685";
		 document.getElementById('Layer7').style.fontSize="40px";
		 document.getElementById('Layer7').style.fontFamily= "Arial";
		 document.getElementById('MoneyPosition').innerHTML=money;
		}
		else
		{	
		  document.getElementById('Layer7').innerHTML="OFF-PEAK";
		  document.getElementById('Layer7').style.color = "#000000";
		  document.getElementById('Layer7').style.fontSize="40px";
		  document.getElementById('Layer7').style.fontFamily= "Arial";	
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

///////////////////////////////
function GetTvPower(){

	var xmlhttp;
	var str = 2;
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
////////////////////////////////
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
////////////////////////////////////

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

//////////////////////////////////
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
///////////////////////////////////
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
				
}
/////////////////////////////////////////////
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
		if(VD2==1)
			VD2 = 14;
		
}
//////////////////////////////////////////////
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
		
		all = (parseInt(TotalPower)+parseInt(VD1)+parseInt(VD2)+parseInt(VD3));
		//alert(RestPower);
		money = (parseInt(RestPower)/3600*0.18+parseInt(all)*30*(8*0.2+16*0.16)*0.25/1000).toFixed(2);
		//alert(money);
		//alert(all);
}
///////////////////////////////////////////
 </script>

 	<?php
	function printData(){
		$user = 'root';  
		$pswd = 'calplug2012';  
		$db = 'smart_meter_reading';  
		$conn = mysql_connect('localhost', $user, $pswd);  
		mysql_select_db($db, $conn);
		$query = "select Consumption from smart_meter_reading.housemeterdemo where Timestamp like \"%58%\" order by Timestamp ASC";
		$result = mysql_query($query);
		$temp = 0;
		$counter = 0;
		while($row = mysql_fetch_array($result))
		{
			if ($counter==0){
				echo 0.289;//hardcoded estimation for the initial value
				echo ",";
				$temp = intval($row[0]) / 1000;
				$counter++;
			}
			else {
				$value = intval($row[0]) / 1000 - $temp;
				echo round($value,4);
				echo ",";
				$temp = intval($row[0]) / 1000;
			}
		};
		
		
		//echo "7,8,8,1,2,3,4,5,5,5,6,6,6,6,6,7,8,8,1,2,3,4,5,5,5,6,6,6,6,6,7,8,8,1,2,3,4,5,5,5,6,6,6,6,67,8,8,1,2,3,4,5,5,5,6,6,6,6,67,8,8,1,2,3,4,5,5,5,6,6,6,6,67,8,8,1,2,3,4,5,5,5,6,6,6,6,6";
	}
	?>
	<script>
			var date = new Date();
		var now = Date.now();
		//alert(Date.UTC(2013, 3, 03));

	</script>
	
<div id="container" style="width: 800px; height: 300px; top:680px; left:0px;position:absolute;z-index:9; background-color:rgba(200,200,200,0)"></div>


<script type="text/javascript">
var makeDrag_flag = false;
var makeDrop_objs = [];
function makeDrop(obj)
{
	makeDrop_objs.push(obj);	
}
function makeDrag(obj)
{
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

function getObjPos(obj)
{
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

function getCurPos(e)
{
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
// this block is used to display the consumption curve
/*var date = new Date();
var daysDisplay = 30;
var startYear;
var startMonth;
var startDate;
var startUTCDate;

startMonth = date.getMonth();
startYear = date.getFullYear();
if (date.getDate() >= daysDisplay) {
	startDate = date.getDate() - daysDisplay + 1;	
} else {
	startMonth--;
	if (startMonth == -1) {
		startMonth = 11;
		startYear--;
	}
	startDate = getDaysInMonth(date.getMonth()-1, date.getFullYear()) - (daysDisplay - date.getDate()) + 1;
}
startUTCDate = Date.UTC(startYear, startMonth, startDate, 0,0,0,0);


var dateDetail = new Date();
var daysDisplayDetail = 7;
var startYearDetail;
var startMonthDetail;
var startDateDetail;
var startUTCDateDetail;

startMonthDetail = date.getMonth();
startYearDetail = date.getFullYear();
if (date.getDate() >= daysDisplayDetail) {
	startDateDetail = date.getDate() - daysDisplayDetail + 1;	
} else {
	startMonthDetail--;
	if (startMonthDetail == -1) {
		startMonthDetail = 11;
		startYearDetail--;
	}
	startDateDetail = getDaysInMonth(date.getMonth(), date.getFullYear()) - (daysDisplayDetail - date.getDate()) + 1;
}
startUTCDateDetail = Date.UTC(startYearDetail, startMonthDetail, startDateDetail, 0,0,0,0);

console.log("NowDate= %s,%s,%s",date.getFullYear(),date.getMonth()+1,date.getDate());
console.log("setStart= %s,%s,%s",startYear,startMonth+1,startDate);
console.log("setStartDetail= %s,%s,%s",startYearDetail,startMonthDetail+1,startDateDetail);

var consumption = [<?php GetConsumptionData() ?>];	
var timeStamp = [<?php GetDateData() ?>];
var data = new Array();
var dataLow = new Array();
var dataMedium = new Array();
var dataHigh = new Array();
var dataHighest = new Array();
var dataLowest = new Array();


var average = 0;
var indexData = 0, dataNumber = 0;
for (var i = 0; i < consumption.length; ++i) {
	timeStamp[6*i+1]--;
	var curDate = Date.UTC(timeStamp[6*i], timeStamp[6*i+1],timeStamp[6*i+2],timeStamp[6*i+3],timeStamp[6*i+4],timeStamp[6*i+5],0);
	if (curDate > startUTCDate) {	
		data[indexData++] = [curDate, consumption[i]];
		average += consumption[i];
		dataNumber ++ ;
	}
}
var curUTCDate = Date.UTC(timeStamp[6*consumption.length-6], timeStamp[6*consumption.length-5],timeStamp[6*consumption.length-4],timeStamp[6*consumption.length-3],timeStamp[6*consumption.length-2],timeStamp[6*consumption.length-1],0);

console.log('ifDate'+timeStamp[0], timeStamp[1]+1,timeStamp[2],timeStamp[3],timeStamp[4],timeStamp[5]);
console.log('curDate'+timeStamp[6*consumption.length-6], timeStamp[6*consumption.length-5]+1,timeStamp[6*consumption.length-4],timeStamp[6*consumption.length-3],timeStamp[6*consumption.length-2],timeStamp[6*consumption.length-1]);


average /= dataNumber;
var lowaverage = average / 3;

var indexLow = 0, indexMedium = 0, indexHigh = 0;
var maxConsumption = 0, minConsumption = 0;
var maxConsumptionTime = 0, minConsumptionTime = data[0][1];
for (var i = 0; i < data.length; ++i) {
	
	if (data[i][1] > maxConsumption) {
		maxConsumption = data[i][1];
		maxConsumptionTime = data[i][0];
	} 
	if (data[i][1] <= minConsumption) {
		minConsumption = data[i][1];
		minConsumptionTime = data[i][0];
	}
	if (data[i][1] > average) {
		dataMedium[indexMedium++] = [data[i][0], average];
	}
	else{dataMedium[indexMedium++] = data[i];}
	
	if (data[i][1] > lowaverage) {
		dataLow[indexLow++] = [data[i][0], lowaverage];
	}
	else{dataLow[indexLow++] = data[i];}

}   
    
    //dataLow[0] = [data[0][0], minConsumption];
    //dataLow[1] = [data[data.length - 1][0], minConsumption];
	dataHighest[0] = [maxConsumptionTime, maxConsumption];
	dataLowest[0] = [minConsumptionTime, minConsumption];

    $(document).ready(function() {
    Highcharts.theme = {
    colors: ["#DDDF0D", "#7798BF", "#55BF3B", "#DF5353", "#aaeeee", "#ff0066", "#eeaaee",
        "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
    chart: {
        backgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
                [0, 'rgb(96, 96, 96)'],
                [1, 'rgb(16, 16, 16)']
            ]
        },
        borderWidth: 0,
        borderRadius: 15,
        plotBackgroundColor: null,
        plotShadow: false,
        plotBorderWidth: 0
    },
    title: {
        style: {
            color: '#FFF',
            font: '16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
        }
    },
    subtitle: {
        style: {
            color: '#DDD',
            font: '12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
        }
    },
    xAxis: {
        gridLineWidth: 0,
        lineColor: '#999',
        tickColor: '#999',
        labels: {
            style: {
                color: '#999',
                fontWeight: 'bold'
            }
        },
        title: {
            style: {
                color: '#AAA',
                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
            }
        }
    },
    yAxis: {
        alternateGridColor: null,
        minorTickInterval: null,
        gridLineColor: 'rgba(255, 255, 255, .1)',
        minorGridLineColor: 'rgba(255,255,255,0.07)',
        lineWidth: 0,
        tickWidth: 0,
        labels: {
            style: {
                color: '#999',
                fontWeight: 'bold'
            }
        },
        title: {
            style: {
                color: '#AAA',
                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif'
            }
        }
    },
    legend: {
        itemStyle: {
            color: '#CCC'
        },
        itemHoverStyle: {
            color: '#FFF'
        },
        itemHiddenStyle: {
            color: '#333'
        }
    },
    labels: {
        style: {
            color: '#CCC'
        }
    },
    tooltip: {
        backgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
                [0, 'rgba(96, 96, 96, .8)'],
                [1, 'rgba(16, 16, 16, .8)']
            ]
        },
        borderWidth: 0,
        style: {
            color: '#FFF'
        }
    },


    plotOptions: {
        series: {
            shadow: true
        },
        line: {
            dataLabels: {
                color: '#CCC'
            },
            marker: {
                lineColor: '#333'
            }
        },
        spline: {
            marker: {
                lineColor: '#333'
            }
        },
        scatter: {
            marker: {
                lineColor: '#333'
            }
        },
        candlestick: {
            lineColor: 'white'
        }
    },

    toolbar: {
        itemStyle: {
            color: '#CCC'
        }
    },

    navigation: {
        buttonOptions: {
            symbolStroke: '#DDDDDD',
            hoverSymbolStroke: '#FFFFFF',
            theme: {
                fill: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0.4, '#606060'],
                        [0.6, '#333333']
                    ]
                },
                stroke: '#000000'
            }
        }
    },

    // scroll charts
    rangeSelector: {
        buttonTheme: {
            fill: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0.4, '#888'],
                    [0.6, '#555']
                ]
            },
            stroke: '#000000',
            style: {
                color: '#CCC',
                fontWeight: 'bold'
            },
            states: {
                hover: {
                    fill: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0.4, '#BBB'],
                            [0.6, '#888']
                        ]
                    },
                    stroke: '#000000',
                    style: {
                        color: 'white'
                    }
                },
                select: {
                    fill: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0.1, '#000'],
                            [0.3, '#333']
                        ]
                    },
                    stroke: '#000000',
                    style: {
                        color: 'yellow'
                    }
                }
            }
        },
        inputStyle: {
            backgroundColor: '#333',
            color: 'silver'
        },
        labelStyle: {
            color: 'silver'
        }
    },

    navigator: {
        handles: {
            backgroundColor: '#666',
            borderColor: '#AAA'
        },
        outlineColor: '#CCC',
        maskFill: 'rgba(16, 16, 16, 0.5)',
        series: {
            color: '#7798BF',
            lineColor: '#A6C7ED'
        }
    },

    scrollbar: {
        barBackgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0.4, '#888'],
                    [0.6, '#555']
                ]
            },
        barBorderColor: '#CCC',
        buttonArrowColor: '#CCC',
        buttonBackgroundColor: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0.4, '#888'],
                    [0.6, '#555']
                ]
            },
        buttonBorderColor: '#CCC',
        rifleColor: '#FFF',
        trackBackgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
                [0, '#000'],
                [1, '#333']
            ]
        },
        trackBorderColor: '#666'
    },

    // special colors for some of the demo examples
    legendBackgroundColor: 'rgba(48, 48, 48, 0.8)',
    legendBackgroundColorSolid: 'rgb(70, 70, 70)',
    dataLabelsColor: '#444',
    textColor: '#E0E0E0',
    maskColor: 'rgba(255,255,255,0.3)'
};

// Apply the theme
var highchartsOptions = Highcharts.setOptions(Highcharts.theme);
    
        // create the master chart
        function createMaster() {
            masterChart = $('#master-container').highcharts({
                chart: {
                    reflow: false,
                    borderWidth: 0,
                    backgroundColor: null,
                    marginLeft: 50,
                    marginRight: 20,
                    zoomType: 'x',
                    events: {
    
                        // listen to the selection event on the master chart to update the
                        // extremes of the detail chart
                        selection: function(event) {
                            var extremesObject = event.xAxis[0],
                                min = extremesObject.min,
                                max = extremesObject.max,
                                detailData = [],
                                detailData2 = [],
                                detailData3 = [],
                                detailData4 = [],
                                xAxis = this.xAxis[0];
    
                            // reverse engineer the last part of the data
                            jQuery.each(this.series[0].data, function(i, point) {
                                if (point.x > min && point.x < max) {
                                    detailData.push({
                                        x: point.x,
                                        y: point.y
                                    });
                                }
                            });
                            jQuery.each(this.series[1].data, function(i, point) {
                                if (point.x > min && point.x < max) {
                                    detailData2.push({
                                        x: point.x,
                                        y: point.y
                                    });
                                }
                            });
                            jQuery.each(this.series[2].data, function(i, point) {
                                if (point.x > min && point.x < max) {
                                    detailData3.push({
                                        x: point.x,
                                        y: point.y
                                    });
                                }
                            });
                            jQuery.each(this.series[3].data, function(i, point) {
                                if (point.x > min && point.x < max) {
                                    detailData4.push({
                                        x: point.x,
                                        y: point.y
                                    });
                                }
                            });

    
                            // move the plot bands to reflect the new detail span
                            xAxis.removePlotBand('mask-before');
                            xAxis.addPlotBand({
                                id: 'mask-before',
                                from: startUTCDate,
                                to: min,
                                color: 'rgba(0, 0, 0, 0.2)'
                            });
    
                            xAxis.removePlotBand('mask-after');
                            xAxis.addPlotBand({
                                id: 'mask-after',
                                from: max,
                                to: curUTCDate,
                                color: 'rgba(0, 0, 0, 0.2)'
                            });
    
    
                            detailChart.series[0].setData(detailData);
                            detailChart.series[1].setData(detailData2);
                            detailChart.series[2].setData(detailData3);
                            detailChart.series[3].setData(detailData4);
    
                            return false;
                        }
                    }
                },
                title: {
                    text: null
                },
                xAxis: {
                    type: 'datetime',
                    showLastTickLabel: true,
                    maxZoom:  12 * 3600000, // half day
                    plotBands: [{
                        id: 'mask-before',
                        from: startUTCDate,
                        to: curUTCDate,
                        color: 'rgba(0, 0, 0, 0.2)'
                    }],
                    title: {
                        text: null
                    }
                },
                yAxis: {
                    gridLineWidth: 0,
                    labels: {
                        enabled: false
                    },
                    title: {
                        text: null
                    },
                    //min: 0.6,
                    showFirstLabel: false
                },
                tooltip: {
                    formatter: function() {
                        return false;
                    }
                },
                legend: {
                    enabled: true
                },
                credits: {
                    enabled: false
                },
                plotOptions: {







           column: {
                   pointWidth: 10,
                   borderColor:'transparent',
                },
            line: {
                    dataLabels: {
                        enabled: true,
                        style: {
                            textShadow: '0 0 3px white, 0 0 3px white'
                        }
                    },
                    enableMouseTracking: false
                },













                    series: {
                        fillColor: {
                            linearGradient: [0, 0, 0, 70],
                            stops: [
                                [0, '#4572A7'],
                                [1, 'rgba(0,0,0,0)']
                            ]
                        },
                        lineWidth: 1,
                        marker: {
                            enabled: true
                        },
                        shadow: false,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        enableMouseTracking: false
                    }
                },
    
                series: [{
                    type: 'column',
                    name: 'Over Consumption',
                    pointInterval: 15 * 60 * 1000,
                    pointStart: startUTCDate,
					color: '#CC6600', 
                    data: data
                },{
                    type: 'column',
                    name: 'Below Consumption',
                    pointInterval: 15 * 60 * 1000,
                    pointStart: startUTCDate,
					color: '#228B22', 
                    data: dataMedium
                },{
                    type: 'column',
                    name: 'Low Consumption',
                    pointInterval: 15 * 60 * 1000,
                    pointStart: startUTCDate,
					color: '#00FFFF',
                    data: dataLow
                },{
                    type: 'line',
                    name: 'MAX',
                    pointInterval: 1 * 3600 * 1000,
                    pointStart: maxConsumptionTime,
					color: '#DF0101', 
                    data: dataHighest
                }],
    
                exporting: {
                    enabled: false
                }
    
            }, function(masterChart) {
                createDetail(masterChart)
            })
            .highcharts(); // return chart instance
        }
    
        // create the detail chart
        function createDetail(masterChart) {
    
            // prepare the detail chart
            var detailData = [],
                detailData2 = [],
                detailData3 = [],
                detailData4 = [],
                detailStart = startUTCDateDetail;
    
            jQuery.each(masterChart.series[0].data, function(i, point) {
                if (point.x >= detailStart) {
                    detailData.push(point.y);
                }
            });
            jQuery.each(masterChart.series[1].data, function(i, point) {
                if (point.x >= detailStart) {
                    detailData2.push(point.y);
                }
            });
            jQuery.each(masterChart.series[2].data, function(i, point) {
                if (point.x >= detailStart) {
                    detailData3.push(point.y);
                }
            });
            jQuery.each(masterChart.series[3].data, function(i, point) {
                if (point.x >= detailStart) {
                    detailData4.push(point.y);
                }
            });
    
            // create a detail chart referenced by a global variable
            detailChart = $('#detail-container').highcharts({
                chart: {
                    marginBottom: 120,
                    reflow: false,
					zoomType: 'x',
                    marginLeft: 50,
                    marginRight: 20,
                    style: {
                        position: 'absolute'
                    }
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Energy Consumption'
                },
                subtitle: {
                    text: 'Select an area by dragging across the lower chart'
                },
                xAxis: {
                    type: 'datetime'
                },
                yAxis: {
                    title: {
				text: 'kWH',
				style: {
					fontWeight: 'bold',
					fontSize: '20px' 
				}	
			},
                    maxZoom: 0.1
                },
                tooltip: {
                    formatter: function() {
                        var point = this.points[0];
                        var point2 = this.points[1];
                        return '<b>'+ point.series.name +'</b><br/>'+
                            Highcharts.dateFormat('%A %B %e %Y %H:%M:%S', this.x) + '<br/>'+
                            'Energy Consumption : '+ Highcharts.numberFormat(point.y, 2)+ '<br/>'+ point.series.name +
                            'Average Consumption = '+ Highcharts.numberFormat(point2.y, 2);
                    },
                    shared: true
                },
                legend: {
                    enabled: false,
					margin: 200,
					color: "black",
					
                },
                plotOptions: {
           column: {
                   pointWidth: 10,
                   borderColor:'transparent',
                },
            line: {
                    dataLabels: {
                        enabled: true,
                        style: {
                            textShadow: '0 0 3px white, 0 0 3px white'
                        }
                    },
                    enableMouseTracking: false
                },
				series: {
                        marker: {
                            enabled: true,
                            states: {
                                hover: {
                                    enabled: true,
                                    radius: 3
                                }
                            }
                        }
                    }
                },
                series: [{
                    type: 'column',
                    name: 'High Consumption',
                    pointStart: detailStart,
                    pointInterval: 15 * 60 * 1000,
					color: '#CC6600', 
                    data: detailData
                },{
                    type: 'column',
                    name: 'Average Consumption',
                    pointStart: detailStart,
                    pointInterval: 15 * 60 * 1000,
					color: '#228B22', 
                    data: detailData2
                },{
                    type: 'column',
                    name: 'Low Consumption',
                    pointStart: detailStart,
                    pointInterval: 15 * 60 * 1000,
					color: '#00FFFF',
                    data: detailData3
                },{
                    type: 'line',
                    name: 'Maximum Consumption',
                    pointStart: maxConsumptionTime,
                    pointInterval: 1 * 3600 * 1000,
					color: '#DF0101', 
                    data: 0
                }],
    
                exporting: {
                    enabled: true
                }
    
            }).highcharts(); // return chart
        }
    
        // make the container smaller and add a second container for the master chart
        var $container = $('#container')
            .css('position', 'relative');
    
        var $detailContainer = $('<div id="detail-container">')
            .appendTo($container);
    
        var $masterContainer = $('<div id="master-container">')
            .css({ position: 'absolute', top: 300, height: 130, width: '100%' })
            .appendTo($container);
    
        // create master and in its callback, create the detail chart
        createMaster();
    });
    

function getDaysInMonth(month, year) {
	switch(month) {
		case 0: return 31;
		case 1: if ((year % 100 != 0 && year % 4 == 0) || (year % 400 == 0))
					return 29;
				else
					return 28;
		case 2: return 31;
		case 3: return 30;
		case 4: return 31;
		case 5: return 30;
		case 6: return 31;
		case 7: return 31;
		case 8: return 30;
		case 9: return 31;
		case 10: return 30;
		case 11: return 31;	
	}
}*/
</script>


</head>
<body  onclick="SetPicture()" id="xxx">

<!-- img src="./resources/img/Xmas_santasleigh.gif"  height="400" width="500" style="position: absolute; top: 700px; left: 900px; z-index: 0;"><br -->

<div id="container" style="width: 1500px; height: 500px; margin: 0 auto; background-color:rgba(200,200,200,0)"></div>
<div id="Layer2">
  <table width="481" height="253" border="0" >
    <tr>
      <td width="800" height="400"><div id="placeholder_4" style="width:1px;height:1px;"  align="left"></div></td> 
    </tr>
  </table>
</div>
<div id="Layer3"><td><div id='g3'></div></td></div>
<div id="Layer4"><td><div id='g4'></div></td></div>
<div id="Layer5"><td><div id='g5'></div></td></div>
<div id="Layer6"><td><div id='g6'></div></td></div>

<script type="text/javascript">
//register drag events
var Drag_Gage3 = document.getElementById('Layer3');
var Drag_Gage4 = document.getElementById('Layer4');
var Drag_Gage5 = document.getElementById('Layer5');
var Drag_Gage6 = document.getElementById('Layer6');

makeDrag(Drag_Gage3);
makeDrag(Drag_Gage4);
makeDrag(Drag_Gage5);
makeDrag(Drag_Gage6);

var dropItem = document.getElementById('drop');
makeDrop(dropItem);

</script>

</body>

</html>
