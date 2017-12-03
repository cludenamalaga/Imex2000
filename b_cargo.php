<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$cargo	= $_POST["cod_cargo"];

//Borrando Cargo en Cargos
$con_sql = "DELETE FROM cargos WHERE id_cargo='".$cargo."'";			
$resulta = mysql_query($con_sql);

//Borrando Cargo en precio_xtiphora - Precios
$con_sql = "DELETE FROM precio_xtiphora WHERE id_cargo='".$cargo."'";			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>