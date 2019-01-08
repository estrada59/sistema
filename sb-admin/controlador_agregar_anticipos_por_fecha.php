<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="es">

<head>
    <?php include "header.php"; 

            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if(!isset($_POST["idpaciente"]) && !isset($_POST['fecha_estudio'])){
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
            if($_SESSION['nivel_acceso'] == 4){
                include "nav_op.php";  
            } 
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  
            }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; 
            }
       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Introduzca el monto del anticipo <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Seleccione el tipo de anticipo, si requiere factura y el número de recibo.
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class = "row">
                    <div class="col-lg-3">
                        <form role="form" id="ver_lista" method="post" action="viewmod_ver_anticipos_por_fecha.php" accept-charset="UTF-8">
                              <?php $fecha = $_POST['fecha_estudio'];
                              echo '<input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>'; ?>
                        </form>
                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                        </button>                            
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        1er columnoa de captura         -->
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Datos del anticipo </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-4 col-lg-4"> 
                                        <!-- <form role="form" id="agregar_anticipos" method="post" action="viewmod_anticipos_pacientes_por_fecha.php">-->
                                            <?php 
                                                include "include/mysql.php";

                                                $idpaciente = $_POST["idpaciente"];
                                                $fecha_estudio = $_POST['fecha_estudio'];
                                                $idanticipo = $_POST["idanticipo"];
                                                $paciente = $_POST["nombre_paciente"];
                                                $nombre_estudio = $_POST["estudio"];
                                                $precio_estudio = $_POST["precio"];
                                                $hora = $_POST["hora"];
                                                $debe = $_POST["debe"];
                                                $institucion = $_POST["institucion"];

                                                $debe_f = '$ '.number_format($debe,2);
                                                $precio_estudio_f = '$ '.number_format($precio_estudio,2);
                                    

                                                $a =array($paciente,$nombre_estudio);

                                                date_default_timezone_set('America/Mexico_City');
                                                $fecha_actual_print = date("d-m-Y");
                                                $fecha_actual = date("Y-m-d h:i:s");
                                    
                                                echo '<p><strong>Paciente:  </strong> '.$paciente.'</p>';
                                                echo '<p><strong>Estudio:  </strong> '.$nombre_estudio.'</p>';
                                                echo '<p><strong>Precio estudio:  </strong>'.$precio_estudio_f.'</p>';
                                                echo '<p><strong>Debe:  </strong>'.$debe_f.'</p>';
                                                echo '<p><strong>Fecha de anticipo:  </strong>'.$fecha_actual_print.'</p>';
                                            ?>
                                    </div>

                                    <div class="col-md-3 col-lg-3">
                                        <form role="form" id="agregar_anticipos" method="post" action="viewmod_anticipos_pacientes_por_fecha.php">
                                            <?php
                                                echo'

                                                    <div class="form-group">
                                                        <label for="monto">Forma de pago:</label> </br>
                                                        <input type="radio"  form="agregar_anticipos" name="tipo_pago" value="dep_banamex" checked>DEPÓSITO BANAMEX <br>
                                                        <input type="radio"  form="agregar_anticipos" name="tipo_pago" value="pago_santander">PAGO SANTANDER <br>
                                                        <input type="radio"  form="agregar_anticipos" name="tipo_pago" value="pago_cheque">PAGO CON CHEQUE<br>
                                                        <input type="radio"  form="agregar_anticipos" name="tipo_pago" value="transferencia">TRANSFERENCIA<br>
                                                        <input type="radio"  form="agregar_anticipos" name="tipo_pago" value="anticipo_efe">ANTICIPO EFECTIVO<br>
                                                        <input type="radio"  form="agregar_anticipos" name="tipo_pago" value="sr_pago">SR. PAGO<br>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="monto">Monto $</label>
                                                        <input type="text" class="form-control" form="agregar_anticipos"  name="monto" id="monto" value="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="no_recibo">NO. RECIBO</label>
                                                        <input type="text" class="form-control" form="agregar_anticipos"  name="no_recibo" id="no_recibo" value="">
                                                    </div>  

                                                    <div class="form-group">
                                                        <label>¿REQUIERE FACTURA?</label>
                                                        <select class="form-control combobox" form="agregar_anticipos" name="factura" id="factura">
                                                            <option>NO</option>
                                                            <option>SI</option>
                                                        </select>
                                                    </div>
                                                ';
                                            ?>
                                        </form>
                                    </div>

                                    <div class="col-md-5 col-lg-5">
                                        <form role="form" id="agregar_anticipos" method="post">
                                        <?php

                                            include_once "include/mysql.php";

                                            $mysql = new mysql();
                                            $link = $mysql->connect();

                                            $sql = $mysql->query($link,"SELECT count(idpaciente) as num
                                                                            FROM descuentos 
                                                                            WHERE idpaciente = $idpaciente 
                                                                            LIMIT 1");
                                            $row = $mysql->f_obj($sql);
                                            $numero_filas = $row->num;

                                            if($numero_filas){  //nunca se le ha hecho descuento
                                                $sql2 = $mysql->query($link,"SELECT idpaciente, descuento
                                                                            FROM descuentos 
                                                                            WHERE idpaciente = $idpaciente 
                                                                            LIMIT 1");
                                                $row = $mysql->f_obj($sql2);
                                                $existe_pac = $row->idpaciente; 
                                                $porciento_descuento = $row->descuento;
                                            }
                                            else{              //ya se le hizo descuento o no existe
                                                $existe_pac = 0;
                                            }

                                            $mysql->close();

                                            //cuantos anticipos ha dejado el paciente
                                            $mysql = new mysql();
                                            $link = $mysql->connect();

                                            $sql = $mysql->query($link,"SELECT count(pacientes_idpacientes) as num_pac
                                                                            from pacientes_has_anticipos
                                                                            where pacientes_idpacientes = $idpaciente");
                                            $row = $mysql->f_obj($sql);
                                            $num_ant = $row->num_pac;

                                                if($num_ant<2){                          //no se le hizo descuento
                                                        echo'
                                                        <div class="form-group">
                                                            <label for="descuento"  >Descuento</label>
                                                            <input type="text"  form="agregar_anticipos" name="descuento" id="descuento" value="" >
                                                            %
                                                        </div>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="existe_pac_descuento"  value="1"/>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="porciento_descuento"  value="orden"/>';
                                                }

                                                if($idpaciente == $existe_pac){ //ya se le hizo descuento
                                                    if($num_ant>=2){
                                                        echo'
                                                            <div class="form-group">
                                                                <label for="descuento"  disabled>Descuento</label>
                                                                <input type="text"  form="agregar_anticipos" name="descuento" id="descuento" value="'.$porciento_descuento.'"  disabled>
                                                                %
                                                            </div>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="existe_pac_descuento"  value="0"/>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="descuento"  value="0"/>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="porciento_descuento"  value="'.$porciento_descuento.'"/>';
                                                    }
                                                }
                                               
                                                if($num_ant >=2 && $existe_pac == 0){ //no se hace descuento despues de 1er anticipo
                                                    
                                                         echo'
                                                        <div class="form-group">
                                                            <label for="descuento"  disabled>Descuento</label>
                                                            <input type="text"  form="agregar_anticipos" name="descuento" id="descuento" value="0"  disabled>
                                                            %
                                                        </div>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="existe_pac_descuento"  value="0"/>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="descuento"  value="0"/>';
                                                        echo '   <input type="hidden" form="agregar_anticipos" name="porciento_descuento"  value="tiene_anticipo_previo"/>';
                                                       
                                                }
                                                // -----  Aquí se muestran las fechas, forma de pago y monto de todos los anticipos de ciertos pacientes------
                                                echo '<div class="form-group">';
                                                        include_once "include/funciones_consultas.php";
                                                        ver_anticipos_anteriores($idpaciente);
                                                echo '</div>';
                                                                                            
                                                echo'
                                                    <div class="form-group">
                                                        <input type="hidden" form="agregar_anticipos" name="idpaciente"     value="'.$idpaciente.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="fecha_estudio"  value="'.$fecha_estudio.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="idanticipo"     value="'.$idanticipo.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="paciente"       value="'.$paciente.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="nombre_estudio" value="'.$nombre_estudio.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="precio_estudio" value="'.$precio_estudio.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="hora"           value="'.$hora.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="debe"           value="'.$debe.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="institucion"    value="'.$institucion.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="fecha_actual"   value="'.$fecha_actual.'"/>
                                                        <input type="hidden" form="agregar_anticipos" name="fecha_actual_print"   value="'.$fecha_actual_print.'"/>
                                                    </div>
                                                ';
                                        ?>
                                        </form>
                                    </div>    

                                </div><!--fin row -->
                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">
                                <!--<input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="agregar_anticipos" type="submit" value="Aceptar" class="btn btn-primary">-->
                                <button id="submit" name="submit" class="btn btn-danger btn-lg btn-block" form="agregar_anticipos" type="submit" >
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                    ACEPTAR
                                </button>
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>
            </div> 
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
        <?php $debe = $_POST["debe"]; ?>
        f1.add( Validate.Numericality, { maximum: <?php echo $debe; ?> } );
        f1.add( Validate.Numericality, { minimum: 0 } );
    </script> 

    <script type="text/javascript">
        var f4 = new LiveValidation('no_recibo',{ validMessage: 'OK!', wait: 500});
        f4.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        f4.add( Validate.Numericality );
    </script> 

    <script type="text/javascript">
        var f2 = new LiveValidation('descuento',{ validMessage: 'OK!', wait: 500});
        f2.add( Validate.Numericality );
        f2.add( Validate.Numericality, { maximum: 100 } );
        f2.add( Validate.Numericality, { minimum: 1 } );
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

