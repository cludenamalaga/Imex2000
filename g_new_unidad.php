<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$unidad  = $_POST["unidad"];
$simbolo = $_POST["simbolo"];

$con_sql = "INSERT unidades SET 
						unidad='".$unidad."',
						simbolo='".$simbolo."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>