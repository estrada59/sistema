<!DOCTYPE html>


<?php $_POST["pagina"]="citar_pacientes"; 

    session_start(); 
    include "../include/sesion.php";
    comprueba_url();

    if($_SESSION['nivel_acceso'] >= 5){
    header("Location: index.php");
    exit();
    }
    if($_SESSION['nivel_acceso'] <= 2){
    header("Location: index.php");
    exit();
    }
?>
<html lang="en">

<head>
    <?php include "header.php"; ?>
    <!-- css validation form -->
    <link rel="stylesheet" href="css/style_validation_form.css">    
    <!-- jquery validation form -->
    <script src="js/livevalidation_standalone.js"></script>
</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
       <?php 
            include_once 'include/nav_session.php';
       ?>
        <div id="page-wrapper">
            <div class="container-fluid">
                
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h1 class="page-header"> <small> Programar Cita pacientes 
                        <?php 
                            include_once 'include/funciones_consultas.php';
                            $institucion = $_POST["institucion"];
                            echo pasarMayusculas($institucion);

                        ?>

                              </small></h1>
                     <!--  <ol class="breadcrumb">
                            <li class="active"><i class="icon-file-alt"></i> Gammagrama</li>
                        </ol>-->
                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-3">
                        <form role="form" id="ver_lista" method="post" action="controlador_citar_pacientes.php" accept-charset="UTF-8">
                        </form>
                        <button type="submit" class="btn btn-success btn-lg btn-block" aria-label="Left Align" form="ver_lista">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            Atrás
                        </button>  
                    </div>
                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-3">
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
                                    <!--<form role="form" id="hoja_cita" method="post" action="viewmod_citar_pacientes.php" onsubmit="clear()">-->
                                    <form role="form" id="hoja_cita" method="post" action="viewmod_citar_pacientes.php" onsubmit="clear()">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <?php 
                                                if(isset($_POST["nombre"])){
                                                    echo '<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s) del paciente" value ="'.$_POST['nombre'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s) del paciente" >';        
                                                }
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="apepat">Apellido paterno</label>
                                            <?php 
                                                if(isset($_POST["apepat"])){
                                                    echo '<input type="text" class="form-control" name="apepat" id="apepat" placeholder="Apellido paterno" value ="'.$_POST['apepat'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" name="apepat" id="apepat" placeholder="Apellido paterno">';        
                                                }
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="apemat">Apellido materno</label>
                                            <?php 
                                                if(isset($_POST["apemat"])){
                                                    echo '<input type="text" class="form-control" name="apemat" id="apemat" placeholder="Apellido materno" value ="'.$_POST['apemat'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" name="apemat" id="apemat" placeholder="Apellido materno">';        
                                                }
                                            ?>  
                                        </div>

                                        <div class="form-group">
                                            <label for="tel_local">Teléfono local o celular</label>
                                            <?php 
                                                if(isset($_POST["tel_local"])){
                                                    echo '<input type="text" class="form-control" name="tel_local" id="tel_local" placeholder="número de teléfono" value ="'.$_POST['tel_local'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" name="tel_local" id="tel_local" placeholder="número de teléfono">';        
                                                }
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="tel_cel">Teléfono local o celular</label>
                                            <?php 
                                                if(isset($_POST["tel_cel"])){
                                                    echo '<input type="text" class="form-control" name="tel_cel" id="tel_cel" placeholder="número de teléfono" value ="'.$_POST['tel_cel'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" name="tel_cel" id="tel_cel" placeholder="número de teléfono">';        
                                                }
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">e-mail</label>
                                            <?php 
                                                if(isset($_POST["email"])){
                                                    echo '<input type="text" class="form-control" name="email" id="email" placeholder="correo electrónico" maxlength="70" value ="'.$_POST['email'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" name="email" id="email" placeholder="correo electrónico" maxlength="70">';        
                                                }
                                            ?>
                                        </div>
 
                                        <div class="form-group">
                                            <label for="edad">Edad:</label>
                                            <?php 
                                                if(isset($_POST["edad"])){
                                                    echo '<div class="row">  ';
                                                        echo '<div class="col-md-6 col-lg-6">  ';
                                                            echo '<input type="number" max="100" min="1" class="form-control" name="edad" id="edad" placeholder="Edad del paciente"  value ="'.$_POST['edad'].'">';
                                                        echo ' </div>';

                                                        if (isset($_POST['tipo_edad'])){
                                                            echo '<div class="col-md-6 col-lg-6">  ';

                                                                echo'<select class="form-control" form="hoja_cita" name="tipo_edad" id="tipo_edad">';
                                                            
                                                                $var= $_POST['tipo_edad'];
                                                            

                                                                echo '<option value ="'.$var.'">'.$var.'</option>';

                                                                include_once "include/mysql.php";

                                                                $mysql = new mysql();
                                                                $link = $mysql->connect(); 
                        
                                                                $sql = $mysql->query($link,"SELECT  descripcion
                                                                                                FROM tblc_edad
                                                                                                WHERE activo = 1;");  
                                                                
                                                                while($row = $mysql->f_obj($sql)){

                                                                    echo '<option value ="'.$row->descripcion.'">'.$row->descripcion.'</option>';
                                                                    
                                                                }

                                                                $mysql->close();

                                                                echo '</select>';
                                                                
                                                            echo ' </div>';
                                                        }

                                                    echo ' </div>';
                                                    
                                                }else{
                                                    echo '<div class="row">  ';
                                                        echo '<div class="col-md-6 col-lg-6">  ';
                                                            echo '  <input type="number" max="100" min="1" class="form-control" name="edad" id="edad" placeholder="Edad del paciente" >';        
                                                        echo ' </div>'; 

                                                        include_once "include/mysql.php";

                                                        $mysql = new mysql();
                                                        $link = $mysql->connect(); 
                
                                                        $sql = $mysql->query($link,"SELECT descripcion
                                                                                        FROM tblc_edad
                                                                                        WHERE activo = 1;");  
                                                        
                                                        echo '<div class="col-md-6 col-lg-6">  ';
                                                        echo'<select class="form-control" form="hoja_cita" name="tipo_edad" id="tipo_edad">';

                                                        while($row = $mysql->f_obj($sql)){

                                                            echo '<option value ="'.$row->descripcion.'">'.$row->descripcion.'</option>';
                                                            
                                                        }

                                                        $mysql->close();

                                                        echo '</select>';
                                                        
                                                        echo ' </div>';
                                                    echo ' </div>';
                                                }
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                                            <?php 
                                                if(isset($_POST["fecha_nacimiento"])){
                                                    echo '<input type="date" class="form-control"  name="fecha_nacimiento" id="fecha_nacimiento" placeholder="Fecha de nacimiento" required value ="'.$_POST['fecha_nacimiento'].'">';
                                                }else{
                                                    echo '<input type="date" class="form-control"  name="fecha_nacimiento" id="fecha_nacimiento" placeholder="Fecha de nacimiento" required>';       
                                                }
                                            ?>
                                        </div>
                                    </form>
                                </div>  <!--    fin panel body  -->                
                                <div class="panel-footer">
                                </div>  <!--    fin panel footer    -->
                            </div>  <!--    fin panel-primary   -->
                        </div>  <!--    fin col-lg-6    -->
                        <!--        Fin 1er columna de captura          -->

                      

                          <!--        1er columnoa de captura         -->
                          <?php 
                            
                            
                            
                          ?>
                        <div class="col-md-4 col-lg-4">  
                            <div class="panel panel-primary">        
                                <div class="panel-heading">
                                    <h3 class="panel-title"> Datos del estudio </h3>
                                </div>  <!--    fin panel heading   -->
                                <div class="panel-body">
                                    <form role="form" id="hoja_cita" >
                                        <div class="form-group">
                                            <label for="nombre_estudio">Nombre del estudio</label>
                                                <select class="form-control" form="hoja_cita" name="estudio" id="estudio">
                                                    <?php 
                                                        if(isset($_POST["idgammagramas"] ) &&  isset($_POST['nombre_estudio'])){
                                                            echo '<option value ="'.$_POST["idgammagramas"].'">'.$_POST['nombre_estudio'].'</option>';
                                                        }else{
                                                            echo '<option></option>';        
                                                        }
                                                    ?>

                                                    
                                     <?php 
                                        include_once "include/mysql.php";
                                        include_once "include/funciones_consultas.php";
                                        
                                        $institucion = $_POST["institucion"];
                                        $institucion = str_replace(" ", "_", $institucion);

                                        $mysql =new mysql();
                                        $link = $mysql->connect(); 

                                        $sql = $mysql->query($link,"SELECT  idgammagramas,
                                                                            tipo,
                                                                            nombre 
                                                                        FROM estudio
                                                                        WHERE $institucion != 0.00;");  
                                        /*$sql = $mysql->query($link,"SELECT  idgammagramas,
                                                                            tipo,
                                                                            nombre
                                                                    FROM estudio 
                                                                    WHERE (tipo != 'TRATAMIENTO' 
                                                                            and tipo != '')
                                                                            and nombre != 'RASTREO TIROIDEO I-131 (1-5 MCI)';");  */
   
                                            while ($row = $mysql->f_array($sql)) {
                                                echo '<option value ="'.$row["idgammagramas"].'">'.$row["tipo"].' '.$row["nombre"].'</option>';
                                            }
                                            
                                            $mysql->close();
                                    ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="cantidad">Cantidad de I-131 (en caso de tratamiento o rastreo)</label>
                                            <?php
                                            if(isset($_POST["cantidad_i131"])){
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="cantidad_i131" id="cantidad_i131" placeholder="Dósis tratamiento" maxlength="190" value ="'.$_POST['cantidad_i131'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="cantidad_i131" id="cantidad_i131" placeholder="Dósis tratamiento" maxlength="190">';        
                                                }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Institución</label>
                                                <select class="form-control combobox" form="hoja_cita" name="institucion" id="institucion">
                                                
                                                <!--<option>PARTICULAR</option>-->
                                                <?php
                                                    
                                                    $institucion = pasarMayusculas($_POST["institucion"]);

                                                    $institucion = str_replace("_", " ", $institucion);

                                                    echo '<option>'.$institucion.'</option>';
                                                ?> 
                                                <!--<option>DIF</option>-->
                                                <!--<option>ISSSTE</option>-->
                                                <!--<option>ISSTECH</option>-->
                                                <!--<option>BENEFICENCIA</option>-->
                                                <!--<option>VITAMEDICA</option>-->
                                                <!--<option>BANRURAL</option>-->
                                                <!--<option>FIDEICOMISO</option>-->
                                                <!--<option>OTRO</option>-->
                                                </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="fecha">Fecha de cita:</label>
                                            <?php
                                                if(isset($_POST["fecha_estudio"])){
                                                    echo '<input type="date" class="form-control" form="hoja_cita" name="fecha_estudio" id="fecha_estudio" placeholder="Fecha estudio" required value ="'.$_POST['fecha_estudio'].'">';
                                                }else{
                                                    echo '<input type="date" class="form-control" form="hoja_cita" name="fecha_estudio" id="fecha_estudio" placeholder="Fecha estudio" required>';        
                                                }
                                            ?>
                                            
                                        </div>
                        
                                        <div class="form-group">
                                            <label for="hora">Hora</label>
                                            <?php
                                                if(isset($_POST["hora"])){
                                                    echo '<input type="time" class="form-control" form="hoja_cita" name="hora" id="hora" placeholder="Hora" required value ="'.$_POST['hora'].'">';
                                                }else{
                                                    echo '<input type="time" class="form-control" form="hoja_cita" name="hora" id="hora" placeholder="Hora" required>';        
                                                }
                                            ?>
                                            
                                        </div>
                        
                                        <div class="form-group">
                                            <label for="indicaciones">Indicaciones</label>
                                            <?php
                                                if(isset($_POST["indicaciones"])){
                                                    echo '<textarea type="text" class="form-control" rows="5" form="hoja_cita" name="indicaciones" id="indicaciones" placeholder="Indicaciones">'.$_POST['indicaciones'].'</textarea>';
                                                }else{
                                                    echo '<textarea type="text" class="form-control" rows="5" form="hoja_cita" name="indicaciones" id="indicaciones" placeholder="Indicaciones"></textarea>';        
                                                }
                                            ?>
                                            
                                        </div>
                        
                                       <!-- <div class="col-lg-3">  
                                            <input type="checkbox" form="hoja_cita" name="complemento" id="complemento">Complemento
                                        </div>-->
                                    </form>
                                </div>  <!--    fin panel body  -->                
                                <div class="panel-footer">
                                </div>  <!--    fin panel footer    -->
                            </div>  <!--    fin panel-primary   -->
                        </div>  <!--    fin col-lg-6    -->
                        <!--        Fin 1er columna de captura          -->

                        
                           <!--        1er columnoa de captura         -->
                        <div class="col-md-4 col-lg-4">  
                            <div class="panel panel-primary">        
                                <div class="panel-heading">
                                    <h3 class="panel-title"> Datos del médico </h3>
                                </div>  <!--    fin panel heading   -->
                                <div class="panel-body">
                                    <form role="form" id="hoja_cita" >        
                                        <div class="form-group">
                                            <label for="medico_tratante">Médico Tratante</label>
                                        </div>     
                                     <!--   <div class="form-group">
                                            <input type="checkbox" form="hoja_cita" name="dr" id="dr">Dr.
                                            <input type="checkbox" form="hoja_cita" name="dra" id="dra">Dra.
                                        </div>-->
                                        <div class="form-group">
                                            <?php
                                                if (isset($_POST['dr'])){
                                                    $var = $_POST['dr'];    
                                                    if($var == 'dr'){
                                                        echo'<input type="radio"  form="hoja_cita" name="dr"  value="dr" checked> Dr. <br>
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="dra"> Dra. <br>
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="aquien_corresponda"> A quien corresponda <br>
                                                        ';
                                                    }
                                                    if($var == 'dra'){
                                                        echo '
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="dr"> Dr. <br>
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="dra" checked> Dra. <br>
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="aquien_corresponda"> A quien corresponda <br>
                                                        ';
                                                    }
                                                    if(($var == 'aquien_corresponda')){
                                                        echo '
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="dr"> Dr. <br>
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="dra"> Dra. <br>
                                                            <input type="radio"  form="hoja_cita" name="dr"  value="aquien_corresponda" checked> A quien corresponda <br>
                                                        ';
                                                    }
                                                }
                                                else{
                                                    echo'<input type="radio"  form="hoja_cita" name="dr"  value="dr" checked> Dr. <br>
                                                        <input type="radio"  form="hoja_cita" name="dr"  value="dra"> Dra. <br>
                                                        <input type="radio"  form="hoja_cita" name="dr"  value="aquien_corresponda"> A quien corresponda <br>
                                                    ';
                                                }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <?php
                                                if(isset($_POST["nombre_med"])){
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name ="nombre_med" id="nombre_med" placeholder="Nombre(s) del médico" value="'.$_POST['nombre_med'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name ="nombre_med" id="nombre_med" placeholder="Nombre(s) del médico">';        
                                                }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="apepat">Apellido paterno</label>
                                            <?php
                                                if(isset($_POST["apepat_med"])){
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="apepat_med" id="apepat_med" placeholder="Apellido paterno" value="'.$_POST['apepat_med'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="apepat_med" id="apepat_med" placeholder="Apellido paterno">';        
                                                }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="apemat">Apellido materno</label>
                                            <?php
                                                if(isset($_POST["apemat_med"])){
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="apemat_med" id="apemat_med" placeholder="Apellido materno" value="'.$_POST['apemat_med'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="apemat_med" id="apemat_med" placeholder="Apellido materno">';        
                                                }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="apemat">Especialidad</label>
                                            <?php
                                                if(isset($_POST["especialidad_med"])){
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="especialidad_med" id="especialidad_med" placeholder="Especialidad" value="'.$_POST['especialidad_med'].'">';
                                                }else{
                                                    echo '<input type="text" class="form-control" form="hoja_cita" name="especialidad_med" id="especialidad_med" placeholder="Especialidad">';        
                                                }
                                            ?>    
                                        </div>
                                      <!--  <div class="form-group">
                                            <input type="checkbox" form="hoja_cita" form="hoja_cita" name="aquien_corresponda" id="aquien_corresponda">A quien corresponda
                                        </div>-->
                                    </form> 
                                </div>  <!--    fin panel body  -->                
                                <div class="panel-footer">
                                </div>  <!--    fin panel footer    -->
                            </div>  <!--    fin panel-primary   -->
                        </div>  <!--    fin col-lg-6    -->
                        <!--        Fin 1er columna de captura          -->
        
                 </div>   <!-- /.row -->

                <div class="row">
                      <div class="col-md-12 col-lg-12">  
                        <div class="panel panel-primary">        
                            <div class="panel-body">
                                 <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="hoja_cita" type="submit" value="Aceptar" class="btn btn-primary">
                            </div>
                        </div>
                 </div>         
            </div>
        </di><!-- /#page-wrapper -->
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
        var apepat = new LiveValidation('apepat', { validMessage: 'OK!', wait: 500});
        apepat.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        apepat.add(Validate.Length, {minimum: 2, maximum: 45 } );
        apepat.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script> 
    <script type="text/javascript">
        var apemat = new LiveValidation('apemat', { validMessage: 'OK!', wait: 500});
        apemat.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        apemat.add(Validate.Length, {minimum: 2, maximum: 45 } );
        apemat.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script> 
   
    <script type="text/javascript">
        var f3 = new LiveValidation('tel_local', { validMessage: 'OK!', wait: 500});
        f3.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
        f3.add( Validate.Numericality );
        f3.add( Validate.Length, { minimum: 7, maximum:11 } );
    </script> 

    <script type="text/javascript">
        var f4 = new LiveValidation('tel_cel', { validMessage: 'OK!', wait: 500});
        f4.add( Validate.Numericality );
        f4.add( Validate.Length, { minimum: 7,maximum:11 }  );
    </script> 

    <script type="text/javascript">
        var f5 = new LiveValidation('email',{ validMessage: 'OK!', wait: 500});
        f5.add( Validate.Email,  );
    </script> 
    <script type="text/javascript">
        var f6 = new LiveValidation('estudio',{ validMessage: 'OK!', wait: 500});
        f6.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
    </script> 
    <script type="text/javascript">
        var cantidad_i131 = new LiveValidation('cantidad_i131', { validMessage: 'OK!', wait: 500});
        cantidad_i131.add( Validate.Numericality );
        cantidad_i131.add( Validate.Length, { maximum:5 }  );
        cantidad_i131.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
        
    </script> 
    <script type="text/javascript">
        var f7 = new LiveValidation('institucion',{ validMessage: 'OK!', wait: 500});
        f7.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
    </script> 
    <script type="text/javascript">
        var f7 = new LiveValidation('fecha_estudio',{ validMessage: 'OK!', wait: 500});
        f7.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
    </script> 
    <script type="text/javascript">
        var f8 = new LiveValidation('hora',{ validMessage: 'OK!', wait: 500});
        f8.add(Validate.Presence, {failureMessage: "Es necesario llenar este campo"});
    </script> 
    <script type="text/javascript">
        var nombre_med = new LiveValidation('nombre_med',{ validMessage: 'OK!', wait: 500});
        nombre_med.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script> 
    <script type="text/javascript">
        var appat_med = new LiveValidation('apepat_med',{ validMessage: 'OK!', wait: 500});
        appat_med.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script> 
    <script type="text/javascript">
        var apmat_med = new LiveValidation('apemat_med',{ validMessage: 'OK!', wait: 500});
        apmat_med.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script>
    <script type="text/javascript">
        var especialidad_med = new LiveValidation('especialidad_med',{ validMessage: 'OK!', wait: 500});
        especialidad_med.add( Validate.Exclusion, { within: [ '.' , ',', ';',':','-','_' ], partialMatch: true } );
    </script>
    <script type="text/javascript">
    function clear(){
        
        document.getElementById("hoja_cita").reset();
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