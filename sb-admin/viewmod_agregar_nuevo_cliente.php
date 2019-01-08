<?php
include "include/mysql.php";
include_once "include/funciones_consultas.php";


//captura datos del formulario 
function insertar(){

echo '<pre>';
print_r($_POST);
echo '</pre>';


$razon_social      = pasarMayusculas($_POST['razon_social']);
$rfc               = pasarMayusculas($_POST['rfc']);
$nombre_comercial  = pasarMayusculas($_POST['nombre_comercial']);
$calle             = pasarMayusculas($_POST['calle']);
$numero            = pasarMayusculas($_POST['numero']);
$colonia           = pasarMayusculas($_POST['colonia']);
$codigo_postal     = pasarMayusculas($_POST['codigo_postal']);
$municipio         = pasarMayusculas($_POST['municipio']);
$estado            = pasarMayusculas($_POST['estado']);
$pais              = pasarMayusculas($_POST['pais']);


$mysql =new mysql();
$link = $mysql->connect(); 

$sql = $mysql->query($link,"INSERT INTO 
							clientes(   idcliente,
                                        razon_social,
                                        rfc,
                                        calle,
                                        numero,
                                        colonia,
                                        cp,
                                        municipio,
                                        estado,
                                        pais,
                                        nombre_comercial) 
							VALUES( '',
                                    '$razon_social',
                                    '$rfc',
                                    '$calle',
                                     $numero,
                                    '$colonia',
                                     $codigo_postal,
                                    '$municipio',
                                    '$estado',
                                    '$pais',
                                    '$nombre_comercial');");

echo 'filas afectadas: (Se insertó) '.$mysql->affect_row().'registro completo';

//$sql = $mysql->query($link, "ALTER TABLE estudio ADD $nombre_comercial DECIMAL(10,2) NOT NULL");	


//echo 'filas afectadas: (Se insertó) '.$mysql->affect_row().'registro completo';

$mysql->close();
//header('Location: agregar_nuevo_estudio.php');
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
                             <?php  insertar();  ?><small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> SE INSERTÓ
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <form  role="form" id="agregar_estudio" method="post" action="controlador_agregar_cliente.php">
                
                </form>
            <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="agregar_estudio" type="submit" value="Aceptar" class="btn btn-primary">


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

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>


</body>


</html>