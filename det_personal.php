<?php
	session_start();
	include ('conexion.php');
  	mysql_select_db('imex2000_sistema', $sistema);  
  	
	$personal = $_POST["varper"];
	$con_sql1 = "select * from personal where id_personal = '".$personal."'";

	$resulta = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_per = mysql_result($resulta, 0, "id_personal");
		$dni = mysql_result($resulta, 0, "dni");
		$nombres = mysql_result($resulta, 0, "nombres");
		$apellidos = mysql_result($resulta, 0, "apellidos");
		$direccion = mysql_result($resulta, 0, "direccion");
		$dpto = mysql_result($resulta, 0, "dpto");
		$prov = mysql_result($resulta, 0, "prv");	
		$dis= mysql_result($resulta, 0, "dis");
		$referencia= mysql_result($resulta, 0, "referencia");	
		$id_cargo= mysql_result($resulta, 0, "id_cargo");		
		$celular1 = mysql_result($resulta, 0, "celular1");
		$tel1 = mysql_result($resulta, 0, "tel1");
		$correo= mysql_result($resulta, 0, "correo");									
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="<tbody>	
				<tr>
					<td><strong>DNI</strong></td>
					<td colspan='2'>".$dni."</td>
				</tr>

				<tr>
					<td><strong>NOMBRES</strong></td>
					<td colspan='2'>".$nombres."</td>
				</tr>

				<tr>
					<td><strong>APELLIDOS</strong></td>
					<td colspan='2'>".$apellidos."</td>
				</tr>";			

	$rpta.=" 	<tr>
					<td><strong>DIRECCION</strong></td>
					<td colspan='2'>".$direccion."</td>
				</tr>

				<tr>
					<td><strong>REFERENCIA</strong></td>
					<td colspan='2'>".$referencia."</td>
				</tr>";

	$rpta .="	<tr>
					<td>";		

				$con_sql = "select * from ubigeo where dep = '".$dpto."' and prov = '00' and dist ='00'";
				$resulta = mysql_query($con_sql);
				
				if (mysql_num_rows($resulta)!=0) {
					$dep_name = mysql_result($resulta, 0, "nombre");
				}

				$con_sql = "select * from ubigeo where dep = '".$dpto."' and prov = '".$prov."' and dist ='00'";
				$resulta = mysql_query($con_sql);
				
				if (mysql_num_rows($resulta)!=0) {
					$prv_name = mysql_result($resulta, 0, "nombre");
				}

				$con_sql = "select * from ubigeo where dep = '".$dpto."' and prov = '".$prov."' and dist ='".$dis."'";
				$resulta = mysql_query($con_sql);
				
				if (mysql_num_rows($resulta)!=0) {
					$dis_name = mysql_result($resulta, 0, "nombre");
				}

	$rpta .="	<tr>
					<td><strong>DEPARTAMENTO</strong></td>
					<td><strong>PROVINCIA</strong></td>
					<td><strong>DISTRITO</strong></td>
				</tr>	
				<tr>
					<td>".$dep_name."</td>
					<td>".$prv_name."</td>
					<td>".$dis_name."</td>
				</tr>";				

				$con_sql = "select * from cargos where id_cargo = '".$id_cargo."'";
				$resulta = mysql_query($con_sql);
				
				if (mysql_num_rows($resulta)!=0) {
					$cargo_name = mysql_result($resulta, 0, "cargo");
				}

		$rpta .="</tr>
				<tr>
					<td><strong>CARGO</strong></td>
					<td colspan='2'>".strtoupper($cargo_name)."</td>
				</tr>
				<tr>
					<td><strong>CELULAR</strong></td>
					<td colspan='2'>".$celular1."</td>
				</tr>
				<tr>
					<td><strong>FIJO</strong></td>
					<td colspan='2'>".$tel1."</td>
				</tr>
				<tr>
					<td><strong>CORREO</strong></td>
					<td colspan='2'>".$correo."</td>
				</tr>
				</tbody>			
		  	</table>";

	echo $rpta;
?>