<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  

	function is_empty($var, $allow_false = false, $allow_ws = false) {
	    if (!isset($var) || is_null($var) || ($allow_ws == false && trim($var) == "" && !is_bool($var)) || ($allow_false === false && is_bool($var) && $var === false) || (is_array($var) && empty($var))) {   
	        return true;
	    } else {
	        return false;
	    }
	}	

	$jsondata = array();

	$ot = $_POST["ot"];
	$ref = $_POST["ref"];
	$fecha = $_POST["fecha"];
	$taller = $_POST["taller"];	
	$cliente = $_POST["cliente"];
	$descrip = $_POST["descrip"];

	if (is_empty($_POST["inicio"])){
		$inicio = '0000-00-00';	
	} else {
		$inicio = date( "Y-m-d", strtotime(str_replace('/', '-', $_POST["inicio"])));
	}

	if (is_empty($_POST["fin"])){
		$fin = '0000-00-00';	
	} else {
		$fin = date( "Y-m-d", strtotime(str_replace('/', '-', $_POST["fin"])));
	}

	$valor = $_POST["valor"];	
	$avance = $_POST["avance"];	
	$estado = $_POST["estado"];	
	$motivo = $_POST["motiv"];	
	$obs = $_POST["observ"];	

	$con_sql = "UPDATE ots SET 
						  	referencia='".$ref."',
						  	id_taller='".$taller."',
						  	id_cliente='".$cliente."',
						  	descripcion='".$descrip."',
						  	inicio='".$inicio."',
						  	fin='".$fin."',
						  	valor='".$valor."',
						  	avance='".$avance."',
						  	estado='".$estado."',
						  	motivo='".$motivo."', 
						  	observ='".$obs."'
					  WHERE id_ot='".$ot."'"; 

	$resulta = mysql_query($con_sql);

	$jsondata['mensaje'] = "S";

	$jsondata['ini'] = $_POST["inicio"];
	$jsondata['fin'] = $_POST["fin"];

	echo json_encode($jsondata);

?>