<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$id_tipo	= $_POST["cod_tipo"];

$con_sql = "DELETE FROM tipos_hora WHERE id_tipo_hora='".$id_tipo."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>