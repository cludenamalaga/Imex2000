<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$material  = $_POST["material"];
$unidad = $_POST["unidad"];
$precio = $_POST["precio"];

$con_sql = "INSERT materiales SET 
						material='".$material."',
						id_unidad='".$unidad."',
						precio='".$precio."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>