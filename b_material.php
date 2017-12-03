<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$material	= $_POST["cod_ma"];


$sql = "select * from reg_mat where id_material = '".$material."'";
$result = mysql_query($sql);

if (mysql_num_rows($result) == 0) {
	$con_sql = "DELETE FROM materiales WHERE id_material='".$material."'";
	$resulta = mysql_query($con_sql);

	echo "S";

} else { // Hay material registrado NO BORRAR
	echo "N";
}

?>