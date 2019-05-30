<div class="row">
	<br>
	<div class="span1" style="width: 1%;"></div> 
	<div id='chart-div' class="span12" style="width: 90%; min-height: 350px"></div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> <!-- jQuery Core -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

$(document).ready(function(){
	google.charts.load('current', {'packages':['corechart', 'line']});
	google.charts.setOnLoadCallback(drawGraph);

	function drawGraph() {
		var data = new google.visualization.DataTable();
		data.addColumn('date', '시간');
		data.addColumn('number', 'KWh');
		
		$.ajax({                                               
	    	url: "/index.php/graph/jsonExternalBattery/",      
	    	dataType:'json',                                   
	    	cache: 'false',                                    
	    	contentType: 'application/json; charset=utf-8',    
	    	crossDomain: true,                                 
	                                                       
	    	success: function(json){                           
				var arr = new Array();
				
				for (var i in json){
				//	console.log(json[i].date, json[i].cons);
					arr.push([new Date(json[i].date), json[i].cons]);
				}

				//console.log(arr);
				data.addRows(arr);
				
				var options={
					chart:{
						title: 'Power Consumption',
						subtitle: '전기 소비량',
					},	
					legend: {position: 'none'},
					hAxis: {
						title: 'Time(시간)',	
					},
					vAxis: {
						title: 'KWh',
					},
        		}
	    	    //console.log(data);
		        var chart = new google.charts.Line(document.getElementById('chart-div'));
		        chart.draw(data, options);
			}                                                  
                                                       
		});                                                    
	}

	$(window).resize(function(){
		drawGraph();
	});
});
</script>

