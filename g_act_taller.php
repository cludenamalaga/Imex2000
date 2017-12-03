<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$id_taller = $_POST["id_taller"];
$taller    = $_POST["taller"];

$con_sql = "UPDATE talleres SET 
						taller='".$taller."'
				WHERE id_taller = '".$id_taller."'";
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>