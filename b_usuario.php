<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$usuario	= $_POST["id_usuario"];

$con_sql = "DELETE FROM usuarios WHERE id_usuario='".$usuario."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>