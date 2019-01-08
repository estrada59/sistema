<?php
include "include/mysql.php";
include_once "include/funciones_consultas.php";

//captura datos del formulario 
function insertar(){

$nombre 		=	$_POST['nombre'];
$tipo_estudio	=	$_POST['tipo_estudio'];
$precio			=	$_POST['precio'];

//echo $nombre.' '.$tipo_estudio.' '.$precio;
$nombre         =   pasarMayusculas($nombre);
$tipo_estudio   =   pasarMayusculas($tipo_estudio);
$precio         =   pasarMayusculas($precio);

$mysql =new mysql();
$link = $mysql->connect(); 

$sql = $mysql->query($link,"INSERT INTO 
							estudio (idgammagramas, tipo, nombre, particular) 
							VALUES
							('','$tipo_estudio','$nombre','$precio');");

echo 'filas afectadas: (Se insertó) '.$mysql->affect_row().'registro completo';
	
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
        <?php include_once "include/nav_session.php" ?>

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
                <form  role="form" id="agregar_estudio" method="post" action="controlador_agregar_nuevo_estudio.php">
                
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
</body>


</html>