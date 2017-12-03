<?php
	session_start();
	date_default_timezone_set("America/Lima");
	
	include ('conexion.php');
	mysql_select_db('imex2000_sistema', $sistema);
  

	$rpta="	<table class='table table-striped table-bordered'>";

	$rpta.="	<tr>
					<td><strong>RUC</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='ruc_new' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>RAZON SOCIAL</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='razon_new'></td>
				</tr>

				<tr>
					<td><strong>CONTACTO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='contacto_new'></td>
				</tr>
			";			
	

	$rpta.=" 	<tr>
					<td><strong>DIRECCION</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='direccion_new'></td>
				</tr>

				<tr>
					<td><strong>REFERENCIA</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='referencia_new'></td>
				</tr>";

	$rpta .="	<tr>
					<td><strong>DEPARTAMENTO</strong></td>
					<td><strong>PROVINCIA</strong></td>
					<td><strong>DISTRITO</strong></td>
				</tr>	
				<tr>
					<td>";

	$con_sql = "select * from ubigeo where prov='00' and dist='00' order by nombre asc";
    $result = mysql_query($con_sql);

	$rpta .="<select class='form-control' id='depa' name='depa' onChange='llena_prov_add(this)'>
					<option value='X' selected>- Elija Departamento -</option>";
					while($fila = mysql_fetch_array($result))
                     {								
                        $rpta.='<option value="'.$fila['dep'].'">'.$fila['nombre'].'</option>';
                     }
	$rpta .="</select>";


	$rpta .="	   </td>
					<td>
						<div id='zona_prov'>
	         			</div>
	    			</td>";
					
	$rpta .="<td>
				<div id='zona_dist'>
				</div>
			</td>";	

	$rpta .="</tr>	
				<tr>
					<td><strong>CELULAR</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='celular_new' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>FIJO</strong></td>
					<td colspan='2'><input type='text' class='form-control' id='fijo_new' onkeypress='return valida2(event)'></td>
				</tr>
				<tr>
					<td><strong>CORREO</strong></td>
					<td colspan='2'><input type='email' class='form-control' id='correo_new'></td>
				</tr>			
		  	</table>";

	echo $rpta;
	//echo $con_sql1;
?>