<?php

function previsualizacion(){
//datos anteriores   

date_default_timezone_set('America/Mexico_City');

$nombre             =   $_POST['nombre'];
$ap_paterno         =   $_POST['ap_paterno'];
$ap_materno         =   $_POST['ap_materno'];
$fecha              =   $_POST["fecha"];  
$hora               =   $_POST['hora'];
$tel_local          =   $_POST['tel_local'];
$tel_cel            =   $_POST['tel_cel'];
$idpaciente         =   $_POST['idpaciente'];
$idestudio_actual   =   $_POST['idestudio_actual'];      //estudio con el que estaba agendado anteriormente
$nombre_estudio_ant =   pasarMayusculas($_POST['nombre_estudio']);
$idestudio_nuevo    =   $_POST['idestudio_nuevo'];      //estudio nuevo que se le asignará al paciente.
$institucion        =   $_POST['institucion'];
$institucion_estudio=   $_POST['institucion_estudio'];

$paciente           =   $nombre.' '.$ap_paterno.' '.$ap_materno;    
//datos actuales

$fecha          =   date('d-m-Y',strtotime($fecha));
$hora           =   date('g:i a',strtotime($hora));

$nombre_estudio = "abc";

$mysql = new mysql();
            $link = $mysql->connect();
            $sql = $mysql->query($link,"SELECT concat(tipo, nombre) as nombre
                                        FROM estudio
                                        WHERE idgammagramas = $idestudio_nuevo;");  
            $row = $mysql->f_obj($sql);
$nombre_estudio = $row->nombre;


echo' 
<div class="col-md-12 col-lg-12">  
    <div class="panel panel-primary">        
        <div class = "panel-heading">
            <h3 class="panel-title"> Se actualizaron los registros </h3>
        </div>  <!--    fin panel heading   -->
            
        <div class="panel-body">
';

echo '      <p><h3>Se cambio</h3> </p>';
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="fecha">'.$fecha.'</th>
                            <th data-field="hora">'.$hora.'</th>
                            <th data-field="paciente">'.$paciente.'</th>
                            <th data-field="estudio">'.$nombre_estudio_ant.'</th>
                            <th data-field="tel_local">'.$tel_local.'</th>
                            <th data-field="tel_cel">'.$tel_cel.'</th>
                        </tr>
                    <tbody>
                </table>
            </div>

            ';


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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th data-field="fecha">'.$fecha.'</th>
                            <th data-field="hora">'.$hora.'</th>
                            <th data-field="paciente">'.$paciente.'</th>
                            <th data-field="estudio">'.$nombre_estudio.'</th>
                            <th data-field="tel_local">'.$tel_local.'</th>
                            <th data-field="tel_cel">'.$tel_cel.'</th>
                        </tr>
                    <tbody>
                </table>
            </div>
        </div>    

        <div class="panel-footer">
            
        </div>
            
    </div>
</div>';

}
?>
<?php
function update(){

    
$nombre             =   $_POST['nombre'];
$ap_paterno         =   $_POST['ap_paterno'];
$ap_materno         =   $_POST['ap_materno'];

$fecha              =   date('Y-m-d',strtotime($_POST["fecha"]));  

$idpaciente         =   $_POST['idpaciente'];
$idestudio_actual   =   $_POST['idestudio_actual'];      //estudio con el que estaba agendado anteriormente
$idestudio_nuevo    =   $_POST['idestudio_nuevo'];      //estudio nuevo que se le asignará al paciente.
$institucion        =   $_POST['institucion'];          //identifica institución particular
$institucion_estudio=   $_POST['institucion_estudio'];  //identifica intitucion particular en especifico cual es.
$num_anticipos      = 0;

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';
 
$usuario = $_SESSION['usuario'];
                  
                    
// institucion particular
//********************************** 
if($institucion == 'PARTICULAR'){
    //contar el número de anticipos
    include_once "include/mysql.php";

    $mysql = new mysql();
    $link = $mysql->connect();

    $sql = $mysql->query($link,"SELECT count(pacientes_idpacientes) as num_anticipos
                                FROM pacientes_has_anticipos
                                WHERE pacientes_idpacientes = $idpaciente;");  
                                       
    $row = $mysql->f_obj($sql);

    $num_anticipos = $row->num_anticipos;
    // echo '<pre>';
    // print_r($row);
    // echo '</pre>';
    $mysql->close();

    if($num_anticipos == 1){
    //Si cumple ya finiquito o no ha dejado anticipo (debe el total)
    //**********************************

        $mysql  = new mysql();
        $link   = $mysql->connect();
        $sql2   = $mysql->query($link,"SELECT  fecha_anticipo
                                    FROM pacientes_has_anticipos
                                    WHERE 
                                    pacientes_idpacientes = $idpaciente;");  
                                       
        $row = $mysql->f_obj($sql2);
          
        $fecha_anticipo = $row->fecha_anticipo;
        
        //echo $fecha_anticipo;

        $mysql->close();

        if($fecha_anticipo != '0000-00-00 00:00:00'){
        //si cumple ya finiquito el total del estudio
        //*******************************************

            
            // echo '<pre>';
            // echo $sumatoria_anticipos.' finiquitó ';
            // echo '</pre>';

            $mysql = new mysql();
            
            $link = $mysql->connect();
            $sql = $mysql->query($link,"SELECT $institucion_estudio as precio
                                        FROM estudio
                                        WHERE idgammagramas = $idestudio_nuevo;");  
                                        
            $row = $mysql->f_obj($sql);

            $precio_estudio = $row->precio;
            
            $mysql->close();

            $sumatoria_anticipos = sumatoria_de_anticipos($idpaciente);

            $y =  $sumatoria_anticipos - $precio_estudio;
        
            $total_pagado = number_format( $sumatoria_anticipos, 2);
            $precio_estudio_impresion = number_format($precio_estudio, 2);

            echo '<div class="alert alert-success" role="alert">Total de anticipos hechos por el paciente: $ <span>'.$total_pagado.'</span></div>';
            echo '<div class="alert alert-success" role="alert">Precio del nuevo estudio: $ <span>'.$precio_estudio_impresion.'</span></div>';  

            //El paciente debe después de cambiar el precio del estudio y no tiene saldo a favor
            if( $y < 0.00 ){
             //*************************************************************************
                
                
                $nuevo_monto = abs($y);
                $total_nuevo_monto = number_format($nuevo_monto , 2);

                echo '<div class="alert alert-danger" role="alert">El paciente DEBE: $ <span>'.$total_nuevo_monto.'</span></div>';
                
                $mysql = new mysql();
                    
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                            SET     monto_restante = $nuevo_monto
                                            WHERE pacientes_idpacientes = $idpaciente 
                                                AND monto_restante = 0.00;");

                $link = $mysql->connect();
                $sql = $mysql->query($link,"INSERT INTO anticipos
                                                        (idanticipos,
                                                        dep_banamex, 
                                                        pago_santander,
                                                        pago_cheque,
                                                        transferencia,
                                                        anticipo_efe,
                                                        factura,
                                                        no_recibo)
                                            VALUES ('', '0.00', '0.00', '0.00', '0.00', '0.00', 'NO', '')");
                
                $idanticipos =  mysqli_insert_id($link);

                $link = $mysql->connect();
                $sql = $mysql->query($link,"INSERT INTO pacientes_has_anticipos
                                                    (pacientes_idpacientes,
                                                    anticipos_idanticipos,
                                                    fecha_anticipo,
                                                    fecha_estudio,
                                                    monto_restante) 

                                            VALUES  ($idpaciente,
                                                    $idanticipos,
                                                    '0000-00-00 00:00:00',
                                                    '$fecha',
                                                    $nuevo_monto)");

                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 
                    
                $mysql->close();

                return 0;

                //*************************************************************************
            }
            //El paciente no debe después de cambiar el precio del estudio pero tiene saldo a favor
            if($y > 0.00){
                //*************************************************************************
                // hacer recibo de devolucion e insertar registro en BD por el monto de $Y
                //*************************************************************************                
                $dev = number_format($y, 2);
                echo '<div class="alert alert-danger" role="alert">HACER DEVOLUCIÓN AL PACIENTE POR: <span> $ '.$dev.'</span></div>';
                $nuevo_monto = 0.00;

                date_default_timezone_set('America/Mexico_City');
                $fecha_actual = date('Y-m-d');

                $mysql = new mysql();
                
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                            SET   monto_restante =   $nuevo_monto,
                                                fecha_anticipo='$fecha_actual'
                                            WHERE pacientes_idpacientes   = $idpaciente
                                                 AND fecha_anticipo = '0000-00-00 00:00:00'");
                
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 
                 

                $mysql->close();

                return $y;
                //*************************************************************************
            }

            //El paciente no debe después de cambiar el precio del estudio y tampoco tiene saldo a favor
            if ($y == 0.00){
                echo '<div class="alert alert-success" role="alert">El paciente no debe y tampoco tiene saldo a favor.</div>';
                $nuevo_monto = 0.00;

                date_default_timezone_set ('America/Mexico_City');
                $fecha_actual = date('Y-m-d H:i:s');

                $mysql = new mysql();
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                            SET  monto_restante =    $nuevo_monto,
                                                 fecha_anticipo =    '$fecha_actual'
                                            WHERE pacientes_idpacientes   = $idpaciente 
                                            AND fecha_anticipo = '0000-00-00 00:00:00'");                 

                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 

                $mysql->close();

            //********************************** 
            //********************************** 

            //pendiente corregir datos en tabla pacientes has anticipos debido a que los
            //reportes no salen bien.
            //********************************** 
            //********************************** 

                return 0;           
            }
        }
        else{
        //Está registrado que debe pero no ha dejado ningún anticipo
        // Actualizado y revisado 2019-07-18
        //**********************************************************

            echo 'no ha dejado anticipo';

            $mysql = new mysql();
            
            $link = $mysql->connect();
            $sql = $mysql->query($link,"SELECT $institucion_estudio as precio
                                        FROM estudio
                                        WHERE idgammagramas = $idestudio_nuevo;");  
                                       
            $row = $mysql->f_obj($sql);

            $precio_estudio = $row->precio;
            $mysql->close();


            $mysql = new mysql();
            
            $link = $mysql->connect();
            $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                        SET   monto_restante =   $precio_estudio
                                        WHERE pacientes_idpacientes   = $idpaciente
                                            AND fecha_anticipo = '0000-00-00 00:00:00'");
            $link = $mysql->connect();
            $sql = $mysql->query($link,"UPDATE pacientes 
                                        SET   estudio_idgammagramas =   $idestudio_nuevo
                                        WHERE idpacientes   = $idpaciente");
            $mysql->close();

            return 0;
        }

    }
    else{
    //ha dejado  mas de un anticipo.
    //En esta condición puede que ya halla finiquitado o que deba
    //*******************************************

        $mysql  = new mysql();
        $link   = $mysql->connect();
        $sql2   = $mysql->query($link,"SELECT  monto_restante
                                    FROM pacientes_has_anticipos
                                    WHERE 
                                    pacientes_idpacientes = $idpaciente AND monto_restante = 0.00 ;");  
                                       
        $row = $mysql->f_obj($sql2);
        
        if(isset($row->monto_restante))
        {
            $monto_restante = $row->monto_restante;
        }else{
            $monto_restante = 1.00; //para que pase al else
        }
        
        
        //echo $fecha_anticipo;

        $mysql->close(); 
        
        //Si el paciente ya finiquitó entra
        if($monto_restante == 0.00)
        {
            /********************************************************************************************************
            Solo funciona cuando hay mas de dos anticipos y ademas el paciente NO DEBE
            Rev. 23-07-2019
            ********************************************************************************************************/
                
            $mysql = new mysql();
            
            $link = $mysql->connect();
            $sql = $mysql->query($link,"SELECT $institucion_estudio as precio
                                        FROM estudio
                                        WHERE idgammagramas = $idestudio_nuevo;");  
                                        
            $row = $mysql->f_obj($sql);

            $precio_estudio = $row->precio;
            
            $mysql->close();

            $sumatoria_anticipos = sumatoria_de_anticipos($idpaciente);
        
            $total_pagado = number_format( $sumatoria_anticipos, 2);
            $precio_estudio_impresion = number_format($precio_estudio, 2);

            echo '<div class="alert alert-success" role="alert">Total de anticipos hechos por el paciente: $ <span>'.$total_pagado.'</span></div>';
            echo '<div class="alert alert-success" role="alert">Precio del nuevo estudio: $ <span>'.$precio_estudio_impresion.'</span></div>';    

            $y =  $sumatoria_anticipos - $precio_estudio;  //        
            

            //FUNCION COMPROBADA FUNCIONA AL 100% REV. 19/07/2019
            //El paciente debe después de cambiar el precio del estudio y no tiene saldo a favor

            if( $y < 0.00 ){

                $nuevo_monto = abs($y);
                $total_nuevo_monto = number_format( $nuevo_monto, 2 );
            
                echo '<div class="alert alert-danger" role="alert">El paciente DEBE: $ <span>'.$total_nuevo_monto.'</span></div>';
                

                $mysql = new mysql();
                    
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                            SET     monto_restante = $nuevo_monto
                                            WHERE pacientes_idpacientes = $idpaciente 
                                                AND monto_restante = 0.00;");

                $link = $mysql->connect();
                $sql = $mysql->query($link,"INSERT INTO anticipos
                                                        (idanticipos,
                                                        dep_banamex, 
                                                        pago_santander,
                                                        pago_cheque,
                                                        transferencia,
                                                        anticipo_efe,
                                                        factura,
                                                        no_recibo)
                                            VALUES ('', '0.00', '0.00', '0.00', '0.00', '0.00', 'NO', '')");
                
                $idanticipos =  mysqli_insert_id($link);

                $link = $mysql->connect();
                $sql = $mysql->query($link,"INSERT INTO pacientes_has_anticipos
                                                    (pacientes_idpacientes,
                                                    anticipos_idanticipos,
                                                    fecha_anticipo,
                                                    fecha_estudio,
                                                    monto_restante) 

                                            VALUES  ($idpaciente,
                                                    $idanticipos,
                                                    '0000-00-00 00:00:00',
                                                    '$fecha',
                                                    $nuevo_monto)");

                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 
                    
                $mysql->close();

                return 0;
            }
            
            //FUNCION COMPROBADA FUNCIONA AL 100% REV. 19/07/2019
            //El paciente no debe después de cambiar el precio del estudio, pero tiene saldo a favor

            if($y > 0.00){
                    
                // hacer recibo por el monto de $Y
                //*************************************************************************
                $y = abs($y);
                $dev = number_format($y);

                echo '<div class="alert alert-danger" role="alert">HACER DEVOLUCIÓN AL PACIENTE POR: <span> $ '.$dev.'</span></div>';

                $nuevo_monto = 0.00;

                date_default_timezone_set('America/Mexico_City');
                $fecha_actual = date('Y-m-d H:i:s');

                $mysql = new mysql();
                    
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                            SET monto_restante  =   $nuevo_monto,
                                                fecha_anticipo  =   '$fecha_actual'
                                            WHERE pacientes_idpacientes   = $idpaciente
                                                AND monto_restante = 0.00");
                    
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 
                    
                $mysql->close();

                return $y;
            }

            //FUNCION COMPROBADA FUNCIONA AL 100% REV. 19/07/2019
            //El paciente no debe después de cambiar el precio del estudio y tampoco tiene saldo a favor

            if ($y == 0.00){
                // echo'El paciente no debe y tampoco tiene saldo a favor';
                echo '<div class="alert alert-success" role="alert">El paciente no debe y tampoco tiene saldo a favor.</div>';

                $mysql = new mysql();
                               

                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 

                $mysql->close();

                return 0;
            }

        }
        else
        {
            /********************************************************************************************************
            Solo funciona cuando hay mas de dos anticipos y ademas el paciente debe
            Rev. 19-07-2019
            ********************************************************************************************************/
            
            $mysql = new mysql();
            $link = $mysql->connect();
            $sql = $mysql->query($link,"SELECT $institucion_estudio as precio
                                        FROM estudio
                                        WHERE idgammagramas = $idestudio_nuevo;");  
                                       
            $row = $mysql->f_obj($sql);

            $precio_estudio = $row->precio;
            $mysql->close();

            $sumatoria_anticipos = sumatoria_de_anticipos($idpaciente);
            
            $total_pagado = number_format( $sumatoria_anticipos, 2);
            $precio_estudio_impresion = number_format($precio_estudio, 2);

            echo '<div class="alert alert-success" role="alert">Total de anticipos hechos por el paciente: $ <span>'.$total_pagado.'</span></div>';
            echo '<div class="alert alert-success" role="alert">Precio del nuevo estudio: $ <span>'.$precio_estudio_impresion.'</span></div>';           


            $y =  $sumatoria_anticipos - $precio_estudio;  //

            //FUNCION COMPROBADA FUNCIONA AL 100% REV. 19/07/2019
            //El paciente DEBE después de cambiar el precio del estudio y no tiene saldo a favor

            if( $y < 0.00 ){

                $nuevo_monto = abs($y);
                $total_nuevo_monto = number_format($nuevo_monto, 2);
            
                echo '<div class="alert alert-danger" role="alert">El paciente DEBE: $ <span>'.$total_nuevo_monto.'</span></div>';
                

                $mysql = new mysql();
                    
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                            SET   monto_restante = $nuevo_monto
                                            WHERE pacientes_idpacientes = $idpaciente 
                                                AND fecha_anticipo = '0000-00-00 00:00:00';");

                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 
                    
                $mysql->close();

                //return 0;
            }
            
            //FUNCION COMPROBADA FUNCIONA AL 100% REV. 19/07/2019
            //El paciente no debe después de cambiar el precio del estudio, pero tiene saldo a favor

            if($y > 0.00){
                    
                // hacer recibo por el monto de $Y
                //*************************************************************************
                $y = abs($y);

                $total_devolver = number_format($y, 2);
                echo '<div class="alert alert-danger" role="alert">HACER DEVOLUCIÓN AL PACIENTE POR: <span> $ '.$total_devolver.'</span></div>';

                $nuevo_monto = 0.00;

                date_default_timezone_set('America/Mexico_City');
                $fecha_actual = date('Y-m-d H:i:s');

                $mysql = new mysql();
                    
                $link = $mysql->connect();

                $sql = $mysql->query($link,"SELECT anticipos_idanticipos 
                                                FROM pacientes_has_anticipos
                                                WHERE pacientes_idpacientes = $idpaciente and fecha_anticipo = '0000-00-00 00:00:00'");
                $res = $mysql->f_obj($sql);

                if(isset($res->anticipos_idanticipos)){
                    $res->anticipos_idanticipos = $res->anticipos_idanticipos;
                }

                $sql = $mysql->query($link,"DELETE FROM anticipos 
                                            WHERE idanticipos  = $res->anticipos_idanticipos");
                
                $sql = $mysql->query($link,"DELETE FROM pacientes_has_anticipos 
                                                WHERE pacientes_idpacientes = $idpaciente and fecha_anticipo = '0000-00-00 00:00:00'");
                

                $sql = $mysql->query($link,"SELECT anticipos_idanticipos 
                                                FROM pacientes_has_anticipos
                                                WHERE pacientes_idpacientes = $idpaciente ORDER BY anticipos_idanticipos desc limit 1");
                
                $res = $mysql->f_obj($sql);  
                

                $sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
                                                SET   monto_restante =   0.00
                                                WHERE anticipos_idanticipos   = $res->anticipos_idanticipos");                              

                    
                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 
                    
                $mysql->close();

                return $y;
            }

            //FUNCION COMPROBADA FUNCIONA AL 100% REV. 19/07/2019
            //El paciente no debe después de cambiar el precio del estudio y tampoco tiene saldo a favor

            if ($y == 0.00){
                // echo'El paciente no debe y tampoco tiene saldo a favor';
                echo '<div class="alert alert-success" role="alert">El paciente no debe y tampoco tiene saldo a favor.</div>';
                // $nuevo_monto = 0.00;

                //date_default_timezone_set ('America/Mexico_City');
                //$fecha_actual = date('Y-m-d');
                //echo $fecha_actual;

                $mysql = new mysql();
                
                //*************************************
                //este update no tiene caso
                /*
                    hay que eliminar el registro con fecha = '0000-00-00' de pacientes_has_anticipos.

                    recolectar todos los anticipos.

                    obtener el precio del estudio nuevo y restar; precio estudio - 
                    
                    monto. asi con cada registro de anticipo que se tenga.
                    utilizar num_rows

                    el monto de $0.00 hay que ponerlo en el ultimo registro y poner la fecha actual.
                */
                //*************************************    
                // $link = $mysql->connect();
                // $sql= $mysql->query($link,"UPDATE pacientes_has_anticipos 
                //                             SET     monto_restante =    $nuevo_monto,
                //                                     fecha_anticipo =    '$fecha_actual'
                //                             WHERE pacientes_idpacientes   = $idpaciente 
                //                                 AND fecha_anticipo = '0000-00-00'");                 

                $link = $mysql->connect();
                $sql = $mysql->query($link,"UPDATE pacientes 
                                            SET   estudio_idgammagramas =   $idestudio_nuevo
                                            WHERE idpacientes   = $idpaciente"); 
                
                //********************************** 
                //********************************** 
                //pendiente corregir datos en tabla pacientes has anticipos debido a que los
                //reportes no salen bien.
                //********************************** 
                //********************************** 


                $mysql->close();

                return 0;
            }

        }

       
    }
}
// institucion pública
//********************************** 
else{
    $mysql =new mysql();
    $link = $mysql->connect();

    $sql = $mysql->query($link,"UPDATE pacientes SET   estudio_idgammagramas =   $idestudio_nuevo
            WHERE idpacientes   = $idpaciente"); 

    $mysql->close();

    return 0;
}

//**********************************    


    $mysql->close();
    return 0;

         
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
       <?php 
            include_once 'include/nav_session.php';
       ?>

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
            <?php

                include_once "include/mysql.php";
                include_once "include/funciones_consultas.php";

                $res = update();
                previsualizacion();

            ?>
            <form  role="form" id="editar_paciente" method="post" action="viewmod_ver_editar_estudio_de_paciente.php" >
                <?php 
                    $fecha_ant          =   $_POST["fecha"];
                    $fecha_ant          =   date('Y-m-d',strtotime($fecha_ant)); 
                    echo '<input type="hidden" form="editar_paciente" name="fecha_estudios" id="fecha_estudios" value="'.$fecha_ant.'"/>';
                ?>
            </form>
            <form  role="form" id="editar_paciente_devolucion" method="post" action="view_imprimir_devolucion.php" target="_blank" onsubmit="myFunction()">
                <?php 
                    $fecha_ant          =   $_POST["fecha"];
                    $fecha_ant          =   date('Y-m-d',strtotime($fecha_ant)); 
                    $idpaciente         =   $_POST['idpaciente'];

                    
                    echo '<input type="hidden" form="editar_paciente_devolucion" name="monto_devolucion" value="'.$res.'"/>';
                    echo '<input type="hidden" form="editar_paciente_devolucion" name="id_paciente" value="'.$idpaciente.'"/>';
                ?>
            </form>
            <?php
                if($res == 0){
                    // echo $res;
                echo'
                    <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="editar_paciente" type="submit" value="Aceptar" class="btn btn-primary">';
                }
                else{
                    // echo $res;
                
                echo'<button type="submit" id="btn_aceptar" class="btn btn-danger btn-lg btn-block" form="editar_paciente_devolucion">
                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                        Aceptar
                    </button>  ';
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

    <script>
        function myFunction() {
            //se puede comentar para debug
            document.getElementById("btn_aceptar").disabled = true;
      
        }
    </script>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>