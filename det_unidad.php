<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$unidad = $_POST["unidad"];
	$con_sql1 = "select * from unidades where id_unidad = '".$unidad."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_unidad = mysql_result($resulta, 0, "id_unidad");
		$unidad = mysql_result($resulta, 0, "unidad");
		$simbolo = mysql_result($resulta, 0, "simbolo");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>UNIDAD</strong></td>
					<td colspan='2'>".$unidad."</td>
				</tr>

				<tr>
					<td><strong>SIMBOLO</strong></td>
					<td colspan='2'>".$simbolo."</td>
				</tr>
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>