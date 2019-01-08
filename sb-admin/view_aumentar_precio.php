
<!DOCTYPE html>
<?php $_POST["pagina"]="ver_lista_precios"; ?>
<html lang="es">
<head>
    <!-- aqui pondremos el contenido html -->
     <?php include "header.php"; 
        session_start(); 
            include "../include/sesion.php";
            comprueba_url();

        if(!isset($_POST["institucion"])){
            header('Location: index.php');
        }
     ?>
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
                             <?php    ?><small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Se actualizaron los precios en 
                                <?php 
                                include_once 'include/funciones_consultas.php';
                                $ins = pasarMayusculas($_POST["institucion"]);
                                echo $ins;?> 
                            </li>
                        </ol>

                    </div>
                </div>
                <!-- /.row -->
            <!-- /.container-fluid -->
                <br>
            <?php 
                include_once 'include/funciones_consultas.php';
                $institucion = str_replace(' ', '_', $_POST["institucion"]);
                $descuento = $_POST["descuento"];
                aumentar_precio($institucion, $descuento);
            ?>

            <form  role="form" id="editar_lista" method="post" action="viewmod_ver_lista_precios_instituciones.php">
                <?php
                    echo' 
                        <input type="hidden" form="editar_lista" name="institucion" value="'. $_POST['institucion'].'"/>
                        ';
                ?>
            </form>
            <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_lista" type="submit" value="Aceptar" class="btn btn-primary">


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