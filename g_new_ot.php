<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);

$jsondata = array();

$sql = "select * from contadores";
$resulta = mysql_query($sql);

$no_ot 	= mysql_result($resulta, 0, "no_ot");

$ot = $no_ot + 1;

$ref 	= strtoupper($_POST["ref"]);
$proceso = $_POST["prc"];
$fecha 	= strtoupper($_POST["fecha"]);
$taller = strtoupper($_POST["taller"]);
$cliente = strtoupper($_POST["cliente"]);
$descrip = strtoupper($_POST["descrip"]);
$inicio = strtoupper($_POST["inicio"]);
$fin 	= strtoupper($_POST["fin"]);
$valor 	= strtoupper($_POST["valor"]);

//Grabando OT
$con_sql = "insert into ots set 
						no_ot='".$ot."',
						id_proceso='".$proceso."',
						referencia='".$ref."',
						fecha='".$fecha."', 						
						id_taller='".$taller."',
						id_cliente='".$cliente."',
						descripcion='".$descrip."',				
						inicio='".$inicio."',
						fin='".$fin."',
						valor='".$valor."',
						avance='0',
						estado='1'";	

$resulta = mysql_query($con_sql);

//Actualizando Contadores
$con_sql2 = "UPDATE contadores SET no_ot = '".$ot."'"; 
$resulta = mysql_query($con_sql2);

$jsondata['respuesta'] = "S";
$jsondata['sql'] = $con_sql;

echo json_encode($jsondata);
?>