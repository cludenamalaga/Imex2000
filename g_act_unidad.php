<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$id_unidad = $_POST["id_unidad"];
$unidad    = $_POST["unidad"];
$simbolo	= $_POST["simbolo"];

$con_sql = "UPDATE unidades SET 
						unidad='".$unidad."',
						simbolo='".$simbolo."'
				WHERE id_unidad = '".$id_unidad."'";
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>