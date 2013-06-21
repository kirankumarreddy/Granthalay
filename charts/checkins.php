<?php
	include "../libchart/libchart/classes/libchart.php";
	//create a new chart object
	$chart = new VerticalBarChart(500, 250);

	//create some bars
 	$dataSet = new XYDataSet();
	$dataSet->addPoint(new Point("Jan 2005", 273));
	$dataSet->addPoint(new Point("Feb 2005", 321));
	$dataSet->addPoint(new Point("March 2005", 442));
	$dataSet->addPoint(new Point("April 2005", 711));

	//link the dataset to the chart
	$chart->setDataSet($dataSet);

	//display the chart
	$chart->setTitle("Monthly usage for www.example.com");
	$chart->render("../libchart/demo/generated/demo1.png");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Libchart pie chart demonstration</title>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15" />
</head>
<body>
	<img alt="Bar chart"  src="../libchart/demo/generated/demo1.png" style="border: 1px solid gray;"/>
</body>
</html>
