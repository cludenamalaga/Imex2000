<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$personal	= $_POST["id_p"];

$con_sql = "DELETE FROM personal WHERE id_personal = '".$personal."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>