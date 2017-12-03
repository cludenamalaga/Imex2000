<?php
	ob_start();
	session_start();

	include ('conexion.php');

	$jsondata = array();
	$jsondata['ingreso'] = "N";

	$usuario = $_POST["id"];
	$password = $_POST["pwd"];

	mysql_select_db('imex2000_sistema', $sistema) or die('No puedo accesar a la BD del Sistema');	

	if (empty($usuario)||empty($password)){
		$jsondata['mensaje'] = "Falta ingresar datos";
	} else {
		$sql = "select * from usuarios where usuario='".$usuario."'";
		$result = mysql_query($sql);

		if (mysql_num_rows($result)==0){ // Si no Existe Usuario
			$jsondata['mensaje'] = "No existe usuario";
		} else { //Existe Usuario, Perguntar por clave
			$sql2 = "select * from usuarios where usuario='".$usuario."' and clave='".$password."'";
			$result2 = mysql_query($sql2);



			if (mysql_num_rows($result2)==0){ // Si clave no existe
				$jsondata['mensaje'] = "Clave Erronea";

			} else { // Si es usuario

				$usuario 		= mysql_result($result2, 0, "usuario");
				$nom_usuario 	= mysql_result($result2, 0, "nom_usuario");
				$nivel_usuario 	= mysql_result($result2, 0, "nivel_usuario");
				$clave_usuario 	= mysql_result($result2, 0, "clave");
				$email_usuario 	= mysql_result($result2, 0, "email");		

				$_SESSION["usuario"] = $usuario;
				$_SESSION["nom_usuario"] = $nom_usuario;
				$_SESSION["nivel_usuario"] = $nivel_usuario;

				$jsondata['ingreso'] = "S";	
				$jsondata['mensaje'] = "Bienvenido ".$nom_usuario." !";
			}
		}	

	}
	//echo $sql2;
	echo json_encode($jsondata);				
	ob_end_flush();
?>