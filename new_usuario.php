<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>NOMBRES</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='nombres_new'></td>
				</tr>

				<tr>
					<td><strong>USUARIO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='usuario_new'></td>
				</tr>

				<tr>
					<td><strong>TIPO</strong></td>
					<td>
						<select id='cbo_tipuser_new' class='form-control'>
								<option value='A'>Administrador</option>
								<option value='M'>Almacén</option>
								<option value='U' selected>Usuario</option>
						</select>
					</td>
				</tr>";		

	$rpta .="   <tr>
					<td><strong>CLAVE</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='clave_new'></td>
				</tr>

				<tr>
					<td><strong>CORREO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='correo_new'></td>
				</tr>
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>