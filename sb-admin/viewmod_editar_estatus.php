<?php
include "include/mysql.php";

function previsualizacion(){
//print_r($_POST);
//[pag_retorno] => ver_pacientes_del_dia 

$idpaciente     =   $_POST["idpaciente"];    
$fecha_estudios =   $_POST['fecha_estudios'];
$estatus        =   $_POST['estatus'];
$nombre         =   $_POST['nombre_paciente'];
$estudio        =   $_POST['estudio'];
$fecha          =   $_POST['fecha'];

if(!isset($_POST['motivos'])){
    $motivos = '';
}
else{
    include_once 'include/funciones_consultas.php';
    $motivos        =   $_POST['motivos'];
    $motivos        =   pasarMayusculas($motivos);
}

$estatus_bd     =   consultar_estatus($idpaciente);
$motivos_bd      =   consultar_motivos($idpaciente);

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
                            <th data-field="id">Fecha estudio</th>
                            <th data-field="name">Paciente</th>
                            <th data-field="price">Estudio</th>
                            <th data-field="price">Estatus</th>
                            <th data-field="price">Motivos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="id">'.$idpaciente.'</th>
                            <th data-field="fecha">'.$fecha.'</th>
                            <th data-field="nombre">'.$nombre.'</th>
                            <th data-field="estudio">'.$estudio.'</th>
                            <th data-field="estatus">'.$estatus_bd.'</th>
                            <th data-field="estatus">'.$motivos_bd.'</th>
                        </tr>
                    <tbody>
                </table>   ';

echo '<p><strong>Por estos de acá</strong></p> '; 


echo '  <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="id">Item ID</th>
                            <th data-field="id">Fecha estudio</th>
                            <th data-field="name">Paciente</th>
                            <th data-field="price">Estudio</th>
                            <th data-field="price">Estatus</th> 
                            <th data-field="price">Motivos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="id">'.$idpaciente.'</th>
                            <th data-field="fecha">'.$fecha.'</th>
                            <th data-field="nombre">'.$nombre.'</th>
                            <th data-field="estudio">'.$estudio.'</th>
                            <th data-field="estudio">'.$estatus.'</th>
                            <th data-field="estudio">'.$motivos.'</th>
                           
                        </tr>
                    <tbody>
                </table> 
            </div>

            <div class="panel-footer">
            ';

             echo'
            </div>
            
        </div>';

        update($idpaciente, $estatus, $motivos);
}
?>
<?php
function update($idpaciente, $estatus, $motivos){

            $mysql = new mysql();
            $mysqli = $mysql->connect();
            
            $sql = "UPDATE pacientes SET estatus='$estatus', observaciones='$motivos'
                        WHERE idpacientes='$idpaciente'";

            $mysqli->query($sql);

            //printf("%s\n", mysqli_info($mysqli));

            //echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';


    
            $mysql->close();
}
function consultar_estatus($idpaciente){

            include_once "include/mysql.php";

            $mysql =new mysql();
            $link = $mysql->connect(); 
            
            $sql = $mysql->query($link, "SELECT estatus FROM pacientes WHERE idpacientes=$idpaciente");
            
            $obj = $mysql->f_obj($sql);

            $mysql->close();

            return $obj->estatus;

}
function consultar_motivos($idpaciente){

            include_once "include/mysql.php";

            $mysql =new mysql();
            $link = $mysql->connect(); 
            
            $sql = $mysql->query($link, "SELECT observaciones FROM pacientes WHERE idpacientes=$idpaciente");
            
            $obj = $mysql->f_obj($sql);
           
            $mysql->close();

            return $obj->observaciones;

}
function delay(){
    sleep(10);
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
                                <i class="fa fa-dashboard"></i> Modificación de estatus de paciente
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

                // echo'<pre>';
                // print_r($_POST);
                // echo '</pre>';
                
                switch ($var) {
                                case 'ver_pacientes_mañana':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_mañana.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                     break;
                                case 'ver_pacientes_por_mes':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_mes.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                     break;
                                case 'ver_pacientes_del_dia':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_del_dia.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                     break;
                                case 'ver_pacientes_por_semana':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_semana.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                     break;
                                case 'editar_paciente_por_fecha':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_editar_pacientes_por_fecha.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                     break;
                                case 'editar_estudio_de_paciente':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_editar_estudio_de_paciente.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                     break;

                                case 'facturas':
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_mes_factura.php" accept-charset="UTF-8">
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Aceptar
                                    </button> ';
                                     break;
                                
                                     case 'buscar_paciente':
                                     echo'
                                      <form role="form" id="ver_lista" method="post" action="viewmod_buscar_paciente.php" accept-charset="UTF-8">
                                         <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>';
                                         
                                     echo'    
                                         <input type="hidden" form="ver_lista" name="nombre" value="'.$_POST['nombre'].'"/>
                                         <input type="hidden" form="ver_lista" name="appat" value="'.$_POST['appat'].'"/>
                                         <input type="hidden" form="ver_lista" name="apmat" value="'.$_POST['apmat'].'"/>';

                                    echo'
                                     </form>
                                     <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                         <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                         Atrás
                                     </button> ';
                                      break;
                                 
                                 
                                default:
                                    $fecha = $_POST['fecha'];
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_por_semana.php" accept-charset="UTF-8">
                              
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