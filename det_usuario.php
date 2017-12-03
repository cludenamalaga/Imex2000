<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$usuario = $_POST["usuario"];
	$con_sql1 = "select * from usuarios where id_usuario = '".$usuario."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$cod_vendedor = mysql_result($resulta, 0, "id_usuario");
		$usuario = mysql_result($resulta, 0, "usuario");
		$clave = mysql_result($resulta, 0, "clave");
		$nombres = mysql_result($resulta, 0, "nom_usuario");
		$nivel = mysql_result($resulta, 0, "nivel_usuario");
		$correo = mysql_result($resulta, 0, "email");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>NOMBRES</strong></td>
					<td colspan='2'>".$nombres."</td>
				</tr>

				<tr>
					<td><strong>USUARIO</strong></td>
					<td colspan='2'>".$usuario."</td>
				</tr>

				<tr>
					<td><strong>CLAVE</strong></td>
					<td colspan='2'>".$clave."</td>
				</tr>
				<tr>
					<td><strong>TIPO</strong></td>
				";

				if ($nivel == "A"){
	                  $rpta .="<td>Administrador</td>";
	              } else {
	                  $rpta .="<td>Usuario</td>";
	            }
				

	$rpta .="	</tr>
				<tr>
					<td><strong>CORREO</strong></td>
					<td colspan='2'>".$correo."</td>
				</tr>
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>