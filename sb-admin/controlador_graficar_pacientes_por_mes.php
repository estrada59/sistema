<!DOCTYPE html>
<?php $_POST["pagina"]="grafica"; ?>
<html lang="en">

<head>
    <?php   include "header.php";
            session_start(); 
            include_once "../include/sesion.php";
            include_once "../include/mysql.php";
            comprueba_url();
            time_session();

            if($_SESSION['nivel_acceso'] >= 4){
                header("Location: index.php");
                exit();
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
                            Graficar catidad de pacientes por mes<small>
                            </small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                 Graficar cantidad de pacientes
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <form role="form" id="ver_pacientes" method="post" action="viewmod_graficar_pacientes_por_mes.php">
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" class="form-control" form="ver_pacientes" name="fecha_estudios" id="fecha_estudios" placeholder="Fecha estudio" required>
                    </div>
                </form>
                <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="ver_pacientes" type="submit" value="Aceptar" class="btn btn-primary">
                <?php 
                /*  
                    print "<pre>";
                    print_r($_POST);
                    print_r($_SESSION);
                    print "</pre>";
                */
                ?>
    
    </div>  <!--    fin col-lg-12   -->
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
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
