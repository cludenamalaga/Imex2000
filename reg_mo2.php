<?php
  ob_start();
  session_start();

  include ('conexion.php');

  mysql_select_db('imex2000_sistema', $sistema);  
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>M.Obra | IMEX 2000</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- jQuery  -->
  <script src="libs/jquery/jquery-3.2.1.min.js"></script>

  <!-- jQuery UI -->
  <script src="libs/jquery-ui/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="libs/jquery-ui/jquery-ui.min.css">


  <!-- Popper -->
  <script src="libs/popper/umd/popper.min.js"></script>
  <script src="libs/popper/umd/popper-utils.min.js"></script>

  <!-- Bootstrap -->
  <script src="libs/bootstrap/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="libs/bootstrap/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="libs/font-awesome/css/font-awesome.min.css">

  <!-- Admin LTE -->
  <script src="libs/admin/js/app.min.js"></script>
  <link rel="stylesheet" href="libs/admin/css/AdminLTE.min.css">
  <link rel="stylesheet" href="libs/admin/css/skins/skin-blue.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="libs/ionicons/css/ionicons.min.css">

  <!-- Confirm -->
  <link rel="stylesheet" href="libs/confirm/dist/jquery-confirm.min.css">
  <script src="libs/confirm/dist/jquery-confirm.min.js"></script>

  <!-- Datatables -->
  <script src="libs/datatables/datatables.min.js"></script>
  <link href="libs/datatables/datatables.min.css" rel="stylesheet"></script>

  <script type="text/javascript">
        var numero = 1;

        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };

        $.datepicker.setDefaults($.datepicker.regional['es']);

        $(function() {
            $("#opc_btns").hide();

            $("#fech_ini").datepicker({
                          onSelect: function(date) {
                                       buscar();
                                    } 
                        });

            $("#fech_fin").datepicker({
                          onSelect: function(date) {
                                       buscar();
                                    } 
                        });
        });   

        function buscar(){
            $.post('busca_ot3.php',
              {   orden: $("#no_ot").val(),
                  ini: $("#fech_ini").val(),
                  fin: $("#fech_fin").val()
              },
              function(datos) {  
                  if(datos.encontrado == "S"){
                        $("#result").html(datos.tabla);
                  } else {
                        $("#result").html(datos.mensaje);
                  }
              
            },"json");
        }

        function del_row(fila){
            $("#"+fila).remove();          
        }
        




        function escojer(cod_ot){
            $.post('poner_det.php',
              {   ot_id: cod_ot  },
              function(datos) {  
                  $("#zona_det").html(datos.tabla);
                  $("#result").html("");

                  $.post('poner_tbl_mo.php',
                    {   ot_idx: datos.id_otx  },
                    function(datos2) {  
                        $("#result").html(datos2.tabla2);  
                        $("#fec_tarea").datepicker();   

                        if(datos2.mobra == "S") {
                              numero = datos2.fila;
                              $('#opc_btns').show();
                        }               
                  },"json");                  
            },"json");
        }

        function add_row(){
            if ($("#fec_tarea").val()==""){
                  $.alert('Debe Ingresar Fecha!');
                  return;

            } else {
                if ($("#trabajo").val()==""){
                      $.alert('Debe ingresar Tarea!');
                      return;

                } else {
                    if ($("#cant_hor").val()==""){
                        $.alert('Debe ingresar Horas!');
                        return;
                    } else {
                        row = '<tr id="'+numero+'">'+
                                  '<td id="fecha_'+$("#fec_tarea").val()+'">'+ $("#fec_tarea").val()+'</td>'+
                                  '<td id="per_'+$("#cbo_personal option:selected").val()+'">'+ $("#cbo_personal option:selected").text()+'</td>'+
                                  '<td id="trabajo">'+ $("#trabajo").val()+'</td>'+
                                  '<td id="tipoh_'+$("#cbo_tiphora option:selected").val()+'">'+ $("#cbo_tiphora option:selected").text()+'</td>'+
                                  '<td id="num_horas">'+ $("#cant_hor").val()+'</td>'+
                                  '<td style="cursor:pointer" onclick="del_row(\''+numero+'\')"><i class="fa fa-trash" aria-hidden="true"></i></td>'+
                              '</tr>';  

                        $('#tbl_manobra').append(row);   
                        $('#opc_btns').show();

                        numero++;                                   
                    }
                }
            }
        }



        function cerrar(){
            // Tabla de Mano de Obra ---> tbl_manobra
            var id_ot = $('#tbl_result tr').find("td[id^='idot_']").attr("id").substr(5);    
            $.post('cancelar_mo.php',
              {   id_otz : id_ot },
              function(datos) {  

            },"json");

            window.location="reg_mo.php";
        }

        function grabando(){
              // Tabla de Mano de Obra ---> tbl_manobra
              var id_ot = $('#tbl_result tr').find("td[id^='idot_']").attr("id").substr(5);
              console.log("id_ot -> "+id_ot);

              $('#tbl_manobra tbody tr').each(function () {

                  var fecha     = $(this).find("td[id^='fecha_']").html();
                  var id_per    = $(this).find("td[id^='per_']").attr("id").substr(4);
                  var tarea     = $(this).find("td[id='trabajo']").html();
                  var tipo_hora = $(this).find("td[id^='tipoh_']").attr("id").substr(6);
                  var num_horas = $(this).find("td[id='num_horas']").html();

                  console.log(fecha);
                  console.log(id_per);
                  console.log(tarea);
                  console.log(tipo_hora);
                  console.log(num_horas);

                  $.post('graba_mo.php',
                    {   id_ot : id_ot,
                        fecha: fecha,
                        id_per: id_per,
                        tarea: tarea,
                        tipo_hora: tipo_hora,
                        num_horas: num_horas
                    },
                    function(datos) {  
                        
                  },"json");                  

              });
            $.alert('Se grabo Correctamente!');
        }
  </script>  

  <style type="text/css">
        .error {
          background-color: #E30C0C;
          color:#FFFFFF;
        } 

        .ok {
          background-color: #118219;
          color:#FFFFFF;
        }   

        #zona_tbl_manobra{
          height: 330px;
          overflow: auto;
        }
    </style>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="inicio.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IMX</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IMEX 2000</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Cambiar Nav</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="libs/admin/img/profile.png" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">
                <?php
                    echo $_SESSION["nom_usuario"];
                ?>
              </span>
            </a>
          </li>
          <li>
              <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="libs/admin/img/profile.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <?php
              if ($_SESSION["nivel_usuario"] == "A"){
                  echo $_SESSION["nom_usuario"]."<br><b>Administrador</b>";
                } else {
                  echo $_SESSION["nom_usuario"]."<br><b>Usuario</b>";
              }
          ?>          
        </div>
      </div>

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">

        <li class="header">SISTEMA ORDENES DE TRABAJO</li>

        <li>
            <a href="inicio.php"><i class="fa fa-home" aria-hidden="true"></i> <span>Inicio</span></a>
        </li>

        <li>
            <a href="ots.php"><i class="ion-wrench" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Ordenes de Trabajo</span></a>
        </li>

        <li class="active">
            <a href="reg_mo.php"><i class="fa fa-male" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Mano de Obra</span></a>
        </li>   

        <li>
            <a href="reg_mat.php"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Materiales</span></a>
        </li>     

        <li>
            <a href="reg_oc.php"><i class="fa fa-book" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Otros Costos</span></a>
        </li>  
        <li class="treeview">
          <a href="#">
            <i class="fa fa-eye"></i> <span>Consultas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.html"><i class="fa fa-circle-o"></i> Valorización OTs</a></li>
            <li><a href="valhor.php"><i class="fa fa-circle-o"></i> Valorización Horas</a></li>
            <li><a href="valmat.php"><i class="fa fa-circle-o"></i> Valorización Materiales</a></li>
            <li><a href="valava.php"><i class="fa fa-circle-o"></i> Avance OTs</a></li>
          </ul>
        </li>

        <?php
            if ($_SESSION["nivel_usuario"] == "A"){ 
               echo '<li class="treeview">
                      <a href="#">
                        <i class="fa fa-database"></i> <span>Mantenimiento Tablas</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="clientes.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                        <li><a href="personal.php"><i class="fa fa-circle-o"></i> Personal</a></li>
                        <li><a href="cargos.php"><i class="fa fa-circle-o"></i> Cargos y Precios</a></li>
                        <li><a href="tipohor.php"><i class="fa fa-circle-o"></i> Tipos de Horas</a></li>
                        <li><a href="material.php"><i class="fa fa-circle-o"></i> Materiales</a></li>
                        <li><a href="unidades.php"><i class="fa fa-circle-o"></i> Unidades</a></li>
                        <li><a href="procesos.php"><i class="fa fa-circle-o"></i> Procesos</a></li>
                        <li><a href="talleres.php"><i class="fa fa-circle-o"></i> Talleres</a></li>
                        <li><a href="estados.php"><i class="fa fa-circle-o"></i> Estados</a></li>
                      </ul>
                    </li>

                    <li>
                        <a href="usuarios.php"><i class="fa fa-user" aria-hidden="true"></i> <span>Usuarios</span></a>
                    </li>';  
            }  
        ?>

        <li>
            <a href="salir.php"><i class="fa fa-sign-out" aria-hidden="true"></i> <span>Salir</span></a>
        </li>   

      </ul>
      <!-- /.sidebar-menu -->

    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header"  style="background-color: #C0C0C0;padding-top: 5px; padding-bottom: 5px;";>
      <h1>
        Registro Mano de Obra
      </h1>
    </section>

    <!-- Main content -->
    <section class="content" style="padding-top: 0px;">
      <!-- Busqueda  -->
      <div class="row" style="margin-top: 0px;"> 
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0px; padding-right: 0px;">
              <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Busqueda</h3>
                  </div>

                  <div class="box-body" style="padding-top: 0;">   
                      <div id = "zona_det" class="row" style="margin-top: 0px;"> 
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        No. OT
                                    </span>
                                    <input type="text" class="form-control" id="no_ot" oninput="buscar()">
                                </div>   
                            </div>

                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        Fecha inicio
                                    </span>
                                    <input type="text" class="form-control" id="fech_ini">
                                </div> 
                            </div>      

                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        Fecha final
                                    </span>
                                    <input type="text" class="form-control" id="fech_fin">
                                </div> 
                            </div> 

                      </div>                            
                  </div>

              </div>
          </div>
      </div>

      <div class="row" style="margin-top: 0px;">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 0px;padding-left: 0px; padding-right: 0px;">
              <div class="box box-primary">
                  <div id="result" class="box-body" style="margin-bottom: 0px;">
                          No hay resultados
                  </div>

              </div>
          </div>
      </div>
      <!-- Opciones 
      <div id="opc_btns" class="row" style="margin-top: 0px;">

          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-top: 5px;">
                  <button type="button" class="btn btn-primary" onclick="grabando()">
                      <i class="fa fa-save" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Grabar
                  </button>      

                  <button type="button" class="btn btn-info" style="margin-left: 10px;" onclick="cerrar()">
                      <i class="fa fa-window-close-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Cerrar
                  </button>                                                      
          </div>                
      </div>    -->          
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- 
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      Departamento de Sistemas
    </div>

    <strong>&copy; 2017 <a href="#">IMEX 2000 SA</a>.</strong> Todos los derechos reservados.
  </footer>-->

  <aside class="control-sidebar control-sidebar-dark">

  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!--- Ventana Mostrar -->
<div id="ver_unidad" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>MOSTRAR UNIDAD</strong></h4>
      </div>
      <div id="result_show" class="modal-body">


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--- Ventana Editar -->
<div id="edit_ot" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>MODIFICAR ORDEN DE TRABAJO</strong></h4>
      </div>
      <div id="result_edit" class="modal-body">


      </div>
      
      <div id='mensaje_ed' class="text-center">

      </div>  
      <div class="modal-footer">
            <button type="button" id="btn_grabar_ot" class="btn btn-success" onclick="grabar()">Grabar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--- Ventana Adicionar -->
<div id="add_ot" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>NUEVA ORDEN DE TRABAJO</strong></h4>
      </div>
      <div id="result_new" class="modal-body">


      </div>
      
      <div id='mensaje_new' class="text-center">

      </div>  
      <div class="modal-footer">
            <button type="button" id="btn_grabar_new" class="btn btn-success" onclick="grabar_new()">Grabar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
