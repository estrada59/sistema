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
            if($_SESSION['nivel_acceso'] == 3){
                include "nav.php";  }
            if($_SESSION['nivel_acceso'] == 1){
                include "nav_priv.php"; }
       ?>

        <div id="page-wrapper">
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Modificar datos de la institución <small>de Institución a particular</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Aquí se cambian de institución al paciente y pasa a ser un cliente particular.
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class = "row">
                    <div class="col-lg-3">
                        <form role="form" id="ver_lista" method="post" action="viewmod_ver_editar_institucion_a_particular.php" accept-charset="UTF-8">
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
                                    <div class="col-md-5 col-lg-5">

                                        <form role="form" id="editar_estudio" method="post" action="viewmod_editar_institucion_a_particular.php">
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
                                                                                t1.institucion,

                                                                                (SELECT t3.idgammagramas AS gamm
                                                                                    FROM estudio t3 
                                                                                    WHERE idgammagramas= t1.estudio_idgammagramas) AS idestudio,

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

                                            $nombre_estudio = $row->estudio;
                                            $idestudio      = $row->idestudio;
                                            $institucion    = $row->institucion;
                                            
                                            date_default_timezone_set('America/Mexico_City');
                                            $row->fecha = date('d-m-Y ',strtotime($row->fecha));
                                            $row->hora = date('g:i a ',strtotime($row->hora));

                                            echo '<p> <strong>Paciente: </strong> '.$row->nombre.' '.$row->ap_paterno.' '.$row->ap_materno.'</p><br>';                                    
                                            echo '<p> <strong>Estudio: </strong> '.$nombre_estudio.'</p>';

                                            
                                            echo '<p> <strong>Fecha del estudio: </strong> '.$row->fecha.'</p>';
                                            echo '<p> <strong>Hora del estudio: </strong> '.$row->hora.'</p>';
                                            echo '<p> <strong>Teléfono: </strong> '.$row->num_tel.'</p>';
                                            echo '<p> <strong>Teléfono: </strong> '.$row->num_tel2.'</p>';
                                            
                                            
                                            echo'
                                                <div class="form-group">                                        
                                                    <input type="hidden" form="editar_estudio" name="nombre_ant"        value="'.$row->nombre.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_paterno_ant"    value="'.$row->ap_paterno.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_materno_ant"    value="'.$row->ap_materno.'"/>                                        
                                                    <input type="hidden" form="editar_estudio" name="fecha_ant"         value="'.$row->fecha.'"/>
                                                    <input type="hidden" form="editar_estudio" name="hora_ant"          value="'.$row->hora.'"/>
                                                    <input type="hidden" form="editar_estudio" name="tel_local_ant"     value="'.$row->num_tel.'"/>
                                                    <input type="hidden" form="editar_estudio" name="tel_cel_ant"       value="'.$row->num_tel2.'"/>

                                                    <input type="hidden" form="editar_estudio" name="idpaciente" id="idpaciente" value="'.$id.'"/>
                                                    <input type="hidden" form="editar_estudio" name="idestudio" id="idestudio" value="'.$idestudio.'"/>
                                                    <input type="hidden" form="editar_estudio" name="nombre_estudio" id="nombre_estudio" value="'.$nombre_estudio.'"/>
                                                    <input type="hidden" form="editar_estudio" name="institucion" id="institucion" value="'.$institucion.'"/>
                                            
                                                </div>
                                                ';
                                        ?>
                                        </form>
                                    </div>

                                    <div class="col-md-6 col-lg-6 bg-danger">
                                    
                                        <p ></br><h3> <strong> Institución actual: </strong> <?php echo ' '.$institucion.'';?></h3><br></p>
                                        
                                        <form role="form" id="editar_estudio" method="post">
                                            
                                                <?php
                                                    echo ' 
                                                    <p></br><h1><strong>ADVERTENCIA:</strong> </h1> <h2>Se va a cambiar un paciente de ésta institución:'.$institucion.'
                                                    y pasará a ser un cliente PARTICULAR. Si esta de acuerdo de click en Aceptar
                                                    de lo contrario de click en atrás.</h2></br></p>';
                                           
                                                ?>
                                        </form>
                                    </div>
                                </div>

                            </div><!-- /. fin panel body -->

                            <div class="panel-footer">
                                <!--<input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_estudio" type="submit" value="Aceptar" class="btn btn-primary">-->
                                <?php

                                $institucion = strtolower($institucion);
                                $institucion = str_replace(" ", "_", $institucion);
                                
                                $link = $mysql->connect(); 
                                $sql2 = $mysql->query($link,"SELECT  nombre,
                                                                    tipo 
                                                                FROM instituciones
                                                                WHERE nombre= '$institucion';");
                                $row2 = $mysql->f_obj($sql2);

                                if($row2->tipo == 'PARTICULAR') {
                                    echo'<button id="submit" name="submit" class="btn btn-danger btn-lg btn-block" form="editar_estudio" type="submit" disabled="disabled" >
                                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                            ACEPTAR
                                        </button>';
                                }  
                                else{
                                    echo'<button id="submit" name="submit" class="btn btn-danger btn-lg btn-block" form="editar_estudio" type="submit" >
                                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                            ACEPTAR
                                        </button>';
                                }
                                
                                ?>
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
