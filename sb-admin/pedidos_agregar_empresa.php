<!DOCTYPE html>

<?php $_POST["pagina"]="pedidos_material"; 
    session_start();
    include "../include/sesion.php";
    comprueba_url();
    //comprueba_empleado_activo();
    //cerrar_multiples_sesiones(); 

    /*if(!isset($_SESSION['total_detalle_prod']) and !isset($_SESSION['detalle'])){
        $_SESSION['total_detalle_prod'] = 0;
        $_SESSION['detalle'] = array();    
    }else{
        $_SESSION['total_detalle_prod'] = 0;
        $_SESSION['detalle'] = array();   
    }*/
    if(!isset($_SESSION['id'])){
        header("Location: index.php");
    }


    if($_SESSION['nivel_acceso'] == 3){
    header("Location: index.php");
    exit();}

?>
<html lang="en">

<head>
    <?php 
        include "header.php";
        include_once 'include/mysql.php';
        include_once "include/funciones_consultas.php";
    ?>
    <!-- ESTE ARCHIVO ES CLAVE PARA LAS CONSULTAS Y CONTROLAR TODO EL PLUGIN-->
    <script type="text/javascript" src="libs/ajax_pedidos_productos.js"></script>
    <!-- Alertity -->
    <link rel="stylesheet" href="libs/js/alertify/themes/alertify.core.css" />
    <link rel="stylesheet" href="libs/js/alertify/themes/alertify.bootstrap.css" id="toggleCSS" />
    <script src="libs/js/alertify/lib/alertify.min.js"></script>
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
                        <h3 class="page-header">
                            Agregar nuevo proveedor
                        </h3>                       
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-md-2 col-lg-2">
                            <?php //print_r($_POST);?>
                                <form name="form_back_pedidos_lista_pedidos" action="pedidos_lista_pedidos.php" method="post">
                                    <a href="javascript:document.form_back_pedidos_lista_pedidos.submit()" class="btn btn-success  btn-block" role="button" ><span class="glyphicon glyphicon-arrow-left"></span> Atrás</a>                          
                                </form>
                            </div>
                        </div>
                        <br/>
                        
                        </br>
                        <?php
                        /*echo'<pre>'; print_r($_SESSION); echo'</pre>';*/?>
                            
                        <?php
                            //include connection file 
                            include_once 'include/mysql.php';

                            $mysql = new mysql();
                            $link = $mysql->connect(); 

                            $mysql->close();
                            
                        ?>
                                                
                    </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->


</body>


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

</html>
