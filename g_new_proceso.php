<?php
session_start();
date_default_timezone_set("America/Lima");

include ('conexion.php');
mysql_select_db('imex2000_sistema', $sistema);	

$ano = $_POST["ano"];
$mes = $_POST["mes"];
$descrip = $_POST["descrip"];
$gastos  = $_POST["gastos"];
$util  = $_POST["util"];
$igv  = $_POST["igv"];

$gastos = $gastos/100;
$util = $util/100;
$igv = $igv/100;

$sql = "select * from procesos where ano='".$ano."' and mes='".$mes."'";
$resulta = mysql_query($sql);

if(mysql_num_rows($resulta)==0){ // No existe periodo
		$con_sql = "INSERT procesos SET 
								ano='".$ano."',
								mes='".$mes."',
								descrip='".$descrip."',
								gastos='".$gastos."',
								utilidad='".$util."',
								igv='".$igv."'";
					
		$resulta = mysql_query($con_sql);

		echo "S";

	} else { // Ya Existe
		echo "N";
}
?>