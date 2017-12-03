<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);

$dni 		= trim($_POST["dni"]);
$nombres 	= strtoupper($_POST["nombres"]);
$apellidos 	= strtoupper($_POST["apellidos"]);
$direccion 	= strtoupper($_POST["direccion"]);
$referencia = strtoupper($_POST["referencia"]);
$dep    	= strtoupper($_POST["dep"]);
$prov  		= strtoupper($_POST["prov"]);
$dist 		= strtoupper($_POST["dist"]);
$cargo 		= strtoupper($_POST["cargo"]);
$celular 	= strtoupper($_POST["celular"]);
$fijo 		= strtoupper($_POST["fijo"]);
$correo  	= strtoupper($_POST["correo"]);

$con_sql1 = "select * from personal where dni='".$dni."'";
$resulta = mysql_query($con_sql1);

if (mysql_num_rows($resulta)==0) {
		$con_sql2 = "insert into personal set 
								dni='".$dni."',
								nombres='".$nombres."',
								apellidos='".$apellidos."',
								direccion='".$direccion."', 
								referencia='".$referencia."',						
								dpto='".$dep."',
								prv='".$prov."',
								dis='".$dist."',				
								id_cargo='".$cargo."',
								celular1='".$celular."',
								tel1='".$fijo."',
								correo='".$correo."',
								estado='A'";	

		$resulta = mysql_query($con_sql2);

		echo "S";		
	}	
	else {
		echo "N";
}
?>