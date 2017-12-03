<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>CARGO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='cargo_new'></td>
				</tr>

				<tr style='background-color:#A5A8A9;color:#FFFFFF;'>
					<td colspan='3'><strong>PRECIO POR TIPO DE HORA (S/.)</strong></td>
				</tr>";

	$sql = "select * from tipos_hora order by id_tipo_hora";
	$resulta = mysql_query($sql);

	if (mysql_num_rows($resulta)==0){
			$rpta.="<tr>
						<td colspan='3'>No hay Unidades Almacenadas en Tabla UNIDADES</td>
					</tr>";
		} else {
			while ($row = mysql_fetch_array($resulta)){ 
				$rpta.="<tr id='tipos_horas'>
							<td><strong>".strtoupper($row['tipo_hora'])." (".$row['simbolo'].")</strong></td>
							<td colspan='2'>
								<input type='text' class='form-control' id='id_tiphora_".$row['id_tipo_hora']."' onkeypress='return valida(event)'>
							</td>
						</tr>";
			}
	}

	$rpta.= "</table>";

	echo $rpta;
	//echo $con_sql1;
?>