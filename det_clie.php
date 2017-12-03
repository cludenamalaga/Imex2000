<?php
	session_start();
	include ('conexion.php');
  	mysql_select_db('imex2000_sistema', $sistema);  
  	
	$clie = $_POST["varclie"];
	$con_sql1 = "select * from clientes where id_cliente = '".$clie."'";

	$resulta1 = mysql_query($con_sql1);
	
	if (mysql_num_rows($resulta1)!=0) {
		$id_cliente = mysql_result($resulta1, 0, "id_cliente");		
		$ruc = mysql_result($resulta1, 0, "ruc");
		$raz_soc = mysql_result($resulta1, 0, "raz_soc");
		$contacto = mysql_result($resulta1, 0, "contacto");
		$direccion = mysql_result($resulta1, 0, "direccion");
		$dep = mysql_result($resulta1, 0, "dpto");
		$prov = mysql_result($resulta1, 0, "prv");
		$dist = mysql_result($resulta1, 0, "dis");

		$referencia = mysql_result($resulta1, 0, "referencia");

		$celular1 = mysql_result($resulta1, 0, "celular1");
		$celular2 = mysql_result($resulta1, 0, "celular2");

		$tel1 = mysql_result($resulta1, 0, "tel1");
		$correo = mysql_result($resulta1, 0, "correo");
		$estado = mysql_result($resulta1, 0, "estado");
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>RAZON SOCIAL</strong></td>
					<td colspan='2'>".$raz_soc."</td>
				</tr>
				<tr>
					<td><strong>RUC</strong></td>
					<td colspan='2'>".$ruc."</td>
				</tr>
				<tr>
					<td><strong>CONTACTO</strong></td>
					<td colspan='2'>".$contacto."</td>
				</tr>
			";			
	

	$rpta.=" 	<tr>
					<td><strong>DIRECCION</strong></td>
					<td colspan='2'>".$direccion."</td>
				</tr>";

				$con_sql = "select * from ubigeo where dep = '".$dep."' and prov = '00' and dist ='00'";
				$resulta = mysql_query($con_sql);
				
				if (mysql_num_rows($resulta)!=0) {
					$dep_name = mysql_result($resulta, 0, "nombre");
				}

				$con_sql = "select * from ubigeo where dep = '".$dep."' and prov = '".$prov."' and dist ='00'";
				$resulta = mysql_query($con_sql);
				
				if (mysql_num_rows($resulta)!=0) {
					$prv_name = mysql_result($resulta, 0, "nombre");
				}

				$con_sql = "select * from ubigeo where dep = '".$dep."' and prov = '".$prov."' and dist ='".$dist."'";
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


	$rpta .="</tr>	
				<tr>
					<td><strong>REFERENCIA</strong></td>
					<td colspan='2'>".$referencia."</td>
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
		  	</table>";

	echo $rpta;
?>