<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$cliente	= $_POST["id_clie"];

$con_sql = "DELETE FROM clientes WHERE id_cliente = '".$cliente."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>