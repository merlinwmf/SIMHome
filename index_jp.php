<!doctype html>
<html>
<head>
<style>
    @font-face {font-family:rockwell;src:url(fonts/RockwellStd.otf);}
	body{
        font-family: "rockwell";
        font-weight:bold;
        font-size: 40px;
        color: #E9BE00;
		background-image:url('img/CNN tv background.jpg'); //temporary by ZT
		
	}
	div{
		border:hidden;
        text-align:center;
			
/*
		border:#000;
		border-width:thin;
		border-style:dashed;
        border-radius:5px;		
 */
	}
	table {
		text-align:center;
		border-collapse:collapse;
		margin:auto;
    }	
    p {
        margin:0; padding:0;
    }
	#mainFrame {
		//background: #5C5C5C;
		background: rgba(92,92,92,0.65);// temp by ZT
	}
	#TestArea1, #TestArea2 {
		color:#000;
	}
	#CurveDataUsage {
		background-color:transparent;
    }
    #InfoBlock {
        background:#000000;
    }
    #DateDisp, #WeatherDisp{
        font-size: 20px;
        color: #FEF5D4;
    }
    #DateDisp {
        text-align:left;
    }
    #WeatherDisp {
        text-align:right;
    }
    #NextTireLevel {
        font-size:30px;
    }
	@font-face {font-family:LEDFont;src:url(fonts/DS-DIGI.TTF);}
	#BillingCycle td, #BillingCycle th 
	{
        font-size:70px;
        color:#DFD6A5;
		border:8px solid #008633;
		padding:3px 7px 2px 7px;
	}
	#BillingCycle th 
	{
		font-size:40px;
		text-align:center;
		padding-top:5px;
		padding-bottom:4px;
		border:8px solid #008633;
		background-color:#008633;
		color:#E9BE00;
	}
	#BillingCycle tr.alt td 
	{
		color:#DFD6A5;
		background-color:#EAF2D3;
    }
    #WarningBlock {
        font-size:20px;
        text-align:left;
        color:#FF6600;
        font-weight:lighter;
    }
</style>
<?php
function GetConsumptionData(){
	$user = 'root';  
	$pswd = 'calplug2012';  
	$db = 'smart_meter_reading';  
	$conn = mysql_connect('localhost', $user, $pswd);  
	mysql_select_db($db, $conn);
	$query = "select Consumption from smart_meter_reading.calplugmeter order by Timestamp ASC";
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
	$query = "select TimeStamp from smart_meter_reading.calplugmeter order by Timestamp ASC";
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
<meta charset="UTF-8">
<title>Household Energy Consumption Display</title>
<script src="js/jquery-1.8.2.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/highcharts-more.js"></script>
<!--<script src="js/modules/exporting.js"></script>-->
<script src="js/jquery.simpleWeather-2.3.min.js"></script>
<script src="js/jquery-ui-1.10.3.custom.min.js"></script> 

<!-- 
<script src="slider/js-image-slider.js" type="text/javascript"></script>
-->      
<script>
/*
*			Setup Global Information
*/
var userZipcode = '92617';
var tempUnit = 'f';
var screenWidth = 1920;				//TV screen resolution
var screenHeight = 1080;	


/*          Theme Color
*
 */          
var mainThemeColor = '#E9BE00';
var secondThemeColor = '#DFD6A5';
var strongThemeColor = '#E9BE00';
/*
*
*           Page Setup
 */
var marginVal = 5;
var px = "px";
var infoBlockWidth = screenWidth - 2*marginVal;
var infoBlockHeight = screenHeight/20 - 2*marginVal;
	
var tireWidth = screenWidth/4 - 3*marginVal;
var tireHeight = screenHeight/16*15 - 2*marginVal;
var billingCycleWidth = tireWidth - marginVal;
var billingCycleHeight = tireHeight/9*3 - 2*marginVal;
var currentPowerWidth = tireWidth - marginVal;
var currentPowerHeight = tireHeight/9*2 - 2*marginVal;
var meterWidth = tireWidth -marginVal;
var meterHeight = tireHeight/9*4 -2*marginVal;
	
var EdisonLogoWidth = screenWidth/2 - 2*marginVal;
var EdisonLogoHeight = screenHeight/16*3 - 2*marginVal;
	
var dataBlockWidth = screenWidth/2 - 2*marginVal;
var dataBlockHeight = screenHeight/8*5 - 2*marginVal;

var WarningBlockLeft = dataBlockWidth/8;
var WarningBlockWidth = dataBlockWidth/8*7;
var WarningBlockHeight = screenHeight/8*2 - 2*marginVal;

var dataBlockCurveDataUsageWidth = dataBlockWidth;
var dataBlockCurveDataUsageHeight = dataBlockHeight;
var dataBlockCurveDataUsageTopMargin = dataBlockWidth/8-marginVal;
	
var barDataBlockWidth = screenWidth/4 - 2*marginVal;
var barDataBlockHeight = screenHeight/16*15 - 2*marginVal;
/* 
*		prepare consumption data from the system		
*/
// get all consumption data
var consumption;
var timeStamp;
// Tire Information
var tire;
// Bar Information
var barDataThisMonth;
var barDataToday;
// Billing Information
var date = new Date();
var billingStartDate = 15;
var billingStartMonth;
var daysSinceThisBillingCycle;
var billingCycleLength;

if (date.getDate() >= billingStartDate) {
    billingStartMonth = date.getMonth();
} else {
    billingStartMonth = date.getMonth()-1;
    if (billingStartMonth == -1)
        billingStartMonth = 11;
}

// variable names give their purposes
var eachDayUsageDivideByBillingCycle = new Array();
var eachBillingCycleUsage = new Array();
var eachHourUsageDivideByDay = new Array();

var recentNWeek = 10;
var recentNWeekdaysUsage = new Array();
var recentNWeekendsUsage = new Array();
var estimatedHourlyUsageOnMon = new Array();
var estimatedHourlyUsageOnTue = new Array();
var estimatedHourlyUsageOnWed = new Array();
var estimatedHourlyUsageOnThu = new Array();
var estimatedHourlyUsageOnFri = new Array();
var estimatedHourlyUsageOnSat = new Array();
var estimatedHourlyUsageOnSun = new Array();
var numHourlyUsageOnMon = new Array();
var numHourlyUsageOnTue = new Array();
var numHourlyUsageOnWed = new Array();
var numHourlyUsageOnThu = new Array();
var numHourlyUsageOnFri = new Array();
var numHourlyUsageOnSat = new Array();
var numHourlyUsageOnSun = new Array();

var estimatedDailyUsage = 0.0;
var estimatedMonthlyUsage = 0.0;
var todayUsage = 0.0;
var thisCycleUsage = 0.0;


// ProcessDataFromDatabase();

// global value for curve update
var curveDataCurIndex = 0;
// begin render the html
$(document).ready(function(){
	/*			Read Configuration
	*
	*/
	//$("#TestArea1").html(date.getUTCDay()+" "+ date.getHours());
	xmlDoc = ReadSettingXML();
	userZipcode = GetZipCode(xmlDoc);
	// alert(Date.UTC(2013, 3, 03));
	// alert(date.getDate());	
	
	$(function PageSetup() {
		// show background
        $('#mainFrame').css({"top":"0px","left":"0px","width":screenWidth+px,"height":screenHeight+px});
        // show information block
        $('#InfoBlock').css({"top":marginVal+px,"left":marginVal+px,"width":infoBlockWidth+px,"height":infoBlockHeight+px});
        $('#DateDisp').css({"width":infoBlockWidth/4+px,"height":infoBlockHeight+px,"font-size":infoBlockHeight*0.6+px, "vertical-align":"bottom"});
        $('#WeatherDisp').css({"width":infoBlockWidth/4+px,"height":infoBlockHeight+px,"font-size":infoBlockHeight*0.6+px, "vertical-align":"bottom"});

		// show cumulative usage
        $('#BarDataBlock').css({"top":infoBlockHeight+2*marginVal+px,"left":marginVal+px,"width":barDataBlockWidth+px,"height":barDataBlockHeight+px});
        $('#BarDataTitle').css({"width":barDataBlockWidth+px,"height":barDataBlockHeight/4+px,"vertical-align":"middle"});
		$('#BarData1stBlock').css({"width":barDataBlockWidth/2-marginVal*2+px,"height":barDataBlockHeight/4*3+px});
        $('#BarData2ndBlock').css({"width":barDataBlockWidth/2-marginVal*2+px,"height":barDataBlockHeight/4*3+px});
		// show Edison Logo
		$('#EdisonLogo').css({"top":infoBlockHeight+marginVal+px, "left":2*marginVal+tireWidth+px,"width":EdisonLogoWidth+px,"height":EdisonLogoHeight+px}).html("<img src=\"./img/edison_logo.png\" alt=\"Edison Logo\" height=\""+EdisonLogoHeight+"\">");
		// show curve data block
		$('#CurveDataBlock').css({"top":infoBlockHeight+marginVal*2+EdisonLogoHeight+px,"left":2*marginVal+tireWidth+px,"width":dataBlockWidth+px,"height":dataBlockHeight+px});
		// show curve data block title
		//$('#CurveDataTitle').css({"width":dataBlockTitleWidth+px,"height":dataBlockTitleHeight+px});
        $('#CurveDataUsage').css({"width":dataBlockCurveDataUsageWidth+px,"height":dataBlockCurveDataUsageHeight+px});
        
        // show warning block  
		$('#WarningBlock').css({"top":infoBlockHeight+marginVal*2+EdisonLogoHeight+dataBlockHeight+px,"left":2*marginVal+tireWidth+WarningBlockLeft+px,"width":WarningBlockWidth+px,"height":WarningBlockHeight+px});
            
        // show 
        $('#RightBlock').css({"top":infoBlockHeight+marginVal*2+px, "left":4*marginVal+tireWidth+dataBlockWidth+px,"width":tireWidth+px,"height":tireHeight+px});
        // show tire
        $('#BillingCycle').css({"width":infoBlockHeight+billingCycleWidth+px,"height":billingCycleHeight+px});
        // show current power
        $('#CurrentPower').css({"width":currentPowerWidth+px,"height":currentPowerHeight+px});
        $('#Meter').css({"top":marginVal*3+currentPowerHeight+billingCycleHeight+px,"width":meterWidth+px,"height":meterHeight+px});
        $('#NextTireLevel').css({"top":marginVal*3+currentPowerHeight+billingCycleHeight+meterHeight/10*7+px,"left":meterWidth/2+px,"width":meterWidth/2+px,"height":meterHeight/4+px});
        
		// show CalPlug logo
		//$('#CalPlugLogo').css({"top":infoBlockHeight+barDataBlockHeight+4*marginVal+px,"left":4*marginVal+tireWidth+dataBlockWidth+px,"width":CalPlugLogoWidth+px,"height":calPlugLogoHeight+px}).html("<img src=\"./img/calplug_logo.png\" alt=\"Edison Logo\" width=\""+CalPlugLogoWidth+"\">");
		/*
		*			Show charts
         */
        // update every 10 mins
		
        //setTimeout('refreshHTML()',600000); 	//Zhimin add
		
        ProcessDataFromDatabase();
		
        var ProcessDataTimer=setInterval(function(){ProcessDataFromDatabase()},70000000000); //zhimin modify from 7000 to 70000000000
		//$("#TestArea1").html("bill:"+bill+" billToday:"+billToday+" percentage2NextTire:"+percentage2NextTire+" currentTire:"+currentTire+" currentRate: "+currentRate);
	});
	
});
function refreshHTML()
{
    window.location.reload(true);
}

function ProcessDataFromDatabase() {
    // reinitialize
    date = new Date();

    eachDayUsageDivideByBillingCycle = new Array();
    eachBillingCycleUsage = new Array();
    eachHourUsageDivideByDay = new Array();
    estimatedDailyUsage = 0.0;
    estimatedMonthlyUsage = 0.0;
    todayUsage = 0.0;
    thisCycleUsage = 0.0;

	// calculate the current power
	powersInHour = new Array();

    consumption  = [<?php GetConsumptionData() ?>];	
    timeStamp = [<?php GetDateData() ?>];
    for(var i = consumption.length; i--;) {
    	if (consumption[i] > 30) {
		    consumption[i] = 0;
        }
        --timeStamp[6*i+1];
    }	

	// for eachDayUsageDivideByBillingCycle & eachBillingCycleUsage
	var billingCycleIndex = 0;
	var dayIndex4BillingCycle = 0;
	var yesterday = 100;
	var dailyUsage = new Array();  
	dailyUsage[0] = [Date.UTC(timeStamp[0], timeStamp[1], billingStartDate,0,0,0,0), 0.0];
	// for eachBillingCycleUsage
	var usagePerBilling = 0.0;
	// for eachHourUsageDivideByDay
	var dayIndex = 0;
	var hourIndex = 0;
	var lastHour = 100;
	var hourlyUsage = new Array(); // will be initialize in the loop
	// for estimation
	var days = 1;
	var cycles = 1;
	var overallConsumption = 0.0;
    // weekly analysis
    var weekdaysUsage = 0.0;
    var weekendsUsage = 0.0;
    var lastWeek =  new Date(timeStamp[0], timeStamp[1], timeStamp[2], 0, 0, 0, 0);
    recentNWeekdaysUsage = new Array();
    recentNWeekendsUsage = new Array();
    estimatedHourlyUsageOnMon = new Array();
    estimatedHourlyUsageOnTue = new Array();
    estimatedHourlyUsageOnWed = new Array();
    estimatedHourlyUsageOnThu = new Array();
    estimatedHourlyUsageOnFri = new Array();
    estimatedHourlyUsageOnSat = new Array();
    estimatedHourlyUsageOnSun = new Array();
    numHourlyUsageOnMon = new Array();
    numHourlyUsageOnTue = new Array();
    numHourlyUsageOnWed = new Array();
    numHourlyUsageOnThu = new Array();
    numHourlyUsageOnFri = new Array();
    numHourlyUsageOnSat = new Array();
    numHourlyUsageOnSun = new Array();

    for (var i = 0; i < 24; ++i) {
        estimatedHourlyUsageOnMon.push(0.0);
        estimatedHourlyUsageOnTue.push(0.0);
        estimatedHourlyUsageOnWed.push(0.0);
        estimatedHourlyUsageOnThu.push(0.0);
        estimatedHourlyUsageOnFri.push(0.0);
        estimatedHourlyUsageOnSat.push(0.0);
        estimatedHourlyUsageOnSun.push(0.0);
        numHourlyUsageOnMon.push(0.1);
        numHourlyUsageOnTue.push(0.1);
        numHourlyUsageOnWed.push(0.1);
        numHourlyUsageOnThu.push(0.1);
        numHourlyUsageOnFri.push(0.1);
        numHourlyUsageOnSat.push(0.1);
        numHourlyUsageOnSun.push(0.1);
    }


    // begin traverse all data
    for (var i = 0; i < consumption.length; ++i) {
		// Note: if this is the last data, then add to the current cycle
		if (i == consumption.length-1) {
			var m;
			if (timeStamp[6*i+2] >= billingStartDate) {
				m = timeStamp[6*i+1];
			} else {
                m = timeStamp[6*i+1] - 1;
                if (m == -1)
                    m = 11;
			}
			eachDayUsageDivideByBillingCycle[billingCycleIndex] = dailyUsage;
			eachBillingCycleUsage[billingCycleIndex] = [Date.UTC(timeStamp[6*i], m,1,0,0,0,0),usagePerBilling];
			 eachHourUsageDivideByDay[dayIndex] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],0,0,0,0),hourlyUsage];
			 break;
		}
		// Note: if starting billing day is 1. We need to consider it seperately
			if ((timeStamp[6*i+2] >= billingStartDate && yesterday < billingStartDate) ||
			(billingStartDate == 1 && timeStamp[6*i+2] == 1 && yesterday <= 31))
		{
            // new billing cycle
            // get yesterday's billing cycle month
            var m = timeStamp[6*i+1]-1;
            if (m == -1)
                m = 11;
			
			eachDayUsageDivideByBillingCycle[billingCycleIndex] = dailyUsage;
			eachBillingCycleUsage[billingCycleIndex] = [Date.UTC(timeStamp[6*i], m, 1,0,0,0,0),usagePerBilling];
	
			++billingCycleIndex;
			dayIndex4BillingCycle = 1;
			dailyUsage = new Array();
			// dailyUsage[0] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], billingStartDate,0,0,0,0), 0.0];
			dailyUsage[0] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],0,0,0,0), 0.0];
			
			usagePerBilling = 0.0;
			++cycles;
		}
		// different days
		if (timeStamp[6*i+2] != yesterday) { // will run in the first time
			// a new day
			++dayIndex4BillingCycle;
			dailyUsage[dayIndex4BillingCycle] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],0,0,0,0), 0.0];
			yesterday = timeStamp[6*i+2];
			// update eachHourUsageDivideByDay
			eachHourUsageDivideByDay[dayIndex] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],0,0,0,0),hourlyUsage];
	
			++dayIndex;
			hourIndex = 1;
			hourlyUsage = new Array();
			hourlyUsage[0] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],0,0,0,0), 0.0];
			hourlyUsage[1] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],timeStamp[6*i+3],0,0,0), 0.0];
	
			++days;
		}
		// different hours
		if (timeStamp[6*i+3] != lastHour) { // will run in the first time
			// a new hour
			++hourIndex;
			hourlyUsage[hourIndex] = [Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],timeStamp[6*i+3],0,0,0), 0.0];
			lastHour = timeStamp[6*i+3];
		}
		// eachDayUsageDivideByBillingCycle
		dailyUsage[dayIndex4BillingCycle][1] += consumption[i];
		// eachBillingCycleUsage
		usagePerBilling += consumption[i];
		// eachHourUsageDivideByDay
		hourlyUsage[hourIndex][1] += consumption[i]; 
		// overallConsumption
		overallConsumption += consumption[i];
		// update powersInHour for power calculation
		powersInHour.push([Date.UTC(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2],timeStamp[6*i+3],timeStamp[6*i+4],timeStamp[6*i+5],0), consumption[i]]);
		while ((powersInHour.length > 1) && 
		(powersInHour[powersInHour.length-1][0] - powersInHour[0][0] > 3600000)) {
			powersInHour.shift();
        }
        // update weekly data
        var timeNow = new Date(timeStamp[6*i], timeStamp[6*i+1], timeStamp[6*i+2], timeStamp[6*i+3],0 , 0, 0);
        if (i > 0) {
            // it's a new week
            if (timeNow.getTime() - lastWeek.getTime() > 7*24*3600*1000) {
                recentNWeekdaysUsage.push(weekdaysUsage);  
                recentNWeekendsUsage.push(weekendsUsage);
                weekdaysUsage = 0.0;
                weekendsUsage = 0.0;
                lastWeek = timeNow;
            } 
            if (timeNow.getDay() <= 4) {
                // if it is a weekday
                weekdaysUsage += consumption[i];
            } else {
                // if it is a weekend           
                weekendsUsage += consumption[i];    
            }            
        }
        // update hourly estimated data in a week
        switch (timeNow.getDay()) {
        case 0:
            estimatedHourlyUsageOnMon[timeNow.getHours()] += consumption[i];
            ++numHourlyUsageOnMon[timeNow.getHours()];
            break;
        case 1:
            estimatedHourlyUsageOnTue[timeNow.getHours()] += consumption[i];
            ++numHourlyUsageOnTue[timeNow.getHours()];
            break;
        case 2:
            estimatedHourlyUsageOnWed[timeNow.getHours()] += consumption[i];
            ++numHourlyUsageOnWed[timeNow.getHours()];
            break;
        case 3:
            estimatedHourlyUsageOnThu[timeNow.getHours()] += consumption[i];
            ++numHourlyUsageOnThu[timeNow.getHours()];
            break;
        case 4:
            estimatedHourlyUsageOnFri[timeNow.getHours()] += consumption[i];
            ++numHourlyUsageOnFri[timeNow.getHours()];
            break;
        case 5:
            estimatedHourlyUsageOnSat[timeNow.getHours()] += consumption[i];
            ++numHourlyUsageOnSat[timeNow.getHours()];
            break;
        case 6:
            estimatedHourlyUsageOnSun[timeNow.getHours()] += consumption[i];
            ++numHourlyUsageOnSun[timeNow.getHours()];            
            break;        
        }
    }
    // process weekly usage
    var len = recentNWeekdaysUsage.length;
    if (len > recentNWeek) {
        recentNWeekdaysUsage = recentNWeekdaysUsage.slice(len-recentNWeek, len);
    }
    len = recentNWeekendsUsage.length;
    if (len > recentNWeek) {
        recentNWeekendsUsage = recentNWeekendsUsage.slice(len-recentNWeek, len);
    }
    for (var i = 0; i < 24; ++i) {
        estimatedHourlyUsageOnMon[i] /= numHourlyUsageOnMon[i]/4;
        estimatedHourlyUsageOnTue[i] /= numHourlyUsageOnTue[i]/4;
        estimatedHourlyUsageOnWed[i] /= numHourlyUsageOnWed[i]/4;
        estimatedHourlyUsageOnThu[i] /= numHourlyUsageOnThu[i]/4;
        estimatedHourlyUsageOnFri[i] /= numHourlyUsageOnFri[i]/4;
        estimatedHourlyUsageOnSat[i] /= numHourlyUsageOnSat[i]/4;
        estimatedHourlyUsageOnSun[i] /= numHourlyUsageOnSun[i]/4;    
    }

    // update tire information
    xmlDoc = ReadSettingXML();
    tire = CalcPriceTire(consumption, timeStamp, billingStartDate, xmlDoc);
    
    // Update Bar information
    estimatedDailyUsage = overallConsumption / (days);
    estimatedDailyUsage = parseFloat(estimatedDailyUsage.toFixed(0));
    estimatedMonthlyUsage = overallConsumption / (cycles);
    estimatedMonthlyUsage = parseFloat(estimatedMonthlyUsage.toFixed(0));
        // Calc today's usage and this mongth's usage
    todayUsage = eachDayUsageDivideByBillingCycle[eachDayUsageDivideByBillingCycle.length-1];
    todayUsage = parseFloat(todayUsage[todayUsage.length-1][1].toFixed(0));
    thisCycleUsage = parseFloat(eachBillingCycleUsage[eachBillingCycleUsage.length-1][1].toFixed(0));
	
        //Calc the days has passed since beginning of this billing cycle
    if (date.getDate() >= billingStartDate) {
        daysSinceThisBillingCycle = date.getDate() - billingStartDate + 1;
    } else {
        daysSinceThisBillingCycle = getNumberDaysInMonth(billingStartMonth, date.getFullYear()) - billingStartDate + 1 
            + date.getDate();
    }
	
        // Billing Cycle Length
    billingCycleLength = getNumberDaysInMonth(billingStartMonth, date.getFullYear()) - billingStartDate + 1 + 14;

    var estimatedDailyUsageByFar = estimatedDailyUsage / 24.0 * (date.getHours()+1);
    var estimatedMontlyUsageByFar = estimatedMonthlyUsage / billingCycleLength * daysSinceThisBillingCycle;
    estimatedDailyUsageByFar = parseFloat(estimatedDailyUsageByFar.toFixed(2));
    estimatedMontlyUsageByFar = parseFloat(estimatedMontlyUsageByFar.toFixed(2));
    
    var bill = tire[0], billToday = tire[1];


    /*
	*			Show curve charts
	*/	
    var titleChart = ['Hourly Energy Consumption in 24 Hours', 'Weekly Consumption'];
    if (curveDataCurIndex == 0) {
        UpdateWeeklyChart('#CurveDataUsage');
    } else {
        UpdateHourlyChart('#CurveDataUsage');
    }
    curveDataCurIndex = 1 - curveDataCurIndex; //zhimin add
       /*
	*			Show Costs
    */
    $('#BarDataTitle').html("<br><br><p style=\"font-size:30px;\">Current Energy Charge</p><span style=\"font-size:70px;color:#299925;\">&nbsp&nbsp&nbsp&nbsp&nbsp$" + bill + "</span><span style=\"font-size:20px;\">&nbsp&nbsp&nbsp</span>");
	/*
	*			Show Bars
    */
    barDataToday = {id:'#BarData1stBlock',title:"Today",maximum:estimatedDailyUsage,value:todayUsage, expected:estimatedDailyUsageByFar,cost:billToday};
	barDataThisMonth = {id:'#BarData2ndBlock',title:"This Month",maximum:estimatedMonthlyUsage,value:thisCycleUsage,expected:estimatedMontlyUsageByFar,cost:bill};
	$(UpdateBar(barDataToday)); //zhimin add
	$(UpdateBar(barDataThisMonth));	//zhimin add
	/*
	*			Show Warning
    */
    var message1 = "", message2 = "";
    if (barDataToday.value > barDataToday.maximum)
        message1 = '<p>&#8226 The energy consumption ' + barDataToday.value.toFixed(0)+' for today is higher than the average daily usage ' + barDataToday.maximum.toFixed(0)+'.</p>';
    if (barDataThisMonth.value > barDataThisMonth.maximum)
        message2 = '<p>&#8226 The energy consumption ' + barDataThisMonth.value.toFixed(0)+' in this billing cycle is higher than the average daily usage ' + barDataThisMonth.maximum.toFixed(0)+'.</p>';
    if (message1 == "" && message2 == "") {
        message1 = '<p style=\"color:#AFAFAF\">&#8226 It’s all good! You don’t have any new alerts.</p>';
    } else{
        message1 = message1 + "<br>" + message2;
    }
    $('#WarningBlock').html("<br>" + message1);
	/*
	*			Show Weather
	*/
	$(UpdateTimeWeather(userZipcode));	
	/*
	* 
	* Show Billing Cycle
	*
	*/
	UpdateBillingCycle("#BillingCycle");
	/*
	*			Show Tire
    */	    
    UpdateTire(tire,"#Meter");
    /*
    * 
    * 		Show Electric Meter
    *
    */ 
    UpdateCurrentPower("#CurrentPower", powersInHour);

}
/*
* 
* 		Show Electric Meter
*
*/ 
function UpdateCurrentPower(currentPowerId) {
	var lastHourPower = 0.0;
  	for (var i = 0; i < powersInHour.length; ++i) {
		lastHourPower += powersInHour[i][1];
	}
	if (powersInHour.length > 1) {
		lastHourPower = lastHourPower / ((powersInHour[powersInHour.length-1][0] - powersInHour[0][0]) / 3600000.0);
	} 
	$("#CurrentPower").html("<p style=\"font-size:30px;\">Current Power</p><span style=\"font-family:LEDFont;font-size:100px;color:#000000;\"> " + lastHourPower.toFixed(2) + "</span><span style=\"color:#000000;\">KW</p>");
}
/*
* 
* Show Billing Cycle
*
*/
function UpdateBillingCycle(billingCycleId) {
	/*
    var startDay = getMonthDayAbbr(billingStartMonth, billingStartDate);
    var today = getMonthDayAbbr(date.getMonth(), date.getDate());
    var endDay;
    if (billingStartDate == 1)
        endDay = getNumberDaysInMonth(billingStartMonth+1, date.getFullYear());
    else
        endDay = billingStartDate - 1;
    endDay = getMonthDayAbbr(billingStartMonth+1, endDay);
	*/
    var nextBillingStartMonth;
    var daysLeft;
    if (date.getDate() > billingStartDate) {
        daysLeft = getNumberDaysInMonth(date.getMonth(), date.getFullYear()) - date.getDate()
 + 1 + billingStartDate;
		nextBillingStartMonth = date.getMonth()+1;
		if (nextBillingStartMonth == 12)
			nextBillingStartMonth = 0;
    } else {
        daysLeft = billingStartDate -  date.getDate();
		nextBillingStartMonth = date.getMonth();	
	}
	nextBillingStartMonth = getMonthAbbr(nextBillingStartMonth);
	var title = "<p style=\"vertical-align:middle;text-align:center;font-size:30px;\">Next Cycle Ends</p>";
	var table;
	table = "<table>";
	table += "<tr>";
    table += "<th>"+ nextBillingStartMonth + "</th>";
    table += "<td rowspan=\"2\"><p>&nbsp&nbsp" + daysLeft + "&nbsp&nbsp</p><p style=\"font-size:20px\">days left</p></td>";
	table += "</tr>";
	table += "<tr>";
    table += "<td>&nbsp&nbsp"+ billingStartDate + "&nbsp&nbsp</td>";
	table += "</tr>";
	table += "</table>";
	$(billingCycleId).html(title+table);
}
/*
*		Show Tire
*/
function UpdateTire(tire, tireId) {
    var percentage2NextTire = tire[2],currentTire = tire[3], currentRate= tire[4], nextRate = tire[5];

    var nextTier = currentTire+1, text;
    if (currentTire == 4) {
        nextTier = "Highest Tier";
		text = "<p>" + nextTier + "</p>";
	}
    else {
        nextTier = "Until Next Tier "+nextTier;
		text = "<p>" + nextTier + "</p><p style=\"font-size:18px\">Next Tier Rate $" + nextRate + "</p>";
	}
    $("#NextTireLevel").html(text); 
	$(tireId).highcharts({
		chart: {
	    	type: 'gauge',
		    backgroundColor: 'rgba(255,255,255,0.0)',
		    plotBackgroundImage: null,
		    plotBorderWidth: 0,
		    plotShadow: false,
        },

    	credits: {
	    	enabled: false
	    },

        title: {
            text: "<p> Current Tier " + currentTire + "</p><br><p>Rate: $"+ currentRate +"</p>",
            style: {
		        fontWeight: 'bold',
                fontSize: '30px',
                color: mainThemeColor,
                fontFamily: "rockwell"
            }	            
        },

        pane: {
            startAngle: -180,
            endAngle: 90,
		    background: {
			    backgroundColor: 'rgba(255,255,255,0.0)',
		    }
        },

        // the value axis
        xAxis: {
            labels: {
                style: {
                    color: secondThemeColor,                
                }
            }
        },
        yAxis: {
            min: 0,
            max: 100,
        
	    	minorTickInterval: 'auto',
            minorTickWidth: 1,
            minorTickLength: 10,
            minorTickPosition: 'inside',
            minorTickColor: '#666',

            tickPixelInterval: 50,
            tickWidth: 2,
            tickPosition: 'inside',
            tickLength: 10,
            tickColor: '#666',
            labels: {
                step: 2,
                rotation: 'auto',
			    style: {
                    fontWeight: 'bold',
                    fontSize: '20px',
                    color: secondThemeColor,
                }
            },
            plotBands: [{
                from: 0,
                to: 40,
                color: '#55BF3B' // green
            }, {
                from: 40,
                to: 80,
                color: '#DDDF0D' // yellow
            }, {
                from: 80,
                to: 100,
                color: '#DF5353' // red
            }]        
        },
        plotOptions: {
            gauge: {
                animation: false,
            },
        },
        series: [{
            name: 'current tier percentage',
            data: [percentage2NextTire*100],
		    dataLabels: {
                formatter: function () {
                    var p = this.y;
                    return "<div style=\"border-width:0px;font-size:32px;color:" + secondThemeColor + "\">"+ p + " %</div>";
        		},
		    },
		    tooltip: {
                valueSuffix: ' %'
            }	
        }]
	});
}
/*
*		Show Bar Data
*/
function UpdateBar(data) {
	var colorOfBar;
	if (data.value < data.maximum/4*3) // green
		colorOfBar = '#55BF3B';
	else if (data.value < data.maximum/5*4) // yellow
		colorOfBar = '#FACC2E';
	else // red
		colorOfBar = '#D26464';
	$(data.id).highcharts({
		 chart: {
			type: 'column',
			backgroundColor: 'rgba(255,255,255,0.0)'
		},
		credits: {
			enabled: false
        },
		title: {
            text: null			
		},
		legend: {
			enabled: false
		},
		xAxis: {
			categories: [data.title],
			title: {
				enabled: false
			},
			labels: {
				style: {
                    fontSize: '16px',
                    color: secondThemeColor,
				}
            },
		},
		yAxis: {
			//gridLineWidth: 0,
			min: 0,
			max: 1.00,
			title: {
				enabled: false
			},
			labels: {
				enabled: false
            },
             gridLineWidth: 0
		},
        plotOptions: {
            series: {
                borderColor: 'black',
                color: colorOfBar,
                pointWidth: 100,
                borderWidth: 3
            },
            column: {
                animation: false,
            },
        },          
        series: [{
			name: 'Power Consumed',
            data: [{
                    y: 1.00,
                    x: 0,
                    color: 'rgba(255,255,255,0.0)',
                },
                {
                    y: data.value / Math.max(data.value, data.maximum),
                    x: 0,
                    dataLabels: {
                        y: 40,
					    enabled: true,
                        formatter: function () {
                            return "<p style=\"font-size:18px;color:" + secondThemeColor + "\">" + data.value + "KWh</p>";
                        },
                    }

                }]        
		    }]
	});
}
/*
*			Show Curve Data
*/
function UpdateWeeklyChart(id) {
    var category = new Array();
    var len = Math.min(recentNWeek, recentNWeekdaysUsage.length);
    for (var i = 1; i < len; ++i) {
        category.push('Week ' + i);
    }
    category.push('This Week');


    $(id).highcharts({
        chart: {
            backgroundColor: 'rgba(255,255,255,0.0)',
        },
   		credits: {
			enabled: false
        },
        title: {
            text: 'Weekly Consumption',
            style: {
		        fontWeight: 'bold',
                fontSize: '30px',
                color: mainThemeColor,
                fontFamily: "rockwell"
            }	
        },
        xAxis: {
            categories: category,
            title: {
                text: null,
            },
			labels: {
				style: {
                    fontSize: '16px',
                    color: secondThemeColor,
                },
            },     
            gridLineWidth: 1                
        },
		yAxis: {
			title: {
				text: 'kWH',
				style: {
					fontWeight: 'bold',
                    fontSize: '26px',
                    color: secondThemeColor,
				}	
            },
			labels: {
				style: {
					fontWeight: 'bold',
                    fontSize: '16px',
                    color: secondThemeColor,
				}	
            },
                
            gridLineWidth: 0
        },  
        legend: {
            layout: 'vertical',
            backgroundColor: 'rgba(255,255,255,0.0)',
            borderColor: 'rgba(255,255,255,0.0)',
            floating: true,
            align: 'left',
            verticalAlign: 'top',
            x: 90,
            y: 45,
            itemStyle: {
                paddingBottom: '30px'
            },
            labelFormatter: function() {
                return '<p style=\"font-size:16px;color:'+ secondThemeColor +'\">'+this.name+'</p>';
            }
        },      
        plotOptions: {
            column: {
                stacking: 'normal',
            }        
        },
        series: [{
                type: 'column',
                name: "Weekdays Usage",
                data: recentNWeekdaysUsage
        },{
                type: 'column',
                name: "Weekend Usage",
                data: recentNWeekendsUsage
        }]
    })
}
function UpdateHourlyChart(id) {
    var category = new Array();
    for (var i = 0; i < 24; ++i) {
        if (i % 4 == 0) {
            if (i < 10)
                category.push('0' + i + ':00');
            else
                category.push(i + ':00');
        } else {
            category.push('');
        }
    }
    var est_data;
    switch(date.getDay()) {
        case 0: est_data = estimatedHourlyUsageOnMon; break;
        case 1: est_data = estimatedHourlyUsageOnTue; break;
        case 2: est_data = estimatedHourlyUsageOnWed; break;
        case 3: est_data = estimatedHourlyUsageOnThu; break;
        case 4: est_data = estimatedHourlyUsageOnFri; break;
        case 5: est_data = estimatedHourlyUsageOnSat; break;
        case 6: est_data = estimatedHourlyUsageOnSun; break;
    }
    var temp = eachHourUsageDivideByDay[eachHourUsageDivideByDay.length-1][1];
    var actual_data = new Array();
    for (var i = 2; i < temp.length; ++i) {
        actual_data.push(temp[i][1]);
    }
    $(id).highcharts({
        chart: {
            backgroundColor: 'rgba(255,255,255,0.0)',
        },
   		credits: {
			enabled: false
        },
        title: {
            text: 'Hourly Consumption Today',
            style: {
		        fontWeight: 'bold',
                fontSize: '30px',
                color: mainThemeColor,
                fontFamily: "rockwell"
            }	
        },
        xAxis: {
            categories: category,
            title: {
                text: null,
            },
			labels: {
				style: {
                    fontSize: '16px',
                    color: secondThemeColor,
                },
            },     
            gridLineWidth: 1                
        },
		yAxis: {
			title: {
				text: 'kWH',
				style: {
					fontWeight: 'bold',
                    fontSize: '26px',
                    color: secondThemeColor,
                }	
            },
			labels: {
				style: {
					fontWeight: 'bold',
                    fontSize: '16px',
                    color: secondThemeColor,
				}	
            },
                
            gridLineWidth: 0
        },  
        legend: {
            layout: 'vertical',
            backgroundColor: 'rgba(255,255,255,0.0)',
            borderColor: 'rgba(255,255,255,0.0)',
            floating: true,
            align: 'left',
            verticalAlign: 'top',
            x: 90,
            y: 45,
            itemStyle: {
                paddingBottom: '30px'
            },
            labelFormatter: function() {
                return '<p style=\"font-size:16px;color:'+ secondThemeColor +'\">'+this.name+'</p>';
            }
        },      
        plotOptions: {
            series: {
                marker: {
                    radius: 8
                },
                lineWidth: 5
            }
        },
        series: [{
                type: 'area',
                name: "Average Hourly Usage on " + getDayInAWeek(date.getDay()),
                data: est_data,
        },{
                type: 'line',
                name: "Acutal Hourly Usage",
                data: actual_data
        }]
    })			
}
/*
*			Display information
*/
function UpdateTimeWeather(userZipcode) {
	var date = new Date();
	var month;
	switch(date.getMonth()) {
		case 0: month = 'January';break;
		case 1: month = 'February';break;
		case 2: month = 'March';break;
		case 3: month = 'April';break;
		case 4: month = 'May';break;
		case 5: month = 'June';break;
		case 6: month = 'July';break;
		case 7: month = 'August';break;
		case 8: month = 'September';break;
		case 9: month = 'October';break;
		case 10: month = 'November';break;
		case 11: month = 'December';
	}
	var minutes = date.getMinutes();
	if (minutes < 10)
		minutes = '0' + minutes;
	$('#DateDisp').html("&nbsp&nbsp&nbsp"+date.getHours() + ":" + minutes + ' ' + month + ' '+date.getDate());
	/*
	*				Get Weather
	*/
	var isOnline = false;
	var temperature;
	var city;
	var region;
	// var country;
	var unitDisplay;
	var code;
	var currently;
	var url = 'http://l.yimg.com/os/mit/media/m/weather/images/icons/l/';
	$.simpleWeather({
		zipcode: userZipcode,
		unit: 'f',
		success: function(weather) {
			temperature = weather.temp;
			unitDisplay = '°'+weather.units.temp;
			code = weather.todayCode;
			city = weather.city;
			region = weather.region;
			// country = weather.country;
			currently = weather.currently;
			isOnline = true;
			url += code;
			if (date.getHours() >= 6 && date.getHours() <= 18)
				url += 'd-100567.png';
			else
				url += 'n-100567.png';
            $('#WeatherIcon').html('<img style=\"float:middle\" src='+url+' height=\"' + infoBlockHeight*1.5 + '\"">');
            $('#WeatherLocation').html(city+', '+region+', '+temperature+unitDisplay+"&nbsp&nbsp&nbsp");
		},
		error: function(error) {
			isOnline = false;
		}
	});
}
/*				
*				Read Data from configuration file
*/
function ReadSettingXML () {
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.open("GET","doc/config.xml",false);
	xmlhttp.send();
	xmlDoc=xmlhttp.responseXML;
	return xmlDoc;
}
function GetZipCode(xmlDoc) {
	return xmlDoc.getElementsByTagName("zipcode")[0].childNodes[0].nodeValue;
}
function CalcPriceTire(consumption, timeStamp, billingStartDate, xmlDoc) {
	// get season
	var season;
	var now = new Date();
	var searson;
	var startMonth = parseInt(xmlDoc.getElementsByTagName("month")[0].childNodes[0].nodeValue);
	var endMonth = parseInt(xmlDoc.getElementsByTagName("month")[1].childNodes[0].nodeValue);
	if (now.getMonth() >= startMonth-1 && now.getMonth() < endMonth-1) {
		season = "summer";
	} else {
		season = "winter";
	}
	// get prices
	var prices = new Array(), lowerbounds = new Array(), upperbounds = new Array();
	var tireLen = xmlDoc.getElementsByTagName("tire").length;
	for (var i = 0; i < tireLen; ++i) {
		prices[i] = parseFloat(xmlDoc.getElementsByTagName(season)[i].childNodes[0].nodeValue);
		lowerbounds[i] = parseFloat(xmlDoc.getElementsByTagName("lowerbound")[i].childNodes[0].nodeValue);
		upperbounds[i] = parseFloat(xmlDoc.getElementsByTagName("upperbound")[i].childNodes[0].nodeValue);		
	}
	// get base line
	var regioncode = parseInt(xmlDoc.getElementsByTagName("regioncode")[0].childNodes[0].nodeValue);
	var baseline;
	var baselineLen = xmlDoc.getElementsByTagName("baseline").length;
	for (var i = 0; i < baselineLen; ++i) {
		if (regioncode == parseInt(xmlDoc.getElementsByTagName("baseline")[i].getAttributeNode("region").value)) {
			baseline = parseFloat(xmlDoc.getElementsByTagName("baseline"+season)[i].childNodes[0].nodeValue);
			break;
		}
	}   
	// calculate tire level
	// Note: here I separate the dataByThisMonth and datayByToday is because of display reason.
	// for the data is incomplete, I have to select the specific day instead of today.
	var bill = 0.0, billToday = 0.0, percentage2NextTire = 0.0, currentTire = 0, currentRate = 0.0, nextRate = 0.0;
	var date = new Date();
	var tomonth = date.getMonth();
    var toyear = date.getFullYear();
    var yesterday = -100;
	
    var consumptionToday = 0.0;
	for (var i = 0; i < consumption.length; ++i) {
		if (timeStamp[6*i] == toyear && 
            ((timeStamp[6*i+1] == tomonth-1 && timeStamp[6*i+2] >= billingStartDate) ||
            (timeStamp[6*i+1] == tomonth && timeStamp[6*i+2] < billingStartDate))
        ) {
                if (yesterday != timeStamp[6*i+2]) {
                    yesterday = timeStamp[6*i+2];
                    consumptionToday = consumption[i];
                    bill += billToday;
                    billToday = 0.0;
                } else {
                    consumptionToday += consumption[i];
                }
                
                if (consumptionToday <= baseline * upperbounds[0]) {
                    currentTire = 0;
                    percentage2NextTire = (consumptionToday - baseline*lowerbounds[0]) / (baseline * (upperbounds[0] - lowerbounds[0]));
		        	currentRate = prices[0];
					nextRate = prices[1];
                } else if (consumptionToday <= baseline*upperbounds[1]) {
		        	currentTire = 1;	
		        	percentage2NextTire = (consumptionToday - baseline*lowerbounds[1]) / (baseline * (upperbounds[1] -lowerbounds[1]));
			        currentRate = prices[1];
					nextRate = prices[2];
	        	} else if (consumptionToday <= baseline*upperbounds[2]) {
		        	currentTire = 2;
		        	percentage2NextTire = (consumptionToday - baseline*lowerbounds[2]) / (baseline * (upperbounds[2] - lowerbounds[2]));
		        	currentRate = prices[2];
					nextRate = prices[3];
		        } else  {
		        	currentTire = 3;
		        	percentage2NextTire = 1.0;
		        	currentRate = prices[3];
					nextRate = prices[3];
                }
                billToday += currentRate * consumption[i];
                if (i == consumption.length-1) {
                    bill += billToday;
                }
            }
    }
    // for display purpose, we may set values manually
	//bill = 7.78, billToday = 0.12, percentage2NextTire=0.47, currentTire = 1;
	return ([bill.toFixed(2), billToday.toFixed(2), percentage2NextTire.toFixed(2),currentTire+1, currentRate, nextRate]);
	//$("#TestArea1").html(baseline + "##"+ prices.toString()+ "##"+lowerbounds.toString() + "##"+upperbounds.toString());
	//$("#TestArea1").html("bill:"+bill+" billToday:"+billToday+" percentage2NextTire:"+percentage2NextTire+" currentTire:"+currentTire);
}
function getNumberDaysInMonth(monthNum, year) { // month starts from 0 to 11
    switch(monthNum) {
        case 0: return 31;
        case 1: if (year % 4 == 0 || (year % 100 == 0 && year % 400 == 0))
                    return 29;
                else
                    return 28;
        case 2: return 31;
        case 3: return 30;
        case 4: return 33;
        case 5: return 30;
        case 6: return 31;
        case 7: return 31;
        case 8: return 30;
        case 9: return 31;
        case 10: return 30;
        case 11: return 31;
    }
}
function getMonthDayAbbr(monthNum, dayNum) { // month starts from 0 to 11
    var str;
    switch(monthNum) {
        case 0: str = "Jan "; break;
        case 1: str = "Feb "; break;
        case 2: str = "Mar "; break;
        case 3: str = "Apr "; break;
        case 4: str = "May "; break;
        case 5: str = "Jun "; break;
        case 6: str = "Jul "; break;
        case 7: str = "Aug "; break;
        case 8: str = "Sep "; break;
        case 9: str = "Oct "; break;
        case 10: str = "Nov "; break;
        case 11: str = "Dec "; break;
    }
    return str + dayNum;
}
function getDayInAWeek(day) {
    var str;
    switch(day) {
        case 1: str = "Monday"; break;
        case 2: str = "Tuesday"; break;
        case 3: str = "Wednesday"; break;
        case 4: str = "Thursday"; break;
        case 5: str = "Friday"; break;
        case 6: str = "Saturday"; break;
        case 7: str = "Sunday"; break;
    }
    return str;
}
function getMonthAbbr(monthNum) { // month starts from 0 to 11
    var str;
    switch(monthNum) {
        case 0: str = "Jan "; break;
        case 1: str = "Feb "; break;
        case 2: str = "Mar "; break;
        case 3: str = "Apr "; break;
        case 4: str = "May "; break;
        case 5: str = "Jun "; break;
        case 6: str = "Jul "; break;
        case 7: str = "Aug "; break;
        case 8: str = "Sep "; break;
        case 9: str = "Oct "; break;
        case 10: str = "Nov "; break;
        case 11: str = "Dec "; break;
    }
    return str;
}
</script>
</head>

<body>
<div id="mainFrame" class="withboarder" style="position:absolute;margin: 1 auto">

<div id="InfoBlock" style="position:absolute; margin:1 auto">
<div id="DateDisp" style="float:left; margin:1  auto"></div>

<div id="WeatherDisp" style="float:right; margin:1  auto">
<div id="WeatherLocation" style="float:right; margin:1  auto"></div>
<div id="WeatherIcon" style="float:right; margin:1  auto"></div>
</div>

</div>

<div id="BarDataBlock" style="position:absolute; margin:1 auto">
<div id="BarDataTitle" style="margin: 1 auto"></div>
<div id="BarData1stBlock" style="float:left; margin:1  auto"></div>
<div id="BarData2ndBlock" style="float:right; margin:1  auto"></div>
</div>


<div id="EdisonLogo" style="position:absolute; margin:1 auto"></div>
<div id="CurveDataBlock" style="position:absolute; margin:1 auto">
<div id="CurveDataTitle" style="margin:1 auto"></div>
<div id="CurveDataUsage" style="margin:1 auto"></div>
</div>
<div id="WarningBlock" style="position:absolute; margin:1 auto"></div>



<div id="RightBlock" style="position:absolute; margin:1 auto">
<div id="BillingCycle" ></div>
<div id="CurrentPower" style="margin:1 auto"></div>
<!--Display Current Tire-->
<div id="Meter" style="position:absolute; margin:1 auto"></div>
<div id="NextTireLevel" style="position:absolute; margin:1 auto"></div>
</div>	


<div id="CalPlugLogo" style="position:absolute; margin:1 auto"></div>

</div>

<div id="TestArea1", style="position:absolute; top:1150px; left:0px;margin:auto"></div>
<div id="TestArea2", style="position:absolute; top:1250px; left:0px;margin:auto"></div>
</body>
</html>
