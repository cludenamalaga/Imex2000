<?php
	ob_start();
	session_start();

	include ('conexion.php');

	$correo = $_POST["correo"];

	mysql_select_db('imex2000_sistema', $sistema) or die('No puedo accesar a la BD del Sistema');

	$sql = "select * from usuarios where email ='".$correo."'";
	$result = mysql_query($sql);

	if (mysql_num_rows($result)==0){ // Si no Existe correo
		$retorno="No existe ese correo";
	 } else { //Mayor que cero, Si existe
		$usuario 		= mysql_result($result, 0, "usuario");
		$nom_usuario 	= mysql_result($result, 0, "nom_usuario");
		$nivel_usuario 	= mysql_result($result, 0, "nivel_usuario");
		$clave_usuario 	= mysql_result($result, 0, "clave");
		$email_usuario 	= mysql_result($result, 0, "email");

		$mensaje = '<html>
		<head>
		  <title>Clave IMEX 2000 - Ordenes de Trabajo</title>
		</head>
		<body>
		  <p>Estimado Señor '.$nom_usuario.', sus datos son:</p>
		  <table>
		    <tr>
		      <th>Datos de recuperación</th>
		    </tr>
		    <tr>
		      <td>Nombres</td><td>'.$nom_usuario.'</td>
		    </tr>
		    <tr>
		      <td>Usuario</td><td>'.$usuario.'</td>
		    </tr>
		    <tr>
		      <td>Clave</td><td>'.$clave_usuario.'</td>
		    </tr>
		  </table>
		  <p>Departamento de Sistemas - IMEX 2000</p>
		</body>
		</html>';		

		// Para enviar un correo HTML, debe establecerse la cabecera Content-type
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

		// Cabeceras adicionales
		$cabeceras .= 'From: Sistemas IMEX 2000 <sistemas_ot@imex2000.com>' . "\r\n";

		$success = mail($email_usuario, 'Recuperación Clave', $mensaje, $cabeceras);

		if ($success) {
			$retorno = "Correo Enviado!";
		} else {
			$retorno = "Error no se envío correo!";
		}
	}
	
	echo $retorno;
?>