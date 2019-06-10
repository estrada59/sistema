<?php 

include "include/mysql.php";


// obtener el nombre de estudio
$idestudio      = $_POST['estudio'];

$mysql =new mysql();
$link = $mysql->connect(); 

$sql = $mysql->query($link,"SELECT tipo, nombre FROM estudio WHERE idgammagramas =".$idestudio." ");
                    
$row = $mysql->f_row($sql);

$nombre_estudio = $row[0].' '.$row[1];
$mysql->close();
//fin obtener nombre de estudio

$var = $_POST['dr'];

if($var == 'dr'){
    $med = 'Dr.';
}
if($var == 'dra'){
    $med = 'Dra.';
}
if($var != 'dr' && $var != 'dra'){
    $med =" ";
}
if($var == 'aquien_corresponda'){
    $aquien_corresponda="SI";
}
else{
    $aquien_corresponda="NO";
}

?>

<!DOCTYPE html>
<?php $_POST["pagina"]="form"; 
	if(!isset($_POST['nombre'])){
		header("Location: index.php");
	}
?>
<html lang="es">

<head>
    <?php 	include "header.php";
    		session_start(); 
			include "../include/sesion.php";
			comprueba_url();
            if(!isset($_POST)){
                header('Location: index.php');
            }
 	?>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
       <?php 
            include_once 'include/nav_session.php';
       ?>

        <div id="page-wrapper">

            <div class="container-fluid">
            <?php  /*echo'<pre>'; print_r($_POST);  echo'</pre>';*/?>

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="page-header"> <small> ¿Están bien los datos?</small></h1>
                    </div>
                   <div class="col-lg-3">
                            <form role="form" id="citar" method="post" action="controlador_citar_pacientes_institucion.php" accept-charset="UTF-8">
                                <?php
                                echo '<input type="hidden" name="nombre"            id="nombre"                 form="citar" value="'.$_POST['nombre'].'">';
                                echo '<input type="hidden" name="apepat"            id="apepat"                 form="citar" value="'.$_POST['apepat'].'">';
                                echo '<input type="hidden" name="apemat"            id="apemat"                 form="citar" value="'.$_POST['apemat'].'">';
                                echo '<input type="hidden" name="tel_local"         id="tel_local"              form="citar" value="'.$_POST['tel_local'].'">';
                                echo '<input type="hidden" name="tel_cel"           id="tel_cel"                form="citar" value="'.$_POST['tel_cel'].'">';
                                echo '<input type="hidden" name="email"             id="email"                  form="citar" value="'.$_POST['email'].'">';
                                echo '<input type="hidden" name="edad"              id="edad"                   form="citar" value="'.$_POST['edad'].'">';
                                echo '<input type="hidden" name="tipo_edad"         id="tipo_edad"              form="citar" value="'.$_POST['tipo_edad'].'">';
                                echo '<input type="hidden" name="fecha_nacimiento"  id="fecha_nacimiento"       form="citar" value="'.$_POST['fecha_nacimiento'].'">';

                                echo '<input type="hidden" name="nombre_estudio"    id="nombre_estudio" form="citar" value="'.$nombre_estudio.'">';
                                echo '<input type="hidden" name="idgammagramas"     id="idgammagramas"  form="citar" value="'.$_POST['estudio'].'">';
                                echo '<input type="hidden" name="cantidad_i131"     id="cantidad_i131"  form="citar" value="'.$_POST['cantidad_i131'].'">';
                                echo '<input type="hidden" name="fecha_estudio"     id="fecha_estudio"  form="citar" value="'.$_POST['fecha_estudio'].'">';
                                echo '<input type="hidden" name="hora"              id="hora"           form="citar" value="'.$_POST['hora'].'">';
                                echo '<input type="hidden" name="institucion"       id="institucion"    form="citar" value="'.$_POST['institucion'].'">';
                                echo '<input type="hidden" name="indicaciones"      id="indicaciones"   form="citar" value="'.$_POST['indicaciones'].'">';

                                echo '<input type="hidden" name="dr"                id="dr"                 form="citar" value="'.$_POST['dr'].'">';
                                echo '<input type="hidden" name="nombre_med"        id="nombre_med"         form="citar" value="'.$_POST['nombre_med'].'">';
                                echo '<input type="hidden" name="apepat_med"        id="apepat_med"         form="citar" value="'.$_POST['apepat_med'].'">';
                                echo '<input type="hidden" name="apemat_med"        id="apemat_med"         form="citar" value="'.$_POST['apemat_med'].'">';
                                echo '<input type="hidden" name="especialidad_med"  id="especialidad_med"   form="citar" value="'.$_POST['especialidad_med'].'">';
                                ?>
                            </form>

                            <button type="submit" class="btn btn-success btn-lg btn-block" id="btn_atras" aria-label="Left Align" form="citar">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás 
                            </button> 
                            
                            </br> 
                     </div>
                      <div class="col-lg-3">

                            <form role="form" id="citar_nuevo" method="post" action="controlador_citar_pacientes.php" accept-charset="UTF-8">
                            </form>

                            <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="citar_nuevo">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Citar nuevo paciente 
                            </button> 
                      </div>
                </div>
                <!-- /.row -->
                
                <div class="table-responsive">
                <table class="table table-striped">
                    <tbody>

                <?php 
                    include_once 'include/funciones_consultas.php';
                    $nombre             = $_POST['nombre'];
                    $apepat             = $_POST['apepat'];
                    $apemat             = $_POST['apemat'];
                    $tel_local          = $_POST['tel_local'];
                    $tel_cel            = $_POST['tel_cel'];
                    $email              = $_POST['email'];
                    $edad               = $_POST['edad'];
                    $tipo_edad          = $_POST['tipo_edad'];
                    
                    if($tipo_edad == 'AÑOS'){
                        $tipo_edad2='Año(s)';
                    }else{
                        $tipo_edad2='Mes(es)';
                    }

                    $fecha_nacimiento   = $_POST['fecha_nacimiento'];
                    $fecha_nacimiento2  = fecha_letras($fecha_nacimiento);
                    
                    $cantidad_i131       = $_POST['cantidad_i131'];
                    $institucion    = $_POST['institucion'];
                    $fecha_estudio  = $_POST['fecha_estudio'];
                    
                    
                    $fecha_estudio2 = fecha_letras($fecha_estudio);

                    $horas           = $_POST['hora'];
                    $hora = DATE("g:i a", STRTOTIME($horas));
                    $indicaciones   = $_POST['indicaciones'];

                   
                    $nombre_med         =   $_POST['nombre_med'];
                    $apepat_med         =   $_POST['apepat_med'];
                    $apemat_med         =   $_POST['apemat_med'];
                    $especialidad_med   =   $_POST['especialidad_med'];

                    

                    
                    

                echo'   <tr class="info"> 
                            <td >Nombre del paciente</td>
                            <td>'.$nombre.'</td>
                        </tr>
                        <tr> 
                            <td>Apellido paterno</td>
                            <td>'.$apepat.'</td>
                        </tr>
                        <tr> 
                            <td>Apellido materno</td>
                            <td>'.$apemat.'</td>
                        </tr>
                        <tr> 
                            <td>Telléfono local</td>
                            <td>'.$tel_local.'</td>
                        </tr>
                        <tr> 
                            <td>Teléfono celular</td>
                            <td>'.$tel_cel.'</td>
                        </tr>
                        <tr> 
                            <td>Email</td>
                            <td>'.$email.'</td>
                        </tr>
                        <tr> 
                            <td>Edad</td>
                            <td>'.$edad.' '.$tipo_edad2.'</td>
                        </tr>
                        <tr> 
                            <td>Fecha de nacimiento</td>
                            <td>'.$fecha_nacimiento2.'</td>
                        </tr>
                        <tr class="info"> 
                            <td>Nombre del estudio</td>
                            <td>'.$nombre_estudio.'</td>
                        </tr>
                        <tr class="info"> 
                            <td>Cantidad de I-131</td>
                            <td>'.$cantidad_i131.'</td>
                        </tr>
                        <tr>
                            <td>Institucion</td>
                            <td>'.$institucion.'</td>
                        </tr>
                        <tr>
                            <td>Fecha de estudio</td>
                            <td>'.$fecha_estudio2.'</td>
                        </tr>
                        <tr>
                            <td>Hora</td>
                            <td>'.$hora.'</td>
                        </tr>
                        <tr>
                            <td>Indicaciones</td>
                            <td>'.$indicaciones.'</td>
                        </tr>
                        <tr class="info">
                            <td>Nombre del médico</td>
                            <td>'.$med.' '.$nombre_med.'</td>
                        </tr>
                        <tr>
                            <td>Apellido paterno</td>
                            <td>'.$apepat_med.'</td>
                        </tr>
                        <tr>
                            <td>Apellido materno</td>
                            <td>'.$apemat_med.'</td>
                        </tr>
                        <tr>
                            <td>Especialidad</td>
                            <td>'.$especialidad_med.'</td>
                        </tr>
                        <tr>
                            <td> A quien corresponda</td>
                            <td>'.$aquien_corresponda.'</td>
                        </tr>

                        ';
                        
                ?>
                    </tbody>
                </table>
                </div>
                <?php
                     /* print "<pre>";
                        print_r($_POST);
                        print_r($_SESSION);
                        print "</pre>";*/
                ?>

                <div class="row">
                      <div class="col-md-12 col-lg-12">  
                        <div class="panel panel-primary">        
                            <div class="panel-body">
                                <form role="form" id="imprimir" method="post" action="view_imprimir_citar_pacientes.php" target="_blank" onsubmit="myFunction()" accept-charset="UTF-8">
                                <?php 
                                    echo '<input type="hidden" name="nombre"                id="nombre"             form="imprimir" value="'.$nombre.'">';
                                    echo '<input type="hidden" name="apepat"                id="apepat"             form="imprimir" value="'.$apepat.'">';
                                    echo '<input type="hidden" name="apemat"                id="apemat"             form="imprimir" value="'.$apemat.'">';
                                    echo '<input type="hidden" name="tel_local"             id="tel_local"          form="imprimir" value="'.$tel_local.'">';
                                    echo '<input type="hidden" name="tel_cel"               id="tel_cel"            form="imprimir" value="'.$tel_cel.'">';
                                    echo '<input type="hidden" name="email"                 id="email"              form="imprimir" value="'.$email.'">';
                                    echo '<input type="hidden" name="edad"                  id="edad"               form="imprimir" value="'.$edad.'">';
                                    echo '<input type="hidden" name="tipo_edad"             id="tipo_edad"          form="imprimir" value="'.$tipo_edad.'">';
                                    echo '<input type="hidden" name="fecha_nacimiento"      id="fecha_nacimiento"   form="imprimir" value="'.$fecha_nacimiento.'">';

                                    echo '<input type="hidden" name="estudio"       id="estudio"        form="imprimir" value="'.$row[0].' '.$row[1].'">';
                                    echo '<input type="hidden" name="idestudio"     id="idestudio"      form="imprimir" value="'.$idestudio.'">';
                                    echo '<input type="hidden" name="cantidad_i131" id="cantidad_i131"  form="imprimir" value="'.$cantidad_i131.'">';

                                    echo '<input type="hidden" name="fecha_estudio" id="fecha_estudio"  form="imprimir" value="'.$fecha_estudio.'">';
                                    echo '<input type="hidden" name="hora"          id="hora"           form="imprimir" value="'.$hora.'">';
                                    echo '<input type="hidden" name="institucion"   id="institucion"    form="imprimir" value="'.$institucion.'">';
                                    echo '<input type="hidden" name="indicaciones"  id="indicaciones"   form="imprimir" value="'.$indicaciones.'">';

                                    echo '<input type="hidden" name="med"                   id="med"                form="imprimir" value="'.$med.'">';
                                    echo '<input type="hidden" name="nombre_med"            id="nombre_med"         form="imprimir" value="'.$nombre_med.'">';
                                    echo '<input type="hidden" name="apepat_med"            id="apepat_med"         form="imprimir" value="'.$apepat_med.'">';
                                    echo '<input type="hidden" name="apemat_med"            id="apemat_med"         form="imprimir" value="'.$apemat_med.'">';
                                    echo '<input type="hidden" name="especialidad_med"      id="especialidad_med"   form="imprimir" value="'.$especialidad_med.'">';
                                    echo '<input type="hidden" name="aquien_corresponda"    id="aquien_corresponda" form="imprimir" value="'.$aquien_corresponda.'">';

                                ?>
                                </form>

                                <!--<form role="form" id="imprimir_trat" method="post" action="view_imprimir_citar_pacientes_tratamiento.php" target="_blank" onsubmit="myFunction()" accept-charset="UTF-8">
                                <?php 
                                    /*echo '<input type="hidden" name="nombre"               id="nombre"              form="imprimir_trat" value="'.$nombre.'">';
                                    echo '<input type="hidden" name="apepat"               id="apepat"              form="imprimir_trat" value="'.$apepat.'">';
                                    echo '<input type="hidden" name="apemat"               id="apemat"              form="imprimir_trat" value="'.$apemat.'">';
                                    echo '<input type="hidden" name="tel_local"            id="tel_local"           form="imprimir_trat" value="'.$tel_local.'">';
                                    echo '<input type="hidden" name="tel_cel"              id="tel_cel"             form="imprimir_trat" value="'.$tel_cel.'">';
                                    echo '<input type="hidden" name="email"                id="email"               form="imprimir_trat" value="'.$email.'">';
                                    echo '<input type="hidden" name="tipo_edad"            id="tipo_edad"           form="imprimir_trat" value="'.$tipo_edad.'">';
                                    echo '<input type="hidden" name="edad"                 id="edad"                form="imprimir_trat" value="'.$edad.'">';
                                    echo '<input type="hidden" name="fecha_nacimiento"     id="fecha_nacimiento"    form="imprimir_trat" value="'.$fecha_nacimiento.'">';

                                    echo '<input type="hidden" name="estudio"       id="estudio"       form="imprimir_trat" value="'.$row[0].' '.$row[1].'">';
                                    echo '<input type="hidden" name="idestudio"     id="idestudio"     form="imprimir_trat" value="'.$idestudio.'">';
                                    echo '<input type="hidden" name="cantidad_i131" id="cantidad_i131" form="imprimir_trat" value="'.$cantidad_i131.'">';

                                    echo '<input type="hidden" name="fecha_estudio" id="fecha_estudio"  form="imprimir_trat" value="'.$fecha_estudio.'">';
                                    echo '<input type="hidden" name="hora"          id="hora"           form="imprimir_trat" value="'.$hora.'">';
                                    echo '<input type="hidden" name="institucion"   id="institucion"    form="imprimir_trat" value="'.$institucion.'">';
                                    echo '<input type="hidden" name="indicaciones"  id="indicaciones"   form="imprimir_trat" value="'.$indicaciones.'">';

                                    echo '<input type="hidden" name="med"           id="med"        form="imprimir_trat" value="'.$med.'">';
                                    echo '<input type="hidden" name="nombre_med"    id="nombre_med" form="imprimir_trat" value="'.$nombre_med.'">';
                                    echo '<input type="hidden" name="apepat_med"    id="apepat_med" form="imprimir_trat" value="'.$apepat_med.'">';
                                    echo '<input type="hidden" name="apemat_med"    id="apemat_med" form="imprimir_trat" value="'.$apemat_med.'">';
                                    echo '<input type="hidden" name="especialidad_med" id="especialidad_med" form="imprimir_trat" value="'.$especialidad_med.'">';
                                    echo '<input type="hidden" name="aquien_corresponda" id="aquien_corresponda" form="imprimir_trat" value="'.$aquien_corresponda.'">';
                                    */

                                ?>
                                </form>-->
                                <div class="col-lg-12">
                                    <button type="submit" id="btn_gamm" class="btn btn-danger btn-lg btn-block" form="imprimir">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        AGENDAR PACIENTE 
                                    </button>  
                                </div>


                                <!--<div class="col-lg-6">
                                    <button type="submit" id="btn_trat" class="btn btn-danger btn-lg btn-block" form="imprimir_trat">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        AGENDAR PACIENTE TRATAMIENTO
                                    </button>
                                </div>
                                -->


                                <div class = "row">
                </div>
                <br>

                            </div>
                        </div>
                 </div>
                 </br>
               

        
    </div>  <!--    fin col-lg-12   -->

            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <script >
    function myFunction() {
       //se puede comentar para debug
       document.getElementById("btn_gamm").disabled = true;
       document.getElementById("btn_atras").disabled = true;
       document.getElementById("btn_trat").disabled = true;
    }
    </script>
      
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
