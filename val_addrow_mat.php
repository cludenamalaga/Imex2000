<?php
	ob_start();
	session_start();

	include ('conexion.php');

	mysql_select_db('imex2000_sistema', $sistema);  
	$jsondata = array();

	$fecha = $_POST["fecha"];
	$no_ot = $_POST["ot"];
	$id_per = $_POST["id_per"];
	$id_mat = $_POST["id_mat"];
	$cant = $_POST["cnt_mat"];


	function generarCodigo($longitud) {
		 $key = '';
		 $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
		 $max = strlen($pattern)-1;
		 for($i=0;$i < $longitud;$i++) $key .= $pattern{mt_rand(0,$max)};
		 return $key;
	}
	//===========================================================================================

	// Validando si existe la OT
	$sql = "select * from ots where no_ot = '".$no_ot."' and estado = '1'";
	$resulta = mysql_query($sql);

	if(mysql_num_rows($resulta)==0){ // No hay OT
		$jsondata['encontrado']="N";

	} else {
		$jsondata['encontrado']="S";

		$id = generarCodigo(6);

		//Sacando nombres ==================================================
		$sql_per="select * from personal where id_personal = '".$id_per."'";
        $query = mysql_query($sql_per);  

        $nombres =  mysql_result($query, 0, "apellidos")." ".mysql_result($query, 0, "nombres");
        //==================================================================

        $sql_mat = "select  materiales.id_material,
	          				materiales.material,
	          				materiales.id_unidad,
	          				materiales.precio,
	          				unidades.unidad,
	          				unidades.simbolo 
	          		   from materiales, unidades
	          		  where materiales.id_unidad = unidades.id_unidad and
	          		  		materiales.id_material = '".$id_mat."'
	          		  order by materiales.material";
        $query = mysql_query($sql_mat); 

        $material =  mysql_result($query, 0, "material")." (".mysql_result($query, 0, "simbolo").")";
        //==================================================================        

		$fecha_mod = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));

		$jsondata['fila'] = '<tr id="'.$id.'">
									<td id="fecha_'.$fecha_mod.'" class="text-center">'.$fecha.'</td>
									<td id="ot" class="text-center">'
										.str_pad($no_ot, 5, "0", STR_PAD_LEFT).
								   '</td>
                                    <td id="per_'.$id_per.'">'.$nombres.'</td>
             
                                    <td id="mat_'.$id_mat.'" class="text-center">'.strtoupper($material).'</td>
                                    <td id="cantidad" class="text-center">'.$cant.'</td>
                                    <td class="text-center">
                                    	<button type="button" class="btn btn-success btn-sm" onclick="consultar_new(\''.$id.'\')" data-toggle="modal" data-target="#ver_mo"><span class="glyphicon glyphicon-search"></span></button>
                                    	<button type="button" class="btn btn-warning btn-sm" onclick="editar_new(\''.$id.'\')" data-toggle="modal" data-target="#edit_mo_n"><span class="glyphicon glyphicon-edit"></span></button>
                                    	<button type="button" class="btn btn-danger  btn-sm" onclick="del_row(\''.$id.'\')"><span class="glyphicon glyphicon-remove"></span></button>
                                    </td>
                             </tr>';
	}

	echo json_encode($jsondata);
?>