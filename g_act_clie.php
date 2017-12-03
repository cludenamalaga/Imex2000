<?php
	session_start();
	date_default_timezone_set("America/Lima");

	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);

	$id_clie    = strtoupper($_POST["id_clien"]);
	$ruc 		= trim(strtoupper($_POST["ruc"]));
	$razon	 	= strtoupper($_POST["razon"]);
	$contacto 	= strtoupper($_POST["contacto"]);
	$direccion 	= strtoupper($_POST["direccion"]);
	$dep    	= strtoupper($_POST["dep"]);
	$prov  		= strtoupper($_POST["prov"]);
	$dist 		= strtoupper($_POST["dist"]);
	$referencia = strtoupper($_POST["referencia"]);
	$celular 	= strtoupper($_POST["celular"]);
	$fijo 		= strtoupper($_POST["fijo"]);
	$correo  	= strtoupper($_POST["correo"]);

	// Actualizando el Cliente
	$con_sql = "UPDATE clientes SET 
							ruc='".$ruc."',
							raz_soc='".$razon."',
							contacto='".$contacto."', 
							direccion='".$direccion."', 
							dpto='".$dep."',
							prv='".$prov."',
							dis='".$dist."',						
							referencia='".$referencia."',
							celular1='".$celular."',
							tel1='".$fijo."',
							correo='".$correo."' 
						WHERE id_cliente = '".$id_clie."'";
	$resulta = mysql_query($con_sql);	
	
	echo "S";
?>