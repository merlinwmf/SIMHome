<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="refresh" content="900"
<?php
function GetAllData() {
$user = 'root';  
	$pswd = 'calplug2012';  
	$db = 'smart_meter_reading';  
	$conn = mysql_connect('localhost', $user, $pswd);  
	mysql_select_db($db, $conn);
	$query = "select Consumption from smart_meter_reading.housemeter order by Timestamp ASC";
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
	$query = "select Consumption from smart_meter_reading.housemeter order by Timestamp ASC";
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
	$query = "select TimeStamp from smart_meter_reading.housemeter order by Timestamp ASC";
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Household Energy Consumption Display</title>

<script src="js/jquery-1.8.2.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>
<script type="text/javascript">
var date = new Date();
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

//console.log("NowDate= %s,%s,%s",date.getFullYear(),date.getMonth()+1,date.getDate());
//console.log("setStart= %s,%s,%s",startYear,startMonth+1,startDate);
//console.log("setStartDetail= %s,%s,%s",startYearDetail,startMonthDetail+1,startDateDetail);

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
var curDate;
//console.log('conslen'+ consumption.length);

for (var i = 0; i < consumption.length; ++i) {
	timeStamp[6*i+1]--;
	curDate = Date.UTC(timeStamp[6*i], timeStamp[6*i+1],timeStamp[6*i+2],timeStamp[6*i+3],timeStamp[6*i+4],timeStamp[6*i+5],0);
	if (curDate > startUTCDate) {	
		data[indexData++] = [curDate, consumption[i]];
		average += consumption[i];
		dataNumber ++ ;

        //console.log('I'+ i + 'curDate'+ curDate);

	}
}

//console.log('datalen'+ data.length);
//console.log('dataNumber'+ dataNumber);

//console.log(data[dataNumber-1]);

var curDate = data[dataNumber - 1][0];
//console.log('curDate'+ curDate);

//console.log('ifDate'+timeStamp[0], timeStamp[1]+1,timeStamp[2],timeStamp[3],timeStamp[4],timeStamp[5]);
//console.log('curDate'+timeStamp[6*consumption.length-6], timeStamp[6*consumption.length-5]+1,timeStamp[6*consumption.length-4],timeStamp[6*consumption.length-3],timeStamp[6*consumption.length-2],timeStamp[6*consumption.length-1]);


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
    
    
        // create the master chart
        function createMaster() {
            masterChart = $('#master-container').highcharts({
                chart: {
                    reflow: false,
                    borderWidth: 0,
                    backgroundColor: null,
                    marginLeft: 50,
                    marginRight: 20,
                    spacetop: 0,
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
                                to: curDate,
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
				    labels: {
						style: {
                    fontSize:'110%'
                }
				},
                    type: 'datetime',
                    showLastTickLabel: true,
                    minRange:  12 * 3600000, // half day
                    plotBands: [{
                        id: 'mask-before',
                        from: startUTCDate,
                        to: curDate,
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
                    enabled: true,
					itemStyle: {
                 fontSize:'35px'

              }
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
                    marginLeft: 70,
                    marginRight: 20,
                    style: {
                        position: 'absolute'
                    }
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Energy Consumption',
					style: {
                    fontSize:'35px'
                }
                },
                xAxis: {
                    type: 'datetime',
					labels: {
						style: {
                    fontSize:'100%'
                }
				}
                },
                yAxis: {
                    title: {
				text: 'kWH',
				style: {
					fontWeight: 'bold',
					fontSize: '35px' 
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
            .css({ position: 'absolute', top: 0, height: '72%', width: '100%' })
            .appendTo($container);
    
        var $masterContainer = $('<div id="master-container">')
            .css({ position: 'absolute', bottom: '20%', height: '18%', width: '100%' })
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
}
</script>
</head>
<body>
<script src="../../js/highcharts.js"></script>
<script src="../../js/modules/exporting.js"></script>

<div id="container" style="width: 1920px; height: 1080px; margin: 0 auto"></div>

</body>
</html>
