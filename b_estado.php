<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$estado	= $_POST["estado"];

$con_sql = "DELETE FROM estados WHERE id_estado='".$estado."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>