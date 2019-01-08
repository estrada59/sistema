<?php
//captura datos del formulario 

function previsualizacion(){
//datos anteriores   
date_default_timezone_set('America/Mexico_City');
//datos anteriores
$fecha_ant          =   $_POST["fecha_ant"];    
$fecha_ant          =   date('d-m-Y',strtotime($fecha_ant));

$hora_ant           =   $_POST['hora_ant'];
$hora_ant           =   date('g:i a',strtotime($hora_ant));

$nombre_ant         =   $_POST['nombre_ant'];
$ap_paterno_ant     =   $_POST['ap_paterno_ant'];
$ap_materno_ant     =   $_POST['ap_materno_ant'];
$tel_local_ant      =   $_POST['tel_local_ant'];
$tel_cel_ant        =   $_POST['tel_cel_ant'];
$nombre_estudio     =   $_POST['nombre_estudio'];
$idestudio          =   $_POST['idestudio'];
$idpaciente          =   $_POST['idpaciente'];
$institucion         =   $_POST['institucion'];

$paciente_ant       =   $nombre_ant.' '.$ap_paterno_ant.' '.$ap_materno_ant;    

//datos actuales

echo' 
<div class="col-md-12 col-lg-12">  
    <div class="panel panel-primary">        
        <div class = "panel-heading">
            <h3 class="panel-title"> Se actualizaron los registros </h3>
        </div>  <!--    fin panel heading   -->
            
        <div class="panel-body">
';

echo '      <p><strong>Estos datos se actualizaron</strong> </p>';
echo '  
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="fecha">Fecha</th>
                            <th data-field="hora">Hora</th>
                            <th data-field="paciente">Paciente</th>
                            <th data-field="estudio">Nombre del estudio</th>
                            <th data-field="tel_local">Institución anterior</th>
                            <th data-field="tel_local">Institución actual</th>

                            <!--<th data-field="tel_cel">id estudio.</th>-->
                            <!--<th data-field="tel_cel">id Paciente.</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="fecha">'.$fecha_ant.'</th>
                            <th data-field="hora">'.$hora_ant.'</th>
                            <th data-field="paciente">'.$paciente_ant.'</th>
                            <th data-field="estudio">'.$nombre_estudio.'</th>
                            <th data-field="tel_local">'.$institucion.'</th>
                            <th data-field="tel_local">'.'PARTICULAR'.'</th>
                            <!--<th data-field="tel_cel">'.$idestudio.'</th>-->
                            <!--<th data-field="tel_cel">'.$idpaciente.'</th>-->
                        </tr>
                    <tbody>
                </table>
            </div>';

echo '       
        </div>    

        <div class="panel-footer">     
        </div>
            
    </div>
</div>';

    
    $ag = new agenda();
    $ag->agendar_pac_particular();  
}
?>

<?php
class agenda{
    function agendar_pac_particular(){
        
        include_once "include/mysql.php";

        $mysql =new mysql();
        $link = $mysql->connect(); 

        $idestudio       =   $_POST['idestudio'];
        $idpaciente      =  $_POST['idpaciente'];

        date_default_timezone_set('America/Mexico_City');
        $fecha_estudio = date('Y-m-d ',strtotime($_POST["fecha_ant"]));

        
            
        $sql = $mysql->query($link,"UPDATE pacientes 
                                SET   institucion       =   'PARTICULAR'
                                WHERE idpacientes   = $idpaciente");

                
        //inserto anticipos (creo el registro de una venta particular)
        $sql = $mysql->query($link,"INSERT INTO anticipos (idanticipos,
                                            dep_banamex,
                                            pago_santander,
                                            pago_cheque,
                                            transferencia,
                                            anticipo_efe,
                                            factura,
                                            no_recibo)
                                            VALUES ('', '0.00', '0.00', '0.00', '0.00', '0.00', ' ', ' '); ");

        $idanticipo = mysqli_insert_id($link);

        //BUSCO PRECIO DEL ESTUDIO
        $sql = $mysql->query($link, "SELECT particular 
                                        FROM estudio
                                        WHERE idgammagramas = $idestudio;" );
                    
        $fila = $mysql->f_obj($sql);
        $precio_estudio = $fila->particular;
        
        //relaciono el anticipo con el paciente (creo el registro de una venta particular)
        $sql = $mysql->query($link,"INSERT INTO pacientes_has_anticipos (pacientes_idpacientes,
                                                anticipos_idanticipos,
                                                fecha_anticipo,
                                                fecha_estudio,
                                                monto_restante)
                                            VALUES ($idpaciente,
                                                    $idanticipo,
                                                    '0000-00-00',
                                                    '$fecha_estudio',
                                                    $precio_estudio); ");
        $mysql->close();
    }

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

        if(!isset($_POST["idpaciente"]) ){
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
                        <!--<ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Modificación de lista de precios
                            </li>
                        </ol>-->
                    </div>
                </div>
                <!-- /.row -->
            <!-- /.container-fluid -->
            <?php previsualizacion();

            ?>
            <form  role="form" id="editar_paciente" method="post" action="viewmod_ver_editar_institucion_a_particular.php">
                <?php 
                    $fecha_ant          =   $_POST["fecha_ant"]; 
                    date_default_timezone_set('America/Mexico_City');
                    $fecha_ant = date('Y-m-d ',strtotime($fecha_ant));
                    echo '<input type="hidden" form="editar_paciente" name="fecha_estudios" id="fecha_estudios" value="'.$fecha_ant.'"/>';
                ?>
            </form>
            <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_paciente" type="submit" value="Aceptar" class="btn btn-primary">
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