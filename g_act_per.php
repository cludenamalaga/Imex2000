<?php
	session_start();
	date_default_timezone_set("America/Lima");

	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);

	$id_per    	= strtoupper($_POST["id_per"]);
	$dni 		= trim(strtoupper($_POST["dni"]));
	$nombres	= strtoupper($_POST["nombres"]);
	$apellidos 	= strtoupper($_POST["apellidos"]);
	$direccion 	= strtoupper($_POST["direccion"]);
	$dep    	= strtoupper($_POST["dep"]);
	$prov  		= strtoupper($_POST["prov"]);
	$dist 		= strtoupper($_POST["dist"]);
	$referencia = strtoupper($_POST["referencia"]);
	$cargo 		= strtoupper($_POST["cargo"]);
	$celular 	= strtoupper($_POST["celular"]);
	$fijo 		= strtoupper($_POST["fijo"]);
	$correo  	= strtoupper($_POST["correo"]);

	// Actualizando Personal
	$con_sql = "UPDATE personal SET 
							dni='".$dni."',
							nombres='".$nombres."',
							apellidos='".$apellidos."', 
							direccion='".$direccion."', 
							dpto='".$dep."',
							prv='".$prov."',
							dis='".$dist."',						
							referencia='".$referencia."',
							id_cargo='".$cargo."',
							celular1='".$celular."',
							tel1='".$fijo."',
							correo='".$correo."' 
						WHERE id_personal = '".$id_per."'";
	$resulta = mysql_query($con_sql);	
	
	echo "S";
?>