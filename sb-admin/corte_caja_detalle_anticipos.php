<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="es">

<head>
    <?php include "header.php"; 

            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if(!isset($_POST["idpaciente"]) ){
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
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  
            }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; 
            }
             if($_SESSION['nivel_acceso'] == 2){
                include "nav_contador.php"; 
            }
       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Información de anticipos <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Aquí obtendrá información de todos los anticipos que ha hecho el cliente.
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class = "row">
                    <div class="col-lg-3">
                        <form role="form" id="ver_lista" method="post" action="viewmod_ver_corte_caja_por_mes.php" accept-charset="UTF-8">
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
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Datos del anticipo </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-md-4 col-lg-4"> 
                                        <!-- <form role="form" id="agregar_anticipos" method="post" action="viewmod_anticipos_pacientes_por_fecha.php">-->
                                            <?php 
                                                include_once "include/mysql.php";
                                                include_once "include/funciones_consultas.php";
                                                
                                                echo '<pre>';
                                                print_r($_POST);
                                                echo '</pre>';

                                                $idpaciente = $_POST["idpaciente"];
                                                $idanticipo = $_POST["idanticipo"];
                                                $paciente = $_POST["nombre_paciente"];
                                                $nombre_estudio = $_POST["estudio"];
                                                $precio_estudio = $_POST["precio"];

                                                $sumatoria_anticipos = sumatoria_de_anticipos($idpaciente);

                                                echo $sumatoria_anticipos;

                                                $debe =   $precio_estudio - $sumatoria_anticipos ;
                                                $debe = number_format($debe,2);
                                                $precio_estudio_imprimir = number_format($precio_estudio,2);

                                                
                                               
                                    
                                                echo '<p><strong>Paciente:  </strong> '.$paciente.'</p>';
                                                echo '<p><strong>Estudio:  </strong> '.$nombre_estudio.'</p>';
                                                echo '<p><strong>Precio estudio:  </strong>$ '.$precio_estudio_imprimir.'</p>';

                                                if($debe >= 0.00){
                                                    echo '<p><strong>Debe:</strong> $  '.$debe.'</p>';
                                                }else{
                                                    
                                                    echo '<div class="alert alert-success" role="alert">Hubo una devolución:  <span> $ '.$debe.'</span></div>';
                                                }

                                                
                                                
                                                
                                            ?>
                                    </div>

                                    

                                    <div class="col-md-8 col-lg-8">
                                       
                                        <?php
 
                                                // -----  Aquí se muestran las fechas, forma de pago y monto de todos los anticipos de ciertos pacientes------
                                                echo '<div class="form-group">';
                                                        include_once "include/funciones_consultas.php";
                                                        ver_anticipos_anteriores_administrador($idpaciente);
                                                echo '</div>';
                                          
                                        ?>
                                   
                                    </div>    

                                </div><!--fin row -->
                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">
                                <!--<input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="agregar_anticipos" type="submit" value="Aceptar" class="btn btn-primary">-->
                               
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

