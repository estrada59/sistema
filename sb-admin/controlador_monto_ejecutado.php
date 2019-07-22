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

                <!-- INICIO Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Contratos por instituciones<small></small>
                        </h1>

                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                 Relación de contratos por institucion
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- FIN Page Heading -->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        
                        <br>
                        
                            <?php

                                include_once "include/funciones_consultas.php";
                                monto_ejecutado_por_instituciones();
                                
                            ?>

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#myclass').DataTable( {
                                        language: {
                                            url: 'js/dataTables_es.lang'
                                        },
                                        "paging":   false,
                                        "ordering": true,
                                        "info":     true
                                        });
                                } );
                            </script>
                           
                    </div>
                </div> 


            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->


    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
