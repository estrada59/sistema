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
                            Modificar el TIPO DE ESTUDIO<small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> 
                                Aquí modificará el tipo de estudio que tiene actualmente por algún otro y actualizará los registros.

                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class = "row">
                    <div class="col-lg-3">
                        <form role="form" id="ver_lista" method="post" action="viewmod_ver_editar_estudio_de_paciente.php" accept-charset="UTF-8">
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
                                <h3 class="panel-title"> Datos del estudio. </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6 col-lg-6">

                                        <form role="form" id="editar_estudio" method="post" action="viewmod_editar_estudio_de_paciente.php">
                                        <?php 
                                            include_once "include/mysql.php";

                                            $id = $_POST["idpaciente"];
                                            // echo 'este es id'.$id;
                                            $mysql =new mysql();
                                            $link = $mysql->connect(); 

                                            $sql = $mysql->query($link,"SELECT  (t1.estudio_idgammagramas) as idestudio,
                                                                                t1.fecha,
                                                                                t1.hora,
                                                                                t1.nombre,
                                                                                t1.ap_paterno,
                                                                                t1.ap_materno,
                                                                                t1.num_tel,
                                                                                t1.num_tel2,
                                                                                t1.institucion,

                                                                                (SELECT concat(t2.tipo,' ',t2.nombre) AS gamm
                                                                                    FROM estudio t2 
                                                                                    WHERE idgammagramas= t1.estudio_idgammagramas) AS estudio
                                                                        FROM pacientes t1
                                                                        WHERE idpacientes= $id");
                                              
                                            $row = $mysql->f_obj($sql);
                                    
                                            $idestudio_actual   = $row->idestudio;
                                            $institucion        = $row->institucion;
                                            $nombre_pac         = $row->nombre;
                                            $ap_paterno         = $row->ap_paterno;
                                            $ap_materno         = $row->ap_materno;
                                            $nombre_estudio     = $row->estudio;

                                            
                                            echo '<p> <strong>Paciente: </strong> '.$row->nombre.' '.$row->ap_paterno.' '.$row->ap_materno.'</p><br>';  
                                            echo '<p> <strong>Estudio: </strong> '.$nombre_estudio.'</p><br>';

                                            date_default_timezone_set('America/Mexico_City');
                                            $row->fecha = date('d-m-Y ',strtotime($row->fecha));
                                            $row->hora = date('g:i a ',strtotime($row->hora));

                                            echo '<p> <strong>Fecha de estudio: </strong> '.$row->fecha.'<strong> Hora: </strong>'.$row->hora.'</p><br>';  
                                            echo '<p> <strong>Teléfono </strong> '.$row->num_tel.'</p><br>';  
                                            echo '<p> <strong>Teléfono </strong> '.$row->num_tel2.'</p><br>';  
                                            
                                            $institucion = strtolower($institucion);
                                            $institucion = str_replace(" ", "_", $institucion);

                                            echo'
                                
                                                <div class="form-group">                                        
                                                    <input type="hidden" form="editar_estudio" name="nombre"        value="'.$row->nombre.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_paterno"    value="'.$row->ap_paterno.'"/>
                                                    <input type="hidden" form="editar_estudio" name="ap_materno"    value="'.$row->ap_materno.'"/>                                        
                                                    <input type="hidden" form="editar_estudio" name="fecha"         value="'.$row->fecha.'"/>
                                                    <input type="hidden" form="editar_estudio" name="hora"          value="'.$row->hora.'"/>
                                                    <input type="hidden" form="editar_estudio" name="tel_local"     value="'.$row->num_tel.'"/>
                                                    <input type="hidden" form="editar_estudio" name="tel_cel"       value="'.$row->num_tel2.'"/>

                                                    <input type="hidden" form="editar_estudio" name="idpaciente" id="idpaciente" value="'.$id.'"/>
                                                    <input type="hidden" form="editar_estudio" name="idestudio_actual"  id="idestudio_actual"   value="'.$idestudio_actual.'"/>
                                                    <input type="hidden" form="editar_estudio" name="nombre_estudio" id="nombre_estudio" value="'.$nombre_estudio.'"/>
                                                    <input type="hidden" form="editar_estudio" name="institucion_estudio"  value="'.$institucion.'"/>

                                            
                                                </div>
                                                ';
                                        ?>
                                        </form>
                                    </div>
                                    <div class="col-md-6 col-lg-6">

                                        <h2>Estudio actual</h2>
                                        <?php 
                                            echo '<p>'.$nombre_estudio.'</p><br>';  
                                            //echo '<p>id estudio'.$idestudio_actual.'</p><br>'; 
                                            
                                        ?>

                                        <h2>Cambiar por:</h2>
                                        <form role="form" id="editar_estudio" method="post">
                                            <div class="form-group">
                                                <select class="form-control combobox" form="editar_estudio" name="idestudio_nuevo" id="idestudio_nuevo">
                                                    <option></option>
                                                <?php 
                                                    include_once "include/mysql.php";

                                                    $mysql =new mysql();
                                                    $link = $mysql->connect(); 

                                                    $institucion = strtolower($institucion);
                                                    $institucion = str_replace(" ", "_", $institucion);


                                                    $sql2 = $mysql->query($link,"SELECT idgammagramas,
                                                                                        concat(tipo,' ',nombre) as nombre
                                                                                    FROM estudio
                                                                                    WHERE $institucion !='0.00';");  
                                       
                                                    while ($row = $mysql->f_array($sql2)) {
                                                        echo '<option value ="'.$row["idgammagramas"].'">'.$row["nombre"].'</option>';
                                                    }

                                                    $mysql->close();
                                                ?>

                                                </select>
                                            </div>
                                            <?php
                                                $mysql =new mysql();
                                                $link = $mysql->connect(); 

                                                $sql2 = $mysql->query($link,"SELECT tipo as institucion
                                                                                    FROM instituciones
                                                                                    WHERE nombre = '$institucion';");  
                                                $row = $mysql->f_obj($sql2);
                                                $institucion =$row->institucion;

                                                $mysql->close();
                                                echo'
                                
                                                <div class="form-group">                                        
                                                    <input type="hidden" form="editar_estudio" name="institucion"  value="'.$institucion.'"/>

                                                </div>
                                                ';
                                            ?>
                                        </form>
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
