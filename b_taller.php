<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$taller	= $_POST["cod_taller"];

$con_sql = "DELETE FROM talleres WHERE id_taller='".$taller."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>