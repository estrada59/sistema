<?php
include "include/mysql.php";

//captura datos del formulario 

function previsualizacion(){
//datos anteriores   
date_default_timezone_set('America/Mexico_City');
echo '<pre>';
print_r($_POST);
echo '</pre>';
//datos anteriores
$fecha          =   $_POST["fecha"];    
$fecha          =   date('d-m-Y',strtotime($fecha));

$hora           =   $_POST['hora'];
$hora           =   date('g:i a',strtotime($hora));

$nombre         =   $_POST['nombre'];
$ap_paterno     =   $_POST['ap_paterno'];
$ap_materno     =   $_POST['ap_materno'];
$tel_local      =   $_POST['tel_local'];
$tel_cel        =   $_POST['tel_cel'];
$email          =   $_POST['email'];
$paciente       =   $nombre.' '.$ap_paterno.' '.$ap_materno;    
$institucion    =   $_POST['institucion'];
$medico         =   $_POST['medico'];

$estudio_ant    = $_POST['nombre_estudio_ant'];
$estudio_nv     = $_POST['estudio_nuevo'];


include_once "include/mysql.php";


$mysql =new mysql();
$link = $mysql->connect();
$sql = $mysql->query($link,"SELECT tipo, nombre
                                FROM estudio
                                WHERE idgammagramas = $estudio_nv");

$row = $mysql->f_obj($sql);

$estudio_nuevo = $row->tipo.' '.$row->nombre;

//datos actuales

$lista = array( "nombre"        =>  $nombre,
                "ap_paterno"    =>  $ap_paterno,
                "ap_materno"    =>  $ap_materno,
                "tel_local"     =>  $tel_local,
                "tel_cel"       =>  $tel_cel,
                "email"         =>  $email,
                "medico"        =>  $medico);

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
                            <th data-field="tel_local">Teléfono Local</th>
                            <th data-field="tel_cel">Teléfono cel.</th>
                            <th data-field="email">Correo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="fecha">'.$fecha.'</th>
                            <th data-field="hora">'.$hora.'</th>
                            <th data-field="paciente">'.$paciente.'</th>
                            <th data-field="estudio">'.$estudio_ant.'</th>
                            <th data-field="tel_local">'.$tel_local.'</th>
                            <th data-field="tel_cel">'.$tel_cel.'</th>
                            <th data-field="email">'.$email.'</th>
                        </tr>
                    <tbody>
                </table>
            </div>

            <div class="table-responsive">    
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="nombre_med">Nombre del médico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="fecha">'.$medico.'</th>
                        </tr>
                    <tbody>
                </table> 
            </div>';


echo '      <p><strong>Por estos de acá</strong></p> '; 


echo '      <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="fecha">Fecha</th>
                            <th data-field="hora">Hora</th>
                            <th data-field="paciente">Paciente</th>
                            <th data-field="estudio">Nombre del estudio</th>
                            <th data-field="tel_local">Teléfono Local</th>
                            <th data-field="tel_cel">Teléfono cel.</th>
                            <th data-field="email">Correo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="fecha">'.$fecha.'</th>
                            <th data-field="hora">'.$hora.'</th>
                            <th data-field="paciente">'.$paciente.'</th>
                            <th data-field="estudio">'.$estudio_nuevo.'</th>
                            <th data-field="tel_local">'.$tel_local.'</th>
                            <th data-field="tel_cel">'.$tel_cel.'</th>
                            <th data-field="email">'.$email.'</th>
                        </tr>
                    <tbody>
                </table>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th data-field="nombre_med">Nombre del médico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="fecha">'.$medico.'</th>
                        </tr>
                    <tbody>
                </table>
            </div>
        
        </div>    

        <div class="panel-footer">
        </div>
            
    </div>
</div>';

        //update($lista);
}
?>
<?php
function update($lista){

            if($lista['grado'] == 'A quien corresponda'){
                $aquiencorresponda = 'SI';
                $lista['grado'] = ' ';
            }else{
                $aquiencorresponda = 'NO';
            }

            $mysql = new mysql();
            $mysqli = $mysql->connect();

            $idpaciente = $_POST['idpaciente'];
            $fecha      = $_POST['fecha'];
            $hora           =   $_POST['hora'];

            $nombre     = $lista["nombre"];
            $ap_paterno = $lista['ap_paterno'];
            $ap_materno = $lista['ap_materno'];
            $tel_local  = $lista['tel_local'];
            $tel_cel    = $lista['tel_cel'];
            $email      = $lista['email'];

            $sql = "UPDATE pacientes SET    fecha       =   '$fecha',
                                            hora        =   '$hora',
                                            nombre      =   '$nombre',
                                            ap_paterno  =   '$ap_paterno',
                                            ap_materno  =   '$ap_materno',
                                            num_tel     =   '$tel_local',
                                            num_tel2    =   '$tel_cel',
                                            email       =   '$email'
                        WHERE idpacientes   = $idpaciente";
            
            $mysqli->query($sql);

            printf("%s\n", mysqli_info($mysqli));

            echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';

            $nombre_med     = $lista["nombre_med"];
            $ap_paterno_med = $lista['ap_paterno_med'];
            $ap_materno_med = $lista['ap_materno_med'];
            $grado          = $lista['grado'];
            $especialidad   = $lista['especialidad'];


            $sql = "UPDATE doctores SET    
                                            grado       =   '$grado',
                                            nombre      =   '$nombre_med',
                                            ap_paterno  =   '$ap_paterno_med',
                                            ap_materno  =   '$ap_materno_med',
                                            especialidad=   '$especialidad',
                                            aquiencorresponda = '$aquiencorresponda'
                        WHERE idpaciente   = '$idpaciente'";
            
            $mysqli->query($sql);

            printf("%s\n", mysqli_info($mysqli));

            echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';
          /*   $sql = "UPDATE pacientes_has_anticipos SET    fecha       =   '$fecha',
                                                            hora        =   '$hora',
                                                            num_tel     =   '$tel_local',
                                                            num_tel2    =   '$tel_cel',
                                                            email       =   '$email'
                        WHERE idpacientes   = '$idpaciente'";


    
            $mysql->close();*/
            $mysql->close();
}

?>
<?php
function pasarMayusculas($cadena) { 
        $cadena = strtoupper($cadena); 
        $cadena = str_replace("á", "Á", $cadena); 
        $cadena = str_replace("é", "É", $cadena); 
        $cadena = str_replace("í", "Í", $cadena); 
        $cadena = str_replace("ó", "Ó", $cadena); 
        $cadena = str_replace("ú", "Ú", $cadena); 
        $cadena = str_replace("ñ", "Ñ", $cadena);
        return ($cadena); 
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

        if($_SESSION['nivel_acceso'] >= 3){
                header("Location: index.php");
                exit();
        }
        if(!isset($_POST["idpaciente"]) ){
            header('Location: index.php');
        }
     ?>
</head>

<body> 
    <div id="wrapper">
        <!-- Navigation -->
       <?php include "nav_priv.php"; ?>

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
            <form  role="form" id="editar_paciente" method="post" action="controlador_modificar_estudio_paciente.php">
                <?php 
                    $fecha          =   $_POST["fecha"]; 
                    echo '<input type="hidden" form="editar_paciente" name="fecha_estudios" id="fecha_estudios" value="'.$fecha.'"/>';
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