<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

// Recogiendo JSON
$cargo    = $_POST["cargo"]; //Nombre del Cargo
$precios = json_decode($_POST['precios'],true);

// Grabando en Cargos
$con_sql = "INSERT cargos SET 
						cargo='".$cargo."'";
			
$resulta = mysql_query($con_sql);

// Recuperando ultimo Id en Cargos
$id_del_cargo = mysql_insert_id();

// Grabando Los precios en precio_xtiphora
$precios_length = count($precios['id_hora']); 
for($i=0; $i<$precios_length; $i++) {
	$con_sql = "INSERT INTO precio_xtiphora SET 
							id_cargo='".$id_del_cargo."',
							id_tipo_hora='".$precios['id_hora'][$i]."',
						  	precio='".$precios['precio'][$i]."'"; 
	$resulta = mysql_query($con_sql);	
}

//echo $con_sql;
echo "S";
?>