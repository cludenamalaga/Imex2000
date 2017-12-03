<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>TIPO DE HORA</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='tipo_new'></td>
				</tr>

				<tr>
					<td><strong>ABREVIATURA</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='simbolo_new'></td>
				</tr>
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>