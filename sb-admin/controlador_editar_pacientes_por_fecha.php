<!DOCTYPE html>
<?php $_POST["pagina"]="form"; ?>
<html lang="en">

<head>
    <?php include "header.php"; 

            session_start(); 
            include "../include/sesion.php";
            comprueba_url();

            if(!isset($_POST["idpaciente"])){
                    header('Location: index.php');
            }
    ?>
     <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
       <?php 
            include_once "include/nav_session.php"
       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Modificar datos <small>(fecha, hora, teléfonos y correo)</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Modificar datos de la cita.
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class = "row">
                    <div class="col-lg-3">
                        <form role="form" id="ver_lista" method="post" action="viewmod_ver_editar_pacientes_por_fecha.php" accept-charset="UTF-8">
                              <?php $fecha = $_POST['fecha_estudio'];
                              echo '<input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/>'; ?>
                        </form>
                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                Atrás
                        </button>                            
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <!--        1er columnoa de captura         -->
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title"> Datos del estudio </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">

                                        <form role="form" id="editar_estudio" method="post" action="viewmod_editar_pacientes_por_fecha.php">
                                        <?php 
                                            include_once "include/mysql.php";

                                            $id = $_POST["idpaciente"];
                                           // echo 'este es id'.$id;
                                            $mysql =new mysql();
                                            $link = $mysql->connect(); 

                                            $sql = $mysql->query($link,"SELECT  t1.fecha,
                                                                                t1.hora,
                                                                                t1.nombre,
                                                                                t1.ap_paterno,
                                                                                t1.ap_materno,
                                                                                t1.num_tel,
                                                                                t1.num_tel2,
                                                                                t1.email,
                                                                                t1.doctores_iddoctores,
                                                                                t1.indicaciones_tratamiento,

                                                                                (SELECT concat(t2.tipo,' ',t2.nombre) AS gamm
                                                                                    FROM estudio t2 
                                                                                    WHERE idgammagramas= t1.estudio_idgammagramas) AS estudio
                                                                        FROM pacientes t1
                                                                        WHERE idpacientes= $id");
 
                                            //echo 'filas afectadas: (Encontradas) '.$mysql->affect_row().'';
                                            // echo '"SELECT * FROM estudio WHERE idgammagramas="'.$id.'""';
                                    
                                            $row = $mysql->f_obj($sql);
                                    
                                            $nombre_pac = $row->nombre;
                                            $ap_paterno = $row->ap_paterno;
                                            $ap_materno = $row->ap_materno;
                                            $indicaciones_tratamiento = $row->indicaciones_tratamiento;
                                            $nombre_estudio = $row->estudio;
                                            
                                            echo '<p> <strong>Estudio: </strong> '.$nombre_estudio.'</p><br>';                                    
                                            
                                            echo'
                                                <div class="form-group">
                                                    <label for="nombre">Nombre:</label>
                                                    <input type="text" class="form-control" form="editar_estudio" name="nombre" id="nombre" value="'.$row->nombre.'">
                                                </div> 
                                                <div class="form-group">
                                                    <label for="ap_paterno">Apellido paterno</label>
                                                    <input type="text" class="form-control" form="editar_estudio" name="ap_paterno" id="ap_paterno" value="'.$row->ap_paterno.'">
                                                </div> 
                                                <div class="form-group">
                                                    <label for="ap_materno">Apellido materno</label>
                                                    <input type="text" class="form-control" form="editar_estudio" name="ap_materno" id="ap_materno" value="'.$row->ap_materno.'">
                                                </div> 
                                                <div class="form-group">
                                                    <label for="fecha">Fecha del estudio</label>
                                                    <input type="date" class="form-control" form="editar_estudio" name="fecha" id="fecha"           value="'.$row->fecha.'">
                                                </div> 

                                                <div class="form-group">
                                                    <label for="hora">Hora del estudio</label>
                                                    <input type="time" class="form-control" form="editar_estudio" name="hora" id="hora"             value="'.$row->hora.'">
                                                </div>  
                                                <div class="form-group">
                                                    <label for="tel_local">Teléfono local o celular</label>
                                                    <input type="text" class="form-control" form="editar_estudio" name="tel_local" id="tel_local"   value="'.$row->num_tel.'">
                                                </div>  

                                                <div class="form-group">
                                                    <label for="tel_cel">Teléfono celular</label>
                                                    <input type="text" class="form-control" form="editar_estudio" name="tel_cel" id="tel_cel"     value="'.$row->num_tel2.'">
                                                </div> 

                                                <div class="form-group">
                                                    <label for="email">E-mail</label>
                                                    <input type="email" class="form-control" form="editar_estudio" name="email" id="email"        value="'.$row->email.'">
                                                </div>
                                                <div class="form-group">                                        
                                                    <input type="hidden" form="editar_estudio" name="nombre_ant"        value="'.$row->nombre.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_paterno_ant"    value="'.$row->ap_paterno.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_materno_ant"    value="'.$row->ap_materno.'"/>                                        
                                                    <input type="hidden" form="editar_estudio" name="fecha_ant"         value="'.$row->fecha.'"/>
                                                    <input type="hidden" form="editar_estudio" name="hora_ant"          value="'.$row->hora.'"/>
                                                    <input type="hidden" form="editar_estudio" name="tel_local_ant"     value="'.$row->num_tel.'"/>
                                                    <input type="hidden" form="editar_estudio" name="tel_cel_ant"       value="'.$row->num_tel2.'"/>
                                                    <input type="hidden" form="editar_estudio" name="email_ant"         value="'.$row->email.'"/>

                                                    <input type="hidden" form="editar_estudio" name="idpaciente" id="idpaciente" value="'.$id.'"/>
                                                    <input type="hidden" form="editar_estudio" name="nombre_estudio" id="nombre_estudio" value="'.$nombre_estudio.'"/>
                                            
                                                </div>
                                                ';
                                        ?>
                                        </form>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <form role="form" id="editar_estudio" method="post">
                                        <?php

                                            $id = $_POST["idpaciente"];
                                           // echo 'este es id'.$id;
                                            $mysql =new mysql();
                                            $link = $mysql->connect(); 

                                            $sql = $mysql->query($link,"SELECT  t3.grado,
                                                                                t3.nombre as nombre_med,
                                                                                t3.ap_paterno as ap_paterno_med,
                                                                                t3.ap_materno as ap_materno_med,
                                                                                t3.especialidad,
                                                                                t3.aquiencorresponda
                                                                            FROM doctores t3 
                                                                            WHERE idpaciente = $id");
                                            $row = $mysql->f_obj($sql);

                                            echo'
                                                <div class="form-group">';

                                                    if($row->grado == 'DR.'){
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="'.$row->grado.'" checked>'.$row->grado.' <br> ';
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="DRA."> DRA. <br>';
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="SI"> A quien corresponda';
                                                    }
                                                    if($row->grado == 'DRA.'){
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="DR."> DR. <br>';
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="'.$row->grado.'" checked>'.$row->grado.'<br> ';
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="SI"> A quien corresponda';
                                                    }
                                                    if($row->aquiencorresponda == 'SI'){
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="DR." > DR. <br>';
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="DRA."> DRA. <br>';
                                                        echo'<input type="radio" form="editar_estudio" name="grado" value="'.$row->aquiencorresponda.'" checked> A quien corresponda';
                                                    }
                                                   
                                            echo '
                                                </div>';

                                            echo '
                                                <div class="form-group">
                                                    <label for="nombre">Nombre:</label>
                                                    <input type="text" class="form-control" form="editar_estudio"   name="nombre_med"       value="'.$row->nombre_med.'">
                                                </div> 
                                                <div class="form-group">
                                                    <label for="ap_paterno">Apellido paterno</label>
                                                    <input type="text" class="form-control" form="editar_estudio"   name="ap_paterno_med"   value="'.$row->ap_paterno_med.'">
                                                </div> 
                                                <div class="form-group">
                                                    <label for="ap_materno">Apellido materno</label>
                                                    <input type="text" class="form-control" form="editar_estudio"   name="ap_materno_med"   value="'.$row->ap_materno_med.'">
                                                </div> 
                                                <div class="form-group">
                                                    <label for="especialidad">especialidad</label>
                                                    <input type="text" class="form-control" form="editar_estudio"   name="especialidad"      value="'.$row->especialidad.'">
                                                </div>  

                                                <div class="form-group">                                        
                                                    <input type="hidden" form="editar_estudio" name="nombre_med_ant"        value="'.$row->nombre_med.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_paterno_med_ant"    value="'.$row->ap_paterno_med.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_materno_med_ant"    value="'.$row->ap_materno_med.'"/>                                        
                                                    <input type="hidden" form="editar_estudio" name="grado_ant"             value="'.$row->grado.'"/>
                                                    <input type="hidden" form="editar_estudio" name="especialidad_ant"      value="'.$row->especialidad.'"/>
                                                </div>';    
                                        ?>
                                        </form>

                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><strong> Indicar cantidad de I-131 </strong>  (sólo en caso de tratamiento o rastreo)</h3>
                                            </div> 

                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <?php
                                                        echo'
                                                        <div class="form-group input-group">
                                                            <input type="text" class="form-control" form="editar_estudio"   name="indicaciones_tratamiento"  id="indicaciones_tratamiento"      value="'.$indicaciones_tratamiento.'">
                                                                <span class="input-group-addon">mCi</span>
                                                        </div>';
                                                        ?>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">
                                <!--<input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_estudio" type="submit" value="Aceptar" class="btn btn-primary">-->

                                <button id="submit" name="submit" class="btn btn-danger btn-lg btn-block" form="editar_estudio" type="submit" >
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                    ACEPTAR
                                </button>
                            </div>
                        </div><!-- /. fin panel primary -->
                    </div>
            </div> 
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->



    <!-- Validación de formularios -->
     <script type="text/javascript">
        var nombre = new LiveValidation('nombre', { validMessage: 'OK!', wait: 500});
        nombre.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        nombre.add(Validate.Length, {minimum: 2, maximum: 45 } );
        nombre.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script> 
    <script type="text/javascript">
        var apepat = new LiveValidation('ap_paterno', { validMessage: 'OK!', wait: 500});
        apepat.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        apepat.add(Validate.Length, {minimum: 2, maximum: 45 } );
        apepat.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script> 
    <script type="text/javascript">
        var apemat = new LiveValidation('ap_materno', { validMessage: 'OK!', wait: 500});
        apemat.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        apemat.add(Validate.Length, {minimum: 2, maximum: 45 } );
        apemat.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script> 

    <script type="text/javascript">
        var f3 = new LiveValidation('tel_local', { validMessage: 'OK!', wait: 500});
        f3.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        f3.add( Validate.Numericality );
        f3.add( Validate.Length, { minimum: 7  } );
    </script> 

    <script type="text/javascript">
        var f4 = new LiveValidation('tel_cel', { validMessage: 'OK!', wait: 500});
        f4.add( Validate.Numericality );
        f4.add( Validate.Length, { minimum: 7 }  );
    </script> 
    <script type="text/javascript">
        var f10 = new LiveValidation('indicaciones_tratamiento', { validMessage: 'OK!', wait: 500});
        f10.add( Validate.Numericality );
        f10.add( Validate.Numericality, { minimum: 1 } );
        f10.add( Validate.Length, { minimum: 0 }  );
    </script> 
    <script type="text/javascript">
        var f7 = new LiveValidation('fecha',{ validMessage: 'OK!', wait: 500});
        f7.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
    </script> 
    <script type="text/javascript">
        var f8 = new LiveValidation('hora',{ validMessage: 'OK!', wait: 500});
        f8.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
    </script> 
    <script type="text/javascript">
        var f9 = new LiveValidation('grado',{ validMessage: 'OK!', wait: 500});
        f9.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
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
