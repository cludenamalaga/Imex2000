<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$tipo = $_POST["tipo"];
	$con_sql1 = "select * from tipos_hora where id_tipo_hora = '".$tipo."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_tipo = mysql_result($resulta, 0, "id_tipo_hora");
		$tipo = mysql_result($resulta, 0, "tipo_hora");
		$simbolo = mysql_result($resulta, 0, "simbolo");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>TIPO DE HORA</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='tipo_ed' value='".$tipo."'></td>
				</tr>

				<tr>
					<td><strong>ABREVIATURA</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='simbolo_ed' value='".$simbolo."'></td>
				</tr>";

	$rpta .="<input type='hidden' id='id_tipo_ed' value='".$id_tipo."'>";			

	$rpta .="</table>";

	echo $rpta;
	//echo $con_sql1;
?>