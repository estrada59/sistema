<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="en">

<head>
    <?php   include "header.php"; 
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if(!isset($_POST["idpaciente"]) && !isset($_POST['estatus'])){
                    header('Location: index.php');
            }
    ?>
     <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
       <?php 
            include_once 'include/nav_session.php';
       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Modificar estatus del paciente <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> 
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class = "row">
                    <div class="col-lg-3">
                        <?php
                            $var = $_POST["pagina_destino"];
                            
                            $fecha = $_POST['fecha'];
                            date_default_timezone_set('America/Mexico_City'); 
                            $fecha = date('Y-m-d', strtotime($fecha));

                            switch ($var) {
                                case 'ver_pacientes_por_mes':
                                    echo'
                                     <form role="form" id="ver_lista1" method="post" action="viewmod_ver_pacientes_por_mes.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista1" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista1">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                                case 'ver_pacientes_del_dia':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_del_dia.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                                case 'ver_pacientes_por_semana':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_semana.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                                case 'buscar_paciente':
                                    echo'
                                     <form role="form" id="buscar_paciente" method="post" action="viewmod_buscar_paciente.php" accept-charset="UTF-8">
                                        <input type="hidden" form="buscar_paciente" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>

                                    <input type="hidden" form="buscar_paciente" name="nombre" value="'.$_POST["nombre"].'"/>
                                    <input type="hidden" form="buscar_paciente" name="appat" value="'.$_POST["appat"].'"/>
                                    <input type="hidden" form="buscar_paciente" name="apmat" value="'.$_POST["apmat"].'"/>

                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="buscar_paciente">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                                case 'editar_paciente_por_fecha':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_editar_pacientes_por_fecha.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                                case 'editar_estudio_de_paciente':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_editar_estudio_de_paciente.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                               
                                case 'facturas':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_mes_factura.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                                case 'instituciones':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_relacion_instituciones_por_mes.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                        <input type="hidden" form="ver_lista" name="institucion" value="'.$_POST['institucion'].'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button> ';
                                     break;
                                 
                                default:
                                    $fecha = $_POST['fecha'];
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_semana.php" accept-charset="UTF-8">
                              
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/> 
                              
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button>';
                                    break;
                             }
                        ?>
                                                    
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-lg-4">
                        
                        <p>Nombre del paciente: <?php //print_r($_POST);
                            echo  $_POST['nombre_paciente']; ?>
                        </p>
                        <p>Estudio: <?php 
                            echo  $_POST['estudio']; ?>
                        </p>
                        <p>Fecha de estudio: <?php
                            include_once "include/funciones_consultas.php";
                            $fecha = fecha_letras($_POST['fecha']);
                            echo $fecha;  ?>
                        </p>

                        <div class="form-group">
                            <label for="indicaciones">NOTA SOBRE PACIENTE:</label>
                            <?php
                                $idpaciente = $_POST["idpaciente"];
                                
                                include_once "include/mysql.php";
                                $mysql = new mysql();
                                $link = $mysql->connect();
            
                                $sql = $mysql->query($link, "SELECT observaciones,
                                                                    (select 
	                                                                    concat(nombre,' ',ap_paterno,' ',ap_materno)as nombre 
                                                                    from 
                                                                        users
                                                                    where idusuario = (select atendio from pacientes  where idpacientes=$idpaciente)) as nom
                                                                FROM pacientes 
                                                                WHERE idpacientes= $idpaciente");
                                $obj = $mysql->f_obj($sql);
                                $observaciones = $obj->observaciones;
                                $persona_que_agenda = $obj->nom;
                                $mysql->close();
                            ?>
                           
                            
                            
                        </div>
                    </div>
                </div>
                
                <?php

                $fecha = $_POST["fecha"];
                if ($_SESSION['nivel_acceso'] == 3){
                    echo '
                    <div class = "row">
                        
                        <div class="col-lg-4">
                            <form role="form" id="ver_lista4" method="post" action="viewmod_editar_estatus.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista4" name="fecha_estudios" value="'.$fecha.'"/>
                                        <input type="hidden" form="ver_lista4" name="estatus" value="POR ATENDER"/>
                                        <input type="hidden" form="ver_lista4" name="idpaciente" value="'.$_POST["idpaciente"].'"/> 
                                        <input type="hidden" form="ver_lista4" name="nombre_paciente" value="'.$_POST["nombre_paciente"].'"/>
                                        <input type="hidden" form="ver_lista4" name="nombre" value="'.$_POST["nombre"].'"/>
                                        <input type="hidden" form="ver_lista4" name="appat" value="'.$_POST["appat"].'"/>
                                        <input type="hidden" form="ver_lista4" name="apmat" value="'.$_POST["apmat"].'"/>
                                        <input type="hidden" form="ver_lista4" name="estudio" value="'.$_POST["estudio"].'"/>
                                        <input type="hidden" form="ver_lista4" name="fecha" value="'.$fecha.'"/>
                                        <input type="hidden" form="ver_lista4" name="pag_retorno" value="'.$var.'"/>
                                        <textarea type="text" class="form-control" rows="5" form="ver_lista4" name="motivos" id="motivos">'.$observaciones.'</textarea>
                            </form>
                            <button type="submit" class="btn btn-warning btn-lg btn-block" aria-label="Left Align" form="ver_lista4">
                                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                    Por atender
                            </button>                            
                        </div>

                        <div class="col-lg-4">
                            <form role="form" id="ver_lista2" method="post" action="viewmod_editar_estatus.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista2" name="fecha_estudios" value="'.$fecha.'"/>
                                        <input type="hidden" form="ver_lista2" name="estatus" value="ATENDIDO"/>
                                        <input type="hidden" form="ver_lista2" name="idpaciente" value="'.$_POST["idpaciente"].'"/>

                                        <input type="hidden" form="ver_lista2" name="nombre_paciente" value="'.$_POST["nombre_paciente"].'"/>
                                        <input type="hidden" form="ver_lista2" name="nombre" value="'.$_POST["nombre"].'"/>
                                        <input type="hidden" form="ver_lista2" name="appat" value="'.$_POST["appat"].'"/>
                                        <input type="hidden" form="ver_lista2" name="apmat" value="'.$_POST["apmat"].'"/>
                                        <input type="hidden" form="ver_lista2" name="estudio" value="'.$_POST["estudio"].'"/>
                                        <input type="hidden" form="ver_lista2" name="fecha" value="'.$fecha.'"/>
                                        <input type="hidden" form="ver_lista2" name="pag_retorno" value="'.$var.'"/>
                                        <textarea type="text" class="form-control" rows="5" form="ver_lista2" name="motivos" id="motivos">'.$observaciones.'</textarea>
                            </form>
                            <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista2">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    Atendido
                            </button>                            
                        </div>

                        <div class="col-lg-4">
                            <form role="form" id="ver_lista3" method="post" action="viewmod_editar_estatus.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista3" name="fecha_estudios" value="'.$fecha.'"/>
                                        <input type="hidden" form="ver_lista3" name="estatus" value="CANCELADO"/>
                                        <input type="hidden" form="ver_lista3" name="idpaciente" value="'.$_POST["idpaciente"].'"/>
                                        <input type="hidden" form="ver_lista3" name="nombre_paciente" value="'.$_POST["nombre_paciente"].'"/>
                                        <input type="hidden" form="ver_lista3" name="nombre" value="'.$_POST["nombre"].'"/>
                                        <input type="hidden" form="ver_lista3" name="appat" value="'.$_POST["appat"].'"/>
                                        <input type="hidden" form="ver_lista3" name="apmat" value="'.$_POST["apmat"].'"/>
                                        <input type="hidden" form="ver_lista3" name="estudio" value="'.$_POST["estudio"].'"/>
                                        <input type="hidden" form="ver_lista3" name="fecha" value="'.$fecha.'"/>
                                        <input type="hidden" form="ver_lista3" name="pag_retorno" value="'.$var.'"/>
                                        <textarea type="text" class="form-control" rows="5" form="ver_lista3" name="motivos" id="motivos">'.$observaciones.'</textarea>
                            </form>
                            <button type="submit" class="btn btn-danger btn-lg btn-block" aria-label="Left Align" form="ver_lista3">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    Cancelado
                            </button>                            
                        </div>
                    </div>';
                }
                else{
                
                    echo'<textarea type="text" class="form-control" rows="5" form="ver_lista4" name="motivos" id="motivos" disabled>'.$observaciones.'</textarea>';
                
                }
                ?>

                <p><label for="indicaciones">Agendó:</label>
                    <?php echo $persona_que_agenda;  ?>
                </p>
             
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Validación de formularios -->
    <script type="text/javascript">
        var f1 = new LiveValidation('monto', { validMessage: 'OK!', wait: 500});
        f1.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        f1.add( Validate.Numericality );
        <?php $precio_estudio = $_POST["precio"]; ?>
        f1.add( Validate.Numericality, { maximum: <?php echo $precio_estudio; ?> } );
    </script> 

    <script type="text/javascript">
        var f4 = new LiveValidation('no_recibo',{ validMessage: 'OK!', wait: 500});
        f4.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        f4.add( Validate.Numericality );
    </script> 

    <script type="text/javascript">
        function disableF5(e) { if ((e.which || e.keyCode) == 116) e.preventDefault(); };
        // To disable f5
        $(document).bind("keydown", disableF5);
            /* OR jQuery >= 1.7 */
        $(document).on("keydown", disableF5);
        // To re-enable f5
        /* jQuery < 1.7 */
        /*$(document).unbind("keydown", disableF5);
            $(document).off("keydown", disableF5);*/
    </script>

    <script type="text/javascript">
        document.onkeydown = function (event) { 
            if (!event) { /* This will happen in IE */
                //
                event = window.event;
            }      
            var keyCode = event.keyCode;
            
            if (keyCode == 8 &&
                ((event.target || event.srcElement).tagName != "TEXTAREA") && 
                ((event.target || event.srcElement).tagName != "INPUT")) { 
                
                if (navigator.userAgent.toLowerCase().indexOf("msie") == -1) {
                    event.stopPropagation();
                } else {
                    alert("prevented");
                    event.returnValue = false;
                }
                return false;
            }
        };  
    </script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>


</body>

</html>
