<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$cargo = $_POST["cargo"];
	$con_sql1 = "select * from cargos where id_cargo = '".$cargo."'";

	// Sacando datos del Vendedor

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_cargo = mysql_result($resulta, 0, "id_cargo");
		$cargo= mysql_result($resulta, 0, "cargo");
	}

	//--------------------------------------------------------------------------------
	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>CARGO</strong></td>
					<td colspan='2'>
						<input type='text' class='form-control' id='cargo_ed' value='".$cargo."'>
					</td>
				</tr>";

	// Sacando los Precios del Cargo
	$sql = "select cargos.id_cargo,
                    cargos.cargo,
                    tipos_hora.id_tipo_hora,
                    tipos_hora.tipo_hora,
                    tipos_hora.simbolo,
                    precio_xtiphora.precio
				from cargos, tipos_hora, precio_xtiphora
				where precio_xtiphora.id_cargo = cargos.id_cargo and
				   precio_xtiphora.id_tipo_hora = tipos_hora.id_tipo_hora and
				   cargos.id_cargo ='".$id_cargo."' 
				order by cargo, id_tipo_hora asc";

	$resulta = mysql_query($sql);

		$rpta.="<tr style='background-color:#A5A8A9;color:#FFFFFF;'>
					<td colspan='3'><strong>PRECIO POR TIPO DE HORA (S/.)</strong></td>
				</tr>";

	while ($row = mysql_fetch_array($resulta)){ 
			$rpta.="<tr id='tipos_horas'>
						<td><strong>".strtoupper($row['tipo_hora'])." (".$row['simbolo'].")</strong></td>
						<td colspan='2'><input type='text' class='form-control' id='id_tiphora_".$row['id_tipo_hora']."' value='".$row['precio']."' onkeypress='return valida(event)'></td>
					</tr>";
	}
	
	$rpta .="<input type='hidden' id='id_cargo_ed' value='".$id_cargo."'>";

	$rpta.= "</table>"; 

	echo $rpta;
	//echo $con_sql1;
?>