<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$id_proceso = $_POST["proceso"];
$ano = $_POST["ano"];
$mes = $_POST["mes"];
$descrip = $_POST["descrip"];
$gastos  = $_POST["gastos"];
$util  = $_POST["util"];
$igv  = $_POST["igv"];

$gastos = $gastos/100;
$util = $util/100;
$igv = $igv/100;

$con_sql = "UPDATE procesos SET
						ano='".$ano."',
						mes='".$mes."',
						descrip='".$descrip."',
						gastos='".$gastos."',
						utilidad='".$util."',
						igv='".$igv."'
				WHERE id_proceso='".$id_proceso."'";
			
$resulta = mysql_query($con_sql);

echo "S";
?>