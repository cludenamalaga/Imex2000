<?php
  ob_start();
  session_start();

  include ('conexion.php');

  mysql_select_db('imex2000_sistema', $sistema);  

  $ot_idx = $_POST["ot_idx"];

  // Averiaguando si ya hay mano de obra
  $sql = "select * from mano_obra where id_ot = '".$ot_idx."'";
  $query_mo = mysql_query($sql);

  if (mysql_num_rows($query_mo) == 0) {
        $jsondata['tabla2'] ='
            <div class="row" style="margin-top: 0px;"> 
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 0px;">
                      <form class="form-inline">
                              <input type="text" class="form-control" id="fec_tarea" aria-describedby="basic-addon1">';
                          
                              $sql="select * from personal order by apellidos, nombres";
                              $query = mysql_query($sql);  

                              $jsondata['tabla2'] .= '<select id="cbo_personal" class="form-control">';

                              if (mysql_num_rows($query)){
                                      while ($row = mysql_fetch_array($query)){
                                              $jsondata['tabla2'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
                                      }
                                      $jsondata['tabla2'] .=  '</select>';
                              }

      $jsondata['tabla2'] .= '<input type="text" class="form-control" id="trabajo" placeholder="Tarea Realizada" aria-describedby="basic-addon1">'; 

      $jsondata['tabla2'] .= '<select id="cbo_tiphora" class="form-control">
                                    <option value="N">Hora Normal</option>
                                    <option value="E">Hora Extra</option>
                                    <option value="D">Hora Doble</option>
                              </select>'; 

      $jsondata['tabla2'] .=  '<input type="number" min="0" max="15" class="form-control" id="cant_hor" aria-describedby="basic-addon1" style="width: 8%">

                              <button type="button" class="btn btn-success" aria-label="Left Align" onclick="add_row()">
                                  <i class="fa fa-plus" aria-hidden="true"></i>
                              </button>      
                      </form> 
                </div>
            </div>

            <div class="row" style="margin-top: 0px;"> 
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 0px;">
                        <div id="zona_tbl_manobra">
                              <table id="tbl_manobra" class="table table-hover">
                                  <thead>
                                      <th class="text-center">
                                          Fecha
                                      </th>
                                      <th class="text-center">
                                          Personal
                                      </th>
                                      <th class="text-center">
                                          Tarea
                                      </th>
                                      <th class="text-center">
                                          Tipo Hora
                                      </th>
                                      <th class="text-center">
                                          Cnt.Hrs
                                      </th>
                                      <th class="text-center">
                                          <i class="fa fa-trash" aria-hidden="true"></i>
                                      </th>
                                  </thead>
                                  <tbody>

                                  </tbody>
                                  
                              </table>  
                        </div> 
                </div>                            
            </div>'; 

     $jsondata['mobra'] = "N";

  } else {
      // Si hay registro de mano de obra anterior ============================================
      $jsondata['tabla2'] ='
      <div class="row" style="margin-top: 0px;"> 
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 0px;">
                <form class="form-inline">
                        <input type="text" class="form-control" id="fec_tarea" aria-describedby="basic-addon1">';
                    
                        $sql="select * from personal order by apellidos, nombres";
                        $query = mysql_query($sql);  

                        $jsondata['tabla2'] .= '<select id="cbo_personal" class="form-control">';

                        if (mysql_num_rows($query)){
                                while ($row = mysql_fetch_array($query)){
                                        $jsondata['tabla2'] .=  '<option value="'.$row['id_personal'].'">'.$row['apellidos'].' '.$row['nombres'].'</option>'; 
                                }
                                $jsondata['tabla2'] .=  '</select>';
                        }

$jsondata['tabla2'] .= '<input type="text" class="form-control" id="trabajo" placeholder="Tarea Realizada" aria-describedby="basic-addon1">'; 

$jsondata['tabla2'] .= '<select id="cbo_tiphora" class="form-control">
                              <option value="N">Hora Normal</option>
                              <option value="E">Hora Extra</option>
                              <option value="D">Hora Doble</option>
                        </select>'; 

$jsondata['tabla2'] .=  '<input type="number" min="0" max="15" class="form-control" id="cant_hor" aria-describedby="basic-addon1" style="width: 8%">

                        <button type="button" class="btn btn-success" aria-label="Left Align" onclick="add_row()">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>      
                </form> 
          </div>
      </div>

      <div class="row" style="margin-top: 0px;"> 
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 0px;">
                  <div id="zona_tbl_manobra">
                        <table id="tbl_manobra" class="table table-hover">
                            <thead>
                                <th class="text-center">
                                    Fecha
                                </th>
                                <th class="text-center">
                                    Personal
                                </th>
                                <th class="text-center">
                                    Tarea
                                </th>
                                <th class="text-center">
                                    Tipo Hora
                                </th>
                                <th class="text-center">
                                    Cnt.Hrs
                                </th>
                                <th class="text-center">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </th>
                            </thead>
                            <tbody>';

                            $no_fila = 0;

                            while ($row_mo = mysql_fetch_array($query_mo)){

                                    $no_fila = $no_fila + 1;

                                    // Fecha
                                    $fecha = date("d/m/Y", strtotime(str_replace('-', '/', $row_mo['fecha'])));

                                    // Nombres del Personal
                                    $sql = "select * from personal where id_personal = '".$row_mo['id_personal']."'";
                                    $sql_per = mysql_query($sql);

                                    $nombres =  mysql_result($sql_per, 0, "apellidos")." ".mysql_result($sql_per, 0, "nombres");

                                    // Tipo de hora (nombres) y simbolo
                                    $sql = "select * from tipos_hora where id_tipo_hora = '".$row_mo['id_tipo_hora']."'";
                                    $sql_hor = mysql_query($sql);

                                    $nomb_tp =  mysql_result($sql_hor, 0, "tipo_hora");
                                    $simb_tp =  mysql_result($sql_hor, 0, "simbolo");

            $jsondata['tabla2'] .='<tr id="'.$no_fila.'">
                                      <td id="fecha_'.$fecha.'">'.$fecha.'</td>
                                      <td id="per_'.$row_mo['id_personal'].'">'.$nombres.'</td>
                                      <td id="trabajo">'.$row_mo['trabajo'].'</td>
                                      <td id="tipoh_'.$simb_tp.'">'.$nomb_tp.'</td>
                                      <td id="num_horas">'.$row_mo['num_horas'].'</td>
                                      <td style="cursor:pointer" onclick="del_row(\''.$no_fila.'\')"><i class="fa fa-trash" aria-hidden="true"></i></td>
                                  </tr>';
                            }                         

     $jsondata['tabla2'] .='</tbody>
                            
                        </table>  
                  </div> 
          </div>                            
      </div>'; 

    $jsondata['fila'] = $no_fila + 1;
    $jsondata['mobra'] = "S";

    // Marcando los registros de toda la OT para borrado si se graba 
    $con_sql = "UPDATE mano_obra SET marcado = 'B' WHERE id_ot = '".$ot_idx."'";
    $resulta = mysql_query($con_sql);

  }

  echo json_encode($jsondata);
?>