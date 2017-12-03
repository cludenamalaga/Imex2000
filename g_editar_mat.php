<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  
	$jsondata = array();

	
	$id_fila = $_POST["id_fila"];
	$no_ot = $_POST["no_ot"];
	$fecha = $_POST["fecha"];
	$id_per = $_POST["id_per"];

	$id_material = $_POST["id_mat"];

	$cant = $_POST["cant"];

	$no_ot = ltrim($no_ot, "0");


	//Sacando nombres ==================================================
	$sql_per="select * from personal where id_personal = '".$id_per."'";
    $query = mysql_query($sql_per);  

    $nombres =  mysql_result($query, 0, "apellidos")." ".mysql_result($query, 0, "nombres");

    //==================================================================
    $sql_material="select materiales.id_material,
    					  materiales.material,
    					  unidades.unidad,
    					  unidades.simbolo
    			     from materiales, unidades 
    			     where materiales.id_unidad = unidades.id_unidad and
    			     	   materiales.id_material = '".$id_material."'";

    $query = mysql_query($sql_material);  

    $material =  mysql_result($query, 0, "material")." (".mysql_result($query, 0, "simbolo").")";

    //==================================================================


    $fecha_trf = date('Y-m-d', strtotime(str_replace('/', '-', $fecha )));

	$jsondata['fila'] = '		<td id="fecha_'.$fecha_trf.'" class="text-center">'.$fecha.'</td>
								<td id="ot" class="text-center">'
									.str_pad($no_ot, 5, "0", STR_PAD_LEFT).
							   '</td>
                                <td id="per_'.$id_per.'">'.$nombres.'</td>

                                <td id="mat_'.$id_material.'" class="text-center">'.strtoupper($material).'</td>

                                <td id="cantidad" class="text-center">'.$cant.'</td>
                                <td class="text-center">
                                	<button type="button" class="btn btn-success btn-sm" onclick="consultar(\''.$id_fila.'\')" data-toggle="modal" data-target="#ver_mo"><span class="glyphicon glyphicon-search"></span></button>
                                	<button type="button" class="btn btn-warning btn-sm" onclick="editar(\''.$id_fila.'\')" data-toggle="modal" data-target="#edit_mo"><span class="glyphicon glyphicon-edit"></span></button>
                                	<button type="button" class="btn btn-danger  btn-sm" onclick="del_row(\''.$id_fila.'\')"><span class="glyphicon glyphicon-remove"></span></button>
                                </td>';

                                

    $jsondata['id_fila_val'] = $id_fila;
    $jsondata["no_ot"] = $_POST["no_ot"];

	echo json_encode($jsondata);
?>