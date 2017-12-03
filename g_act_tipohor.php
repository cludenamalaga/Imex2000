<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$id_tipo = $_POST["id_tipo"];
$tipo    = $_POST["tipo"];
$simbolo	= $_POST["simbolo"];

$con_sql = "UPDATE tipos_hora SET 
						tipo_hora='".$tipo."',
						simbolo='".$simbolo."'
				WHERE id_tipo_hora = '".$id_tipo."'";
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>