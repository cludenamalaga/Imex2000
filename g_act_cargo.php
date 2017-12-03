<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

// Recogiendo JSON
$id_cargo = $_POST["id_cargo"];
$cargo    = $_POST["cargo"]; //Nombre del Cargo
$precios = json_decode($_POST['precios'],true);

// Actualizando en Cargos
$con_sql = "UPDATE cargos SET 
						cargo='".$cargo."'
				WHERE id_cargo = '".$id_cargo."'";
$resulta = mysql_query($con_sql);

// Grabando Los precios en precio_xtiphora
$precios_length = count($precios['id_hora']); 
for($i=0; $i<$precios_length; $i++) {
	$con_sql2 = "UPDATE precio_xtiphora SET 
						  	precio='".$precios['precio'][$i]."'
					  WHERE id_cargo='".$id_cargo."' and 
					  		id_tipo_hora='".$precios['id_hora'][$i]."'"; 
	$resulta = mysql_query($con_sql2);	
}

//echo $con_sql;
echo "S";
?>