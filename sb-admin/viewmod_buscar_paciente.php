<!DOCTYPE html>
<?php $_POST["pagina"]="buscar_paciente"; ?>
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

            if(!isset($_POST['nombre'])){
                header('Location: controlador_buscar_paciente.php');
            }
    ?>
</head>
<body>
    <div id="wrapper">

        <!-- Navigation -->
        <?php include_once "include/nav_session.php" ?>
        
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Buscar pacientes
                                <?php 
                                
                                ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                Lista de pacientes citados 
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 
                //print_r($semana);
                 /* print "<pre>"; print_r($_POST); print_r($_SESSION); print "</pre>";*/
                 include "include/funciones_consultas.php";
                 $nombre = $_POST["nombre"];
                 $appat = $_POST["appat"];
                 $apmat = $_POST["apmat"];

                 buscar_paciente($nombre, $appat, $apmat, $_POST['pagina']);
                ?>
                
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#myClass').DataTable( {
                            language: {
                                url: 'js/dataTables_es.lang'
                            },
                            "paging":   false,
                            "ordering": true,
                            "info":     true
                            });
                    } );
                </script>
            </div>  <!--    fin col-lg-12   -->
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <!-- Esto es para subir archivo-->
    
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
