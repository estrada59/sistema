<?php
include "include/mysql.php";

//captura datos del formulario 


function previsualizacion(){
//datos anteriores   
date_default_timezone_set('America/Mexico_City');
// echo'<pre>';
// print_r($_POST);
// echo'</pre>';
//datos anteriores
$idpaciente         =   $_POST['idpaciente'];
$nombre_ant         =   $_POST['nombre_ant'];
$ap_paterno_ant     =   $_POST['ap_paterno_ant'];
$ap_materno_ant     =   $_POST['ap_materno_ant'];
$paciente_ant       =   $nombre_ant.' '.$ap_paterno_ant.' '.$ap_materno_ant; 
$tel_local_ant      =   $_POST['tel_local_ant'];
$tel_cel_ant        =   $_POST['tel_cel_ant'];
$email_ant          =   $_POST['email_ant'];
$edad_ant           =   $_POST['edad_ant'];
$tipo_edad_ant      =   $_POST['tipo_edad_ant'];
$fecha_nac_ant      =   $_POST['fecha_nac_ant'];
$fecha_nac_ant      =   date('d-m-Y',strtotime($fecha_nac_ant));

$fecha_estudio_ant          =   $_POST["fecha_estudio_ant"];    
$fecha_estudio_ant          =   date('d-m-Y',strtotime($fecha_estudio_ant));
$hora_estudio_ant               =   $_POST['hora_estudio_ant'];
$hora_estudio_ant               =   date('g:i a',strtotime($hora_estudio_ant));
$indicaciones_estudio_ant       = $_POST['indicaciones_estudio_ant'];
$indicaciones_tratamiento_ant   = $_POST['indicaciones_trat_ant'];  

$grado_ant          =   $_POST['grado_ant'];
$nombre_med_ant     =   $_POST['nombre_med_ant'];
$ap_paterno_med_ant =   $_POST['ap_paterno_med_ant'];
$ap_materno_med_ant =   $_POST['ap_materno_med_ant'];

if($grado_ant == 'DR.' || $grado_ant == 'DRA.' ){
    $dr_ant = $grado_ant.' '.$nombre_med_ant.' '.$ap_paterno_med_ant.' '.$ap_materno_med_ant;
}else{
    $dr_ant = 'Aquien corresponda';
}

$especialidad_med_ant   =   $_POST['especialidad_med_ant'];


//datos actuales


$nombre         =   pasarMayusculas($_POST['nombre']);
$ap_paterno     =   pasarMayusculas($_POST['ap_paterno']);
$ap_materno     =   pasarMayusculas($_POST['ap_materno']);
$paciente_nuevo =   $nombre.' '.$ap_paterno.' '.$ap_materno; 
$tel_local      =   pasarMayusculas($_POST['tel_local']);
$tel_cel        =   pasarMayusculas($_POST['tel_cel']);
$email          =   pasarMayusculas($_POST['email']);
$edad           =   $_POST['edad'];
$tipo_edad      =   $_POST['tipo_edad'];
$fecha_nacimiento      =   $_POST['fecha_nacimiento'];
$fecha_nacimiento     =   date('d-m-Y',strtotime($fecha_nacimiento));


$nombre_estudio =   pasarMayusculas($_POST['nombre_estudio']);
$fecha_estudio  =   $_POST["fecha_estudio"];    
$fecha_estudio          =   date('d-m-Y',strtotime($fecha_estudio));
$hora_estudio           =   $_POST['hora_estudio'];
$hora_estudio           =   date('g:i a',strtotime($hora_estudio));
$indicaciones_estudio = $_POST['indicaciones_estudio'];
$indicaciones_tratamiento = $_POST['indicaciones_tratamiento'];

$grado          =   pasarMayusculas($_POST['grado']);
$nombre_med     =   pasarMayusculas($_POST['nombre_med']);
$ap_paterno_med =   pasarMayusculas($_POST['ap_paterno_med']);
$ap_materno_med =   pasarMayusculas($_POST['ap_materno_med']);
$especialidad_med   =   pasarMayusculas($_POST['especialidad_med']);

if($grado == 'SI'){
    $grado = 'A quien corresponda';}

$dr = $grado.' '.$nombre_med.' '.$ap_paterno_med.' '.$ap_materno_med;


$lista = array( "nombre"                    =>  $nombre,
                "ap_paterno"                =>  $ap_paterno,
                "ap_materno"                =>  $ap_materno,
                "tel_local"                 =>  $tel_local,
                "tel_cel"                   =>  $tel_cel,
                "email"                     =>  $email,
                "edad"                      =>  $edad,
                "tipo_edad"                 =>  $tipo_edad,
                "fecha_nacimiento"          =>  $fecha_nacimiento,
                "fecha_estudio"             =>  $fecha_estudio,
                "hora_estudio"              =>  $hora_estudio,
                "indicaciones_estudio"      =>  $indicaciones_estudio,
                "indicaciones_tratamiento"  =>  $indicaciones_tratamiento,
                "nombre_med"                =>  $nombre_med,
                "ap_paterno_med"            =>  $ap_paterno_med,
                "ap_materno_med"            =>  $ap_materno_med,
                "grado"                     =>  $grado,
                "especialidad"              =>  $especialidad_med);

echo'<pre>';
echo print_r($lista);
echo'</pre>';

echo'<pre>';
echo print_r($_SESSION);
echo'</pre>';


echo'   <div class="col-md-6 col-lg-6">  
            <div class="panel panel-primary">        
                <div class = "panel-heading">
                    <h3 class="panel-title">Registros anteriores </h3>
                </div>  <!--    fin panel heading   -->
                    
                <div class="panel-body">
        ';
        
        echo '      <p><strong>DATOS DEL PACIENTE ANTERIOR</strong> </p>';
        echo '  
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            
                            <tbody>
                                
                                <tr>
                                    <th data-field="paciente">Nombre del paciente</th>
                                    <th data-field="paciente">'.$paciente_ant.'</th>
                                </tr>
        
                                <tr>
                                    <th data-field="tel_local">Teléfono Local</th>
                                    <th data-field="tel_local">'.$tel_local_ant.'</th>
                                </tr>
        
                                <tr>
                                    <th data-field="tel_cel">Teléfono cel.</th>
                                    <th data-field="tel_cel">'.$tel_cel_ant.'</th>
                                </tr>
        
                                <tr>
                                    <th data-field="email">Correo</th>
                                    <th data-field="email">'.$email_ant.'</th>
                                </th>
        
                                <tr>
                                    <th data-field="edad">Edad del paciente</th>
                                    <th data-field="edad">'.$edad_ant.' '.$tipo_edad_ant.'</th>
                                </th>

                                <tr>
                                    <th data-field="fecha_nacimiento">Fecha de nacimiento</th>
                                    <th data-field="fecha_nacimiento">'.$fecha_nac_ant.'</th>
                                </th>
                                
                            <tbody>
                        </table>
                    </div>

                    </br>

                    <p><strong>DATOS DEL ESTUDIO ANTERIOR</strong></p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th data-field="fecha">Fecha del estudio</th>
                                    <th data-field="fecha">'.$fecha_estudio_ant.'</th>
                                </tr>
                                <tr>
                                    <th data-field="hora">Hora del estudio</th>
                                    <th data-field="hora">'.$hora_estudio_ant.'</th>
                                </tr>
                                <tr>
                                    <th data-field="estudio">Nombre del estudio</th>
                                    <th data-field="estudio">'.$nombre_estudio.'</th>
                                </tr>
                                <tr>
                                    <th data-field="indicaciones_estudio">Indicaciones del estudio</th>
                                    <th data-field="indicaciones_estudio">'.$indicaciones_estudio_ant.'</th>
                                </tr>
                                <tr>
                                    <th data-field="indicaciones_tratamiento">Indicaciones del tratamiento</th>
                                    <th data-field="indicaciones_tratamiento">'.$indicaciones_tratamiento_ant.'</th>
                                </tr> 
                            <tbody>
                        </table>
                    </div>

                    </br>

                    <p><strong>DATOS DEL MÉDICO TRATANTE ANTERIOR</strong></p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th data-field="medico">Nombre del médico</th>
                                    <th data-field="medico">'.$dr_ant.'</th>
                                </tr>
                                <tr>
                                    <th data-field="especialidad">Especialidad</th>
                                    <th data-field="especialidad">'.$especialidad_med_ant.'</th>
                                </tr>
                                
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>';


echo'   <div class="col-md-6 col-lg-6">  
        <div class="panel panel-primary">        
            <div class = "panel-heading">
                <h3 class="panel-title"> Registros nuevos </h3>
            </div>  <!--    fin panel heading   -->
                
            <div class="panel-body">
    ';
    
    echo '      <p><strong>DATOS DEL PACIENTE NUEVO</strong> </p>';
    echo '  
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        
                        <tbody>
                            
                            <tr>
                                <th data-field="paciente">Nombre del paciente</th>
                                <th data-field="paciente">'.$paciente_nuevo.'</th>
                            </tr>
    
                            <tr>
                                <th data-field="tel_local">Teléfono Local</th>
                                <th data-field="tel_local">'.$tel_local.'</th>
                            </tr>
    
                            <tr>
                                <th data-field="tel_cel">Teléfono cel.</th>
                                <th data-field="tel_cel">'.$tel_cel.'</th>
                            </tr>
    
                            <tr>
                                <th data-field="email">Correo</th>
                                <th data-field="email">'.$email.'</th>
                            </th>
    
                            <tr>
                                <th data-field="edad">Edad del paciente</th>
                                <th data-field="edad">'.$edad.' '.$tipo_edad.'</th>
                            </th>

                            <tr>
                                <th data-field="fecha_nacimiento">Fecha de nacimiento</th>
                                <th data-field="fecha_nacimiento">'.$fecha_nacimiento.'</th>
                            </th>
                            
                        <tbody>
                    </table>
                </div>

                </br>

                <p><strong>DATOS DEL ESTUDIO NUEVO</strong></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <tbody>
                            <tr>
                                <th data-field="fecha">Fecha del estudio</th>
                                <th data-field="fecha">'.$fecha_estudio.'</th>
                            </tr>
                            <tr>
                                <th data-field="hora">Hora del estudio</th>
                                <th data-field="hora">'.$hora_estudio.'</th>
                            </tr>
                            <tr>
                                <th data-field="estudio">Nombre del estudio</th>
                                <th data-field="estudio">'.$nombre_estudio.'</th>
                            </tr>
                            <tr>
                                <th data-field="indicaciones_estudio">Indicaciones del estudio</th>
                                <th data-field="indicaciones_estudio">'.$indicaciones_estudio.'</th>
                            </tr>
                            <tr>
                                <th data-field="indicaciones_tratamiento">Indicaciones del tratamiento</th>
                                <th data-field="indicaciones_tratamiento">'.$indicaciones_tratamiento.'</th>
                            </tr> 
                        <tbody>
                    </table>
                </div>

                </br>

                <p><strong>DATOS DEL MÉDICO TRATANTE NUEVO</strong></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <tbody>
                            <tr>
                                <th data-field="medico">Nombre del médico</th>
                                <th data-field="medico">'.$dr.'</th>
                            </tr>
                            <tr>
                                <th data-field="especialidad">Especialidad</th>
                                <th data-field="especialidad">'.$especialidad_med.'</th>
                            </tr>
                            
                        <tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>';      

       
        //echo $_POST['idpaciente'];
        //reagendar($lista);
}
?>
<?php 
function reagendar($lista){
            
            if($lista['grado'] == 'A quien corresponda'){
                $aquiencorresponda = 'SI';
                $lista['grado'] = ' ';
            }else{
                $aquiencorresponda = 'NO';
            }


            $mysql = new mysql();
            $mysqli = $mysql->connect();

            $idpaciente = $_POST['idpaciente'];

            $sql = $mysql->query($link,"SELECT  doctores_iddoctores as iddoctor,
                                                estudio_idgammagramas as idgammagrama,
                                                institucion
                                            FROM pacientes
                                            WHERE idpacientes = $idpaciente");
            
            $res = $mysqli->query($sql);

            $fila = mysqli_fetch_object($res);
            
            $id_doctor      = $fila->iddoctor;
            $id_estudio     = $fila->idgammagrama;
            $fecha_estudio  = date('Y-m-d',strtotime($lista['fecha_estudio']));
            $hora_estudio   = date('H:i:s',strtotime($_POST['hora_estudio']));
            $nombre         = $lista["nombre"];
            $ap_paterno     = $lista['ap_paterno'];
            $ap_materno     = $lista['ap_materno'];
            $tel_local      = $lista['tel_local'];
            $tel_cel        = $lista['tel_cel'];
            $email          = $lista['email'];
            $institucion    = $fila->institucion;
            $indicaciones_estudio     = $lista['indicaciones_estudio'];
            $indicaciones_tratamiento = $lista['indicaciones_tratamiento'];
            $estatus        = 'POR ATENDER';
            $observaciones  = '';
            $atendio        = $_SESSION['id'];
            $edad           = $lista['edad'];
            $tipo_edad      = $lista['tipo_edad'];
            $fecha_nac      =date('Y-m-d H:i:s',strtotime($lista['fecha_nacimiento']));
            
            
				 	//VERIFICACIÓN DE DATOS
					echo '$iddoctores '.$id_doctor;
					echo '$id_estudio '.$id_estudio;
					echo '$fecha '.$fecha_estudio;
					echo '$hora '.$hora_estudio;
					echo 'id_usuario '.$id_usuario;
					echo 'nombre '.$nombre;
					echo 'ap_pat '.$ap_paterno;
					echo 'ap_mat '.$ap_materno;
					echo 'tel_local '.$tel_local;
					echo 'tel_cel '.$tel_cel;
					echo 'email '.$email;
					echo 'edad '.$edad;
					echo 'tipo edad '.$tipo_edad;
					echo 'fecha nac '.$fecha_nacimiento;
					echo 'institucion '.$institucion;
					echo 'indicaciones '.$indicaciones;
					echo 'cantidad de 1-131 '.$cantidad_i131;
				

					// $sql = $mysql->query($link,"INSERT INTO pacientes (idpacientes,
					// 						doctores_iddoctores,
					// 						estudio_idgammagramas,
					// 						fecha,
					// 						hora,
					// 						nombre,
					// 						ap_paterno,
					// 						ap_materno,
					// 						num_tel,
					// 						num_tel2,
					// 						email,
					// 						institucion,
					// 						indicaciones,
					// 						indicaciones_tratamiento,
					// 						estatus,
					// 						observaciones,
					// 						atendio,
					// 						edad,
					// 						fecha_nacimiento)
					// 				VALUES ('',
					// 						$iddoctores,
					// 						$idestudio,
					// 						'$fecha_estudio',
					// 						'$hora',
					// 						'$nombre',
					// 						'$apepat',
					// 						'$apemat',
					// 						'$tel_local',
					// 						'$tel_cel',
					// 						'$email',
					// 						'$institucion',
					// 						'$indicaciones',
					// 						'$cantidad_i131',
					// 						'POR ATENDER',
					// 						'',
					// 						$id_usuario,
					// 						$edad,
					// 						'$fecha_nacimiento'); ");
					//echo ' filas afectadas: (Se insertó): '.$mysql->affect_row().' registro completo';
					// $idpaciente =  mysqli_insert_id($link);

					// $sql = $mysql->query($link,"UPDATE doctores 
					// 							SET idpaciente='$idpaciente'
					// 							WHERE iddoctores=$iddoctores");

					// $sql = $mysql->query($link,"SELECT id_edad
					// 							FROM tblc_edad
					// 							WHERE descripcion='$tipo_edad'; ");
					// $row = $mysql->f_obj($sql);


					// $sql = $mysql->query($link,"INSERT INTO tbl_edad_paciente (id_edad_paciente,
					// 							id_edad,
					// 							idpacientes)
					// 					VALUES ('',
					// 							$row->id_edad,
					// 							$idpaciente); ");
            

            
            // $sql = "UPDATE pacientes SET    fecha                   =   '$fecha_estudio',
            //                                 hora                    =   '$hora_estudio',
            //                                 nombre                  =   '$nombre',
            //                                 ap_paterno              =   '$ap_paterno',
            //                                 ap_materno              =   '$ap_materno',
            //                                 num_tel                 =   '$tel_local',
            //                                 num_tel2                =   '$tel_cel',
            //                                 email                   =   '$email',
            //                                 indicaciones            =   '$indicaciones_estudio',
            //                                 indicaciones_tratamiento=   '$indicaciones_tratamiento',
            //                                 edad                    =   '$edad',
            //                                 fecha_nacimiento        =   '$fecha_nac'
            //             WHERE idpacientes   = $idpaciente";
            
            // $mysqli->query($sql);

            //printf("%s\n", mysqli_info($mysqli));

        //    echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';

            // $nombre_med     = $lista["nombre_med"];
            // $ap_paterno_med = $lista['ap_paterno_med'];
            // $ap_materno_med = $lista['ap_materno_med'];
            // $grado          = $lista['grado'];
            // $especialidad   = $lista['especialidad'];


            // $sql = "UPDATE doctores SET    
            //                                 grado       =   '$grado',
            //                                 nombre      =   '$nombre_med',
            //                                 ap_paterno  =   '$ap_paterno_med',
            //                                 ap_materno  =   '$ap_materno_med',
            //                                 especialidad=   '$especialidad',
            //                                 aquiencorresponda = '$aquiencorresponda'
            //             WHERE idpaciente   = '$idpaciente'";
            
            // $mysqli->query($sql);

            //printf("%s\n", mysqli_info($mysqli));

        //    echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';
          /*   $sql = "UPDATE pacientes_has_anticipos SET    fecha       =   '$fecha',
                                                            hora        =   '$hora',
                                                            num_tel     =   '$tel_local',
                                                            num_tel2    =   '$tel_cel',
                                                            email       =   '$email'
                        WHERE idpacientes   = '$idpaciente'";


    
            $mysql->close();*/
            // $sql1 = "SELECT id_edad 
            //             from tblc_edad
            //             where descripcion = '$tipo_edad'";
            // $res = $mysqli->query($sql1);
            // $fila = mysqli_fetch_object($res);

            // $sql = "UPDATE tbl_edad_paciente SET id_edad = $fila->id_edad WHERE idpacientes = $idpaciente";
            // $mysqli->query($sql);

        //    echo '<div class="hola">filas afectadas: (Se actualizó) '.$mysqli->affected_rows.' fila</div>';
            // $mysql->close();
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
            <br>
            <br>
            <!-- /.container-fluid -->
            <?php previsualizacion(); ?>
            <?php 

                $var     =   $_POST["pagina_destino"];
                $fecha   =   $_POST["fecha_estudio"];
                date_default_timezone_set('America/Mexico_City');
                $fecha   = date('Y-m-d',strtotime($fecha));
                
                switch ($var) {
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

          <!--  <form  role="form" id="editar_paciente" method="post" action="viewmod_ver_editar_pacientes_por_fecha.php">
                <?php 
                    // $fecha_ant          =   $_POST["fecha_estudio_ant"]; 
                    // echo '<input type="hidden" form="editar_paciente" name="fecha_estudios" id="fecha_estudios" value="'.$fecha_ant.'"/>';
                ?>
            </form>
            <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_paciente" type="submit" value="Aceptar" class="btn btn-primary">
        </div>-->
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