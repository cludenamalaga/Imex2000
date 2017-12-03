<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$nombres    = $_POST["nombres"];
$usuario	= $_POST["usuario"];
$clave  	= $_POST["clave"];
$tipo_user	= $_POST["tipo"];
$correo  	= $_POST["correo"];

$con_sql = "INSERT usuarios SET 
						nom_usuario='".$nombres."',
						usuario='".$usuario."',
						clave='".$clave."', 
						nivel_usuario='".$tipo_user."', 
						email='".$correo."'";
			
$resulta = mysql_query($con_sql);

//echo $con_sql;
echo "S";
?>