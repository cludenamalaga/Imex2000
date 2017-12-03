<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);

$jsondata = array();

$ruc 		= trim($_POST["ruc"]);
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

$con_sql1 = "select * from clientes where ruc='".$ruc."'";	// Ver si ya existe Cliente
$resulta = mysql_query($con_sql1);

if (mysql_num_rows($resulta)==0) {
		$con_sql2 = "insert into clientes set 
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
								correo='".$correo."',
								estado='A'";	

		$resulta = mysql_query($con_sql2);

		$id=mysql_insert_id();

		$jsondata['respuesta'] = "S";	
		$jsondata['id_cliente'] = $id;
	}	
	else {
		$jsondata['respuesta'] = "N";	
}
echo json_encode($jsondata);
?>