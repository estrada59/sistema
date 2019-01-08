<?php session_start();?>

<?php

include "include/mysql.php";

function previsualizacion(){

$idpaciente     =   $_POST["idpaciente"];    
$fecha_estudios =   $_POST['fecha_estudios'];
$estatus        =   $_POST['estatus'];
$nombre         =   $_POST['nombre'];
$estudio        =   $_POST['estudio'];
$fecha          =   $_POST['fecha'];
$factura        =   $_POST['factura'];
$numero_facturas=   $_POST['numero_facturas'];

/*echo '<pre>';
print_r($_SESSION);
echo '</pre>';*/

$id_usuario = $_SESSION['id'];

$mysql = new mysql();
$link = $mysql->connect(); 
$sql2 = $mysql->query($link,"SELECT idfactura, requiere_factura, facturado 
                                FROM facturas 
                                WHERE idpaciente = $idpaciente");
                        
$row3 =  $mysql->f_obj($sql2);
            
$num = $mysql->f_num($sql2);

           
if($num){
    $sql = $mysql->query($link,"UPDATE facturas 
                                    SET facturado= '$factura',
                                        numero_facturas = '$numero_facturas',
                                        id_usuario = $id_usuario
                                    WHERE idfactura = $row3->idfactura");
    $requiere_factura_ant = $row3->facturado;
}
else{
    if($factura == 'SI'){
    $sql = $mysql->query($link,"INSERT INTO facturas (idfactura,
                                                    idpaciente,
                                                    requiere_factura,
                                                    facturado,
                                                    numero_facturas)
                                        VALUES('', $idpaciente, '$factura', '$factura','$numero_facturas')");
    $requiere_factura_ant = "Estaba sin asignar";
    }
}

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
                            <th data-field="name">Paciente</th>
                            <th data-field="Estudio">Estudio</th>
                            <th data-field="requiere_factura">¿Requiere factura?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="nombre">'.$nombre.'</th>
                            <th data-field="estudio">'.$estudio.'</th>
                            <th data-field="estudio">'.$requiere_factura_ant.'</th>
                            
                        </tr>
                    <tbody>
                </table>   ';

echo '<p><strong>Por estos de acá</strong></p> '; 


echo '  <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="name">Paciente</th>
                            <th data-field="price">Estudio</th>
                            <th data-field="requiere_factura">¿Requiere factura?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="nombre">'.$nombre.'</th>
                            <th data-field="estudio">'.$estudio.'</th>
                            <th data-field="estudio">'.$factura.'</th>
                        </tr>
                    <tbody>
                </table> 
            </div>

            <div class="panel-footer">
            ';

             echo'
            </div>
            
        </div>';

      //  update($idpaciente);
}
?>
<?php
function update($idpaciente){

            $mysql = new mysql();
            $mysqli = $mysql->connect();
            
            $sql = "UPDATE facturas SET requiere_facturas ='$factura'
                        WHERE idpaciente='$idpaciente'";

            $mysqli->query($sql);

            printf("%s\n", mysqli_info($mysqli));

            echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';

            $mysql->close();
}


?>


<!DOCTYPE html>
<?php $_POST["pagina"]="facturas"; ?>
<html lang="es">
<head>
    <!-- aqui pondremos el contenido html -->
     <?php  include "header.php"; 
            include "../include/sesion.php";
            comprueba_url();

        if(!isset($_POST["idpaciente"])){
            header('Location: index.php');
        }
     ?>
</head>

<body> 
    <div id="wrapper">
        <!-- Navigation -->
       <?php include "nav.php"; ?>

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
                                <i class="fa fa-dashboard"></i> Modificación de datos del paciente
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
            <!-- /.container-fluid -->
            <?php previsualizacion();?>

            <?php 
                $var     =   $_POST["pag_retorno"];
                $fecha   =   $_POST["fecha"];
                date_default_timezone_set('America/Mexico_City');
                $fecha   = date('Y-m-d',strtotime($fecha));
                
                switch ($var) {
                                

                                case 'facturas':
                                    if($_SESSION['nivel_acceso']<=3){
                                        echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_mes_factura.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                    
                                    }
                                     break;
                               
                                 
                                default:
                                    $fecha = $_POST['fecha'];
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_semana_facturas.php" accept-charset="UTF-8">
                              
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/> 
                              
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button>';
                                    break;
                             }
            ?>
         
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