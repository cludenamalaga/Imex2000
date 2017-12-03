<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$estado = $_POST["estado"];
	$con_sql1 = "select * from estados where id_estado = '".$estado."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_estado = mysql_result($resulta, 0, "id_estado");
		$estado = mysql_result($resulta, 0, "estado");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>ESTADO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='estado_ed' value='".$estado."'></td>
				</tr>";

	$rpta .="<input type='hidden' id='id_estado_ed' value='".$id_estado."'>";			

	$rpta .="</table>";

	echo $rpta;
	//echo $con_sql1;
?>