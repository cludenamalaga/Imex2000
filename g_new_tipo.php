<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$tipo  = $_POST["tipo"];
$simbolo = $_POST["simbolo"];

// Grabando en tipos_hora
$con_sql = "INSERT tipos_hora SET 
						tipo_hora='".$tipo."',
						simbolo='".$simbolo."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>