<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$id_material = $_POST["id_material"];
$material    = $_POST["material"];
$id_unidad	= $_POST["id_unidad"];
$precio	= $_POST["precio"];

$con_sql = "UPDATE materiales SET 
						material='".$material."',
						id_unidad='".$id_unidad."',
						precio='".$precio."'
				WHERE id_material = '".$id_material."'";
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>