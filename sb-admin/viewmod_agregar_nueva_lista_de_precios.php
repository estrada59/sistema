<!DOCTYPE html>
<?php $_POST["pagina"]="ver_lista_precios"; ?>
<html lang="es">

<head>
    <?php   include "header.php";
            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if($_SESSION['nivel_acceso'] > 2){
                header("Location: index.php");
                exit();
            }

            if(!isset($_POST['nombre_institucion'])){
                header('Location: controlador_agregar_nueva_lista_de_precios.php');
            }
    ?>
</head>
<body>
    <div id="wrapper">

        <!-- Navigation -->
       <?php  
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; }
            if($_SESSION['nivel_acceso'] == 2){
                include "nav_contador.php"; }
            if($_SESSION['nivel_acceso'] == 4){
                include "nav_op.php"; }
       ?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Agregar nueva lista de precios
                                <?php 
                                
                                ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                                Se agregí lista de precios
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               
                <?php 
                 include "include/funciones_consultas.php";
                 $nombre = $_POST["nombre_institucion"];
                 $tipo   = $_POST["tipo"];

                 if(isset($nombre)){
                 echo' 
                    <div class="col-md-12 col-lg-12">  
                        <div class="panel panel-primary">        
                            <div class="panel-heading">
                                <h3 class="panel-title"> Se actualizaron los registros </h3>
                            </div>  <!--    fin panel heading   -->
                        <div class="panel-body">';
                    echo '<p><strong>Se ingreso una nueva lista de precio exitosamente</strong> </p>'; 

                    echo ' 
                        <form  role="form" id="editar_lista" method="post" action="controlador_ver_lista_precios.php">
                        </form>
                        <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_lista" type="submit" value="Aceptar" class="btn btn-primary">';
                    echo '</div>';
             echo'</div>';
        }
                 insertar_nueva_lista_precios($nombre, $tipo);
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
