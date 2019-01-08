<?php
include "include/mysql.php";

function previsualizacion(){
$idgammagramas  =   $_POST["idgammagramas"]; 
$institucion    =   $_POST['institucion'];


$precio         =   $_POST['precio'];

$nombre_ant         =   $_POST['name'];
$nombre_corregido        =   $_POST['nombre'];
$tipo_estudio_ant   =   $_POST['type_study'];
$precio_ant         =   $_POST['price'];


echo' 
    <div class="col-md-12 col-lg-12">  
        <div class="panel panel-primary">        
        <div class="panel-heading">
            <h3 class="panel-title"> Se actualizaron los registros </h3>
        </div>  <!--    fin panel heading   -->
            
        <div class="panel-body">
        ';


echo '<p><strong>Estos datos se actualizaron</strong> </p>';
echo '  <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="id">Item ID</th>
                            <th data-field="name">Tipo de estudio</th>
                            <th data-field="price">Nombre del estudio</th>
                            <th data-field="price">Precio</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="id">'.$idgammagramas.'</th>
                            <th data-field="price">'.$tipo_estudio_ant.'</th>
                            <th data-field="name">'.$nombre_ant.'</th>
                            <th data-field="price">'.$precio_ant.'</th>
                        </tr>
                    <tbody>
                </table>   ';

echo '<p><strong>Por estos de acá</strong></p> '; 


echo '  <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="id">Item ID</th>
                            <th data-field="tipo_estudio">Tipo de estudio</th>
                            <th data-field="nombre">Nombre del estudio</th>
                            <th data-field="precio">Precio</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="id">'.$idgammagramas.'</th>
                            <th data-field="tipo_estudio">'.$tipo_estudio_ant.'</th>
                            <th data-field="nombre">'.$nombre_corregido.'</th>
                            <th data-field="precio">'.$precio.'</th>
                        </tr>
                    <tbody>
                </table> 
            </div>

            <div class="panel-footer">
            ';

             echo'
            </div>
            
        </div>';

        update($idgammagramas, $precio, $institucion, $nombre_corregido);
}
?>
<?php
function update($idgammagramas,  $precio, $institucion, $nombre_corregido){
       
            $idgammagramas  =   $idgammagramas;
            $institucion    =   $institucion;    
            $precio         =   $precio;
            $nombre         =   $nombre_corregido;

            //echo $idgammagramas.$nombre.$tipo_estudio.$precio;
            $mysql = new mysql();
            $mysqli = $mysql->connect();
            
            $sql = "UPDATE estudio SET  $institucion = $precio, nombre = '$nombre'
                        WHERE idgammagramas='$idgammagramas'";

            $mysqli->query($sql);

            printf("%s\n", mysqli_info($mysqli));

            echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';
    
            $mysql->close();
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
            if($_SESSION['nivel_acceso'] == 2){
                include "nav_contador.php";  
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
                                <i class="fa fa-dashboard"></i> Modificación de lista de precios
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
            <!-- /.container-fluid -->
            <?php previsualizacion();?>
            <form  role="form" id="editar_lista" method="post" action="viewmod_ver_lista_precios_instituciones.php">
               <?php 
                    include_once "include/funciones_consultas.php";
                    $institucion = str_replace("_", " ", $_POST['institucion']);
                    $institucion = pasarMayusculas($institucion);
                    echo '<input type="hidden" name = "institucion" value="'.$institucion.'">';
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