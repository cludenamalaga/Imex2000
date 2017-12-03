<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  
	$id= $_POST["varpers"];
	$con_sql = "select * from personal where id_personal = '".$id."'";

	$resulta = mysql_query($con_sql);
	
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
					<td colspan='2'><input type='text' class='form-control' id='dni_ed' value='".$dni."' onkeypress='return valida2(event)'></td>
				</tr>

				<tr>
					<td><strong>NOMBRES</strong></td>
					<td colspan='2'>
						<input type='text' class='form-control' id='nombres_ed' value='".$nombres."'>
					</td>
				</tr>

				<tr>
					<td><strong>APELLIDOS</strong></td>
					<td colspan='2'>
						<input type='text' class='form-control' id='apellidos_ed' value='".$apellidos."'>
					</td>
				</tr>";			

	$rpta.=" 	<tr>
					<td><strong>DIRECCION</strong></td>
					<td colspan='2'>
						<input type='text' class='form-control' id='direccion_ed' value='".$direccion."'>
					</td>
				</tr>

				<tr>
					<td><strong>REFERENCIA</strong></td>
					<td colspan='2'>
						<input type='text' class='form-control' id='referencia_ed' value='".$referencia."'>
					</td>
				</tr>";

	$rpta .="	<tr>
					<td><strong>DEPARTAMENTO</strong></td>
					<td><strong>PROVINCIA</strong></td>
					<td><strong>DISTRITO</strong></td>
				</tr>";

	$rpta .="	<tr>
					<td>";					
						//=========================================================================
						//Departamento
						//=========================================================================
						$con_sql = "select * from ubigeo where prov='00' and dist='00' order by nombre asc";
					    $result = mysql_query($con_sql);

					    if ($dpto == 'X'){
							$rpta .="<select class='form-control' id='depa' name='depa' onChange='llena_prov_add(this)'>
									<option value='X' selected>- Elija Departamento -</option>";
									while($fila = mysql_fetch_array($result))
				                     {								
				                        $rpta.='<option value="'.$fila['dep'].'">'.$fila['nombre'].'</option>';
				                     }
							$rpta .="</select>
					</td>";	
											    	
					    } else {
							$rpta .="<select class='form-control' id='depa' name='depa' onChange='llena_prov_add(this)'>";
		           					while($fila = mysql_fetch_array($result))
			                             {								
			                                if ($fila['dep'] == $dpto){
			                                	$rpta.='<option value="'.$fila['dep'].'" selected>'.$fila['nombre'].'</option>';
			                                } else {
			                                	$rpta.='<option value="'.$fila['dep'].'">'.$fila['nombre'].'</option>';
			                                }
			                             }
	        			    $rpta .="</select>
	        			    </td>";
					    }

					    $rpta .="<td>";

					    //=========================================================================
					    //Provincia
						//=========================================================================
						$con_sql = "select * from ubigeo where dep='".$dpto."' and dist = '00' and prov <> '00' order by nombre asc";
						$result = mysql_query($con_sql);

						if ($prv == 'UN'){

							$rpta .="<div id='zona_prov'>
									 </div>
			        		</td>";

						} else {
							$rpta .="<div id='zona_prov'>
										<select class='form-control' id='prov' name='prov' onChange='llena_dist_add(this)'>";
			           					while($fila = mysql_fetch_array($result))
				                             {								
				                                if ($fila['prov'] == $prov){
				                                	$rpta.='<option value="'.$fila['prov'].'" selected>'.$fila['nombre'].'</option>';
				                                } else {
				                                	$rpta.='<option value="'.$fila['prov'].'">'.$fila['nombre'].'</option>';
				                                }
				                             }
			        		$rpta .="	</select>
			        				</div>
			        		</td>";						
						}
						//=========================================================================
						$rpta .="<td>";
						$con_sql = "select * from ubigeo where dep='".$dpto."' and prov='".$prov."' and dist <> '00' order by nombre asc";
					    $result = mysql_query($con_sql);

					    if ($dis == 'UN'){
							$rpta .="<div id='zona_dist'>
			        				</div>
			        			</td>";

					    } else {
							$rpta .="<div id='zona_dist'>
										<select class='form-control' id='distri' name='distri'>";
		           					while($fila = mysql_fetch_array($result))
			                             {								
			                                if ($fila['dist'] == $dis){
			                                	$rpta.='<option value="'.$fila['dist'].'" selected>'.$fila['nombre'].'</option>';
			                                } else {
			                                	$rpta.='<option value="'.$fila['dist'].'">'.$fila['nombre'].'</option>';
			                                }
			                             }
			        		$rpta .="	</select>
			        				</div>
			        			</td>";
					    }


		$rpta .="</tr>
				<tr>
					<td><strong>CARGO</strong></td>
					<td colspan='2'>";

				$con_sql = "select * from cargos order by cargo asc";
			    $result = mysql_query($con_sql);		

	$rpta .="			<select class='form-control' id='cargo' name='cargo'>";
								while($fila = mysql_fetch_array($result))
			                     {								
			                     	if ($fila['id_cargo'] == $id_cargo){
			                        	$rpta.='<option value="'.$fila['id_cargo'].'" selected>'.$fila['cargo'].'</option>';
			                        } else {
			                        	$rpta.='<option value="'.$fila['id_cargo'].'">'.$fila['cargo'].'</option>';
			                        }
			                     }

	$rpta .="			</select>
					</td>
				</tr>
				<tr>
					<td><strong>CELULAR</strong></td>
					<td colspan='2'>
						<input type='text' class='form-control' id='celular_ed' value='".$celular1."' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>FIJO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='fijo_ed' value='".$tel1."' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>CORREO</strong></td>
					<td colspan='2'>
						<input type='email' class='form-control' id='correo_ed' value='".$correo."'>
						<input type='hidden' id='id_personal' value='".$id_per."'>
					</td>
				</tr>
				</tbody>			
		  	</table>";

	echo $rpta;
?>