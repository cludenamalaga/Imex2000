<?php
  ob_start();
  session_start();

  if (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == ' ') {
        header('Location: index.php'); 
  }


  include ('conexion.php');

  mysql_select_db('imex2000_sistema', $sistema);  
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Materiales | IMEX 2000</title>
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
            $("#ptlla").hide();

            $("#fecha_b").datepicker({
                          onSelect: function(date) {
                                       buscar();
                                    } 
                        });
        });   

        function buscar(){
            $("#ptlla").show();

            $.post('busca_ot4.php',
              {   orden: $("#no_ot").val(),
                  fecha_b: $("#fecha_b").val()
              },
              function(datos) {  


                  if(datos.encontrado == "S"){
                        $("#result").html(datos.tabla);
                        $("#fec_tarea").datepicker();
                        $("#opc_btns").show();
                        
                  } else {
                        $("#result").html(datos.tabla);
                        $("#fec_tarea").datepicker();
                  }
              
            },"json");
        }

        function add_row_new(){
            if ($("#fec_tarea").val()==""){
                  $.alert('Debe Ingresar Fecha!');
                  return;

            } else {
                if ($("#no_ot_tarea").val()==""){
                  $.alert('Debe Ingresar Número de OT!');
                  return;          

                } else {
                    if ($("#cant").val()==""){
                        $.alert('Debe cantidad del Material!');
                        return;

                    } else {
                        $.post('val_addrow_n_mat.php',
                              {   
                                  fecha: $("#fec_tarea").val(),
                                  ot: $("#no_ot_tarea").val(),
                                  id_per: $("#cbo_personal").val(),
                                  id_mat: $("#cbo_material").val(),
                                  cnt_mat: $("#cant").val()
                              },
                          function(datos) {  
                              if(datos.encontrado == "S"){
                                    $('#tbl_manobra').append(datos.fila); 
                                    $('#opc_btns').show();
                                    
                              } else {
                                    $.alert('No Existe esa OT o no está ACTIVA!');
                                    return;
                              }
                          
                        },"json");
                    }
                }
            }
        }

        function consultar_new(id_mobra){
            $.post('consulta_mo_n.php',
              {   no_ot : $("#tbl_manobra").find("tr[id='"+id_mobra+"']").find("td[id='ot']").html() },
              function(datos) {  
                $("#result_show").html(datos.tabla);
            },"json");
        }      

        function del_row(fila){
            $.confirm({
                title: 'Advertencia',
                icon: 'fa fa-exclamation-triangle',
                type: 'red',
                boxWidth: '40%',
                useBootstrap: false,
                content: '¿ Borrar Registro de Material ?',
                buttons: {
                    Si: {
                        text: 'Sí, Borrarlo', 
                        btnClass: 'btn-red',
                        action: function () {
                              $("#tbl_manobra").find("tr[id='"+fila+"']").remove();  
                        }
                    },
                    Cancelar: {
                        text: 'Cancelar', 
                        action: function () {
                       
                        }
                    }
                }
            });               
        }

        function editar_new(id_mobra){
            $('#mensaje_ed_n').html('');
            $('#btn_grabar_mat_n').attr("disabled", false);   

            var no_ot = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id='ot']").html();

            var fecha = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id^='fecha_']").html();

            var id_per= $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id^='per_']").attr("id").substr(4);

            var id_mat = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id^='mat_']").attr("id").substr(4);

            var cant_mat = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id='cantidad']").html();

            $.post('editar_mat_n.php',
              {   id_fila : id_mobra,
                  no_ot_n : no_ot,
                  fecha: fecha,
                  id_per: id_per,
                  id_mat: id_mat,
                  cant_mat: cant_mat
              },
              function(datos) {  
                  $("#result_edit_n").html(datos.tabla);
                  $("#fecha_ed").datepicker();
                  $('#btn_grabar_mat_n').attr("disabled", false); 
            },"json");            
        }

        function grabar_ed_n(){
              $('#btn_grabar_mat_n').attr("disabled", false); 

              if ($("#fecha_ed").val() == ""){
                  $("#mensaje_ed").html("Debe Ingresar Fecha");
                  $('#mensaje_ed').addClass("error");
                  $("#fecha_ed").focus();
              } else {
                  if ($("#cant").val() == ""){
                      $("#mensaje_ed").html("Debe Ingresar Cantidad");
                      $('#mensaje_ed').addClass("error");
                      $("#cant").focus();
                  } else {
                        console.log("id_fila->"+$("#id_fila_mat").val());

                        console.log('no_ot->'+$("#var_mat_no_ot").val());

                        console.log('fecha->'+$("#fecha_ed").val());
                            
                        console.log('id_per->'+$("#cbo_personal_ed").val());

                        console.log('id_mat->'+$("#cbo_material").val());

                        console.log('cant->'+$("#cant").val());

                      $.post('g_editar_mat_n.php',
                        {   id_fila: $("#id_fila_mat").val(),
                            no_ot: $("#var_mat_no_ot").val(), 
                            fecha: $("#fecha_ed").val(),
                            
                            id_per: $("#cbo_personal_ed").val(),

                            id_mat: $("#cbo_material_ed").val(),

                            cant: $("#canti_mat_ed").val()
                        },
                        function(datos) {  
                    
                            $('#tbl_manobra').find("tr[id='"+datos.id_fila_val+"']").html(datos.fila); 
                            $('#mensaje_ed_n').html('Modificaciones hechas');
                            $('#mensaje_ed_n').addClass("ok");
                            $('#btn_grabar_mat_n').attr("disabled", true);   
                      },"json");                      
                  }
              }
        }

        function grabando(){
              // Extrayendo OT de la busqueda
              var busca_no_ot = $("#no_ot").val();
              console.log('ot del form -> '+busca_no_ot);

              var no_filas = $("#tbl_manobra tbody tr").length;

              if (no_filas != 0){
                  $('#tbl_manobra tbody tr').each(function () {
                      var tbl_no_ot     = $(this).find("td[id='ot']").html();
                      var tbl_fecha     = $(this).find("td[id^='fecha_']").html();
                      var tbl_id_per    = $(this).find("td[id^='per_']").attr("id").substr(4);
                      var tbl_id_mat = $(this).find("td[id^='mat_']").attr("id").substr(4);
                      var tbl_canti_mat = $(this).find("td[id='cantidad']").html();

                      
                      console.log(tbl_no_ot);
                      console.log(tbl_fecha);
                      console.log(tbl_id_per);
                      console.log(tbl_id_mat);
                      console.log(tbl_canti_mat);


                      $.post('graba_mat.php',
                        {   
                            bus_no_ot : busca_no_ot,
                            no_ot     : tbl_no_ot,
                            fecha     : tbl_fecha,
                            id_per    : tbl_id_per,
                            id_mat    : tbl_id_mat,
                            canti_mat : tbl_canti_mat
                        },
                        function(datos) {  
                            $('#grabar_todo').attr("disabled", true); 
                      },"json");                  

                  });

                  $.alert('Se grabo Correctamente!');
              } else { // No hay Tabla, quitar todo de la OT
                      $.post('del_all_mat.php',
                        {   
                            bus_no_ot : busca_no_ot
                        },
                        function(datos) {  
                            $('#grabar_todo').attr("disabled", true); 
                      },"json");           

                      $.alert('Se grabo Correctamente!');       
              }
        }

        function cerrar(){
            $.post('cancelar_mat.php',
              function(datos) {  

             },"json");

             window.location="reg_mat.php";
        }

        function add_row(){

            if ($("#fec_tarea").val()==""){
                  $.alert('Debe Ingresar Fecha!');
                  return;

            } else {
                if ($("#no_ot_tarea").val()==""){
                  $.alert('Debe Ingresar Número de OT!');
                  return;          

                } else {
                    if ($("#cant").val()==""){
                        $.alert('Debe ingresar Cantidad del Material!');
                        return;
                    } else {
                        $.post('val_addrow_mat.php',
                              {   
                                  fecha   : $("#fec_tarea").val(),
                                  ot      : $("#no_ot_tarea").val(),
                                  id_per  : $("#cbo_personal").val(),
                                  id_mat  : $("#cbo_material").val(),
                                  cnt_mat : $("#cant").val()

                              },
                          function(datos) {  
                              if(datos.encontrado == "S"){
                                    $('#tbl_manobra').append(datos.fila); 
                                    $('#opc_btns').show();
                                    
                              } else {
                                    $.alert('No Existe esa OT! o no está ACTIVA!');
                                    return;
                              }
                          
                        },"json");   
                  }
              }
            }
        }

        function consultar(id_mobra){
            $.post('consulta_mo_n.php',
              {   no_ot : $("#tbl_manobra").find("tr[id='"+id_mobra+"']").find("td[id='ot']").html() },
              function(datos) {  
                $("#result_show").html(datos.tabla);
            },"json");
        }

        function editar(id_mobra){
            $('#mensaje_ed').html('');
            $('#btn_grabar_mat').attr("disabled", false);   

            var no_ot = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id='ot']").html();

            var fecha = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id^='fecha_']").html();

            var id_per= $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id^='per_']").attr("id").substr(4);

            var id_mat = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id^='mat_']").attr("id").substr(4);

            var cant_mat = $("#tbl_manobra").find("tr[id='"+id_mobra+"']").children("td[id='cantidad']").html();

            $.post('editar_mat.php',
              {   id_fila : id_mobra,
                  no_ot_n : no_ot,
                  fecha: fecha,
                  id_per: id_per,
                  id_mat: id_mat,
                  cant_mat: cant_mat
              },
              function(datos) {  
                  $("#result_edit").html(datos.tabla);
                  $("#fecha_ed").datepicker();
                  $('#btn_grabar_mat').attr("disabled", false); 
            },"json");        
        }

        function grabar_ed(){

              $('#btn_grabar_mat').attr("disabled", false); 

              if ($("#fecha_ed").val() == ""){
                  $("#mensaje_ed").html("Debe Ingresar Fecha");
                  $('#mensaje_ed').addClass("error");
                  $("#fecha_ed").focus();
              } else {
                  if ($("#canti_mat_ed").val() == ""){
                      $("#mensaje_ed").html("Debe Ingresar Cantidad");
                      $('#mensaje_ed').addClass("error");
                      $("#canti_mat_ed").focus();
                  } else {
                        console.log("id_fila->"+$("#id_fila_mat").val());

                        console.log('no_ot->'+$("#var_mat_no_ot").val());

                        console.log('fecha->'+$("#fecha_ed").val());
                            
                        console.log('id_per->'+$("#cbo_personal_ed").val());

                        console.log('id_mat->'+$("#cbo_material").val());

                        console.log('cant->'+$("#canti_mat_ed").val());

                      $.post('g_editar_mat.php',
                        {   id_fila: $("#id_fila_mat").val(),
                            no_ot: $("#var_mat_no_ot").val(), 
                            fecha: $("#fecha_ed").val(),
                            
                            id_per: $("#cbo_personal_ed").val(),

                            id_mat: $("#cbo_material_ed").val(),

                            cant: $("#canti_mat_ed").val()
                        },
                        function(datos) {  
                    
                            $('#tbl_manobra').find("tr[id='"+datos.id_fila_val+"']").html(datos.fila); 
                            $('#mensaje_ed').html('Modificaciones hechas');
                            $('#mensaje_ed').addClass("ok");
                            $('#btn_grabar_mat').attr("disabled", true);   
                      },"json");                      
                  }
              }
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
          height: 360px;
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
                } 

              if ($_SESSION["nivel_usuario"] == "U"){
                  echo $_SESSION["nom_usuario"]."<br><b>Usuario</b>";
              }

              if ($_SESSION["nivel_usuario"] == "M"){
                  echo $_SESSION["nom_usuario"]."<br><b>Almacén</b>";
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

        <?php
            if ($_SESSION["nivel_usuario"] != "M"){ 
                echo '<li>
                          <a href="ots.php"><i class="ion-wrench" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Ordenes de Trabajo</span></a>
                      </li>

                      <li>
                          <a href="reg_mo.php"><i class="fa fa-male" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Mano de Obra</span></a>
                      </li>   

                      <li class="active">
                          <a href="reg_mat.php"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Materiales</span></a>
                      </li>     

                      <li>
                          <a href="reg_oc.php"><i class="fa fa-book" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Otros Costos</span></a>
                      </li>  

                      <li>
                          <a href="consulta_ot.php"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Consulta General OTs</span></a>
                      </li>';   
        
            }

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

            if ($_SESSION["nivel_usuario"] == "M"){ 
               echo '<li class="treeview">
                      <a href="#">
                        <i class="fa fa-database"></i> <span>Mantenimiento Tablas</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                      </a>
                      <ul class="treeview-menu">
                        <li><a href="material.php"><i class="fa fa-circle-o"></i> Materiales</a></li>
                      </ul>
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
        Registro de Materiales
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
                                        Fecha
                                    </span>
                                    <input type="text" class="form-control" id="fecha_b">
                                </div> 
                            </div>      

                      </div>                            
                  </div>

              </div>
          </div>
      </div>

      <div id="ptlla" class="row" style="margin-top: 0px;">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 0px;padding-left: 0px; padding-right: 0px;">
              <div class="box box-primary">
                  <div id="result" class="box-body" style="margin-bottom: 0px;">
  
                  </div>

              </div>
          </div>
      </div>
  
      <div id="opc_btns" class="row" style="margin-top: 0px;">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center" style="margin-top: 5px;">
                  <button type="button" id="grabar_todo" class="btn btn-primary" onclick="grabando()">
                      <i class="fa fa-save" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Grabar
                  </button>      

                  <button type="button" class="btn btn-info" style="margin-left: 10px;" onclick="cerrar()">
                      <i class="fa fa-window-close-o" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Cerrar
                  </button>                                                      
          </div>                
      </div>     

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
<div id="ver_mo" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>MOSTRAR INFORMACION OT</strong></h4>
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
<div id="edit_mo" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>MODIFICAR ORDEN DE SALIDA DE MATERIALES</strong></h4>
      </div>
      <div id="result_edit" class="modal-body">


      </div>
      
      <div id='mensaje_ed' class="text-center">

      </div>  
      <div class="modal-footer">
            <button type="button" id="btn_grabar_mat" class="btn btn-success" onclick="grabar_ed()">Modificar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>




<!--- Ventana Editar New-->
<div id="edit_mo_n" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><strong>MODIFICAR ORDEN DE SALIDA DE MATERIALES</strong></h4>
      </div>
      <div id="result_edit_n" class="modal-body">


      </div>
      
      <div id='mensaje_ed_n' class="text-center">

      </div>  
      <div class="modal-footer">
            <button type="button" id="btn_grabar_mat_n" class="btn btn-success" onclick="grabar_ed_n()">Modificar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



</body>
</html>
