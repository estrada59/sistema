<!DOCTYPE html>

<?php $_POST["pagina"]="instituciones"; 
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
       <?php
            include_once 'include/nav_session.php';
       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Relación de instituciones<small></small>
                        </h1>

                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                 Relación de instituciones
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        agregar lista de precios y eliminar         -->
                    

                        </br>

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Relación de instituciones. </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <?php
                                    include_once "include/funciones_consultas.php";
                                    ver_listas_de_instituciones();
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

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
