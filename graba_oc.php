<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema); 

	$jsondata = array();

	$no_ot = $_POST["no_ot"];
	$fecha = $_POST["fecha"];
	$id_per = $_POST["id_per"];
	$detalle = $_POST["detalle"];
	$total = $_POST["total"];

	// Sancando id de la ot de acuerto al no_ot
	$con_sql = "select * from ots WHERE no_ot='".$no_ot."'";		
	$resulta = mysql_query($con_sql);	

	$id_ot = mysql_result($resulta, 0, "id_ot");

	// Borrando los registro de la OT que han sido Marcados
	$con_sql = "DELETE FROM reg_oc WHERE id_ot='".$id_ot."' and marcado = 'B'";		
	$resulta = mysql_query($con_sql);

	// Llenamos con los nuevos registros
	$fecha = date("Y-m-d", strtotime(str_replace('/', '-', $fecha)));


	//Grabando en Costos
	$con_sql = "INSERT reg_oc SET 
								id_ot ='".$id_ot."',
								no_ot ='".$no_ot."',
								fecha ='".$fecha."',
								id_personal ='".$id_per."',
								detalle = '".addslashes($detalle)."',
								total ='".$total."'";
	$resulta = mysql_query($con_sql);

	echo json_encode($jsondata);

 ?>