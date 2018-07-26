
<?php
 
$fetchUserId=mysqli_query($conn,"SELECT users.user_id AS userId FROM users WHERE users.user_name='$userName'");
while($rowUser = mysqli_fetch_array($fetchUserId, MYSQLI_ASSOC )){
 	$userIds=$rowUser['userId'];
}



 $chartWindow=mysqli_query($conn,"SELECT SUM(mockbiddata.mockbid_score) AS sumMockbidScore, COUNT(mockbiddata.mockbid_score) AS countMockbidScore, EXTRACT(MONTH FROM mockbiddata.mockbid_date) AS mockbidMonth FROM mockbiddata WHERE mockbiddata.m_user_id='$userIds' GROUP BY EXTRACT(MONTH FROM mockbiddata.mockbid_date)");

 $dataPoints=array();
 $arr=array();
 $monthWhichHasScoreArray = array();
 $month_array = array();
 $months = array("1" => "January",
				 "2" => "February",
				 "3" => "March",
				 "4" => "April",
				 "5" => "May",
				 "6" => "June",
				 "7" => "July",
				 "8" => "August",
				 "9" => "September",
				 "10" => "October",
				 "11" => "November",
				 "12" => "December");
 

while ($rowsChart = mysqli_fetch_array($chartWindow, MYSQLI_ASSOC)) {

 	$sumOfHouseAccuracy=$rowsChart['sumMockbidScore'];
 	$countOfHouseAccuracy=$rowsChart['countMockbidScore'];	
 	$totalMonthAccuracy=$sumOfHouseAccuracy/$countOfHouseAccuracy;
 	
 	$mockbidMonth = $rowsChart['mockbidMonth'];
 	$mockbidMonthName = $months[$mockbidMonth];
 	
 	$month_array[$totalMonthAccuracy] = $mockbidMonth;
	$monthWhichHasScoreArray[$mockbidMonth] = $mockbidMonthName;

 	} 
 	
 	for($x=1;$x<13;$x++){
 		if(array_key_exists($x, $monthWhichHasScoreArray)){
 			
 			$key=array_search($x, $month_array);
 			

 			$arr=array("y"=>"$key","label" => "$monthWhichHasScoreArray[$x]");
 			array_push($dataPoints,$arr);
 		}
 		else{
 			$arr=array("y"=>0,"label" =>"$months[$x]");
 			array_push($dataPoints,$arr);
 		}
 	}
?>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> 
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title: {
		text: "Your Overall Bidding accuracy over the past year"
	},
	axisY: {
		title: "Monthly House Accuracy"
	},
	axisX: {
		title: "Months"
	},
	data: [{
		type: "column",
		yValueFormatString: "#,##0.## tonnes",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
<div class="container">
	<div id="chartContainer" class="center" style="display: none; width: 46%; height: 370px;"></div>
</div>
                         