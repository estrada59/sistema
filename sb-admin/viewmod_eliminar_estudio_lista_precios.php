<?php
include "include/mysql.php";

function previsualizacion(){

$idgammagramas  =   $_POST["idgammagramas"];    
$estudio        =   $_POST["estudio"];
$precio         =   $_POST["precio"];

echo' 
    <div class="col-md-12 col-lg-12">  
        <div class="panel panel-danger">        
            <div class="panel-heading">
                <h3 class="panel-title"> Se actualizarán los siguientes registros </h3>
            </div>  <!--    fin panel heading   -->
            
            <div class="panel-body">';

echo '          <p><strong>Estos datos se ELIMINARÁN ¿Está seguro?</strong> </p>';
echo '          <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="id">Item ID</th>
                            <th data-field="price">Nombre del estudio</th>
                            <th data-field="price">Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="id">'.$idgammagramas.'</th>
                            <th data-field="price">'.$estudio.'</th>
                            <th data-field="name">'.$precio.'</th>
                        </tr>
                    <tbody>
                </table> 
            </div>

            <div class="panel-footer">
            </div>
        </div>
    </div>';

        
}
?>

<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="es">
<head>
    <!-- aqui pondremos el contenido html -->
     <?php include "header.php"; 
        session_start(); 
            include "../include/sesion.php";
            comprueba_url();

        if(!isset($_POST["idgammagramas"])){
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
                                <i class="fa fa-dashboard"></i> Confirmar eliminación de estudio en lista de precios
                            </li>
                        </ol>
                        <div class="col-lg-3">
                            <form role="form" id="ver_lista" method="post" action="viewmod_ver_lista_precios.php">
                            </form>
                            <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                            </button>                            
                        </div>

                    </div>
                </div>
                <!-- /.row -->
            <!-- /.container-fluid -->
                <br>
            <?php previsualizacion();?>

            <form  role="form" id="editar_lista" method="post" action="view_eliminar_estudio_lista_precios.php">
                <?php
                    $idgammagramas  =   $_POST["idgammagramas"];
                    $estudio        =   $_POST["estudio"];
                    $precio         =   $_POST["precio"];   
                   
                    echo'
                        <input type="hidden" form="editar_lista" name="idgammagramas" value= "'.$idgammagramas.'">
                        <input type="hidden" form="editar_lista" name="estudio" value= "'.$estudio.'">
                        <input type="hidden" form="editar_lista" name="precio" value= "'.$precio.'">';
                ?>
            </form>
            <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_lista" type="submit" value="Aceptar" class="btn btn-primary">


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