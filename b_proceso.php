<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$proceso = $_POST["proceso"];

$con_sql = "DELETE FROM procesos WHERE id_proceso='".$proceso."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>