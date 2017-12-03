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
  <title>Ordenes | IMEX 2000</title>
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
                $( "#fec_actual" ).datepicker(
                        {
                          onSelect: function(date) {
                                       buscar();
                                    } 
                        }
                );
                $( "#fec_inicio" ).datepicker();
                $( "#fec_final" ).datepicker();
                buscar();
        });   

        function buscar(){
            $.post('busca_ot.php',
              {   proceso: $("#cbo_proceso").val(),
                  orden: $("#no_ot").val(),
                  fecha: $("#fec_actual").val(),
                  ref: $("#no_ref").val(),
                  id_clie: $("#clientes").val(),
                  descrip: $("#trabajo_desc").val(),
                  estado: $("#estado").val()
              },
              function(datos) {  
                  if(datos.encontrado == "S"){
                        tbl = datos.tabla.replace(/([\ \t]+(?=[\ \t])|^\s+|\s+$)/g, '');
                        console.log(tbl);
                        console.log(datos.tabla);
                        $("#result").html(datos.tabla);
                  } else {
                        $("#result").html(datos.mensaje);
                  }
              
            },"json");
        }

        function editar(cod_ot){
            $('#mensaje_ed').html(""); 
            $('#btn_grabar_ot').attr("disabled", false);   

            $.post('editar_ot.php',
              { 
                  orden: cod_ot
              },
              function(datos) {  
                  if(datos.estado == "ANUL"){//Anulado
                        $("#result_edit").html(datos.tabla4);
                        $('#btn_grabar_ot').hide();  
                  }    

                  if(datos.estado == "CERR"){ //Cerrado
                        $("#result_edit").html(datos.tabla3);
                        $('#btn_grabar_ot').hide();  
                  } 

                  if(datos.estado == "ACTI"){ //Activo

                        fechita = datos.fecha;
                        dia = fechita.substring(0, 2);
                        dia = dia - 0;

                        mes = fechita.substring(3, 5);
                        mes = mes - 1;

                        ano = fechita.substring(6, 10);
                        ano = ano - 0;

                        $("#result_edit").html(datos.tabla);
                        $("#fecha_ed").datepicker({defaultDate: datos.fecha});

                        if (datos.inicio == "B"){
                            $("#inicio_ed").datepicker({
                                  defaultDate: new Date(), 
                                  minDate: new Date( ano, mes, dia),
                                  maxDate: "+1y"
                            });
                        } else {
                            $("#inicio_ed").datepicker({
                                  defaultDate: datos.inicio, 
                                  minDate: new Date( ano, mes, dia),
                                  maxDate: "+1y"
                            });                          
                        }

                        if (datos.fin == "B"){
                            $("#fin_ed").datepicker({
                                  defaultDate: new Date,
                                  minDate: new Date( ano, mes, dia),
                                  maxDate: "+1y"                              
                            });  
                        } else {
                            $("#fin_ed").datepicker({
                                  defaultDate: datos.fin,
                                  minDate: new Date( ano, mes, dia),
                                  maxDate: "+1y"                              
                            });  
                        }


                        $('#btn_grabar_ot').show();    
                  }                   

            },"json");              
        }

        function grabar(){
            if($("#estados").val()==4){ //Si es Anulado         
                  $.confirm({
                      title: 'Anular Orden de Trabajo',
                      content: '' +
                      '<form action="" class="formName">' +
                      '<div class="form-group">' +
                      '<label>Ingrese Motivo de Anulación</label>' +
                      '<input type="text" placeholder="Motivo" class="motivo form-control" required />' +
                      '</div>' +
                      '</form>',
                      buttons: {
                          formSubmit: {
                              text: 'Anular',
                              btnClass: 'btn-danger',
                              action: function () {
                                  var motivo = this.$content.find('.motivo').val();
                                  if(!motivo){
                                      $.alert('Falta Ingresar el motivo');
                                      return false;
                                  } else {
                                      $.post('act_ot.php',
                                          { ot:$("#ot_ed").val(),
                                            ref:$("#ref_ed").val(),
                                            fecha:$("#fecha_ed").val(),
                                            taller:$("#taller").val(),
                                            cliente:$("#cliente").val(),
                                            descrip:$("#descrip_ed").val(),
                                            inicio:$("#inicio_ed").val(),
                                            fin:$("#fin_ed").val(),
                                            valor:$("#valor_ed").val(),
                                            avance:$("#avance_ed").val(),
                                            estado:$("#estados").val(),
                                            motiv: motivo
                                          },
                                          function(datos) {  
                                              if (datos.mensaje == "S"){
                                                  $('#mensaje_ed').html("Orden de Trabajo Anulada"); 
                                                  $('#mensaje_ed').addClass("ok");
                                                  $('#btn_grabar_ot').attr("disabled", true);                      
                                              }               
                                        },"json"); 

                                  }
                              }
                          },
                          cancelar: function () {
                              //close
                          },
                      },
                      onContentReady: function () {
                          // bind to events
                          var jc = this;
                          this.$content.find('form').on('submit', function (e) {
                              // if the user submits the form by pressing enter in the field.
                              e.preventDefault();
                              jc.$$formSubmit.trigger('click'); // reference the button and click it
                          });

                      }
                  });
            }
            //==================================================================================

            if($("#estados").val()==3){ //Si es Cerrado         
                  $.confirm({
                      title: 'Cerrar Orden de Trabajo',
                      content: '' +
                      '<form action="" class="formName">' +
                      '<div class="form-group">' +
                      '<label>Observaciones</label>' +
                      '<input type="text" placeholder="Observaciones" class="obs form-control" required />' +
                      '</div>' +
                      '</form>',
                      buttons: {
                          formSubmit: {
                              text: 'Cerrar',
                              btnClass: 'btn-danger',
                              action: function () {
                                  var obs = this.$content.find('.obs').val();

                                  $.post('act_ot.php',
                                      { ot:$("#ot_ed").val(),
                                        ref:$("#ref_ed").val(),
                                        fecha:$("#fecha_ed").val(),
                                        taller:$("#taller").val(),
                                        cliente:$("#cliente").val(),
                                        descrip:$("#descrip_ed").val(),
                                        inicio:$("#inicio_ed").val(),
                                        fin:$("#fin_ed").val(),
                                        valor:$("#valor_ed").val(),
                                        avance:$("#avance_ed").val(),
                                        estado:$("#estados").val(),
                                        observ: obs
                                      },
                                      function(datos) {  
                                          if (datos.mensaje == "S"){
                                              $('#mensaje_ed').html("Orden de Trabajo Cerrada"); 
                                              $('#mensaje_ed').addClass("ok");
                                              $('#btn_grabar_ot').attr("disabled", true);                      
                                          }               
                                    },"json"); 
                              }
                          },
                          cancelar: function () {
                              //close
                          },
                      },
                      onContentReady: function () {
                          // bind to events
                          var jc = this;
                          this.$content.find('form').on('submit', function (e) {
                              // if the user submits the form by pressing enter in the field.
                              e.preventDefault();
                              jc.$$formSubmit.trigger('click'); // reference the button and click it
                          });

                      }
                  });
            }  
            //==============================================================================

            if($("#estados").val()==1){ //Si es Activo        
                    $.post('act_ot.php',
                        { ot:$("#ot_ed").val(),
                          ref:$("#ref_ed").val(),
                          fecha:$("#fecha_ed").val(),
                          taller:$("#taller").val(),
                          cliente:$("#cliente").val(),
                          descrip:$("#descrip_ed").val(),
                          inicio:$("#inicio_ed").val(),
                          fin:$("#fin_ed").val(),
                          valor:$("#valor_ed").val(),
                          avance:$("#avance_ed").val(),
                          estado:$("#estados").val()
                        },
                        function(datos) {  
                            if (datos.mensaje == "S"){
                                $('#mensaje_ed').html("Orden de Trabajo Actualizada"); 
                                $('#mensaje_ed').addClass("ok");
                                $('#btn_grabar_ot').attr("disabled", true);                      
                            }               
                      },"json"); 
            }             
        }

        function cerrar(){
            buscar();
        }

        function adicionar(){
            $.post('new_ot.php',
              function(datos) {   
                $("#result_new").html(datos.tabla);
                
                // Resetando...
                $('#mensaje_new').html("");
                $('#btn_grabar_new').attr("disabled", false);

                $("#fecha").datepicker({
                      defaultDate: new Date(), 
                      maxDate: "+1y",
                      onSelect: function(date) {
                         set_fechas();
                      } 
                });       

            },"json");            
        }

        function set_fechas(){
            $("#inicio").datepicker({
                  defaultDate: $("#fecha").datepicker("getDate"), 
                  minDate: $("#fecha").datepicker("getDate"),
                  maxDate: "+1y"
            });   

            $("#fin").datepicker({
                  defaultDate: $("#fecha").datepicker("getDate"),
                  minDate: $("#fecha").datepicker("getDate"),
                  maxDate: "+1y"
            });            
        }

        function grabar_new(){
            if ($("#fecha").val() == ''){
                    $('#mensaje_new').html("Datos Incompletos");
                    $('#mensaje_new').addClass("error");
                    $('#fecha').focus();                   
            } else {
                if ($("#descrip").val() == ''){
                      $('#mensaje_new').html("Datos Incompletos");
                      $('#mensaje_new').addClass("error");
                      $('#descrip').focus();  
                } else {
                    if ($("#valor").val() == ''){
                          $('#mensaje_new').html("Datos Incompletos");
                          $('#mensaje_new').addClass("error");
                          $('#valor').focus();                        
                    } else {

                          obj_fecha = $("#fecha").datepicker("getDate");
                          txt_fecha = obj_fecha.getFullYear() + "-" + (obj_fecha.getMonth()+1) + "-" + obj_fecha.getDate();

                          obj_inicio = $("#inicio").datepicker("getDate");
                          console.log(obj_inicio);
                          if (obj_inicio){
                                txt_inicio = obj_inicio.getFullYear() + "-" + (obj_inicio.getMonth()+1) + "-" + obj_inicio.getDate(); 
                              } else {
                                txt_inicio ="0000-00-00";
                              }


                          obj_fin = $("#fin").datepicker("getDate");
                          console.log(obj_fin);
                          if(obj_fin){
                              txt_fin = obj_fin.getFullYear() + "-" + (obj_fin.getMonth()+1) + "-" + obj_fin.getDate();
                          } else {
                              txt_fin ="0000-00-00";
                          }

                          $.post('g_new_ot.php',
                            {   ref: $("#ref").val(),
                                prc: $("#cbo_proc").val(),
                                fecha: txt_fecha,
                                taller: $("#taller").val(),
                                cliente: $("#cliente").val(),
                                descrip: $("#descrip").val(),
                                inicio: txt_inicio,
                                fin: txt_fin,
                                valor: $("#valor").val()
                            },
                            function(datos) {  
                                if(datos.respuesta == "S"){
                                      $("#result_new").html(datos.tabla);

                                      $('#mensaje_new').html("Orden de Trabajo Adicionada"); 
                                      $('#mensaje_new').addClass("ok");
                                      $('#btn_grabar_new').attr("disabled", true);                                         
                                }
                            
                          },"json");                      
                    }
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

        #result {
          height: 400px;
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

        <li class="active">
            <a href="ots.php"><i class="ion-wrench" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Ordenes de Trabajo</span></a>
        </li>

        <li>
            <a href="reg_mo.php"><i class="fa fa-male" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Mano de Obra</span></a>
        </li>   

        <li>
            <a href="reg_mat.php"><i class="fa fa-truck" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Materiales</span></a>
        </li>     

        <li>
            <a href="reg_oc.php"><i class="fa fa-book" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Registro Otros Costos</span></a>
        </li>  
        
        <li>
            <a href="consulta_ot.php"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;<span> Consulta General OTs</span></a>
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
        Ordenes de Trabajo
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Busqueda  -->
      <div class="row" style="margin-top: 0px;"> 
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="box box-primary">
                  <div class="box-header with-border">
                    <h3 class="box-title">Busqueda</h3>
                          <div class="box-tools pull-right" style="width: 35%">
                                <form class="form-inline">
                                      <div class="input-group">
                                          <span class="input-group-addon" id="basic-addon1">
                                              No. OT
                                          </span>
                                          <input type="text" class="form-control" id="no_ot" oninput="buscar()">
                                      </div>    
                                      <button type="button" class="btn btn-primary" style="margin-left: 10px;" onclick="adicionar()" data-toggle="modal" data-target="#add_ot">
                                          Nueva OT
                                      </button>   
                                </form>                      
                          </div>
                  </div>

                  <div class="box-body">
                      <div class="row" style="margin-top: 0px;">  
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin-top: 5px;"> 
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" class="form-control" id="fec_actual"  placeholder="dd/mm/aaaa"  oninput="buscar()">
                                </div>
                            </div>

                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin-top: 5px;"> 
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="fa fa-file-o" aria-hidden="true"></i></i>
                                    </span>
                                    <input type="text" class="form-control" id="no_ref" placeholder="Referencia" oninput="buscar()">
                                </div>
                            </div>     

                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="margin-top: 5px;"> 
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                    </span>
                                    <?php
                                        $sql="select * from clientes order by raz_soc";
                                        $query = mysql_query($sql);  

                                        echo '<select id="clientes" class="form-control" oninput="buscar()">';
                                        echo '<option value="T" selected>TODOS</option>';

                                        if (mysql_num_rows($query)){
                                            while ($row = mysql_fetch_array($query)){
                                                  echo '<option value="'.$row['id_cliente'].'">'.$row['raz_soc'].'</option>'; 
                                            }
                                            echo '</select>';
                                        }
                                    ?>
                                </div>
                            </div> 
                                      
                      </div>       
                      <div class="row" style="margin-top: 0px;"> 
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                        <i class="fa fa-briefcase" aria-hidden="true"></i>
                                    </span>
                                    <input type="text" class="form-control" id="trabajo_desc" placeholder="Descripción del Trabajo" oninput="buscar()">
                                </div> 
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                          Proceso
                                    </span>
                                    <?php
                                          $sql="select * from procesos order by ano, mes";
                                          $query = mysql_query($sql);  

                                          echo '<select id="cbo_proceso" class="form-control" oninput="buscar()">';
                                          echo '<option value="T" selected>TODOS</option>';

                                          if (mysql_num_rows($query)){
                                              while ($row = mysql_fetch_array($query)){
                                                    echo '<option value="'.$row['id_proceso'].'">'.$row['descrip'].'</option>'; 
                                              }
                                              echo '</select>';
                                          }
                                    ?>
                                </div> 
                            </div>                            
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" style="margin-top: 5px;">
                                <div class="input-group">
                                    <span class="input-group-addon" id="basic-addon1">
                                          Estado
                                    </span>
                                    <?php
                                          $sql="select * from estados order by id_estado";
                                          $query = mysql_query($sql);  

                                          echo '<select id="estado" class="form-control" oninput="buscar()">';
                                          echo '<option value="T" selected>TODOS</option>';

                                          if (mysql_num_rows($query)){
                                              while ($row = mysql_fetch_array($query)){
                                                    echo '<option value="'.$row['id_estado'].'">'.$row['estado'].'</option>'; 
                                              }
                                              echo '</select>';
                                          }
                                    ?>
                                </div> 
                            </div>
                             
                      </div>                            
                  </div>

              </div>
          </div>
      </div>

      <div class="row" style="margin-top: 0px;">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 0px;">
              <div class="box box-primary">
                  <div id="result" class="box-body text-center">
                          
                  </div>

              </div>
          </div>
      </div>
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer 
  <footer class="main-footer">

    <div class="pull-right hidden-xs">
      Departamento de Sistemas
    </div>

    <strong>&copy; 2017 <a href="#">IMEX 2000 SA</a>.</strong> Todos los derechos reservados.
  </footer>-->

  <!-- Control Sidebar -->
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
