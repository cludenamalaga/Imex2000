<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$taller  = $_POST["taller"];

$con_sql = "INSERT talleres SET 
						taller='".$taller."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>