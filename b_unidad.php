<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$unidad	= $_POST["cod_un"];

$con_sql = "DELETE FROM unidades WHERE id_unidad='".$unidad."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>