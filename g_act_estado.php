<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$id_estado = $_POST["id_estado"];
$estado   = $_POST["estado"];

$con_sql = "UPDATE estados SET 
						estado='".$estado."'
				WHERE id_estado = '".$id_estado."'";
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>