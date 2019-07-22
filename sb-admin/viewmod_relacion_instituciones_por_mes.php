<!DOCTYPE html>
<?php $_POST["pagina"]="instituciones"; ?>
<html lang="es">

<head>
    <?php   include "header.php";
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            /* if($_SESSION['nivel_acceso'] >= 4){
                header("Location: index.php");
                exit();
            }*/

            if(!isset($_POST['fecha_estudios'])){
                header('Location: controlador_relacion_instituciones.php');
            }
    ?>
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

                            
                                <?php 
                                include_once 'include/funciones_consultas.php';
                                $fecha = $_POST['fecha_estudios'];

                                $institucion = $_POST['institucion'];
                                $institucion = pasarMayusculas($institucion);
        
                                $fecha_fin = last_month_day($fecha);
                                $fecha_ini = first_month_day($fecha); 

                                $fecha_ini = date("d-m-Y",strtotime($fecha_ini));
                                $fecha_fin = date("d-m-Y",strtotime($fecha_fin));                                    
                                
                                $mes =obtener_mes($fecha);
                                $mes = pasarMayusculas($mes);
                                echo 'Relaciones de pacientes '.$institucion.' del mes '.$mes.'';
                                ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                Relación de pacientes por mes <?php echo '<small> del '.$fecha_ini.' al '.$fecha_fin.'</small>'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 
                //print_r($semana);
                 /* print "<pre>"; print_r($_POST); print_r($_SESSION); print "</pre>";*/
                ?>

                <div class="row">
                    <div class="col-lg-3">
                            <form role="form" id="ver_lista" method="post" action="viewmod_relacion_instituciones.php" accept-charset="UTF-8">
                                <input type="hidden" name="institucion" value= <?php echo '"'.$institucion.'"'; ?> >
                            </form>
                            <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                            </button> 
                            <br>                           
                    </div>

                   
                    <div class="col-md-12 col-lg-12">
                   <?php if($_SESSION['nivel_acceso'] < 3){
                        // echo'
                        // <form role="form" id="imprimir_lista_precios" method="post" action="view_imprimir_lista_precios_instituciones.php" target="_blank">
                        //     <input type="hidden" form="imprimir_lista_precios" name="imprimir_precios" value="1"/>
                        //     <input type="hidden" form="imprimir_lista_precios" name="institucion" value="'.$institucion.'"/>
                        // </form>

                        // <button type="submit" class="btn btn-primary btn-lg btn-block"  aria-label="Left Align" form="imprimir_lista_precios">
                        //         <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                        //         IMPRIMIR LISTA
                        // </button>


                        
                        // </br>
                        // ';
                    }
                    ?>
                    </div>
                </div>                

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        1er columnoa de captura  LUNES       -->
                        
                                <?php
                                    include_once 'include/funciones_consultas.php';
                    
                                    $fecha = $_POST['fecha_estudios'];
        
                                    $fecha_fin = last_month_day($fecha);
                                    $fecha_ini = first_month_day($fecha); 
                                    
                                    relacion_pacientes_por_institucion($institucion, $fecha_ini, $fecha_fin,$_POST['pagina']);
                                ?>
                           
            </div>  <!--    fin col-lg-12   -->
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <!-- Esto es para subir archivo-->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-fancyfile.min.js"></script>
    

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
