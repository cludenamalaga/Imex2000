<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$id= $_POST["varclie"];
	$con_sql = "select * from clientes where id_cliente = '".$id."'";

	// Sacando datos del Cliente

	$resulta = mysql_query($con_sql);
	
	if (mysql_num_rows($resulta)!=0) {
		$id_cli = mysql_result($resulta, 0, "id_cliente");
		$ruc = mysql_result($resulta, 0, "ruc");
		$raz_soc = mysql_result($resulta, 0, "raz_soc");
		$contacto = mysql_result($resulta, 0, "contacto");
		$direccion = mysql_result($resulta, 0, "direccion");
		$dpto = mysql_result($resulta, 0, "dpto");
		$prv = mysql_result($resulta, 0, "prv");	
		$dis= mysql_result($resulta, 0, "dis");
		$referencia= mysql_result($resulta, 0, "referencia");			
		$celular1 = mysql_result($resulta, 0, "celular1");
		$tel1 = mysql_result($resulta, 0, "tel1");
		$correo= mysql_result($resulta, 0, "correo");									
	}

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>RUC</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='ruc_ed' value='".$ruc."' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>RAZON SOCIAL</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='razon_ed' value='".$raz_soc."'></td>
				</tr>

				<tr>
					<td><strong>CONTACTO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='contacto_ed' value='".$contacto."'></td>
				</tr>
			";			

	$rpta.=" 	<tr>
					<td><strong>DIRECCION</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='direccion_ed' value='".$direccion."'></td>
				</tr>

				<tr>
					<td><strong>REFERENCIA</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='referencia_ed' value='".$referencia."'></td>
				</tr>";

	$rpta .="	<tr>
					<td><strong>DEPARTAMENTO</strong></td>
					<td><strong>PROVINCIA</strong></td>
					<td><strong>DISTRITO</strong></td>
				</tr>";

	$rpta .="	<tr>
					<td>";					
						$con_sql = "select * from ubigeo where prov='00' and dist='00' order by nombre asc";
					    $result = mysql_query($con_sql);

				$rpta .="<select class='form-control' id='depa' name='depa' onChange='llena_prov(this)'>";
           					while($fila = mysql_fetch_array($result))
	                             {								
	                                if ($fila['dep'] == $dpto){
	                                	$rpta.='<option value="'.$fila['dep'].'" selected>'.$fila['nombre'].'</option>';
	                                } else {
	                                	$rpta.='<option value="'.$fila['dep'].'">'.$fila['nombre'].'</option>';
	                                }
	                             }
        			    $rpta .="</select></td>
					<td>";

					$con_sql = "select * from ubigeo where dep='".$dpto."' and dist = '00' and prov <> '00' order by nombre asc";
					    $result = mysql_query($con_sql);

					$rpta .="<div id='zona_prov'><select class='form-control' id='prv' name='prv' onChange='llena_dist(this)'>";
	           					while($fila = mysql_fetch_array($result))
		                             {								
		                                if ($fila['prov'] == $prv){
		                                	$rpta.='<option value="'.$fila['prov'].'" selected>'.$fila['nombre'].'</option>';
		                                } else {
		                                	$rpta.='<option value="'.$fila['prov'].'">'.$fila['nombre'].'</option>';
		                                }
		                             }
	        		$rpta .="</select></div></td>
					<td>";
						$con_sql = "select * from ubigeo where dep='".$dpto."' and prov='".$prv."' and dist <> '00' order by nombre asc";
					    $result = mysql_query($con_sql);

				$rpta .="<div id='zona_dist'><select class='form-control' id='distri' name='distri'>";
           					while($fila = mysql_fetch_array($result))
	                             {								
	                                if ($fila['dist'] == $dis){
	                                	$rpta.='<option value="'.$fila['dist'].'" selected>'.$fila['nombre'].'</option>';
	                                } else {
	                                	$rpta.='<option value="'.$fila['dist'].'">'.$fila['nombre'].'</option>';
	                                }
	                             }
        		$rpta .="</select></div></td>
				</tr>

				<tr>
					<td><strong>CELULAR</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='celular_ed' value='".$celular1."' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>FIJO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='fijo_ed' value='".$tel1."' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>CORREO</strong></td>
					<td colspan='2'>
						<input type='email' class='form-control' id='correo_ed' value='".$correo."'>
						<input type='hidden' id='id_cli_ed' value='".$id_cli."'>
					</td>
				</tr>			
		  	</table>";

	echo $rpta;
?>