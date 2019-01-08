<!DOCTYPE html>

<?php $_POST["pagina"]="ver_lista_precios"; 
    session_start(); 
    include_once "../include/sesion.php";
    include_once "../include/mysql.php";
    comprueba_url();
    time_session();

    if($_SESSION['nivel_acceso'] >= 6){
    header("Location: index.php");
    exit();
    }

?>
<html lang="en">

<head>
    <?php include "header.php"; ?>
     <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
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
                            Listas de precios<small></small>
                        </h1>

                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                 Listas de precios
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        agregar lista de precios y eliminar         -->
                        <?php
                        if ($_SESSION['nivel_acceso']<=1) {
                            echo'
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">
                                        <a href="controlador_agregar_nueva_lista_de_precios.php" class="btn btn-warning btn-lg btn-block" role="button">
                                            <span class="glyphicon glyphicon-plus"></span> Agregar nueva lista de precios</a>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <a href="controlador_eliminar_lista_de_precios.php" class="btn btn-danger btn-lg btn-block" role="button">
                                        <span class="glyphicon glyphicon-minus"></span> Eliminar lista de precios</a>
                                    </div>
                                </div>';
                        }
                        ?>

                        </br>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Listas de precios. </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    include_once "include/funciones_consultas.php";
                                    ver_listas_de_precios();
                                ?>
                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">   
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
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

    <!-- jQuery -->
    <!--<script src="js/jquery.js"></script>-->

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>


</body>

</html>
