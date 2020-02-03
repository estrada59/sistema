<!DOCTYPE html>
<?php $_POST["pagina"]="form"; 
      $pagina =  $_POST['pagina_destino']?>
<html lang="en">

<head>
    <?php include "header.php"; 
            date_default_timezone_set('America/Mexico_City');
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
                <?php
                    // echo'<pre>';
                    // print_r($_POST);
                    // echo '</pre>';
                ?>
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Estatus de sms <small></small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Estatus SMS.
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <div class = "row">
                    <div class="col-lg-3">
                        <?php
                        ?>

                    </div>
                </div>
                <div class = "row">
                    <div class="col-lg-3">
                        <?php
                            $var = $_POST["pagina_destino"];
                            
                            $fecha = $_POST['fecha_estudio'];
                            date_default_timezone_set('America/Mexico_City'); 
                            $fecha = date('Y-m-d', strtotime($fecha));

                            switch ($var) {
                                case 'ver_pacientes_mañana': 
                                    echo'
                                        <form role="form" id="ver_lista1" method="post" action="viewmod_ver_pacientes_mañana.php" accept-charset="UTF-8">
                                            <input type="hidden" form="ver_lista1" name="fecha_estudios" value="'.$fecha.'"/>
                                        </form>
                                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista1">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            Atrás
                                        </button> ';

                                break;
                                
                                 
                                default:
                                    $fecha = $_POST['fecha_estudio'];
                                    echo'
                                     <form role="form" id="ver_lista" method="post" action="viewmod_ver_pacientes_mañana.php" accept-charset="UTF-8">
                              
                                        <input type="hidden" form="ver_lista" name="fecha_estudios" value="'.$fecha.'"/> 
                              
                                    </form>
                                    <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                        Atrás
                                    </button>';
                                    break;
                             }
                        ?>
                                                    
                    </div>
                </div>
                <br>

                <div class="row">
                       
                    <!--        1er columnoa de captura         -->
            
                    <div class="col-md-4 col-lg-4">  
                        <div class="panel panel-primary">        

                            <div class="panel-heading">
                                <h3 class="panel-title"> Datos del paciente </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <!-- datos de la primera columna-->
                                <form role="form" id="editar_estudio" method="post" action="viewmod_editar_pacientes_por_fecha.php">
                                <?php 
                                    include_once "include/mysql.php";

                                    $id = $_POST["idpaciente"];
                                // echo 'este es id'.$id;
                                    $mysql =new mysql();
                                    $link = $mysql->connect(); 

                                    $sql = $mysql->query($link,"SELECT    
                                                                        t1.nombre,
                                                                        t1.ap_paterno,
                                                                        t1.ap_materno,
                                                                        t1.num_tel,
                                                                        t1.num_tel2,
                                                                        t1.email,
                                                                        t1.doctores_iddoctores,
                                                                        t1.fecha_nacimiento,
                                                                        t1.edad,

                                                                        (select descripcion
                                                                            from tblc_edad 
                                                                            where id_edad = (select id_edad 
                                                                                                from tbl_edad_paciente 
                                                                                                where idpacientes= $id) ) as tipo_edad,

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
                                    $num_tel = $row->num_tel;

                                    if($row->fecha_nacimiento == '0000-00-00 00:00:00'){
                                        $fecha_nacimiento='';    
                                    }else{
                                        $fecha_nacimiento= date("Y-m-d",strtotime($row->fecha_nacimiento));
                                    }
                                    
                                    echo'
                                        <div class="form-group">
                                            <label for="nombre">Nombre:</label>
                                            <input type="text" class="form-control" form="editar_estudio" name="nombre" id="nombre" value="'.$row->nombre.' '.$row->ap_paterno.' '.$row->ap_materno.'" disabled>
                                        </div> 
                                       
                                        
                                        <div class="form-group">
                                            <label for="tel_local">Teléfono local o celular</label>
                                            <input type="text" class="form-control" form="editar_estudio" name="tel_local" id="tel_local"   value="'.$row->num_tel.'" disabled>
                                        </div>  

                                        <div class="form-group">
                                            <label for="tel_cel">Teléfono celular</label>
                                            <input type="text" class="form-control" form="editar_estudio" name="tel_cel" id="tel_cel"     value="'.$row->num_tel2.'" disabled>
                                        </div> ';
                                ?>

                              

                                </form>
                                
                                <!-- fin datos de la primera columna-->
                            </div><!--fin panel body-->

                            <div class="panel-footer">
                            </div>  <!--    fin panel footer    -->

                        </div>
                    </div>
            
                    <!--        2da columnoa de captura         -->
            
                    <div class="col-md-4 col-lg-4">  
                        <div class="panel panel-primary"> 

                            <div class="panel-heading">
                                <h3 class="panel-title"> Datos del estudio </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <!--datos de la columna 3-->
                            
                                <form role="form" id="editar_estudio" method="post">
                                <?php

                                    $id = $_POST["idpaciente"];
                                    // echo 'este es id'.$id;
                                    $mysql =new mysql();
                                    $link = $mysql->connect(); 

                                    $sql = $mysql->query($link,"SELECT  
                                                                        (SELECT concat(t2.tipo,' ',t2.nombre) AS gamm
                                                                            FROM estudio t2 
                                                                            WHERE idgammagramas= t1.estudio_idgammagramas) AS estudio,
                                                                        t1.fecha,
                                                                        t1.hora,
                                                                        t1.indicaciones,
                                                                        t1.indicaciones_tratamiento
                                                                FROM pacientes t1
                                                                WHERE idpacientes= $id");
                                    
                                    $row = $mysql->f_obj($sql);
                            
                                    $nombre_estudio = $row->estudio;

                                    echo '<p> <strong>Estudio: </strong> '.$nombre_estudio.'</p><br>';                                    
                        
                                    echo '
                                        <div class="form-group">
                                            <label for="fecha">Fecha del estudio</label>
                                            <input type="date" class="form-control" form="editar_estudio" name="fecha_estudio" value="'.$row->fecha.'" required disabled>
                                        </div> 

                                        <div class="form-group">
                                            <label for="hora">Hora del estudio</label>
                                            <input type="time" class="form-control" form="editar_estudio" name="hora_estudio"  value="'.$row->hora.'" required disabled>
                                        </div>';
                                        
                                ?>       
                             
                                </form>
                                <!--fin datos de la columna 3-->
                            </div>

                            <div class="panel-footer">
                            </div>  <!--    fin panel footer    -->
                        </div>
                    </div>

                    <?php 
                    
                    $cont = strlen($num_tel);

                    if ($cont == 10){
                        
                        $ch = curl_init();

                        $nombre_pac = str_replace(' ','%20',$nombre_pac);
                        $fecha = date('d-m-Y',strtotime($row->fecha));
                        $hora = date('h:i A',strtotime($row->hora)); //entrega el formato hh:ii am/pm
                        $hora = str_replace(' ','%20',$hora); // devuelve hh:ii%20am/pm


                        $mensaje='ESTIMADO(A)%20'.$nombre_pac.'%20LE%20RECORDAMOS%20QUE%20TIENE%20CITA%20EN%20MEDICINA%20NUCLEAR%20EL%20DIA%20'.$fecha.'%20A%20LAS%20'.$hora.'%20SI%20TIENE%20ALGUNA%20DUDA%20MARQUE%20AL%209616029211%20O%209616029479';
                        
                        //NUMERO TELEFÓNICO PARA TEST
                        // $num_tel= '9611724612';  

                        $telefono_sms = '52'.$num_tel;
                        $usuario = 'numedics';
                        $pass ='nume_sms2020';

                        $m = str_replace('%20',' ',$mensaje);
                        
                        // TEST API NO ENVÍA SMS
                        // curl_setopt($ch, CURLOPT_URL,"http://sms.kirasip.com/ws_smsv3/ws_smsv3_kirasip.php?"); 
                        
                        $url= 'http://sms.kirasip.com/ws_smsv3/ws_smsv3_kirasip.php?usuario='.$usuario.'&password='.$pass.'&numero='.$telefono_sms.'&mensaje='.$mensaje.'.';
                       
                        curl_setopt($ch, CURLOPT_URL,$url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        $res = curl_exec($ch); //respuesta obtenida del servidor
                        // echo $res;
                        curl_close($ch);


                        $id = $_POST["idpaciente"];
                        //  echo 'este es id'.$id;
                        $mysql =new mysql();
                        $link = $mysql->connect(); 

                        $sql = $mysql->query($link,"INSERT INTO log_sms (id_log_sms,
                                                                         idpaciente,
                                                                         respuesta_sms)
                                                    VALUES('',$id,'$res')
                                                    ");
                        
                                                    
                    }
                    else{
                        $res = 'No se envió sms debido a que el número de teléfono tiene menos de 10 dígitos';
                    }
                    
                    ?>

                    <!--        3ra columnoa de captura         -->
            
                    <div class="col-md-4 col-lg-4">  
                        <div class="panel panel-primary"> 

                            <div class="panel-heading">
                                <h3 class="panel-title"> SMS </h3>
                            </div>  <!--    fin panel heading   -->

                            <div class="panel-body">
                                <!--datos de la columna 3-->
                                <form role="form" id="editar_estudio" method="post">
                                <?php
                                    echo '
                                        <div class="form-group">
                                            <label for="nombre">MENSAJE ENVIADO</label>
                                            <textarea type="text" class="form-control" rows="5">'.$m.'</textarea>
                                        </div> 
                                        <div class="form-group">
                                            <label for="ap_paterno">RESPUESTA DEL SERVIDOR</label>
                                            <textarea type="text" class="form-control" rows="5">'.$res.'</textarea>
                                        </div>
                                       ';    
                                ?>
                                </form>    
                                
                                <!--fin datos de la columna 3-->
                            </div>

                            <div class="panel-footer">
                            </div>  <!--    fin panel footer    -->
                        </div>
                    </div>
       
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
        var f11 = new LiveValidation('edad', { validMessage: 'OK!', wait: 500});
        nombre.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        
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
