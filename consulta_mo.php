<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  
	$jsondata = array();

	$id_mo = $_POST["id_mo"];

	// Sacando el id de la OT de mano_obra
	$sql = "select * from mano_obra where id = '".$id_mo."'";
	$result = mysql_query($sql);

	$id_ot_mobra = mysql_result($result, 0, "id_ot");

	// Sacando datos de la OT
	$sql_id_ot = "select * from ots where id_ot = '".$id_ot_mobra."'";
	$result2 = mysql_query($sql_id_ot);

	$no_ot = mysql_result($result2, 0, "no_ot");
	$ref = mysql_result($result2, 0, "referencia");
	$fecha = mysql_result($result2, 0, "fecha");
	$desc = mysql_result($result2, 0, "descripcion");
	$id_clie = mysql_result($result2, 0, "id_cliente");

	// Sacando Datos Cliente
	$sql_cliente= "select * from clientes where id_cliente = '".$id_clie."'";
	$result3 = mysql_query($sql_cliente);	

	$cliente = mysql_result($result3, 0, "raz_soc");

	$jsondata['tabla'] = "	<table class='table table-striped'>
								<tr>
									<td><strong>No. OT</strong></td>
									<td>".str_pad($no_ot, 5, "0", STR_PAD_LEFT)."</td>
								</tr>
								<tr>
									<td><strong>REFERENCIA</strong></td>
									<td>".$ref."</td>
								</tr>
								<tr>
									<td><strong>FECHA</strong></td>
									<td>".date( "d/m/Y", strtotime($fecha))."</td>
								</tr>
								<tr>
									<td><strong>CLIENTE</strong></td>
									<td>".$cliente."</td>
								</tr>								
								<tr>
									<td><strong>ESCRIPCION</strong></td>
									<td>".$desc."</td>
								</tr>

							</table>";

	echo json_encode($jsondata);
?>