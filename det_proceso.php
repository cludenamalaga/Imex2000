<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$proceso = $_POST["proceso"];
	$con_sql1 = "select * from procesos where id_proceso = '".$proceso."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_proceso = mysql_result($resulta, 0, "id_proceso");
		$ano = mysql_result($resulta, 0, "ano");
		$mes = mysql_result($resulta, 0, "mes");
		$descrip = mysql_result($resulta, 0, "descrip");
		$gastos = mysql_result($resulta, 0, "gastos");
		$utilidad = mysql_result($resulta, 0, "utilidad");
		$igv = mysql_result($resulta, 0, "igv");

		$gastos = $gastos * 100;
		$utilidad = $utilidad * 100;
		$igv = $igv * 100;
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>AÑO</strong></td>
					<td colspan='2'>".$ano."</td>
				</tr>

				<tr>
					<td><strong>MES</strong></td>
					<td colspan='2'>".str_pad($mes, 2, "0", STR_PAD_LEFT)."</td>
				</tr>

				<tr>
					<td><strong>DESCRIPCION</strong></td>
					<td colspan='2'>".$descrip."</td>
				</tr>

				<tr>
					<td><strong>GASTOS (%)</strong></td>
					<td colspan='2'>".$gastos."</td>
				</tr>

				<tr>
					<td><strong>UTILIDAD (%)</strong></td>
					<td colspan='2'>".$utilidad."</td>
				</tr>

				<tr>
					<td><strong>IGV (%)</strong></td>
					<td colspan='2'>".$igv."</td>
				</tr>				
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>