<?php
function ver_pacientes_del_dia($fecha_act, $pagina){

/*---------------------------------------------------------------------
//Permite ver a los pacientes que se tienen citados en determinada fecha
//para eso sele pasa como parametro la fecha en la que queremos saber
//que pacientes hay citados, ademas de que esta rutina se emplea para
//el área de recepción ya que le indica con franjas de colores que paciente
//deben (franaja color rojo) y cuales no (franja color verde indica que 
// ya pagaron el total)

// invoca: viewmod_ver_pacientes_del_dia.php
//         viewmod_ver_pacientes_por_semana.php
//         viewmod_ver_pacientes_por_fecha.php

// Llama a otros procesos  funciones_consultas.php --> mod_estatus_pac($row, $pagina);
//                         funciones_consultas.php --> editar_datos_pac($row, $pagina);

// rev. 2019/07/12
---------------------------------------------------------------------*/
    echo '
        <div class ="table-responsive">
            <table class ="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <!--<th data-field="id">Fecha</th>-->
                        <th data-field="nombre_del_paciente">Nombre de la paciente</th>
                        <th data-field="nombre_del_estudio">Nombre del estudio</th>
                        <th data-field="hora">Hora</th>
                        <th data-field="institucion">Institución</th>
                        <th data-field="medico">Médico tratante</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="estatus">Estatus</th>';
                        
                        if( $_SESSION['nivel_acceso'] == 3)
                        {
                            echo'
                            <th data-field="estatus">Editar</th>
                            <!--<th data-field="re-agendar">Re-agendar</th>-->
                            ';

                        }

                        
        echo'       </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,

                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,

                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,
                                                            
                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) 
                    {   
                        // echo'<pre>';
                        // print_r($row);
                        // echo'</pre>';
                        if($row->debe > '0.00')     //pacientes que deben
                        {            
                            echo '<tr class ="danger">'; 
                            //echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }

                            $row->hora = date('g:i a ',strtotime($row->hora));

                            echo '<td>'.$row->hora.'</td>';
                            echo '<td>'.$row->institucion.'</td>';
                            echo '<td>'.$row->medico.'</td>';
                            echo '<td>'.$row->num_tel.'</td>';
                            echo '<td>'.$row->num_tel2.'</td>';

                            mod_estatus_pac($row, $pagina);

                            if( $_SESSION['nivel_acceso'] == 3 ){
                                editar_datos_pac($row, $pagina);
                                //reagendar_pac($row, $pagina);
                            }
                            
                            echo '</tr>';
                        }
                        else
                        {
                            // busca a que tipo de institución pertenece
                            //ayuda a marcar de verde si ya pago de lo contrario no lo 
                            //sombrea a la hora de hacer la búsqueda.
                            // ************************************************************
                            $institucion = mb_strtolower($row->institucion, "UTF-8" );
                            $institucion = str_replace(" ", "_", $institucion);
                            //echo $institucion.'-----';

                            $link = $mysql->connect(); 
                            $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                FROM instituciones t5 
                                                                WHERE t5.nombre = '$institucion' ");
     
                            $row2 = $mysql->f_obj($sqltest2);

                            //print_r($row2->tipo);
                            // ************************************************************
                            if($row2->tipo == 'PARTICULAR' ){  //pacientes que ya no deben
                                echo '<tr class ="success" >'; 
                                //echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row, $pagina);

                                if( $_SESSION['nivel_acceso'] == 3)
                                {
                                    editar_datos_pac($row, $pagina);
                                    //reagendar_pac($row, $pagina);
                                }

                                echo '</tr>';
                            }
                            else{
                                echo '<tr>';                            //pacientes de instituciones públicas
                                //echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                
                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row, $pagina);
                                
                                if( $_SESSION['nivel_acceso'] == 3)
                                {
                                    editar_datos_pac($row, $pagina);
                                    //reagendar_pac($row, $pagina);
                                }

                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>
                ';               
}

function ver_pacientes_mañana($fecha_act, $pagina){

/*---------------------------------------------------------------------
//Permite ver a los pacientes que se tienen citados para mañana y de esa
// forma presentar la lista con la opción de enviar sms y recordarles su cita

// invoca: viewmod_ver_pacientes_mañana.php


// Llama a otros procesos  funciones_consultas.php --> mod_estatus_pac($row, $pagina);
//                         funciones_consultas.php --> enviar_sms($row, $pagina);

// rev. 2020/02/03
---------------------------------------------------------------------*/
    echo '
        <div class ="table-responsive">
            <table class ="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <!--<th data-field="id">Fecha</th>-->
                        <th data-field="nombre_del_paciente">Nombre de la paciente</th>
                        <th data-field="nombre_del_estudio">Nombre del estudio</th>
                        <th data-field="hora">Hora</th>
                        <th data-field="institucion">Institución</th>
                        <th data-field="medico">Médico tratante</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="estatus">Estatus</th>';
                        
                        if( $_SESSION['nivel_acceso'] == 3)
                        {
                            echo'
                            <th data-field="estatus">Enviar sms</th>
                            <th data-field="estatus">SMS enviado?</th>
                            <!--<th data-field="re-agendar">Re-agendar</th>-->
                            ';

                        }

                        
        echo'       </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  (select s_sms_exite(t1.idpacientes))as sms,
                                                            t1.idpacientes,
                                                            t1.fecha,

                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,

                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,
                                                            
                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) 
                    {   
                        // echo'<pre>';
                        // print_r($row);
                        // echo'</pre>';
                        if($row->debe > '0.00')     //pacientes que deben
                        {            
                            echo '<tr class ="danger">'; 
                            //echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }

                            $row->hora = date('g:i a ',strtotime($row->hora));

                            echo '<td>'.$row->hora.'</td>';
                            echo '<td>'.$row->institucion.'</td>';
                            echo '<td>'.$row->medico.'</td>';
                            echo '<td>'.$row->num_tel.'</td>';
                            echo '<td>'.$row->num_tel2.'</td>';

                            mod_estatus_pac($row, $pagina);

                            if( $_SESSION['nivel_acceso'] == 3 ){
                                enviar_sms($row, $pagina);
                                echo '<td>'.$row->sms.'</td>';
                                //reagendar_pac($row, $pagina);
                            }
                            
                            echo '</tr>';
                        }
                        else
                        {
                            // busca a que tipo de institución pertenece
                            //ayuda a marcar de verde si ya pago de lo contrario no lo 
                            //sombrea a la hora de hacer la búsqueda.
                            // ************************************************************
                            $institucion = mb_strtolower($row->institucion, "UTF-8" );
                            $institucion = str_replace(" ", "_", $institucion);
                            //echo $institucion.'-----';

                            $link = $mysql->connect(); 
                            $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                FROM instituciones t5 
                                                                WHERE t5.nombre = '$institucion' ");
     
                            $row2 = $mysql->f_obj($sqltest2);

                            //print_r($row2->tipo);
                            // ************************************************************
                            if($row2->tipo == 'PARTICULAR' ){  //pacientes que ya no deben
                                echo '<tr class ="success" >'; 
                                //echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row, $pagina);

                                if( $_SESSION['nivel_acceso'] == 3)
                                {
                                    enviar_sms($row, $pagina);
                                    echo '<td>'.$row->sms.'</td>';
                                    //reagendar_pac($row, $pagina);
                                }

                                echo '</tr>';
                            }
                            else{
                                echo '<tr>';                            //pacientes de instituciones públicas
                                //echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                
                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row, $pagina);
                                
                                if( $_SESSION['nivel_acceso'] == 3)
                                {
                                    enviar_sms($row, $pagina);
                                    echo '<td>'.$row->sms.'</td>';
                                    //reagendar_pac($row, $pagina);
                                }

                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>
                ';               
}

function ver_sms_enviados($fecha_act, $pagina){

/*---------------------------------------------------------------------
//Permite ver a los pacientes que se tienen citados para mañana y de esa
// forma presentar la lista con la opción de enviar sms y recordarles su cita

// invoca: controlador_ver_sms_enviados.php


// Llama a otros procesos  funciones_consultas.php --> mod_estatus_pac($row, $pagina);
//                         funciones_consultas.php --> enviar_sms($row, $pagina);

// rev. 2020/02/03
---------------------------------------------------------------------*/
    echo '
        <div class ="table-responsive">
            <table class ="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <!--<th data-field="id">Fecha</th>-->
                        <th data-field="nombre_del_paciente">Nombre de la paciente</th>
                        <th data-field="nombre_del_estudio">Nombre del estudio</th>
                        <th data-field="hora">Hora</th>
                        <th data-field="institucion">Institución</th>
                        <th data-field="medico">Médico tratante</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="estatus">Estatus</th>';
                        
                        if( $_SESSION['nivel_acceso'] == 3)
                        {
                            echo'
                            <th data-field="estatus">Estatus servidor sms</th>
                            <th data-field="estatus">SMS enviado?</th>
                            <!--<th data-field="re-agendar">Re-agendar</th>-->
                            ';

                        }

                        
        echo'       </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sql = $mysql->query($link,"SELECT DISTINCT idpaciente  from log_sms where idpaciente=4488;");
                    
                    
                    while ($row2 = $mysql->f_obj($sql)) 
                    {   echo'<pre>';
                        print_r($row2);
                        echo'</pre>';
                        $sqltest = $mysql->query($link,"SELECT  
                                                            (select s_sms_exite($row2->idpaciente))as sms,
                                                            (select s_sms_respuesta($row2->idpaciente))as respuesta_sms,
                                                            t1.idpacientes,
                                                            t1.fecha,

                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,

                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,
                                                            
                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE idpacientes = $row2->idpaciente  ORDER BY idpacientes");
                    
                        while ($row = $mysql->f_obj($sqltest)) 
                        {   
                            // echo'<pre>';
                            // print_r($row);
                            // echo'</pre>';
                            if($row->debe > '0.00')     //pacientes que deben
                            {            
                                echo '<tr class ="danger">'; 
                                //echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';

                                mod_estatus_pac($row, $pagina);

                                if( $_SESSION['nivel_acceso'] == 3 ){
                                    echo '<td>'.$row->respuesta_sms.'</td>';
                                    echo '<td>'.$row->sms.'</td>';
                                    //reagendar_pac($row, $pagina);
                                }
                                
                                echo '</tr>';
                            }
                            else
                            {
                                // busca a que tipo de institución pertenece
                                //ayuda a marcar de verde si ya pago de lo contrario no lo 
                                //sombrea a la hora de hacer la búsqueda.
                                // ************************************************************
                                $institucion = mb_strtolower($row->institucion, "UTF-8" );
                                $institucion = str_replace(" ", "_", $institucion);
                                //echo $institucion.'-----';

                                $link = $mysql->connect(); 
                                $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                    FROM instituciones t5 
                                                                    WHERE t5.nombre = '$institucion' ");
        
                                $row2 = $mysql->f_obj($sqltest2);

                                //print_r($row2->tipo);
                                // ************************************************************
                                if($row2->tipo == 'PARTICULAR' ){  //pacientes que ya no deben
                                    echo '<tr class ="success" >'; 
                                    //echo '<td>'.$row->fecha.'</td>';
                                    echo '<td>'.$row->nombre.'</td>';

                                    if($row->indicaciones_tratamiento != ''){
                                        echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                    }else{
                                        echo '<td>'.$row->estudio.'</td>';    
                                    }
                                    

                                    $row->hora = date('g:i a ',strtotime($row->hora));

                                    echo '<td>'.$row->hora.'</td>';
                                    echo '<td>'.$row->institucion.'</td>';
                                    echo '<td>'.$row->medico.'</td>';
                                    echo '<td>'.$row->num_tel.'</td>';
                                    echo '<td>'.$row->num_tel2.'</td>';
                                    mod_estatus_pac($row, $pagina);

                                    if( $_SESSION['nivel_acceso'] == 3)
                                    {
                                        echo '<td>'.$row->respuesta_sms.'</td>';
                                        echo '<td>'.$row->sms.'</td>';
                                        //reagendar_pac($row, $pagina);
                                    }

                                    echo '</tr>';
                                }
                                else{
                                    echo '<tr>';                            //pacientes de instituciones públicas
                                    //echo '<td>'.$row->fecha.'</td>';
                                    echo '<td>'.$row->nombre.'</td>';
                                    
                                    if($row->indicaciones_tratamiento != ''){
                                        echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                    }else{
                                        echo '<td>'.$row->estudio.'</td>';    
                                    }

                                    $row->hora = date('g:i a ',strtotime($row->hora));

                                    echo '<td>'.$row->hora.'</td>';
                                    echo '<td>'.$row->institucion.'</td>';
                                    echo '<td>'.$row->medico.'</td>';
                                    echo '<td>'.$row->num_tel.'</td>';
                                    echo '<td>'.$row->num_tel2.'</td>';
                                    mod_estatus_pac($row, $pagina);
                                    
                                    if( $_SESSION['nivel_acceso'] == 3)
                                    {
                                        echo '<td>'.$row->respuesta_sms.'</td>';
                                        echo '<td>'.$row->sms.'</td>';
                                        //reagendar_pac($row, $pagina);
                                    }

                                    echo '</tr>';
                                }
                            }
                        }
                        
                    }

                    
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>
                ';               
}

function ver_pacientes_del_mes($fecha_ini, $fecha_fin, $pagina){

/*---------------------------------------------------------------------
//Permite ver a los pacientes que se tienen citados en el mes seleccionado
//para eso sele pasa como parametro la fecha del mes en el que estemos interesados
//y con ello veremos que pacientes hay citados, ademas de que esta rutina 
//se emplea para el área de recepción ya que le indica con franjas de colores 
//que paciente deben (franaja color rojo) y cuales no (franja color verde indica  
// que ya pagaron el total)

// invoca: viewmod_ver_pacientes_por_mes.php

// Llama a otros procesos  funciones_consultas.php --> mod_estatus_pac($row, $pagina);
//                         funciones_consultas.php --> editar_datos_pac($row, $pagina);

// rev. 2019/07/12
---------------------------------------------------------------------*/

    echo '
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="myClass">
                <thead>
                    <tr>
                        <th data-field="id">Fecha</th>
                        <th data-field="nombre_paciente">Nombre de la paciente</th>
                        <th data-field="nombre_estudio">Nombre del estudio</th>
                        <th data-field="hora">Hora</th>
                        <th data-field="institucion">Institución</th>
                        <th data-field="medico_tratante">Médico tratante</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="teléfono">Teléfono</th>
                        <th data-field="estatus">Estatus</th>';

                        if( $_SESSION['nivel_acceso'] == 3)
                        {
                            echo'
                            <th data-field="estatus">Editar</th>
                            <!--<th data-field="re-agendar">Re-agendar</th>-->
                            ';

                        }
                        
       echo'        </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            t1.institucion,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,

                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,

                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE fecha >= '$fecha_ini' AND fecha <= '$fecha_fin'  ORDER BY fecha");
                    
                    while ($row = $mysql->f_obj($sqltest)) {   

                        //convertir formato de fecha yyyy-mm-dd a dd-mm-yyyy
                        $row->fecha = date("d-m-Y",strtotime($row->fecha));

                        if($row->debe > '0.00'){
                            if($_SESSION['nivel_acceso'] < 4){
                            echo '<tr class ="danger">'; }
                            echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }

                            $row->hora = date('g:i a ',strtotime($row->hora));

                            echo '<td>'.$row->hora.'</td>';
                            echo '<td>'.$row->institucion.'</td>';
                            echo '<td>'.$row->medico.'</td>';
                            echo '<td>'.$row->num_tel.'</td>';
                            echo '<td>'.$row->num_tel2.'</td>';
                            mod_estatus_pac($row, $pagina);
                            
                            if( $_SESSION['nivel_acceso'] == 3)
                            {
                                editar_datos_pac($row, $pagina);
                                //reagendar_pac($row, $pagina);
                            }

                            echo '</tr>';
                        }
                        else{

                            // busca a que tipo de institución pertenece
                            //ayuda a marcar de verde si ya pago de lo contrario no lo 
                            //sombrea a la hora de hacer la búsqueda.
                            // ************************************************************
                            $institucion = mb_strtolower($row->institucion, "UTF-8" );
                            $institucion = str_replace(" ", "_", $institucion);
                            //echo $institucion;

                            $link = $mysql->connect(); 
                            $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                FROM instituciones t5 
                                                                WHERE t5.nombre = '$institucion'");
     
                            $row2 = $mysql->f_obj($sqltest2);

                            
                            // ************************************************************

                            if($row2->tipo == 'PARTICULAR'){
                                if($_SESSION['nivel_acceso'] < 4){
                                echo '<tr class ="success" >'; }
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row,$pagina);
                                
                                if( $_SESSION['nivel_acceso'] == 3)
                                {
                                    editar_datos_pac($row, $pagina);
                                    //reagendar_pac($row, $pagina);
                                }

                                echo '</tr>';
                            }
                            else{
                                echo '<tr>'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                
                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row,$pagina);

                                if( $_SESSION['nivel_acceso'] == 3)
                                {
                                    editar_datos_pac($row, $pagina);
                                    //reagendar_pac($row, $pagina);
                                }

                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>';  
}

function ver_pacientes_del_dia_operario($fecha_act, $pagina){
/*---------------------------------------------------------------------
//Permite ver a los pacientes que se tienen citados en determinada fecha
//para eso sele pasa como parametro la fecha en la que queremos saber
//que pacientes hay citados, ademas de que esta rutina se emplea para
//el área en donde está la técnico. Aquí no se indica si debe o no.

//invoca:   viewmod_ver_pacientes_del_dia.php
//          viewmod_ver_pacientes_por_semana.php

// rev. 2019/07/12
---------------------------------------------------------------------*/
    
    echo '
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <!--<th data-field="id">Fecha</th>-->
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Hora</th>
                        <th data-field="price">Médico tratante</th>
                        <th data-field="estatus">Estatus</th>
                       <!-- <th data-field="subir_imagenes"  width="5">PDF</th>
                        <th data-field="subir_imagenes">Subir</th>-->
                        <!-- <th data-field="estatus">Editar</th>-->
                    </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,

                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,

                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {   
                        echo '<tr>'; 
                        echo '<td>'.$row->nombre.'</td>';
                                
                        if($row->indicaciones_tratamiento != ''){
                            echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                        }
                        else{
                            echo '<td>'.$row->estudio.'</td>';    
                        }

                        $row->hora = date('g:i a ',strtotime($row->hora));
                        echo '<td>'.$row->hora.'</td>';
                        echo '<td>'.$row->medico.'</td>';
                        mod_estatus_pac($row, $pagina);
                        //upload_images($row);  //habilita la subida de archivos pdf
                        echo '</tr>';
                    }

                    $mysql->close();
            echo'</tbody>
            </table>
        </div>';    
}

function reimprimir_hoja_de_citas($fecha_act, $pagina){

/*---------------------------------------------------------------------
// Con esta funcion podemos reimprimir la hoja de citas de cualquier paciente
// para ello sólo se requiere la fecha en la que fue citado el paciente

invoca:  viewmod_ver_reimprimir_hoja_de_cita.php

// rev. 2019/07/12
---------------------------------------------------------------------*/
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                                <tr>
                                    <th data-field="id">Fecha</th>
                                    <th data-field="name">Nombre de la paciente</th>
                                    <th data-field="price">Nombre del estudio</th>
                                    <th data-field="price">Hora</th>
                                    <th data-field="price">Institución</th>';
                                    
                                    if($_SESSION['nivel_acceso'] > 2){
                                        echo'
                                            <th data-field="price">Editar</th>
                                            <!--<th data-field="price">Estatus</th>-->
                                            ';
                                    }
                        echo '  </tr>
                            </thead>
                            <tbody>
                            ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,
                                                            t1.hora,
                                                            t1.institucion,
                                                            t1.estatus

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {                      
                        echo '<tr>'; 
                        echo '<td>'.$row->fecha.'</td>';
                        echo '<td>'.$row->nombre.'</td>';
                        echo '<td>'.$row->estudio.'</td>';

                        $row->hora = date('g:i a ',strtotime($row->hora));

                        echo '<td>'.$row->hora.'</td>';
                        echo '<td>'.$row->institucion.'</td>';

                        if($_SESSION['nivel_acceso'] > 2){
                             echo'   <td>
                                    <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="view_reimprimir_hoja_de_citas.php" target="_blank">
                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>
                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="idusuario" value="'.$_SESSION['id'].'"/>
                                        
                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$fecha_act.'"/>
                                        
                                        <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-primary  btn-block" >
                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                            Imprimir
                                        </button>

                                    </form>
                                </td>';

                            //mod_estatus_pac($row, $pagina);
                        }
                        echo '</tr>';
                    }
                    $mysql->close();
                echo'</tbody>
                </table>
    </div>            ';             
}

function ver_resultados_pacientes($fecha_act){
/*---------------------------------------------------------------------
// Ésta función actualemente no se usa

// rev. 2019/07/12
---------------------------------------------------------------------*/   
    echo '
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <!--<th data-field="id">Fecha</th>-->
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Hora</th>
                        <th data-field="price">Médico tratante</th>
                        <th data-field="subir_imagenes"  width="5">PDF</th>
                        <!--<th data-field="estatus">Estatus</th>-->
                        
                        <!--<th data-field="subir_imagenes">Subir</th>-->
                        <!-- <th data-field="estatus">Editar</th>-->
                    </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {   
                        echo '<tr>'; 
                        echo '<td>'.$row->nombre.'</td>';
                                
                        if($row->indicaciones_tratamiento != ''){
                            echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                        }
                        else{
                            echo '<td>'.$row->estudio.'</td>';    
                        }

                        $row->hora = date('g:i a ',strtotime($row->hora));
                        echo '<td>'.$row->hora.'</td>';
                        echo '<td>'.$row->medico.'</td>';
                        view_images($row);
                        //mod_estatus($row);
                        //upload_images($row);

                        echo '</tr>';
                    }

                    $mysql->close();
            echo'</tbody>
            </table>
        </div>';    
}

function buscar_paciente($nombre, $appat, $apmat, $pagina){
/*---------------------------------------------------------------------
Esta es una función que permite buscar un paciente dentro de la base
de datos para ello es necesario pasarle como paramtro el nombre el 
apellido paterno y el apellido materno y nos devuelve una tabla con
el resultado obtenido. Nota: el único inconveniente es que hay que 
escribir bien su nombre completo.

invoca:                     viewmod_buscar_paciente.php
llama a otros procesos:     funciones_consultas.php  --> mod_estatus_pac_buscar($row, $nombre, $appat, $apmat, $pagina);
                            funciones_consultas.php  --> editar_datos_pac_buscar($row, $nombre, $appat, $apmat, $pagina);

// rev. 2019/07/12
---------------------------------------------------------------------*/
    //echo $pagina;

    echo '
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="myClass">
                <thead>
                    <tr>
                        <th data-field="id">Fecha</th>
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Institución</th>
                        <th data-field="estatus">Estatus</th>';

                        if( $_SESSION['nivel_acceso'] == 3)
                        {
                            echo'
                            <th data-field="estatus">Editar</th>
                            <!--<th data-field="re-agendar">Re-agendar</th>-->
                            ';

                        }

    echo '          </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.institucion,
                                                            t1.estatus,
                                                            

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE nombre LIKE  '%$nombre%'
                                                        AND  ap_paterno LIKE  '%$appat%'
                                                        AND  ap_materno LIKE  '%$apmat%' ");
                    
                    while ($row = $mysql->f_obj($sqltest)) {   

                        if($row->debe > '0.00'){
                            echo '<tr class ="danger">'; 
                            echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';
                            echo '<td>'.$row->estudio.'</td>';    
                            echo '<td>'.$row->institucion.'</td>';
                            mod_estatus_pac_buscar($row, $nombre, $appat, $apmat, $pagina);

                            if( $_SESSION['nivel_acceso'] == 3 ){
                                editar_datos_pac_buscar($row, $nombre, $appat, $apmat, $pagina);
                                //reagendar_pac($row, $pagina);
                            }

                            

                            echo '</tr>';
                        }
                        else{
                            if($row->institucion == 'PARTICULAR'){
                                echo '<tr class ="success" >'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';    
                                echo '<td>'.$row->institucion.'</td>';
                                mod_estatus_pac_buscar($row, $nombre, $appat, $apmat, $pagina);

                                if( $_SESSION['nivel_acceso'] == 3 )
                                {
                                    editar_datos_pac_buscar($row, $nombre, $appat, $apmat, $pagina);
                                    //reagendar_pac($row, $pagina);
                                }

                                echo '</tr>';
                            }
                            else{
                                echo '<tr>'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';    
                                echo '<td>'.$row->institucion.'</td>';
                                mod_estatus_pac_buscar($row, $nombre, $appat, $apmat, $pagina);

                                if( $_SESSION['nivel_acceso'] == 3 )
                                {
                                    editar_datos_pac_buscar($row, $nombre, $appat, $apmat, $pagina);
                                    //reagendar_pac($row, $pagina);
                                }
                                
                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>
                ';
}

function view_images($row){
/*---------------------------------------------------------------------
// Ésta función actualmente no se usa

// rev. 2019/07/12
---------------------------------------------------------------------*/
    echo'<td width="26%">
            <form id="subir_archivo'.$row->idpacientes.'" action="view_resultados_subidos.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "nombre" value="'.$row->nombre.'"/>
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>
            </form>   
        </td>
        <td>
            <button type="submit" form="subir_archivo'.$row->idpacientes.'" name="subir" value="subir" class="btn btn-success  btn-block" >
                <span class="glyphicon " aria-hidden="true"></span>
                    Ver archivos
            </button>
        </td>';
}

function upload_images($row){
/*---------------------------------------------------------------------
// Ésta función actualmente no se usa

// rev. 2019/07/12
---------------------------------------------------------------------*/
    echo'<td width="26%">
            <form id="subir_archivo'.$row->idpacientes.'" action="controlador_subir_resultados.php" method="POST" enctype="multipart/form-data">
                <!--<input type="file" form=subir_archivo'.$row->idpacientes.' name="imagen" id="imagen"  >-->
          
                <input type="file" form=subir_archivo'.$row->idpacientes.' name="imagen" id="demo3" data-toggle="fancyfile" data-text="false" class="input-medium">
                
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "nombre" value="'.$row->nombre.'"/>
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                <input type="hidden" form="subir_archivo'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>
                
            </form>   
        </td>
        <td>
            <button type="submit" form="subir_archivo'.$row->idpacientes.'" name="subir" value="subir" class="btn btn-success  btn-block" >
                <span class="glyphicon " aria-hidden="true"></span>
                    Subir
            </button>
        </td>';
}

function mod_estatus($row){
/*---------------------------------------------------------------------
// Está función se inhabilitara por que ya no se usa lo reemplaza
// mod_estatus_pac($row,  $pagina)

// rev. 2019/07/12
---------------------------------------------------------------------*/
    echo'<td>
            <form role="form" id="estatus'.$row->idpacientes.'" method="post" action="controlador_actualizar_estatus.php">
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "estatus" value="'.$row->estatus.'"/>

                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "nombre" value="'.$row->nombre.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>';
                
                if($row->estatus == 'POR ATENDER'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }
                if($row->estatus == 'ATENDIDO'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-success btn-default btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }   
                if($row->estatus == 'CANCELADO'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-danger btn-default btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }             
    echo '
            </form>
        </td>';
}

function mod_estatus_pac($row,  $pagina){
/*---------------------------------------------------------------------
Aquí se encuentra el botón que aparece al ver pacientes por dia, semana
o mes, y es el encargado de mostrar el estatus del paciente para despues
poderlo modificar (ATENDIDO, POR ATENDER Y CANCELADO).

// invoca: funciones_consultas.php --> ver_pacientes_del_dia();
//         funciones_consultas.php --> ver_pacientes_del_mes();
//         funciones_consultas.php --> editar_estudio_paciente();
//         funciones_consultas.php --> ver_pacientes_del_dia_operario();

// rev. 2019/07/12
---------------------------------------------------------------------*/
    //echo $pagina;
    echo'<td>
            <form role="form" id="estatus'.$row->idpacientes.'" method="post" action="controlador_actualizar_estatus.php">
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "estatus" value="'.$row->estatus.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>';
                
                if(!isset($row->institucion)){
                    echo '<input type="hidden" form="estatus'.$row->idpacientes.'" name = "institucion" value=" "/>';
                }
                else{
                    echo '<input type="hidden" form="estatus'.$row->idpacientes.'" name = "institucion" value="'.$row->institucion.'"/>';
                }
                
    echo'
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "nombre_paciente" value="'.$row->nombre.'"/>

                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "nombre" value="'.$row->nombre_pila.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "appat" value="'.$row->appat.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "apmat" value="'.$row->apmat.'"/>
                
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>';

                
                if($row->estatus == 'POR ATENDER'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-sm btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }
                if($row->estatus == 'ATENDIDO'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-success btn-sm btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }   
                if($row->estatus == 'CANCELADO'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-danger btn-sm btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }             
    echo '
            </form>
        </td>';
}


function mod_estatus_pac_buscar($row, $nombre, $appat, $apmat, $pagina){
/*---------------------------------------------------------------------
// Se emplea cuando despues de buscar al paciente es necesario mostrar
// la opción modificar estatus.

// invoca: funciones_consultas.php --> buscar_pacientes();

// rev. 2019/07/12
---------------------------------------------------------------------*/
    //echo $pagina;
    echo'<td>
            <form role="form" id="estatus'.$row->idpacientes.'" method="post" action="controlador_actualizar_estatus.php">
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "estatus" value="'.$row->estatus.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>';
                
                if(!isset($row->institucion)){
                    echo '<input type="hidden" form="estatus'.$row->idpacientes.'" name = "institucion" value=" "/>';
                }
                else{
                    echo '<input type="hidden" form="estatus'.$row->idpacientes.'" name = "institucion" value="'.$row->institucion.'"/>';
                }
                
    echo'
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "nombre_paciente" value="'.$row->nombre.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "nombre" value="'.$nombre.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "appat" value="'.$appat.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "apmat" value="'.$apmat.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>';

                
                if($row->estatus == 'POR ATENDER'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-sm btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }
                if($row->estatus == 'ATENDIDO'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-success btn-sm btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }   
                if($row->estatus == 'CANCELADO'){
                    echo '
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-danger btn-sm btn-block" >
                            <span class="glyphicon " aria-hidden="true"></span>
                            '.$row->estatus.'
                        </button>';
                }             
    echo '
            </form>
        </td>';
}

function mod_estatus_pac_requiere_factura($row, $pagina){
/*---------------------------------------------------------------------
Esta es una función muestra en forma de buttons si el paciente requiere
factura o no, y de acuerdo al tipo de usuario lo habilita o deshabilita  
para la edición de los datos
---------------------------------------------------------------------*/
    //echo $pagina;
    echo'<td>
            <form role="form" id="estatus_factura'.$row->idpacientes.'" method="post" action="controlador_actualizar_estatus_requiere_factura.php">
                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "estatus" value="'.$row->estatus.'"/>
                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "estatus_factura" value="'.$row->requiere_factura.'"/>
                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>

                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "nombre" value="'.$row->nombre.'"/>
                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>
            ';
                 
                if($row->requiere_factura == 'NO'){

                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=1){
                             echo '
                            <button   class="btn btn-sm btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3){
                            echo '
                            <button type="submit" form="estatus_factura'.$row->idpacientes.'" name="editar" class="btn btn-sm btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                    }
                    
                }
                if($row->requiere_factura == 'SI'){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=1){
                            echo '
                            <button  class="btn btn-danger  btn-sm btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_factura'.$row->idpacientes.'" name="editar" class="btn btn-sm btn-danger btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                    }
                } 
                if($row->requiere_factura == NULL){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=1){
                            echo '
                            <button  class="btn btn-info btn-sm btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_factura'.$row->idpacientes.'" name="editar" class="btn btn-info btn-sm btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';   
                        }
                    }
                    
                }   
                           
    echo '
           </form>
        </td>';
}

function mod_estatus_pac_facturado($row, $pagina){
/*---------------------------------------------------------------------
Esta es una función muestra en forma de buttons si el paciente ya se
encuentra facturado o no, y de acuerdo al tipo de usuario lo habilita o 
deshabilita  para la edición de los datos
---------------------------------------------------------------------*/    
    //echo $pagina;
    echo'<td>
            <form role="form" id="estatus_facturado'.$row->idpacientes.'" method="post" action="controlador_actualizar_estatus_facturado.php">
                <input type="hidden" form="estatus_facturado'.$row->idpacientes.'" name = "estatus_factura" value="'.$row->facturado.'"/>                
                <input type="hidden" form="estatus_facturado'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="estatus_facturado'.$row->idpacientes.'" name = "estatus" value="'.$row->estatus.'"/>
                <input type="hidden" form="estatus_facturado'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>

                <input type="hidden" form="estatus_facturado'.$row->idpacientes.'" name = "nombre" value="'.$row->nombre.'"/>
                <input type="hidden" form="estatus_facturado'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                <input type="hidden" form="estatus_facturado'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>';
                
                if($row->facturado == 'NO'){

                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=1){
                             echo '
                            <button   class="btn btn-sm btn-block " >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3){
                            echo '
                            <button type="submit" form="estatus_facturado'.$row->idpacientes.'" name="editar" class="btn btn-sm btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                    }
                    
                }
                if($row->facturado == 'SI'){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=1){
                            echo '
                            <button  class="btn btn-danger btn-sm btn-block " >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_facturado'.$row->idpacientes.'" name="editar" class="btn btn-danger btn-sm btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                    }
                } 
                if($row->facturado == NULL){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=1){
                            echo '
                            <button  class="btn btn-info  btn-sm btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_facturado'.$row->idpacientes.'" name="editar" class="btn btn-info btn-sm btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';   
                        }
                    }
                    
                }             
    echo '
            </form>
        </td>';
}


function editar_datos_pac($row, $pagina){

/*---------------------------------------------------------------------
// Se emplea cuando despues de ver pacientes se necesita editar datos
// del paciente.

// invoca:  funciones_consultas.php --> ver_pacientes_del_dia();
//          funciones_consultas.php --> ver_pacientes_del_mes();

// rev. 2019/07/12
---------------------------------------------------------------------*/
     echo'   <td>
                <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_editar_pacientes_por_fecha.php">
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$row->fecha.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>
                                        
                    <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </button>
                </form>
            </td>';
}

function enviar_sms($row, $pagina){

/*---------------------------------------------------------------------
// Se emplea cuando despues de ver pacientes se necesita enviar sms
// del paciente.

// invoca:  funciones_consultas.php --> ver_pacientes_mañana();


// rev. 2020/02/03
---------------------------------------------------------------------*/
     echo'   <td>
                <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_enviar_sms.php">
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$row->fecha.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>
                                        
                    <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-primary btn-default btn-block" >
                        <span class="glyphicon glyphicon glyphicon-envelope" aria-hidden="true"></span>
                    </button>
                </form>
            </td>';
}

function editar_datos_pac_buscar($row, $nombre, $appat, $apmat, $pagina){
/*---------------------------------------------------------------------
// Se emplea cuando despues de buscar al paciente es necesario mostrar
// la opción modificar editar datos del paciente.

// invoca: funciones_consultas.php --> buscar_pacientes();

// rev. 2019/07/12
---------------------------------------------------------------------*/

     echo'   <td>
                <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_editar_pacientes_por_fecha.php">
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$row->fecha.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name = "nombre" value="'.$nombre.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name = "appat" value="'.$appat.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name = "apmat" value="'.$apmat.'"/>
                                        
                    <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </button>
                </form>
            </td>';
}

function reagendar_pac($row, $pagina){
     echo'   <td>
                <form role="form" id="reagendar'.$row->idpacientes.'" method="post" action="controlador_reagendar_paciente.php">
                    <input type="hidden" form="reagendar'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>
                    <input type="hidden" form="reagendar'.$row->idpacientes.'" name="fecha_estudio" value="'.$row->fecha.'"/>
                    <input type="hidden" form="reagendar'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>
                                        
                    <button type="submit" form="reagendar'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                        <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
                    </button>
                </form>
            </td>';
}

function editar_pacientes_del_dia($fecha_act, $pagina){
    
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                                <tr>
                                    <th data-field="id">Fecha</th>
                                    <th data-field="name">Nombre de la paciente</th>
                                    <th data-field="price">Nombre del estudio</th>
                                    <th data-field="price">Hora</th>
                                    <th data-field="price">Institución</th>';
                                    
                                    if($_SESSION['nivel_acceso'] > 2){
                                        echo'
                                            <th data-field="price">Editar</th>
                                            <th data-field="price">Estatus</th>
                                            ';
                                    }
                        echo '  </tr>
                            </thead>
                            <tbody>
                            ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,
                                                            t1.hora,
                                                            t1.institucion,
                                                            t1.estatus

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {                      
                        echo '<tr>'; 
                        echo '<td>'.$row->fecha.'</td>';
                        echo '<td>'.$row->nombre.'</td>';
                        echo '<td>'.$row->estudio.'</td>';

                        $row->hora = date('g:i a ',strtotime($row->hora));

                        echo '<td>'.$row->hora.'</td>';
                        echo '<td>'.$row->institucion.'</td>';

                        if($_SESSION['nivel_acceso'] > 2){
                             echo'   <td>
                                    <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_editar_pacientes_por_fecha.php">
                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>

                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$fecha_act.'"/>
                                        
                                        <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            Editar
                                        </button>
                                    </form>
                                </td>';

                            mod_estatus_pac($row, $pagina);
                        }
                        echo '</tr>';
                    }
                    $mysql->close();
                echo'</tbody>
                </table>
    </div>            ';             
}

function editar_estudio_paciente($fecha_act,$pag){
/*---------------------------------------------------------------------
//Despliega una lista de pacientes que estan agendados en la $fecha_act.    
//posteriormente muestra datos del paciente y la opción editar para
//cambiar el estudio tanto a pacientes de institucion Particular
//como a los de institución Pública.

// invoca: viewmod_ver_editar_estudio_de_paciente.php

//Llama a otros procesos: funciones_consultas.php --> mod_estatus_pac($row, $pag);

// rev. 2019/07/12
---------------------------------------------------------------------*/

    $fecha_act = $fecha_act;
    
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                                <tr>
                                    <th data-field="id">Fecha</th>
                                    <th data-field="name">Nombre de la paciente</th>
                                    <th data-field="price">Nombre del estudio</th>
                                    <th data-field="price">Hora</th>
                                    <th data-field="price">Institución</th>';
                                    
                                    if($_SESSION['nivel_acceso'] > 2){
                                        echo'
                                            <th data-field="price">Editar</th>
                                            <th data-field="price">Estatus</th>
                                            ';
                                    }
                        echo '  </tr>
                            </thead>
                            <tbody>
                            ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,

                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,
                                                            
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,
                                                            t1.hora,
                                                            t1.institucion,
                                                            t1.estatus

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {                      
                        echo '<tr>';
                        echo '<td>'.$row->fecha.'</td>';
                        echo '<td>'.$row->nombre.'</td>';
                        echo '<td>'.$row->estudio.'</td>';

                        $row->hora = date('g:i a ',strtotime($row->hora));

                        echo '<td>'.$row->hora.'</td>';
                        echo '<td>'.$row->institucion.'</td>';

                        if($_SESSION['nivel_acceso'] > 2){
                             echo'   <td>
                                    <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_editar_estudio_de_paciente.php">
                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>

                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$fecha_act.'"/>
                                        
                                        <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            Editar
                                        </button>
                                    </form>
                                </td>';

                            mod_estatus_pac($row, $pag);
                        }
                        echo '</tr>';
                    }
                    $mysql->close();
                echo'</tbody>
                </table>
    </div>            ';             
}

function editar_institucion_a_particular($fecha_act){ 
/*---------------------------------------------------------------------
//Si un paciente que tiene subrogado de cualquier institución requiere
//pagar el  estudio por su propia cuenta y ya no hacer uso del subrogado 
//que la institucion le emitio puede hacerlo solo hay que crearle una 
//cuenta como paciente particular para poder cobrarle. 

// invoca: viewmod_ver_editar_institucion_a_particular.php

// rev. 2019/07/12
---------------------------------------------------------------------*/

    $fecha_act = $fecha_act;
    
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                                <tr>
                                    <th data-field="id">Fecha</th>
                                    <th data-field="name">Nombre de la paciente</th>
                                    <th data-field="price">Nombre del estudio</th>
                                    <th data-field="price">Hora</th>
                                    <th data-field="price">Institución</th>';
                                    
                                    if($_SESSION['nivel_acceso'] > 2){
                                        echo'
                                            <th data-field="price">Editar</th>
                                            <!--<th data-field="price">Estatus</th>-->
                                            ';
                                    }
                        echo '  </tr>
                            </thead>
                            <tbody>
                            ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,
                                                            t1.hora,
                                                            t1.institucion,
                                                            t1.estatus

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {                      
                        echo '<tr>'; 
                        echo '<td>'.$row->fecha.'</td>';
                        echo '<td>'.$row->nombre.'</td>';
                        echo '<td>'.$row->estudio.'</td>';

                        $row->hora = date('g:i a ',strtotime($row->hora));

                        echo '<td>'.$row->hora.'</td>';
                        echo '<td>'.$row->institucion.'</td>';

                        if($_SESSION['nivel_acceso'] > 2){
                             echo'   <td>
                                    <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_editar_institucion_a_particular.php">
                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>

                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$fecha_act.'"/>
                                        
                                        <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            Editar
                                        </button>
                                    </form>
                                </td>';
                        }
                        echo '</tr>';
                    }
                    $mysql->close();
                echo'</tbody>
                </table>
    </div>            ';             
}


function modificar_estudio_paciente($fecha_act){
    
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                                <tr>
                                    <th data-field="id">Fecha</th>
                                    <th data-field="name">Nombre de la paciente</th>
                                    <th data-field="price">Nombre del estudio</th>
                                    <th data-field="price">Hora</th>
                                    <th data-field="price">Institución</th>
                                    <th data-field="price">Editar</th>';
                                    
                                    if($_SESSION['nivel_acceso'] > 2){
                                        echo'
                                            
                                            <th data-field="price">Estatus</th>
                                            ';
                                    }
                        echo '  </tr>
                            </thead>
                            <tbody>
                            ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,
                                                            t1.hora,
                                                            t1.institucion,
                                                            t1.estatus

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act'  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {                      
                        echo '<tr>'; 
                        echo '<td>'.$row->fecha.'</td>';
                        echo '<td>'.$row->nombre.'</td>';
                        echo '<td>'.$row->estudio.'</td>';

                        date_default_timezone_set('America/Mexico_City');
                        
                        $row->hora = date('g:i a ',strtotime($row->hora));

                        echo '<td>'.$row->hora.'</td>';
                        echo '<td>'.$row->institucion.'</td>';

                        if($_SESSION['nivel_acceso'] == 1){
                             echo'   <td>
                                    <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_editar_modificar_estudio_paciente.php">
                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>

                                        <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$fecha_act.'"/>
                                        
                                        <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            Editar
                                        </button>
                                    </form>
                                </td>';

                            //mod_estatus($row);
                        }
                        echo '</tr>';
                    }
                    $mysql->close();
                echo'</tbody>
                </table>
    </div>            ';             
}

function ver_listas_de_precios(){
//---------------------------------------------------------------------
// Despliega todas las listas de precios que se hayan creado y las 
// muestra como botones.    

// invoca: controlador_ver_lista_precios.php

//Llama a procesos: funciones_consultas.php --> pasarMayusculas();

// rev. 2019/07/12
//---------------------------------------------------------------------
                                    
    $mysql =new mysql();
    $link = $mysql->connect(); 
                    
    $sql = $mysql->query($link,"SELECT  nombre,
                                        tipo
                                    FROM instituciones 
                                    WHERE tipo = 'PARTICULAR' AND estatus = 'ACTIVO';");

    //*************************************************
    //visualiza lista de precios instituciones PRIVADAS
    //*************************************************

    while ($row = $mysql->f_row($sql)) {

        
        $row[0] = str_replace("_", " ", $row[0]);
        
        if($row[1]=='PARTICULAR'){

                echo '
                <form role="form" id="instituciones'.$row[0].'" method="post" action="viewmod_ver_lista_precios_instituciones.php" accept-charset="UTF-8">
                    <input type="hidden" form="instituciones'.$row[0].'" name="institucion" value="'.$row[0].'"/>
                </form>';
                echo'        
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="instituciones'.$row[0].'" type="submit" value="'. pasarMayusculas($row[0]).'">
                    </div> 
                    <div class="col-md-4 col-lg-4">
                    </div>
                </div>
                </br>';
        }
    }

    //*************************************************
    //visualiza lista de precios instituciones PUBLICAS
    //*************************************************

    if($_SESSION['nivel_acceso'] <= 2){

        $link = $mysql->connect();
        $sql = $mysql->query($link,"SELECT  nombre,
                                        tipo
                                    FROM instituciones 
                                    WHERE tipo='PUBLICA' ;");

        while ($row = $mysql->f_row($sql)){
            $row[0] = str_replace("_", " ", $row[0]);
            echo '
                <form role="form" id="instituciones'.$row[0].'" method="post" action="viewmod_ver_lista_precios_instituciones.php" accept-charset="UTF-8">
                    <input type="hidden" form="instituciones'.$row[0].'" name="institucion" value="'.$row[0].'"/>
                </form>';
                echo'        
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="instituciones'.$row[0].'" type="submit" value="'. pasarMayusculas($row[0]).'">
                    </div> 
                    <div class="col-md-4 col-lg-4">
                    </div>
                </div>
                </br>';  
        }
    }

    $mysql->close(); 
}

function insertar_nueva_lista_precios($nombre_institucion, $tipo){
//---------------------------------------------------------------------
// Inserta una nueva lista de precio en la base de datos de cualquier
// institución.
//---------------------------------------------------------------------
    include_once "mysql.php";
    $nombre_institucion = strtolower($nombre_institucion);
    $nombre_institucion = str_replace(" ", "_", $nombre_institucion);
    $mysql = new mysql();
    $link = $mysql->connect(); 

    $sql = $mysql->query($link,"ALTER TABLE estudio ADD $nombre_institucion decimal(10,2) NOT NULL;");

    $link = $mysql->connect();
    $sql = $mysql->query($link,"INSERT INTO instituciones 
                                            (idinstitucion,
                                             nombre,
                                             tipo,
                                             estatus) 
                                    VALUES ('',
                                            '$nombre_institucion',
                                            '$tipo',
                                            'ACTIVO') ");
    $mysql->close();
}

function eliminar_lista_precios(){
//---------------------------------------------------------------------
// Elimina una lista de precios de la base de datos, nota: eliminación
// total sin posibilidad de recuperarla.
//---------------------------------------------------------------------
    include_once "mysql.php";

    $mysql = new mysql();
    $link = $mysql->connect(); 
    $sql = $mysql->query($link,"SELECT  nombre
                                    FROM instituciones 
                                    WHERE estatus='ACTIVO';");

    echo '<div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                <tr>
                    <th data-field="name">Institución</th>
                    <th data-field="price">Eliminar</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = $mysql->f_obj($sql)) {

        if($row->nombre != 'particular' ){
       echo'
                <tr>
                    <th data-field="name">'.pasarMayusculas($row->nombre).'</th>
                    <th>
                        <form role="form" id="edicion'.$row->nombre.'" method="post" action="viewmod_eliminar_lista_de_precio.php">
                            <input type="hidden" form="edicion'.$row->nombre.'" name="institucion" value="'.$row->nombre.'"/>
                 
                            <button type="submit" form="edicion'.$row->nombre.'" name="editar" class="btn btn-danger btn-default btn-block" >
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                Eliminar
                            </button>
                        </form>
                    </th>
                </tr>
            ';
        }
       // print_r($row); 
    }
    echo '</tbody>
    </table>';

    $mysql->close(); 

   // $sql = $mysql->query($link,"ALTER TABLE estudio ADD $nombre_institucion decimal(10,2);");
    
}

function inhabilitar_lista_precios(){
//---------------------------------------------------------------------
// Elimina una lista de precios de la base de datos, nota: eliminación
// total sin posibilidad de recuperarla.
//---------------------------------------------------------------------
    include_once "mysql.php";

    $mysql = new mysql();
    $link = $mysql->connect(); 
    $sql = $mysql->query($link,"SELECT  nombre, estatus
                                    FROM instituciones 
                                    WHERE 1;");

    echo '<div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                <tr>
                    <th data-field="institucion">Institución</th>
                    <th data-field="estado">Estado</th>
                    <th data-field="inhabilitar">Opciones</th>
                    
                </tr>
            </thead>
            <tbody>';

    while ($row = $mysql->f_obj($sql)) {

        if($row->nombre != 'particular' ){
       echo'
                <tr>
                    <th data-field="institucion">'.pasarMayusculas($row->nombre).'</th>
                    <th>'.$row->estatus.'</th>';

                    if($row->estatus == 'ACTIVO')
                    {
                        echo'<th>
                                <form role="form" id="edicion'.$row->nombre.'" method="post" action="viewmod_inhabilitar_lista_de_precios.php">
                                    <input type="hidden" form="edicion'.$row->nombre.'" name="institucion" value="'.$row->nombre.'"/>
                        
                                    <button type="submit" form="edicion'.$row->nombre.'" name="editar" class="btn btn-danger btn-default btn-block" >
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        Inhabilitar
                                    </button>
                                </form>
                            </th>';
                    }
                    else
                    {
                        echo'<th>
                                <form role="form" id="habilitar'.$row->nombre.'" method="post" action="viewmod_habilitar_lista_de_precios.php">
                                    <input type="hidden" form="habilitar'.$row->nombre.'" name="institucion" value="'.$row->nombre.'"/>
                        
                                    <button type="submit" form="habilitar'.$row->nombre.'" name="editar" class="btn btn-success btn-default btn-block" >
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        Habilitar
                                    </button>
                                </form>
                            </th>';
                    }
                    
            echo'        
                </tr>
            ';
        }
       // print_r($row); 
    }
    echo '</tbody>
    </table>';

    $mysql->close(); 

   // $sql = $mysql->query($link,"ALTER TABLE estudio ADD $nombre_institucion decimal(10,2);");
    
}

function aumentar_precio($institucion, $descuento){
//******************************************************************
//CON ESTA RUTINA SE LE AUMENTA UN PORCENTAJE A LAS LISTAS DE PRECIO
//******************************************************************
    include_once 'mysql.php';

    $mysql = new mysql();
    $link = $mysql->connect();

    $descuento = $descuento/100;
    $estado = 0;

    $sql = $mysql->query($link,"SELECT
                                    idgammagramas, 
                                    $institucion as precio
                                FROM estudio
                                WHERE idgammagramas = idgammagramas;");  


    while ($row = $mysql->f_obj($sql)) {
        
        $aumento = $row->precio * $descuento;
        $aumento = round($aumento,2);
        $nuevo_monto = $aumento + $row->precio;
        $nuevo_monto = round($nuevo_monto,2);


        $link = $mysql->connect();
        $sql2= $mysql->query($link,"    UPDATE estudio
                                SET $institucion =    $nuevo_monto
                                WHERE idgammagramas = $row->idgammagramas;");    

        $estado += mysqli_affected_rows($link);
        
    }
    echo '<h3>Cantidad de estudios modificados '.$estado.'</h3>';
//******************************************************************
}

function citar_pacientes(){
/*---------------------------------------------------------------------
Lista todas las instituciones en una tabla con forma de botones 

// invoca: controlador_citar_pacientes.php

//Llama a procesos:  pasarMayusculas();

// rev. 2019/07/12
---------------------------------------------------------------------*/
    include_once "mysql.php";
                                    
    $mysql =new mysql();
    $link = $mysql->connect(); 
                    
    $sql = $mysql->query($link,"SELECT  nombre
                                    FROM instituciones 
                                    WHERE estatus='ACTIVO';");
    
    while ($row = $mysql->f_row($sql)) {

        $row[0] = str_replace("_", " ", $row[0]); 
         
   //     if($row[0] != 'precio'){ //significa que son pacientes de institución PUBLICA o particular

            $row[0] = str_replace("_", " ", $row[0]);

            echo'<form role="form" id="'.$row[0].'" method="post" action="controlador_citar_pacientes_institucion.php" accept-charset="UTF-8">
                 </form>';
            echo '<input type="hidden" form="'.$row[0].'" name="institucion" id="institucion"  value="'.$row[0].'">';
            echo '<input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="'.$row[0].'" type="submit" value="'.pasarMayusculas($row[0]).'">';
            echo '</br>';
   //     }
      /*  else{           //significa que son pacientes de institución PARTICULAR

            $row[0] = str_replace("_", " ", $row[0]);
            echo '<form role="form" id="'.$row[0].'" method="post" action="controlador_citar_pacientes_particulares.php" accept-charset="UTF-8">
                  </form>';
            echo '<input type="hidden" form="'.$row[0].'" name="institucion" id="institucion"  value="'.$row[0].'">';
            echo '<input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="'.$row[0].'" type="submit" value="PARTICULAR">';
            echo '</br>';  
        }*/
    }

    $mysql->close();  
}

function anticipo_paciente($fecha_estudio){
/*---------------------------------------------------------------------
// De acuerdo con la fecha de cita del paciente, se despliega una tabla
// de los pacientes que hay agendaddos en ese día y a un costado de acuedo
// al estatus del paciente que puede ser PAGADO o COBRAR, el operario
// puede cobrarle al paciente sólo en el caso de que aparesca la opcion 
// COBRAR, si aparece la opción pagado significa que el cliente ya pagó
// y  no hay nada mas que hacer :) XD...

// invoca: viewmod_ver_anticipos_por_fecha.php

// rev. 2019/07/12
---------------------------------------------------------------------*/
    $fecha_estudio = $fecha_estudio;
    
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                <tr>
                    <th data-field="id">Fecha</th>
                    <th data-field="name">Nombre de la paciente</th>
                    <th data-field="price">Nombre del estudio</th>
                    <th data-field="price">Hora</th>
                    <th data-field="price">Institución</th>';
                                    
                    if($_SESSION['nivel_acceso'] > 2){
                    echo'
                        <th data-field="price">Cobrar</th>';
                    }
        echo '  </tr>
            </thead>
            <tbody>';

                    include_once "mysql.php";

                    $mysql  = new mysql();
                    $link   = $mysql->connect(); 

                    // busca a que tipo de institución pertenece
                    // ************************************************************
                    $link   = $mysql->connect();
                    $sql    = $mysql->query($link," SELECT  idpacientes, 
                                                            nombre,
                                                            institucion
                                                    FROM pacientes
                                                    WHERE fecha = '$fecha_estudio'");

                    while ($row3 = $mysql->f_obj($sql)) {
                        //$row3->idpacientes;
                        //$row3->nombre;
                        //$row3->institucion;

                        $institucion = mb_strtolower($row3->institucion, "UTF-8" );
                        $institucion = str_replace(" ", "_", $institucion);

                        $link = $mysql->connect(); 
                        $sql2 = $mysql->query($link,"SELECT nombre,
                                                            tipo
                                                            FROM instituciones
                                                            WHERE nombre = '$institucion'");
                        //$row2->nombre
                        //$row2->tipo

                        $row2 = $mysql->f_obj($sql2);
                        
                        if($row2->tipo == 'PARTICULAR'){

                            $link = $mysql->connect();
                            $sqltest = $mysql->query($link,"SELECT  t1.fecha,
                                                            t1.hora,
                                                            t1.institucion,
                                                            t1.idpacientes,
                                                            t1.estudio_idgammagramas,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            (SELECT concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                FROM estudio t2 
                                                                WHERE idgammagramas = t1.estudio_idgammagramas) as estudio,
                                                            
                                                            (SELECT t5.anticipos_idanticipos 
                                                                FROM pacientes_has_anticipos t5 
                                                                WHERE t5.pacientes_idpacientes = t1.idpacientes and t5.fecha_anticipo='0000-00-00' ) as idanticipo,
                                                            
                                                            (SELECT t4.$row2->nombre 
                                                                    FROM estudio t4 
                                                                    WHERE idgammagramas = t1.estudio_idgammagramas) AS precio 

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_estudio' AND (idpacientes = $row3->idpacientes) ORDER by hora");                
                            
                            while ($row = $mysql->f_obj($sqltest)) {  
                                echo '<tr>'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';

                                //búsqueda en pacientes_has_anticipos
                                if($_SESSION['nivel_acceso'] > 2){

                                    $total_anticipo = sumatoria_de_anticipos($row->idpacientes);
                                    $debe = $row->precio - $total_anticipo;
                                    
                                    if($debe > '0.00'){    //si no debe no se muestra
                                        echo'<td>
                                                <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_agregar_anticipos_por_fecha.php">
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name = "fecha_estudio" value="'.$fecha_estudio.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name = "idanticipo" value="'.$row->idanticipo.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name="nombre_paciente" value="'.$row->nombre.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name="estudio" value="'.$row->estudio.'"/>';
                                       
                                        echo'   <input type="hidden" form="edicion'.$row->idpacientes.'" name="precio" value="'.$row->precio.'"/>';
                                
                                
                                        echo'   <input type="hidden" form="edicion'.$row->idpacientes.'" name="hora" value="'.$row->hora.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name="debe" value="'.$debe.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name="institucion" value="'.$row->institucion.'"/>
                                        
                                                <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                                                    <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                                                    Cobrar
                                                </button>
                                                </form>
                                            </td>';
                                    }
                                    else{
                                        echo'<td>
                                                <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-success btn-default btn-block" >
                                                    <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
                                                    Pagado
                                                </button>
                                            </td>';
                                    }   
                                }
                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
                echo'</tbody>
                </table>
    </div>           
    ';                          
}

function corte_caja($fecha_estudio){
/*---------------------------------------------------------------------
// Con ésta función se muestra el corte de caja por el día que se halla
// seleccionado.

invoca: viewmod_ver_corte_caja_por_dia.php

// rev. 2019/07/12
---------------------------------------------------------------------*/
    $fecha_anticipo = $fecha_estudio;
  
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive">
            <thead>
                <tr>                
                    <th data-field="name">Nombre de la paciente</th>
                    <th data-field="price">Nombre del estudio</th>
                    <th data-field="price">Costo del estudio</th>
                    <th data-field="price">Por cobrar</th>
                    <th data-field="price">Forma de pago</th>
                    <th data-field="price">Monto cobrado</th>
                    <th data-field="price">Factura</th>
                    <th data-field="price">No. recibo</th>
                    <th data-field="price">¿Descuento?</th>
                </tr>
            </thead>
            <tbody>
    ';
        include_once "include/mysql.php";

        $mysql = new mysql();
        $link = $mysql->connect(); 

        $sqltest = $mysql->query($link,"SELECT  t1.pacientes_idpacientes as idpaciente,
                                                t1.anticipos_idanticipos as idanticipo,
                                                t1.monto_restante as debe,

                                                (SELECT concat(t2.nombre,' ',t2.ap_paterno,' ',t2.ap_materno) as nombre
                                                        FROM pacientes t2
                                                        WHERE idpacientes = t1.pacientes_idpacientes) as nombre,
                                                            
                                                (SELECT concat(t4.tipo,' ',t4.nombre) as estudio
                                                    FROM pacientes t3 inner join estudio t4
                                                    ON t4.idgammagramas = t3.estudio_idgammagramas
                                                    where idpacientes = t1.pacientes_idpacientes ) as estudio,
                                                
                                                (SELECT t8.descuento
                                                    from descuentos t8
                                                    where t8.idpaciente = t1.pacientes_idpacientes) as descuento,            

                                                t7.dep_banamex,
                                                t7.pago_santander,
                                                t7.anticipo_efe,
                                                t7.pago_cheque,
                                                t7.transferencia,
                                                t7.sr_pago,
                                                t7.factura,
                                                t7.no_recibo

                                        FROM pacientes_has_anticipos t1 inner join anticipos t7 
                                        on idanticipos = t1.anticipos_idanticipos
                                        WHERE (fecha_anticipo >= '$fecha_anticipo 00:00:00') and (fecha_anticipo<='$fecha_anticipo 23:59:59') ORDER BY t7.no_recibo ");


            
                    
            while ($row = $mysql->f_obj($sqltest)) { 

            //   busca el precio del estudio de acuerdo al tipo de 
            //   institución particular
                $link   = $mysql->connect();
                $sql    = $mysql->query($link,"SELECT   institucion,
                                                        estudio_idgammagramas
                                                    FROM pacientes
                                                    WHERE idpacientes = $row->idpaciente ;");

                $row2  = $mysql->f_obj($sql);

                $institucion = mb_strtolower($row2->institucion, "UTF-8" );
                $institucion = str_replace(" ", "_", $institucion);

                $link = $mysql->connect(); 
                $sql2 = $mysql->query($link,"SELECT nombre
                                                FROM instituciones
                                                WHERE nombre = '$institucion'");
                        
                $row3 =  $mysql->f_obj($sql2);

                $link = $mysql->connect(); 
                $sql3 = $mysql->query($link,"SELECT $row3->nombre as precio
                                                FROM  estudio 
                                                WHERE idgammagramas = $row2->estudio_idgammagramas");
                $row4 =  $mysql->f_obj($sql3);

                $row->precio = $row4->precio;

                if($row->descuento > 0){
                    $descuento_set = ' '.$row->descuento.' %';
                }
                else{
                    $descuento_set = ' '.$row->descuento.'0 %';  //0% de descuento
                } 

                if($row->descuento == 100){  //si es un 100% de descuento
                    $colum_set = 'Ninguno';
                    $monto_anticipo = $row->anticipo_efe;
                }
                else{

                    if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->sr_pago == '0.00'){
                        $colum_set = 'Efectivo';
                        $monto_anticipo = $row->anticipo_efe;} //es efectivo

                    if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                        $colum_set = 'Transferencia';
                        $monto_anticipo = $row->transferencia;} //transferencia

                    if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                        $colum_set = 'Pago con cheque';
                        $monto_anticipo = $row->pago_cheque;} //pago cheque

                    if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                        $colum_set = 'Pago santander';
                        $monto_anticipo = $row->pago_santander;} //pago santander

                    if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'  && $row->sr_pago == '0.00'){
                        $colum_set = 'Pago banamex';
                        $monto_anticipo = $row->dep_banamex;} //pago banamex
                    
                    if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->anticipo_efe == '0.00'){
                        $colum_set = 'Sr pago';
                        $monto_anticipo = $row->sr_pago;} //es efectivo
                    }

                    echo '<tr>'; 
                        
                    echo '<td>'.$row->nombre.'</td>';
                    echo '<td>'.$row->estudio.'</td>';

                    $precio         =number_format($row->precio,2);
                    $debe           =number_format($row->debe,2);
                    $monto_anticipo = number_format($monto_anticipo,2);

                    echo '<td> $ '.$precio.'</td>';
                    //por pagar
                    echo '<td> $ '.$debe.'</td>';
                    //método de pago
                    echo '<td> '.$colum_set.'</td>';
                    echo '<td>$ '.$monto_anticipo.'</td>';
                    echo '<td> '.$row->factura.'</td>';
                    echo '<td> '.$row->no_recibo.'</td>';
                    echo '<td> '.$descuento_set.'</td>';
                    echo '</tr>';
            }

        
                    echo'   </tbody>
                                </table>
                            </div>            
                            '; 
                    //$mysql->close();
                    echo '  
                                <form role="form" id="edicion_imprimir" method="post" action="view_imprimir_corte_caja_por_dia.php" target="_blank">
                                    <input type="hidden" form="edicion_imprimir" name="fecha_corte" value="'.$fecha_estudio.'">
                        
                                    <button type="submit" form="edicion_imprimir" name="editar" class="btn btn-primary btn-default btn-block" >
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                        Imprimir
                                    </button>
                                </form>';             
}
function corte_caja_mensual($fecha_estudio){
/*---------------------------------------------------------------------
// Con ésta función se muestra el corte de caja mensual el operario puede 
// seleccionar cualquier día del mes que necesite y la rutina va hacer el 
// corte de todo el mes seleccionado.

// invoca: viewmod_ver_corte_caja_por_mes.php

// rev. 2019/07/12
---------------------------------------------------------------------*/ 
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive" id="myClass">
            <thead>
                <tr>
                    <th data-field="name">Fecha</th>                
                    <th data-field="name">Nombre del paciente</th>
                    <th data-field="price">Estudio</th>
                    <th data-field="price">Costo del estudio</th>
                    <th data-field="price">Por cobrar</th>
                    <th data-field="price">Forma de pago</th>
                    <th data-field="price">Monto cobrado</th>
                    <th data-field="price">Información Anticipos</th>
                    <th data-field="price">No. recibo</th>
                    <th data-field="price">¿Descuento?</th>
                </tr>
            </thead>
            <tbody>
    ';

                    include_once "mysql.php";

                    $fecha_ini = first_month_day($fecha_estudio);
                    $fecha_fin = last_month_day($fecha_estudio);

                    $fecha_ini = $fecha_ini.' 00:00:00';
                    $fecha_fin = $fecha_fin.' 23:59:59';

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.pacientes_idpacientes as idpaciente,
                                                            t1.anticipos_idanticipos as idanticipo,
                                                            t1.fecha_anticipo as fecha_anticipo,
                                                            t1.monto_restante as debe,

                                                            (SELECT concat(t2.nombre,' ',t2.ap_paterno,' ',t2.ap_materno) as nombre
                                                                FROM pacientes t2
                                                                WHERE idpacientes = t1.pacientes_idpacientes) as nombre,
                                                            
                                                            (SELECT concat(t4.tipo,' ',t4.nombre) as estudio
                                                                FROM pacientes t3 inner join estudio t4 
                                                                    ON t4.idgammagramas = t3.estudio_idgammagramas
                                                                where idpacientes = t1.pacientes_idpacientes ) as estudio,

                                                            (SELECT t8.descuento
                                                                from descuentos t8
                                                                where t8.idpaciente = t1.pacientes_idpacientes) as descuento,

                                                            t7.dep_banamex,
                                                            t7.pago_santander,
                                                            t7.anticipo_efe,
                                                            t7.transferencia,
                                                            t7.pago_cheque,
                                                            t7.sr_pago,
                                                            t7.factura,
                                                            t7.no_recibo

                                                    FROM pacientes_has_anticipos t1 inner join anticipos t7 
                                                        on idanticipos = t1.anticipos_idanticipos
                                                    WHERE 
                                                        fecha_anticipo >= '$fecha_ini' AND fecha_anticipo <= '$fecha_fin'
                                                        ORDER BY t7.no_recibo; ");
                    $cont = 0;
                    
                    while ($row = $mysql->f_obj($sqltest)) { 
                        //   busca el precio del estudio de acuerdo al tipo de 
                        //   institución particular
                        $link   = $mysql->connect();
                        $sql    = $mysql->query($link,"SELECT institucion,
                                                              estudio_idgammagramas
                                                        FROM pacientes
                                                        WHERE idpacientes = $row->idpaciente ;");

                        $row2  = $mysql->f_obj($sql);

                        $institucion = mb_strtolower($row2->institucion, "UTF-8" );
                        $institucion = str_replace(" ", "_", $institucion);

                        $link = $mysql->connect(); 
                        $sql2 = $mysql->query($link,"SELECT nombre
                                                            FROM instituciones
                                                            WHERE nombre = '$institucion'");
                        $row3 =  $mysql->f_obj($sql2);

                        $link = $mysql->connect(); 
                        $sql3 = $mysql->query($link,"SELECT $row3->nombre as precio
                                                                from  estudio 
                                                                where idgammagramas = $row2->estudio_idgammagramas");
                        $row4 =  $mysql->f_obj($sql3);

                        $row->precio = $row4->precio;

                        // fin búsqueda precio de acuerdo a la institucion.

                        if($row->descuento > 0){
                            $descuento_set = ' '.$row->descuento.' %';
                        }else{
                            $descuento_set = ' '.$row->descuento.'0 %';  //0% de descuento
                        } 

                        if($row->descuento == 100){  //si es un 100% de descuento
                            $colum_set = 'Ninguno';
                            $monto_anticipo = $row->anticipo_efe;
                        }
                        else{

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Efectivo';
                                $monto_anticipo = $row->anticipo_efe;} //es efectivo
        
                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Transferencia';
                                $monto_anticipo = $row->transferencia;} //transferencia
        
                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Pago con cheque';
                                $monto_anticipo = $row->pago_cheque;} //pago cheque
        
                            if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Pago santander';
                                $monto_anticipo = $row->pago_santander;} //pago santander
        
                            if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'  && $row->sr_pago == '0.00'){
                                $colum_set = 'Pago banamex';
                                $monto_anticipo = $row->dep_banamex;} //pago banamex
                            
                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->anticipo_efe == '0.00'){
                                $colum_set = 'Sr pago';
                                $monto_anticipo = $row->sr_pago;} //es efectivo
                        }
                        
                        date_default_timezone_set('America/Mexico_City');
			            $row->fecha_anticipo = date('d-m-Y', strtotime($row->fecha_anticipo));

                        echo '<tr>'; 
                        echo '<td>'.$row->fecha_anticipo.'</td>';
                        echo '<td>'.$row->nombre.'</td>';
                        echo '<td>'.$row->estudio.'</td>';

                        $precio         = number_format($row->precio,2);
                        $debe           = number_format($row->debe,2);
                        $monto_anticipo = number_format($monto_anticipo,2);

                        echo '<td> $ '.$precio.'</td>';
                        //por pagar
                        echo '<td> $ '.$debe.'</td>';
                        //método de pago
                        echo '<td> '.$colum_set.'</td>';
                        echo '<td>$ '.$monto_anticipo.'</td>';
                        echo '<td> 
                                    <form role="form" id="edicion'.$row->idpaciente.'" method="post" action="corte_caja_detalle_anticipos.php">
                                        <input type="hidden" form="edicion'.$row->idpaciente.'" name = "idpaciente" value="'.$row->idpaciente.'"/>
                                        <input type="hidden" form="edicion'.$row->idpaciente.'" name = "nombre_paciente" value="'.$row->nombre.'"/>
                                        <input type="hidden" form="edicion'.$row->idpaciente.'" name = "estudio" value="'.$row->estudio.'"/>
                                        <input type="hidden" form="edicion'.$row->idpaciente.'" name = "precio" value="'.$row->precio.'"/>
                                        <input type="hidden" form="edicion'.$row->idpaciente.'" name = "debe" value="'.$debe.'"/>
                                        
                                        <input type="hidden" form="edicion'.$row->idpaciente.'" name = "fecha_estudio" value="'.$fecha_estudio.'"/>

                                        <button type="submit" form="edicion'.$row->idpaciente.'" name="editar" class="btn btn-info btn-default btn-block" >
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                            Detalle
                                        </button>
                                    </form>
                                </td>';
                        echo '<td> '.$row->no_recibo.'</td>';
                        echo '<td> '.$descuento_set.'</td>';
                        echo '</tr>';

                    } //FIN WHILE
                    $mysql->close();
        echo'   </tbody>
            </table>
            </div>            
        '; 
                echo '
                    <form role="form" id="edicion_imprimir" method="post" action="view_imprimir_corte_caja_por_mes.php" target="_blank">
                        <input type="hidden" form="edicion_imprimir" name="fecha_corte" value="'.$fecha_estudio.'">
                        
                        <button type="submit" form="edicion_imprimir" name="editar" class="btn btn-primary btn-default btn-block" >
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                            Imprimir
                        </button>
                    </form>';
}

function reimprimir_recibos($fecha_estudio){
//agregado el 2016_03_22
/*---------------------------------------------------------------------
// Con ésta función se pueden reimprimir los recibos hechos anteriormente
// al haber realizado un cobro

// invoca: viewmod_reimprimir_recibo.php

//Llama a procesos: funciones_consultas --> first_month_day($fecha_estudio);
                    funciones_consultas --> last_month_day($fecha_estudio);

// rev. 2019/07/12
---------------------------------------------------------------------*/
    
    echo '
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-responsive" id="myClass">
            <thead>
                <tr>
                    <th data-field ="fecha_anticipo">Fecha anticipo</th>                
                    <th data-field ="nombre">Nombre de la paciente</th>
                    <th data-field ="estudio">Nombre del estudio</th>
                    <th data-field ="forma_de_pago">Forma de pago</th>
                    <th data-field ="monto_cobrado">Monto cobrado</th>
                    <th data-field ="no_recibo">No. recibo</th>
                    <th data-field ="imprimir">Imprimir</th>
                </tr>
            </thead>
            <tbody>
    ';

                    include_once "mysql.php";

                    $fecha_ini = first_month_day($fecha_estudio);
                    $fecha_fin = last_month_day($fecha_estudio);
                    
                    $fecha_ini = $fecha_ini.' 00:00:00';
                    $fecha_fin = $fecha_fin.' 23:59:59';

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.pacientes_idpacientes as idpaciente,
                                                            t1.fecha_anticipo as fecha_anticipo,
                                                            t1.anticipos_idanticipos as idanticipo,
                                                            t1.monto_restante as debe,

                                                            (SELECT concat(t2.nombre,' ',t2.ap_paterno,' ',t2.ap_materno) as nombre
                                                                FROM pacientes t2
                                                                WHERE idpacientes = t1.pacientes_idpacientes) as nombre,
                                                            
                                                            (SELECT concat(t4.tipo,' ',t4.nombre) as estudio
                                                                FROM pacientes t3 inner join estudio t4 
                                                                    ON t4.idgammagramas = t3.estudio_idgammagramas
                                                                where idpacientes = t1.pacientes_idpacientes ) as estudio,

                                                            (SELECT t8.descuento
                                                                from descuentos t8
                                                                where t8.idpaciente = t1.pacientes_idpacientes) as descuento,

                                                            t7.dep_banamex,
                                                            t7.pago_santander,
                                                            t7.anticipo_efe,
                                                            t7.transferencia,
                                                            t7.pago_cheque,
                                                            t7.sr_pago,
                                                            t7.factura,
                                                            t7.no_recibo

                                                    FROM pacientes_has_anticipos t1 inner join anticipos t7 
                                                        on idanticipos = t1.anticipos_idanticipos
                                                    WHERE 
                                                        fecha_anticipo >= '$fecha_ini' AND fecha_anticipo <= '$fecha_fin'
                                                        ORDER BY t7.no_recibo; ");
                    $cont = 0;
                    
                    while ($row = $mysql->f_obj($sqltest)) { 
                        //   busca el precio del estudio de acuerdo al tipo de 
                        //   institución particular
                        $link   = $mysql->connect();
                        $sql    = $mysql->query($link,"SELECT institucion,
                                                              estudio_idgammagramas
                                                        FROM pacientes
                                                        WHERE idpacientes = $row->idpaciente ;");

                        $row2  = $mysql->f_obj($sql);

                        $institucion = mb_strtolower($row2->institucion, "UTF-8" );
                        $institucion = str_replace(" ", "_", $institucion);

                        $link = $mysql->connect(); 
                        $sql2 = $mysql->query($link,"SELECT nombre
                                                            FROM instituciones
                                                            WHERE nombre = '$institucion'");
                        $row3 =  $mysql->f_obj($sql2);

                        $link = $mysql->connect(); 
                        $sql3 = $mysql->query($link,"SELECT $row3->nombre as precio
                                                                from  estudio 
                                                                where idgammagramas = $row2->estudio_idgammagramas");
                        $row4 =  $mysql->f_obj($sql3);

                        $row->precio = $row4->precio;

                        // fin búsqueda precio de acuerdo a la institucion.

                        if($row->descuento > 0){
                            $descuento_set = ' '.$row->descuento.' %';
                        }else{
                            $descuento_set = ' '.$row->descuento.'0 %';  //0% de descuento
                        } 

                        if($row->descuento == 100){  //si es un 100% de descuento
                            $colum_set = 'Ninguno';
                            $monto_anticipo = $row->anticipo_efe;
                        }
                        else{

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Efectivo';
                                $monto_anticipo = $row->anticipo_efe;} //es efectivo

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Transferencia';
                                $monto_anticipo = $row->transferencia;} //transferencia

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Pago con cheque';
                                $monto_anticipo = $row->pago_cheque;} //pago cheque

                            if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Pago santander';
                                $monto_anticipo = $row->pago_santander;} //pago santander

                            if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
                                $colum_set = 'Pago banamex';
                                $monto_anticipo = $row->dep_banamex;}

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->anticipo_efe == '0.00'){
                                $colum_set = 'Sr. Pago';
                                $monto_anticipo = $row->sr_pago;} //es sr pago
                        }

                        $fecha = date('d-m-Y', strtotime($row->fecha_anticipo));

                        echo '<tr>';                         
                        echo '<td>'.$fecha.'</td>';
                        echo '<td>'.$row->nombre.'</td>';
                        echo '<td>'.$row->estudio.'</td>';

                        $precio         = number_format($row->precio,2);
                        $debe           = number_format($row->debe,2);
                        
                        $monto_anticipo_format = number_format($monto_anticipo,2);

                        //echo '<td> $ '.$precio.'</td>';
                        //por pagar
                        //echo '<td> $ '.$debe.'</td>';
                        //método de pago
                        echo '<td> '.$colum_set.'</td>';
                        echo '<td>$ '.$monto_anticipo_format.'</td>';
                        echo '<td> '.$row->no_recibo.'</td>';
                        echo '<td>
                                <form role="form" id="reimprimir'.$row->idanticipo.'" method="post" action="view_reimprimir_recibo_anticipo.php" target="_blank">
                                    <input type="hidden" form="reimprimir'.$row->idanticipo.'" name="fecha_corte" value="'.$fecha_estudio.'">
                                    <input type="hidden" form="reimprimir'.$row->idanticipo.'" name="idpaciente" value="'.$row->idpaciente.'">
                                    <input type="hidden" form="reimprimir'.$row->idanticipo.'" name="nombre_paciente" value="'.$row->nombre.'">
                                    <input type="hidden" form="reimprimir'.$row->idanticipo.'" name="estudio" value="'.$row->estudio.'">
                                    <input type="hidden" form="reimprimir'.$row->idanticipo.'" name="monto_anticipo" value="'.$monto_anticipo.'">
                                    <input type="hidden" form="reimprimir'.$row->idanticipo.'" name="fecha_anticipo" value="'.$fecha.'">
                                    <input type="hidden" form="reimprimir'.$row->idanticipo.'" name="id_anticipo" value="'.$row->idanticipo.'">
                        
                                    <button type="submit" form="reimprimir'.$row->idanticipo.'" name="editar" class="btn btn-primary btn-default btn-block" >
                                        <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                        Imprimir
                                    </button>
                                </form></td>';
                        
                        echo '</tr>';



                    } //FIN WHILE


                    $mysql->close();
        echo'   </tbody>
            </table>
            </div>            
        '; 
               /* echo '
                    <form role="form" id="edicion_imprimir" method="post" action="view_imprimir_corte_caja_por_mes.php" target="_blank">
                        <input type="hidden" form="edicion_imprimir" name="fecha_corte" value="'.$fecha_estudio.'">
                        
                        <button type="submit" form="edicion_imprimir" name="editar" class="btn btn-primary btn-default btn-block" >
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                            Imprimir
                        </button>
                    </form>';*/
}

function ver_anticipos_anteriores($idpaciente){
/*---------------------------------------------------------------------
// La rutina permite obtener todos los anticipos que haya hecho el paciente
// y mostrarlos en una tabla (fecha_anticipo, forma de pago, monto pagado,
// y si requiere factura).

invoca: controlador_agregar_anticipos_por_fecha.php

// rev. 2019/07/12
---------------------------------------------------------------------*/   
    include_once "include/mysql.php";

    $mysql = new mysql();
    $link = $mysql->connect();

    $sql2 = $mysql->query($link,"SELECT anticipos_idanticipos,
                                        fecha_anticipo
                                FROM pacientes_has_anticipos
                                WHERE pacientes_idpacientes = $idpaciente 
                                AND fecha_anticipo != '0000-00-00'");

    echo'   <div class="panel panel-primary">
                <div class="panel-heading">Anticipos anteriores</div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td align="center">Fecha anticipo</td>
                                <td align="center">Forma de pago</td>
                                <td align="center">Monto Anticipo</td>
                                <td align="center">Requiere factura</td>
                            </tr>';
                                                    
    while( $row2 = $mysql->f_obj($sql2) ){
                                                        
        $idanticipo_pac     = $row2->anticipos_idanticipos;

        date_default_timezone_set("America/Mexico_City");
        $fecha_anticipo_pac =date("d-m-Y",strtotime( $row2->fecha_anticipo ));

        $sql =  $mysql->query($link,"SELECT dep_banamex,
                                            pago_santander,
                                            pago_cheque,
                                            transferencia,
                                            anticipo_efe,
                                            sr_pago,
                                            no_recibo,
                                            factura
                                    FROM anticipos
                                    WHERE idanticipos = $idanticipo_pac");
                                                    
        $row = $mysql->f_obj($sql);
                                                        
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Efectivo';
            $monto_anticipo = $row->anticipo_efe;} //es efectivo

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Transferencia';
            $monto_anticipo = $row->transferencia;} //transferencia

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago con cheque';
            $monto_anticipo = $row->pago_cheque;} //pago cheque

        if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago santander';
            $monto_anticipo = $row->pago_santander;} //pago santander

        if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago banamex';        //pago banamex
            $monto_anticipo = $row->dep_banamex;}
        
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Sr. Pago';
            $monto_anticipo = $row->sr_pago;} //es sr. pago
                                                
        $monto_anticipo     =   number_format($monto_anticipo,2);

        echo '
                            <tr>
                                <td align="center">'.$fecha_anticipo_pac.'</td>
                                <td>'.$colum_set.'</td>
                                <td align="center">$ '.$monto_anticipo.'</td>
                                <td align="center"> '.$row->factura.'</td>
                            </tr>';
    }  
    echo'               </table>
                    </div>
                </div>'; 
}

function ver_anticipos_anteriores_administrador($idpaciente){
/*---------------------------------------------------------------------
// La rutina permite obtener todos los anticipos que haya hecho el paciente
// y mostrarlos en una tabla (fecha_anticipo, forma de pago, monto pagado,
// y si requiere factura).
// Se emplea la rutina en viewmod_ver_corte_caja_por_mes.php y nos permite
// visualizar el DETALLE de los pagos hecho de un paciente, no importando 
// si hizo un pago en agosto y otro en diciembre, la rutina mostrara todos
// los pagos realizados

// invoca: corte_caja_detalle_anticipos.php

// rev. 2019/07/12
---------------------------------------------------------------------*/  
    include_once "include/mysql.php";

    $mysql = new mysql();
    $link = $mysql->connect();

    $sql2 = $mysql->query($link,"SELECT anticipos_idanticipos,
                                        fecha_anticipo
                                FROM pacientes_has_anticipos
                                WHERE pacientes_idpacientes = $idpaciente 
                                AND fecha_anticipo != '0000-00-00 00:00:00'");

    echo'   <div class="panel panel-primary">
                <div class="panel-heading">Anticipos anteriores</div>
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td align="center">Fecha anticipo</td>
                                <td align="center">Forma de pago</td>
                                <td align="center">Monto Anticipo</td>
                                <td align="center">Requiere factura</td>
                                <td align="center">No. de recibo</td>
                            </tr>';
                                                    
    while( $row2 = $mysql->f_obj($sql2) ){
                                                        
        $idanticipo_pac     = $row2->anticipos_idanticipos;
        
        date_default_timezone_set("America/Mexico_City");

        //  $row2->fecha_anticipo;   ----------devuelve 2018-09-27 00:00:00------------
        
        $fecha_anticipo_pac =date("d-m-Y",strtotime( $row2->fecha_anticipo ));

        //--------devuelve 12:00:00 cuando es 00:00:00--------

        $hora_anticipo = date("h:i:s",strtotime( $row2->fecha_anticipo )); 
         
        if($hora_anticipo == '12:00:00'){
            $hora_anticipo = '';   //por motivo de acutalización hay pacientes que no se puede registrar esos datos
        }

        $sql =  $mysql->query($link,"SELECT dep_banamex,
                                            pago_santander,
                                            pago_cheque,
                                            transferencia,
                                            anticipo_efe,
                                            sr_pago,
                                            no_recibo,
                                            factura
                                    FROM anticipos
                                    WHERE idanticipos = $idanticipo_pac");
                                                    
        $row = $mysql->f_obj($sql);
                                                        
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Efectivo';
            $monto_anticipo = $row->anticipo_efe;} //es efectivo

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Transferencia';
            $monto_anticipo = $row->transferencia;} //transferencia

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago con cheque';
            $monto_anticipo = $row->pago_cheque;} //pago cheque

        if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago santander';
            $monto_anticipo = $row->pago_santander;} //pago santander

        if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago banamex';        //pago banamex
            $monto_anticipo = $row->dep_banamex;}
        
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Sr. Pago';
            $monto_anticipo = $row->sr_pago;} //es sr pago
                                                
        $monto_anticipo     =   number_format($monto_anticipo,2);

        echo '
                            <tr>
                                <td align="center">'.$fecha_anticipo_pac.'  /  '.$hora_anticipo.'</td>
                                <td>'.$colum_set.'</td>
                                <td align="center">$ '.$monto_anticipo.'</td>
                                <td align="center"> '.$row->factura.'</td>
                                <td align="center"> '.$row->no_recibo.'</td>
                            </tr>';
    }  
    echo'               </table>
                    </div>
                </div>'; 
}

function sumatoria_de_anticipos($idpaciente){
/*---------------------------------------------------------------------
// Consulta todos los anticipos que tiene el paciente y los suma para 
// después retornarlos.

// invoca: viewmod_editar_estudio_de_paciente.php

// rev. 2019/07/17
---------------------------------------------------------------------*/
    
    include_once "mysql.php";

    $mysql = new mysql();
    $link = $mysql->connect();

    $sql2 = $mysql->query($link,"SELECT anticipos_idanticipos as idanticipo
                                FROM pacientes_has_anticipos
                                WHERE pacientes_idpacientes = $idpaciente 
                                AND fecha_anticipo != '0000-00-00'");
     
    $sumatoria_anticipos = 0 ;  

    while( $row2 = $mysql->f_obj($sql2) ){
                                                        
        $idanticipo     = $row2->idanticipo;

        $sql =  $mysql->query($link,"SELECT dep_banamex,
                                            pago_santander,
                                            pago_cheque,
                                            transferencia,
                                            anticipo_efe,
                                            sr_pago,
                                            no_recibo
                                    FROM anticipos
                                    WHERE idanticipos = $idanticipo");
                                                    
        $row = $mysql->f_obj($sql);
                                                        
        /*señor pago*/
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Efectivo';
            $monto_anticipo = $row->anticipo_efe;} //es efectivo

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Transferencia';
            $monto_anticipo = $row->transferencia;} //transferencia

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago con cheque';
            $monto_anticipo = $row->pago_cheque;} //pago cheque

        if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00' && $row->sr_pago == '0.00'){
            $colum_set = 'Pago santander';
            $monto_anticipo = $row->pago_santander;} //pago santander

        if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'  && $row->sr_pago == '0.00'){
            $colum_set = 'Pago banamex';
            $monto_anticipo = $row->dep_banamex;} //pago banamex
        
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Sr pago';
            $monto_anticipo = $row->sr_pago;} //es efectivo
        /**********/
                                                
        $sumatoria_anticipos += $monto_anticipo;
    }
    $mysql->close();
    return $sumatoria_anticipos;   
}

function ver_tratamientos_del_dia($fecha_act){
/*---------------------------------------------------------------------
// Devuelve una tabla en la que apartir de la $fecha_act busca su hay pacientes
// que tienen tratamientos so los encuentra entonces procede a mostrarlos.
// Una vez mostrados se visualiza el botón para imprimier las hojas de tratamientos
// por pacientes de acuerdo con la dosis suministradas seran los ciudados que 
// tiene que tener el paciente.

// invoca: viewmod_imprimir_hoja_tratamientos.php.php

// rev. 2019/07/12
---------------------------------------------------------------------*/
    echo '
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Imprimir hoja de tratamiento</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <!--<th data-field="id">Fecha</th>-->
                                <th data-field="name">Nombre de la paciente</th>
                                <th data-field="price">Nombre del estudio</th>
                                <th data-field="price">Hora</th>
                                <th data-field="price">Institución</th>
                                <th data-field="price">Médico tratante</th>
                                <th data-field="price">Teléfono</th>
                                <th data-field="price">Teléfono</th>
                                <th data-field="estatus">Estatus</th>
                                <!-- <th data-field="estatus">Editar</th>-->
                            </tr>
                        </thead>
                        <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,
                                                            t1.institucion,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE fecha = '$fecha_act' AND indicaciones_tratamiento != ''  ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {  

                            echo '<tr>'; 
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }

                            $row->hora = date('g:i a ',strtotime($row->hora));

                            echo '<td>'.$row->hora.'</td>';
                            echo '<td>'.$row->institucion.'</td>';
                            echo '<td>'.$row->medico.'</td>';
                            echo '<td>'.$row->num_tel.'</td>';
                            echo '<td>'.$row->num_tel2.'</td>';
                            mod_imprimir($row);
                            //editar_datos_pac($row);
                            echo '</tr>';
                    }

                    $mysql->close();

                    echo'</tbody>
                    </table>
                </div>
            </div>
        </div>
                ';               
}

function ver_clientes(){
    echo '
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Ver lista de clientes</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                
                                <th data-field="name">Razón social</th>
                                <th data-field="price">R.F.C.</th>
                                <th data-field="price">Domicilio</th>
                                <th data-field="price">Nombre comercial</th>
                            </tr>
                        </thead>
                        <tbody>
        ';
                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect();

                    $sqltest = $mysql->query($link,"SELECT * from clientes where 1");

                    while ($row = $mysql->f_obj($sqltest)) {  

                            echo '<tr>'; 
                            echo '<td>'.$row->razon_social.'</td>';
                            echo '<td>'.$row->rfc.'</td>';
                            echo '<td>'.$row->calle.' NO. '.
                                        $row->numero.' COLONIA '.
                                        $row->colonia.' C.P. '.
                                        $row->cp.' '.
                                        $row->municipio.', '.
                                        $row->estado.' '.
                                        $row->pais.'</td>';
                            echo '<td>'.$row->nombre_comercial.'</td>';
                            echo '</tr>';
                    }
                    $mysql->close();

                    echo'</tbody>
                    </table>
                </div>
            </div>
        </div>
                ';  
}

function mod_imprimir($row){
    echo'<td>
            <form role="form" id="estatus'.$row->idpacientes.'" method="post" action="view_imprimir_hoja_tratamiento.php" target="_blank">
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "cantidad_i131" value="'.$row->indicaciones_tratamiento.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "nombre_paciente" value="'.$row->nombre.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "nombre_medico" value="'.$row->medico.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "institucion" value="'.$row->institucion.'"/>';
                
                echo'
                        <button type="submit" form="estatus'.$row->idpacientes.'" name="editar" class="btn btn-primary  btn-block" >
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                            Imprimir
                        </button>';
                
                           
    echo '
            </form>
        </td>';
}

function ver_pacientes_del_dia_factura($fecha_act, $pagina){

/*---------------------------------------------------------------------
//Permite ver a los pacientes que requieren factura asi como dar opciones
para modificar el estatus del mismo, ademas de que 

---------------------------------------------------------------------*/
    echo '
        <div class ="table-responsive">
            <table class ="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <!--<th data-field="id">Fecha</th>-->
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Institución</th>
                        <th data-field="estatus">Estatus</th>
                        <th data-field="estatus">¿Requiere factura?</th>
                        <th data-field="estatus">¿Está facturado?</th>
                        
                    </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT   distinct t1.idpacientes,
                                                            t1.fecha,
                                                            t5.pacientes_idpacientes,
                                                            t1.indicaciones_tratamiento,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
                                                            
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.institucion,
                                                            t1.estatus,
                                                        
                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe,
                                                            
                                                            (SELECT t6.requiere_factura
                                                                FROM facturas t6
                                                                WHERE t6.idpaciente =t1.idpacientes)as requiere_factura,
                                                            
                                                            (SELECT t6.facturado
                                                                FROM facturas t6
                                                                WHERE t6.idpaciente =t1.idpacientes)as facturado
                                                             

                                                    FROM pacientes t1 inner join pacientes_has_anticipos t5
                                                    WHERE fecha = '$fecha_act'  and t5.pacientes_idpacientes= t1.idpacientes ORDER BY hora");
                    
                    while ($row = $mysql->f_obj($sqltest)) {   
                        if($row->debe > '0.00'){            //pacientes que deben
                            echo '<tr class ="danger">'; 
                            //echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }
                    
                            echo '<td>'.$row->institucion.'</td>';

                            mod_estatus_pac($row, $pagina);

                            mod_estatus_pac_requiere_factura($row, $pagina);

                            mod_estatus_pac_facturado($row, $pagina);

                            //editar_datos_pac($row);
                            echo '</tr>';
                        }
                        else{
                            // busca a que tipo de institución pertenece
                            //ayuda a marcar de verde si ya pago de lo contrario no lo 
                            //sombrea a la hora de hacer la búsqueda.
                            // ************************************************************
                            $institucion = mb_strtolower($row->institucion, "UTF-8" );
                            $institucion = str_replace(" ", "_", $institucion);
                            //echo $institucion;

                            $link = $mysql->connect(); 
                            $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                FROM instituciones t5 
                                                                WHERE t5.nombre = '$institucion' and estatus = 'ACTIVO'");
     
                            $row2 = $mysql->f_obj($sqltest2);

                            
                            // ************************************************************
                            if($row2->tipo == 'PARTICULAR' ){  //pacientes que ya no deben
                                echo '<tr class ="success" >'; 
                                //echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                                            

                                echo '<td>'.$row->institucion.'</td>';
                                
                                mod_estatus_pac($row, $pagina);                           
                                mod_estatus_pac_requiere_factura($row, $pagina);
                                mod_estatus_pac_facturado($row, $pagina);
                                
                                
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                            else{
                                echo '<tr>';                            //pacientes de instituciones públicas
                                //echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                
                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->institucion.'</td>';
                                mod_estatus_pac($row, $pagina);                           
                                mod_estatus_pac_requiere_factura($row, $pagina);
                                mod_estatus_pac_facturado($row, $pagina);
                                
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>
                ';               
}

function ver_pacientes_del_mes_factura($fecha_ini, $fecha_fin, $pagina){
/*---------------------------------------------------------------------
Muestra todos los pacientes particulares, en el mes especificado y permite
modificar el estatus de (requiere factura y está facturado?)
una factura, ademas de agregar el numero de factura 
---------------------------------------------------------------------*/   
    echo '
        <div class="table-responsive">
            <table class="table  table-hover table-striped" id="myclass">
                <thead>
                    <tr>
                        <th data-field="id">Fecha de estudio</th>
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Institución</th>
                        <th data-field="price">¿Requiere factura?</th>
                        <th data-field="price">¿Está facturado?</th>
                        <th data-field="estatus">Estatus</th>
                    </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT   distinct t1.idpacientes,
                                                            t5.pacientes_idpacientes,
                                                            t1.fecha,
                                                            t1.institucion,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,

                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,

                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe,
                                                            
                                                            (SELECT t6.requiere_factura
                                                                FROM facturas t6
                                                                WHERE t6.idpaciente =t1.idpacientes)as requiere_factura,
                                                            
                                                            (SELECT t6.facturado
                                                                FROM facturas t6
                                                                WHERE t6.idpaciente =t1.idpacientes)as facturado

                                                    FROM pacientes t1 inner join pacientes_has_anticipos t5
                                                    WHERE (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin') and  t5.pacientes_idpacientes= t1.idpacientes  ORDER BY fecha");
                    
                    while ($row = $mysql->f_obj($sqltest)) {   

                        //convertir formato de fecha yyyy-mm-dd a dd-mm-yyyy
                        $row->fecha = date("d-m-Y",strtotime($row->fecha));

                        if($row->debe > '0.00'){
                            echo '<tr class ="danger">'; 
                            echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }

                            echo '<td>'.$row->institucion.'</td>';
                            
                            mod_estatus_pac_requiere_factura($row, $pagina);
                            mod_estatus_pac_facturado($row, $pagina);
                            mod_estatus_pac($row, $pagina);
                            //editar_datos_pac($row);
                            echo '</tr>';
                        }
                        else{

                            // busca a que tipo de institución pertenece
                            //ayuda a marcar de verde si ya pago de lo contrario no lo 
                            //sombrea a la hora de hacer la búsqueda.
                            // ************************************************************
                            $institucion = mb_strtolower($row->institucion, "UTF-8" );
                            $institucion = str_replace(" ", "_", $institucion);
                            //echo $institucion;

                            $link = $mysql->connect(); 
                            $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                FROM instituciones t5 
                                                                WHERE t5.nombre = '$institucion'");
     
                            $row2 = $mysql->f_obj($sqltest2);

                            
                            // ************************************************************

                            if($row2->tipo == 'PARTICULAR'){
                                echo '<tr class ="success" >'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                

                                echo '<td>'.$row->institucion.'</td>';
                                mod_estatus_pac_requiere_factura($row, $pagina);
                                mod_estatus_pac_facturado($row, $pagina);
                                mod_estatus_pac($row,$pagina);
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                            else{
                                echo '<tr>'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                
                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                echo '<td>'.$row->institucion.'</td>';
                                mod_estatus_pac_requiere_factura($row, $pagina);
                                mod_estatus_pac_facturado($row, $pagina);
                                mod_estatus_pac($row,$pagina);
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>'; 
        // echo "<script type='text/javascript'>
        //                         $(document).ready( function () {
        //                             $('#myclass').DataTable();} );
        //                     </script>";
}

function ver_pacientes_del_mes_factura_folios($fecha_ini, $fecha_fin, $pagina){
/*---------------------------------------------------------------------
Muestra todos los pacientes particulares, en el mes especificado y permite
imprimir todos los folios de facturas ingresados en ver_pacientes_del_mes_factura()
---------------------------------------------------------------------*/ 
    echo '
        <div class="table-responsive">
            <table class="table  table-hover table-striped" id="myclass" >
                <thead>
                    <tr>
                        <th data-field="id">Fecha de estudio</th>
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Institución</th>
                        <th data-field="price">¿Requiere factura?</th>
                        <th data-field="price">¿Está facturado?</th>
                        <th data-field="folio">Folio Facturas</th>
                        <th data-field="estatus">Estatus</th>
                    </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT   distinct t1.idpacientes,
                                                            t5.pacientes_idpacientes,
                                                            t1.fecha,
                                                            t1.institucion,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,

                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,

                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe,
                                                            
                                                            (SELECT t6.requiere_factura
                                                                FROM facturas t6
                                                                WHERE t6.idpaciente =t1.idpacientes)as requiere_factura,
                                                            
                                                            (SELECT t6.facturado
                                                                FROM facturas t6
                                                                WHERE t6.idpaciente =t1.idpacientes)as facturado,
                                                            
                                                            (SELECT numero_facturas 
                                                                FROM facturas 
                                                                WHERE idpaciente= t1.idpacientes) as num_facturas

                                                    FROM pacientes t1 inner join pacientes_has_anticipos t5
                                                    WHERE (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin') and  t5.pacientes_idpacientes= t1.idpacientes  ORDER BY fecha");
                    
                    while ($row = $mysql->f_obj($sqltest)) {   

                        //convertir formato de fecha yyyy-mm-dd a dd-mm-yyyy
                        $row->fecha = date("d-m-Y",strtotime($row->fecha));

                        if($row->debe > '0.00'){
                            echo '<tr class ="danger">'; 
                            echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }

                            echo '<td>'.$row->institucion.'</td>';
                           
                            
                            //mod_estatus_pac_requiere_factura($row, $pagina);
                            //mod_estatus_pac_facturado($row, $pagina);
                            echo '<td>
                                    <button   class="btn btn-sm btn-block disabled" >
                                        <span class="glyphicon " aria-hidden="true"></span>
                                        '.$row->requiere_factura.'
                                    </button>
                                </td>';

                                echo '<td>
                                    <button   class="btn btn-sm btn-block disabled" >
                                        <span class="glyphicon " aria-hidden="true"></span>
                                        '.$row->facturado.'
                                    </button>
                                </td>';
                            echo '<td>'.$row->num_facturas.'</td>';

                            mod_estatus_pac($row, $pagina);
                            //editar_datos_pac($row);
                            echo '</tr>';
                        }
                        else{

                            // busca a que tipo de institución pertenece
                            //ayuda a marcar de verde si ya pago de lo contrario no lo 
                            //sombrea a la hora de hacer la búsqueda.
                            // ************************************************************
                            $institucion = mb_strtolower($row->institucion, "UTF-8" );
                            $institucion = str_replace(" ", "_", $institucion);
                            //echo $institucion;

                            $link = $mysql->connect(); 
                            $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                FROM instituciones t5 
                                                                WHERE t5.nombre = '$institucion'");
     
                            $row2 = $mysql->f_obj($sqltest2);

                            
                            // ************************************************************

                            if($row2->tipo == 'PARTICULAR'){
                                echo '<tr class ="success" >'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                

                                echo '<td>'.$row->institucion.'</td>';
                                
                                //mod_estatus_pac_requiere_factura($row, $pagina);
                                //mod_estatus_pac_facturado($row, $pagina);
                                echo '<td>
                                    <button   class="btn btn-sm btn-block disabled" >
                                        <span class="glyphicon " aria-hidden="true"></span>
                                        '.$row->requiere_factura.'
                                    </button>
                                </td>';

                                echo '<td>
                                    <button   class="btn btn-sm btn-block disabled" >
                                        <span class="glyphicon " aria-hidden="true"></span>
                                        '.$row->facturado.'
                                    </button>
                                </td>';
                                echo '<td>'.$row->num_facturas.'</td>';
                                mod_estatus_pac($row,$pagina);
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                            else{
                                echo '<tr>'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                
                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                echo '<td>'.$row->institucion.'</td>';
                                
                                //mod_estatus_pac_requiere_factura($row, $pagina);
                                //mod_estatus_pac_facturado($row, $pagina);
                                echo '<td>
                                    <button   class="btn btn-sm btn-block disabled" >
                                        <span class="glyphicon " aria-hidden="true"></span>
                                        '.$row->requiere_factura.'
                                    </button>
                                </td>';

                                echo '<td>
                                    <button   class="btn btn-sm btn-block disabled" >
                                        <span class="glyphicon " aria-hidden="true"></span>
                                        '.$row->facturado.'
                                    </button>
                                </td>';
                                
                                echo '<td>'.$row->num_facturas.'</td>';

                                mod_estatus_pac($row,$pagina);
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>'; 

        $fecha = $_POST['fecha_estudios'];
        echo '
        <form role="form" id="edicion_imprimir" method="post" action="view_imprimir_folios_facturas.php" target="_blank">
            <input type="hidden" form="edicion_imprimir" name="fecha_corte" value="'.$fecha.'">
            
            <button type="submit" form="edicion_imprimir" name="editar" class="btn btn-primary btn-default btn-block" >
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                Imprimir
            </button>
        </form>';


 
}

function ver_pacientes_del_mes_prueba_esfuerzo($fecha_ini, $fecha_fin, $pagina){
 
    include_once "include/mysql.php";

    $mysql = new mysql();
    $link = $mysql->connect(); 

    $sql = $mysql->query($link,"SELECT 
                                    t1.idpacientes, 
                                    t1.fecha,
                                    t1.estatus,
                            
                                    CONCAT(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) AS nombre,

                                    (SELECT concat(t2.tipo,' ',t2.nombre) AS estudio 
                                        FROM estudio t2 
                                        WHERE idgammagramas = t1.estudio_idgammagramas) AS estudio,

                                    t1.institucion,

                                    (SELECT concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) AS medico 
                                        FROM doctores t3 
                                        WHERE iddoctores = t1.doctores_iddoctores) AS medico

                            FROM pacientes t1 
                            WHERE  (estudio_idgammagramas >= 3  AND estudio_idgammagramas <= 10 OR estudio_idgammagramas =78)
                                    AND 
                                    (estatus = 'ATENDIDO' AND (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin')) ORDER BY fecha;");

    echo'
    <div class=" table-responsive">
        <table class="table table-bordered table-hover table-striped" id="myclass">
        <thead>
            <tr>
                <th data-field="fecha" width="15">Fecha</th>
                <th data-field="nombre_paciente" width="70">Nombre del paciente</th>
                <th data-field="estudio_realizado" width="15">Estudio realizado</th>
                <th data-field="institucion" width="15">Institucion</th>
                <th data-field="medico_tratante" width="15">Médico tratante</th>
                <th data-field="editar" width="15">Atendido por</th>';
            
            if($_SESSION['nivel_acceso']==3){
              echo  '<th data-field="editar" width="15">Editar</th>';
            }

    echo '      </tr>
       </thead>
        <tbody>
    ';
        //print_r($_SESSION);
        while ($row = $mysql->f_obj($sql)) {
            date_default_timezone_set('America/Mexico_City'); 
            $row->fecha = date('d-m-Y', strtotime($row->fecha));
            echo '<tr>'; 
            
            echo '<td>'.$row->fecha.'</td>';
            echo '<td>'.$row->nombre.'</td>';
            echo '<td>'.$row->estudio.'</td>';
            echo '<td>'.$row->institucion.'</td>';
            echo '<td>'.$row->medico.'</td>';
            //mod_estatus_pac($row, $pagina);

            if(isset($_SESSION)){
                $nivel_acceso = $_SESSION['nivel_acceso'];
                    echo'<td class="success"> ';

                        $sql2 = $mysql->query($link,"SELECT  (SELECT descripcion
                                                                FROM tblc_grado_medico
                                                                WHERE idgrado_medico = t2.idgrado_medico) AS grado,
                                                            t2.nombre,
                                                            t2.ap_pat,
                                                            t2.ap_mat
                                                    FROM   tbl_p_esfuerzo t1 INNER JOIN tblc_doctor_p_esfuerzo t2 
                                                            ON t1.iddoctor_p_esfuerzo = t2.iddoctor_p_esfuerzo
                                                    WHERE  t1.idpaciente = $row->idpacientes  ; ");
                        $row2 = $mysql->f_obj($sql2);

                        if($sql2->num_rows){
                            echo $row2->grado.' '.$row2->nombre.' '.$row2->ap_pat.' '.$row2->ap_mat;
                        }else{
                            echo '';    
                        }
                        echo '</td>';
            }
            if($_SESSION['nivel_acceso']==3){
                echo '  <td> 
                            <form role="form" id="estatus_factura'.$row->idpacientes.'" method="post" action="controlador_editar_medico_prueba_esfuerzo.php">
                                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "estatus" value="'.$row->estatus.'"/>
                                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>

                                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "nombre" value="'.$row->nombre.'"/>
                                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "estudio" value="'.$row->estudio.'"/>
                                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>
                                <input type="hidden" form="estatus_factura'.$row->idpacientes.'" name = "fecha_estudios" value="'.$_POST['fecha_estudios'].'"/>
                            </form>    
                            <button type="submit" form="estatus_factura'.$row->idpacientes.'" name="editar" class="btn btn-sm btn-warning btn-block" >
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                Editar
                            </button>
                        </td>';
                echo '</tr>'; 
            }
        }   

        $mysql->close();
    echo'</tbody>
        </table>
    </div>';
       
}

function ver_listas_de_instituciones(){
//---------------------------------------------------------------------
// Despliega una lista de instituciones publicas
// muestra como botones.    
//---------------------------------------------------------------------
                                    
    $mysql =new mysql();
    $link = $mysql->connect(); 
                    
    $sql = $mysql->query($link,"SELECT  nombre,
                                        tipo
                                    FROM instituciones 
                                    WHERE tipo = 'PARTICULAR' AND estatus = 'ACTIVO';");

    //*************************************************
    //visualiza lista de precios instituciones PRIVADAS
    //*************************************************

    while ($row = $mysql->f_row($sql)) {

        
        $row[0] = str_replace("_", " ", $row[0]);
        
        if($row[1]=='PARTICULAR'){

                echo '
                <form role="form" id="instituciones'.$row[0].'" method="post" action="viewmod_relacion_instituciones.php" accept-charset="UTF-8">
                    <input type="hidden" form="instituciones'.$row[0].'" name="institucion" value="'.$row[0].'"/>
                </form>';
                echo'        
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="instituciones'.$row[0].'" type="submit" value="'. pasarMayusculas($row[0]).'">
                    </div> 
                    <div class="col-md-4 col-lg-4">
                    </div>
                </div>
                </br>';
        }
    }

    //*************************************************
    //visualiza lista de precios instituciones PUBLICAS
    //*************************************************

    if($_SESSION['nivel_acceso'] <= 2){

        $link = $mysql->connect();
        $sql = $mysql->query($link,"SELECT  nombre,
                                        tipo
                                    FROM instituciones 
                                    WHERE tipo='PUBLICA' AND estatus='ACTIVO';");

        while ($row = $mysql->f_row($sql)){
            $row[0] = str_replace("_", " ", $row[0]);
            echo '
                <form role="form" id="instituciones'.$row[0].'" method="post" action="viewmod_relacion_instituciones.php" accept-charset="UTF-8">
                    <input type="hidden" form="instituciones'.$row[0].'" name="institucion" value="'.$row[0].'"/>
                </form>';
                echo'        
                <div class="row">
                    <div class="col-md-4 col-lg-4">
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <input id="submit" name="submit" class="btn btn-success btn-lg btn-block" form="instituciones'.$row[0].'" type="submit" value="'. pasarMayusculas($row[0]).'">
                    </div> 
                    <div class="col-md-4 col-lg-4">
                    </div>
                </div>
                </br>';  
        }
    }

    $mysql->close(); 
}

function monto_ejecutado_por_instituciones(){
/*---------------------------------------------------------------------
// Despliega una lista de instituciones publicas y muestra los contratos
// existentes

// invoca: controlador_monto_ejecutado.php

// Llama a procesos:  pasarMayusculas();

// rev. 2019/07/12
---------------------------------------------------------------------*/
    
    //*************************************************
    //visualiza lista de precios instituciones PUBLICAS
    //*************************************************

    if($_SESSION['nivel_acceso'] <= 2)
    {
        $mysql =new mysql();
        $link = $mysql->connect();
        $resp = $mysql->query($link, "SET lc_time_names = 'es_ES';");


        /**************************************************
        //INICIA ACTUALIZA MONTOS DISPONIBLES
        **************************************************/
        $sql = $mysql->query($link,"SELECT 	id_montos_disponibles,
                                            monto_maximo,
                                            
                                            (SELECT 
                                                instituciones.nombre as nombre 
                                            FROM instituciones 
                                            WHERE instituciones.idinstitucion = tbl_montos_disponibles.idinstitucion) AS nombre,

                                            (SELECT DATE_FORMAT(fecha_inicio, '%d %b %Y %r')) as fecha_inicio,
                                            
                                            (SELECT DATE_FORMAT(fecha_fin, '%d %b %Y %r')) as fecha_fin

                                            FROM tbl_montos_disponibles where 1;");
                                            
        
        while ($row = $mysql->f_obj($sql))
        {
            // print_r($row);
            $nombre_minusculas = $row->nombre;
            $nombre = str_replace("_", " ", $row->nombre);
            $nombre = pasarMayusculas($nombre);

            if($nombre == 'IMSS TUXTLA')
            {

                $sql2 = $mysql->query($link,"SELECT 	
                                            sum(estudio.imss_tuxtla) as total_ejercido
                                            
                                            from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                                            where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                    from tbl_montos_disponibles 
                                                    where id_montos_disponibles=  $row->id_montos_disponibles)
                                                )
                                                and
                                                (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                                from tbl_montos_disponibles 
                                                                where id_montos_disponibles=  $row->id_montos_disponibles)	
                                                ))
                                                and
                                                (pacientes.institucion = '$nombre')");

                $row2 = $mysql->f_obj($sql2);

                if(isset($row2))
                {
                    $monto_restante = $row->monto_maximo - $row2->total_ejercido;

                    if($row2->total_ejercido < $row->monto_maximo)
                    {
                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 
                    }else {
                        
                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 

                        echo'   <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Se rebazó el tope máximo del contrato! </strong> '.$nombre.' por: $ '.number_format($monto_restante,2).'
                                </div>';
                                
                    
                    }
                    
                }
                                        
            }
            else
            {
            
                $sql2 = $mysql->query($link,"SELECT 	
                                            sum(estudio.$nombre_minusculas) as total_ejercido
                                    from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                                    where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                            from tbl_montos_disponibles 
                                                            where id_montos_disponibles=  $row->id_montos_disponibles)
                                            )
                                            and
                                            (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                            from tbl_montos_disponibles 
                                                            where id_montos_disponibles=  $row->id_montos_disponibles)	
                                            ))
                                            and
                                            (pacientes.institucion = '$nombre')
                                            and 
                                            (pacientes.estatus = 'ATENDIDO' || pacientes.estatus = 'POR ATENDER') ");
                $row2 = $mysql->f_obj($sql2);

                if(isset($row2))
                {
                    $monto_restante = $row->monto_maximo - $row2->total_ejercido;

                    if($row2->total_ejercido < $row->monto_maximo)
                    {

                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 

                    }else {

                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 
                        
                        echo'   <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Se rebazó el tope máximo del contrato! </strong> '.$nombre.' por: $ '.number_format($monto_restante,2).'
                                </div>';
                    }
                    
                }
            }
        }
        /**************************************************
        //FINALIZA ACTUALIZA MONTOS DISPONIBLES
        **************************************************/


        /**************************************************
        //INICIA.- CONSULTA TODOS LOS DATOS DE LOS CONTRATOS
        **************************************************/
        $sql = $mysql->query($link,"SELECT 	id_montos_disponibles,
                                            monto_maximo,
                                            monto_restante, 
                                            monto_ejecutado,
                                            (SELECT 
                                                instituciones.nombre as nombre 
                                            FROM instituciones 
                                            WHERE instituciones.idinstitucion = tbl_montos_disponibles.idinstitucion) AS nombre,

                                            (SELECT DATE_FORMAT(fecha_inicio, '%d %b %Y')) as fecha_inicio,
                                            
                                            (SELECT DATE_FORMAT(fecha_fin, '%d %b %Y')) as fecha_fin

                                            FROM tbl_montos_disponibles where 1;");
        
    echo '
        <div class="table-responsive">
            <table class="table  table-hover table-striped table-bordered" id="myclass">

                <thead>
                    <tr>
                        <th>Institucion</th>
                        <th>Monto máximo de contrato</th>
                        <th>Monto ejecutado</th>
                        <th>Monto restante</th>
                        <th>Fecha inicio de contrato</th>
                        <th>Fecha termino de contrato</th>
                        <th>Editar</th>
                        <th>EXCEL</th>
                    </tr>
                </thead>

                <tbody>
               
        ';    

        while ($row = $mysql->f_obj($sql)){
             // print_r($row);
             $nombre = str_replace("_", " ", $row->nombre);
             $monto_maximo =number_format($row->monto_maximo,2);
             $ejecutado=number_format($row->monto_ejecutado,2);
             $monto_restante = number_format($row->monto_restante,2);
             
             //$row->fecha_inicio = date('d-m-Y',strtotime($row->fecha_inicio));

             echo ' <tr>
                        <th>'.pasarMayusculas($nombre).'</th>
                        <th>$ '.$monto_maximo.'</th>
                        <th>$ '.$ejecutado.'</th>
                        <th>$ '.$monto_restante.'</th>
                        <th>'.$row->fecha_inicio.'</th>
                        <th>'.$row->fecha_fin.'</th>
                        <th>';
            echo'
                            <form role="form" id="editar_monto'.$row->id_montos_disponibles.'" method="post" action="viewmod_crear_contrato.php">
                                <input type="hidden" form="editar_monto'.$row->id_montos_disponibles.'" name="id_contrato" value="'.$row->id_montos_disponibles.'"/>
                            </form>

                            <button type="submit" class="btn btn-success btn-sm btn-block" form="editar_monto'.$row->id_montos_disponibles.'">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </button> 

                        </th>
                        
                        <th>';
            

                echo'       <form role="form" id="exportar'.$row->id_montos_disponibles.'" method="post"                                      action="view_reporte_pacientes_institucion.php" target="_blank">

                                <input type="hidden" form="exportar'.$row->id_montos_disponibles.'" name="fecha_inicio" value="'.$row->fecha_inicio.'"/>

                                <input type="hidden" form="exportar'.$row->id_montos_disponibles.'" name="fecha_fin" value="'.$row->fecha_fin.'"/>

                                <input type="hidden" form="exportar'.$row->id_montos_disponibles.'" name="nombre_institucion" value="'.pasarMayusculas($nombre).'"/>

                                <input type="hidden" form="exportar'.$row->id_montos_disponibles.'" name="nombre_minusculas" value="'.$row->nombre.'"/>

                                <input type="hidden" form="exportar'.$row->id_montos_disponibles.'" name="id_contrato" value="'.$row->id_montos_disponibles.'"/>
                            </form>

                            <button type="submit" class="btn btn-primary btn-sm btn-block" form="exportar'.$row->id_montos_disponibles.'">
                                <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
                            </button> 

                        </th>';
            echo'   </tr>';
        }

        echo' </tbody>
            </table>
        </div>';
    }

    $mysql->close(); 

    
}

function monto_ejecutado_por_instituciones_resumen(){
/*---------------------------------------------------------------------
// Despliega una lista de instituciones publicas y muestra los contratos
// existentes

// invoca: controlador_monto_ejecutado.php

// Llama a procesos:  pasarMayusculas();

// rev. 2019/07/12
---------------------------------------------------------------------*/
    
    //*************************************************
    //visualiza lista de precios instituciones PUBLICAS
    //*************************************************

    if($_SESSION['nivel_acceso'] <= 2)
    {
        $mysql =new mysql();
        $link = $mysql->connect();
        $resp = $mysql->query($link, "SET lc_time_names = 'es_ES';");


        /**************************************************
        //INICIA ACTUALIZA MONTOS DISPONIBLES
        **************************************************/
        $sql = $mysql->query($link,"SELECT 	id_montos_disponibles,
                                            monto_maximo,
                                            
                                            (SELECT 
                                                instituciones.nombre as nombre 
                                            FROM instituciones 
                                            WHERE instituciones.idinstitucion = tbl_montos_disponibles.idinstitucion) AS nombre,

                                            (SELECT DATE_FORMAT(fecha_inicio, '%d %b %Y %r')) as fecha_inicio,
                                            
                                            (SELECT DATE_FORMAT(fecha_fin, '%d %b %Y %r')) as fecha_fin

                                            FROM tbl_montos_disponibles where 1;");
                                            
        
        while ($row = $mysql->f_obj($sql))
        {
            // print_r($row);
            $nombre_minusculas = $row->nombre;
            $nombre = str_replace("_", " ", $row->nombre);
            $nombre = pasarMayusculas($nombre);

            if($nombre == 'IMSS TUXTLA')
            {

                $sql2 = $mysql->query($link,"SELECT 	
                                            sum(estudio.imss_tuxtla) as total_ejercido
                                            
                                            from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                                            where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                    from tbl_montos_disponibles 
                                                    where id_montos_disponibles=  $row->id_montos_disponibles)
                                                )
                                                and
                                                (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                                from tbl_montos_disponibles 
                                                                where id_montos_disponibles=  $row->id_montos_disponibles)	
                                                ))
                                                and
                                                (pacientes.institucion = '$nombre')");

                $row2 = $mysql->f_obj($sql2);

                if(isset($row2))
                {
                    $monto_restante = $row->monto_maximo - $row2->total_ejercido;

                    if($row2->total_ejercido < $row->monto_maximo)
                    {
                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 
                    }else {
                        
                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 

                        echo'   <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Se rebazó el tope máximo del contrato! </strong> '.$nombre.' por: $ '.number_format($monto_restante,2).'
                                </div>';
                                
                    
                    }
                    
                }
                                        
            }
            else
            {
            
                $sql2 = $mysql->query($link,"SELECT 	
                                            sum(estudio.$nombre_minusculas) as total_ejercido
                                    from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                                    where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                            from tbl_montos_disponibles 
                                                            where id_montos_disponibles=  $row->id_montos_disponibles)
                                            )
                                            and
                                            (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                            from tbl_montos_disponibles 
                                                            where id_montos_disponibles=  $row->id_montos_disponibles)	
                                            ))
                                            and
                                            (pacientes.institucion = '$nombre')
                                            and 
                                            (pacientes.estatus = 'ATENDIDO' || pacientes.estatus = 'POR ATENDER') ");
                $row2 = $mysql->f_obj($sql2);

                if(isset($row2))
                {
                    $monto_restante = $row->monto_maximo - $row2->total_ejercido;

                    if($row2->total_ejercido < $row->monto_maximo)
                    {

                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 

                    }else {

                        $sql_update = $mysql->query($link,"UPDATE  tbl_montos_disponibles
                                                        set monto_ejecutado = $row2->total_ejercido,
                                                            monto_restante = $monto_restante
                                                    WHERE id_montos_disponibles = $row->id_montos_disponibles;"); 
                        
                        echo'   <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Se rebazó el tope máximo del contrato! </strong> '.$nombre.' por: $ '.number_format($monto_restante,2).'
                                </div>';
                    }
                    
                }
            }
        }
        /**************************************************
        //FINALIZA ACTUALIZA MONTOS DISPONIBLES
        **************************************************/


        /**************************************************
        //INICIA.- CONSULTA TODOS LOS DATOS DE LOS CONTRATOS
        **************************************************/
        $sql = $mysql->query($link,"SELECT 	id_montos_disponibles,
                                            monto_maximo, 
                                            monto_ejecutado,
                                            (SELECT 
                                                instituciones.nombre as nombre 
                                            FROM instituciones 
                                            WHERE instituciones.idinstitucion = tbl_montos_disponibles.idinstitucion) AS nombre

                                            FROM tbl_montos_disponibles where 1;");
        
    echo '
        <div class="table-responsive">
            <table class="table  table-hover table-striped table-bordered" id="myClass3">

                <thead>
                    <tr>
                        <th>Institucion</th>
                        <th>Monto máximo de contrato</th>
                        <th>Monto ejecutado</th>
                    </tr>
                </thead>

                <tbody>  
        ';    

        while ($row = $mysql->f_obj($sql)){
             // print_r($row);
             $nombre = str_replace("_", " ", $row->nombre);
             $monto_maximo =number_format($row->monto_maximo,2);
             $ejecutado=number_format($row->monto_ejecutado,2);
             
             //$row->fecha_inicio = date('d-m-Y',strtotime($row->fecha_inicio));

             echo ' <tr>
                        <th>'.pasarMayusculas($nombre).'</th>
                        <th>$ '.$monto_maximo.'</th>
                        <th>$ '.$ejecutado.'</th>';
            echo'   </tr>';
        }

        echo' </tbody>
            </table>
        </div>';
    }

    $mysql->close(); 

    
}
function relacion_pacientes_por_institucion($institucion, $fecha_ini, $fecha_fin, $pagina){
    
    echo '
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th data-field="id">Fecha</th>
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Hora</th>
                        <th data-field="price">Institución</th>
                        <th data-field="price">Médico tratante</th>
                        <th data-field="price">Teléfono</th>
                        <th data-field="price">Teléfono</th>
                        <th data-field="estatus">Estatus</th>
                        <!-- <th data-field="estatus">Editar</th>-->
                    </tr>
                </thead>
                <tbody>
        ';

                    include_once "mysql.php";

                    $mysql = new mysql();
                    $link = $mysql->connect(); 

                    $sqltest = $mysql->query($link,"SELECT  t1.idpacientes,
                                                            t1.fecha,
                                                            t1.institucion,
                                                            concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,

                                                            t1.nombre as nombre_pila,
                                                            t1.ap_paterno as appat,
                                                            t1.ap_materno as apmat,
                                                            
                                                            (Select concat(t2.tipo,' ',t2.nombre) as estudio 
                                                                from estudio t2 
                                                                where idgammagramas = t1.estudio_idgammagramas) as estudio,

                                                            t1.hora,

                                                            (Select concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) as medico 
                                                                from doctores t3 
                                                                where iddoctores = t1.doctores_iddoctores) as medico,
                                                            t1.num_tel,
                                                            t1.num_tel2,
                                                            t1.institucion,
                                                            t1.estatus,
                                                            t1.indicaciones_tratamiento,

                                                            (SELECT t4.monto_restante 
                                                                FROM pacientes_has_anticipos t4 
                                                                WHERE t4.pacientes_idpacientes = t1.idpacientes and t4.fecha_anticipo='0000-00-00' ) as debe

                                                    FROM pacientes t1 
                                                    WHERE (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin') and institucion= '$institucion' ORDER BY fecha");
                    $num_rows = $mysql->f_num($sqltest);
                    echo '
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Se encontraron: '.$num_rows.' pacientes</strong> 
                        </div>';

                    echo '</br>';
                    while ($row = $mysql->f_obj($sqltest)) {   

                        //convertir formato de fecha yyyy-mm-dd a dd-mm-yyyy
                        $row->fecha = date("d-m-Y",strtotime($row->fecha));

                        if($row->debe > '0.00'){
                            echo '<tr class ="danger">'; 
                            echo '<td>'.$row->fecha.'</td>';
                            echo '<td>'.$row->nombre.'</td>';

                            if($row->indicaciones_tratamiento != ''){
                                echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                            }else{
                                echo '<td>'.$row->estudio.'</td>';    
                            }

                            $row->hora = date('g:i a ',strtotime($row->hora));

                            echo '<td>'.$row->hora.'</td>';
                            echo '<td>'.$row->institucion.'</td>';
                            echo '<td>'.$row->medico.'</td>';
                            echo '<td>'.$row->num_tel.'</td>';
                            echo '<td>'.$row->num_tel2.'</td>';
                            mod_estatus_pac($row, $pagina);
                            //editar_datos_pac($row);
                            echo '</tr>';
                        }
                        else{

                            // busca a que tipo de institución pertenece
                            //ayuda a marcar de verde si ya pago de lo contrario no lo 
                            //sombrea a la hora de hacer la búsqueda.
                            // ************************************************************
                            $institucion = mb_strtolower($row->institucion, "UTF-8" );
                            $institucion = str_replace(" ", "_", $institucion);
                            //echo $institucion;

                            $link = $mysql->connect(); 
                            $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                                                FROM instituciones t5 
                                                                WHERE t5.nombre = '$institucion'");
     
                            $row2 = $mysql->f_obj($sqltest2);

                            
                            // ************************************************************

                            if($row2->tipo == 'PARTICULAR'){
                                echo '<tr class ="success" >'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';

                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }
                                

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row,$pagina);
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                            else{
                                echo '<tr>'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                
                                if($row->indicaciones_tratamiento != ''){
                                    echo '<td>'.$row->estudio.' <strong>DOSIS: '.$row->indicaciones_tratamiento.' mCi</strong></td>';                                
                                }else{
                                    echo '<td>'.$row->estudio.'</td>';    
                                }

                                $row->hora = date('g:i a ',strtotime($row->hora));

                                echo '<td>'.$row->hora.'</td>';
                                echo '<td>'.$row->institucion.'</td>';
                                echo '<td>'.$row->medico.'</td>';
                                echo '<td>'.$row->num_tel.'</td>';
                                echo '<td>'.$row->num_tel2.'</td>';
                                mod_estatus_pac($row,$pagina);
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                        }
                    }
                    $mysql->close();
            echo'</tbody>
            </table>
        </div>';    
}

function cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin, $pagina, $estatus){
/*---------------------------------------------------------------------
// Obtiene la cantidad de pacientes de acuerdo con las instituciones
// existentes

// invoca:  index.php
            viewmod_graficar_pacientes_por_mes

// rev. 2019/07/15
---------------------------------------------------------------------*/
    

    $mysql = new mysql();
    $link = $mysql->connect(); 

    $sql = $mysql->query($link, "SELECT nombre FROM instituciones WHERE idinstitucion = idinstitucion");

    $cantidad_instituciones = $mysql->f_num($sql);

    $instituciones = array();
   
    $cont =0;
    while ($row = $mysql->f_obj($sql)) {

        $nombre =pasarMayusculas( $row->nombre);
        $nombre = str_replace('_', ' ', $nombre);
    
        $sqltest = $mysql->query($link,"SELECT COUNT(t1.idpacientes)  AS total
                                        FROM pacientes t1 
                                        WHERE (fecha >= '$fecha_ini' AND  fecha <= '$fecha_fin') and institucion= '$nombre' and t1.estatus = '$estatus';");
        $num_rows = $mysql->f_num($sqltest);

        if($num_rows){
            $row2 = $mysql->f_obj($sqltest);
        }
        else{
            $row2->total = 0;
        }

        $instituciones[$cont]["institucion"]    =   $nombre;
        $instituciones[$cont]["cantidad"]       =   $row2->total;

        $cont++; 

    } 
    
    echo '
        <!--  <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Se encontraron: '.$num_rows.' pacientes</strong> 
        </div>-->';

        echo '</br>';                  
    $mysql->close();
            
    return $instituciones;     
}
// modificado  el 13 de Enero de 2019 para sacar gráficas de estudios realizados anualmente
function cantidad_de_estudios($fecha_ini, $fecha_fin, $pagina, $estatus){

    include_once "mysql.php";
    include_once "funciones_consultas.php";

    $mysql = new mysql();
    $link = $mysql->connect();

    $instituciones = array();
   

    $sqltest = $mysql->query($link,"SELECT 
	                                        CONCAT(t2.tipo,' ', t2.nombre) as nombre_estudio,
                                            count(*) as cant_estudios
       
                                        FROM pacientes t1 INNER JOIN estudio t2

                                        WHERE (t1.fecha >= '$fecha_ini' AND  t1.fecha <= '$fecha_fin') 
                                            /*and t1.institucion=   PARA OBTENER POR INSTITUCION*/   
                                            and t1.estatus = '$estatus'
                                            AND (t1.estudio_idgammagramas = t2.idgammagramas)
                                        group BY 
                                            t2.idgammagramas;");
    
    $num_rows = $mysql->f_num($sqltest);
    $cont =0 ;
    while ($row = $mysql->f_obj($sqltest)) {
        $instituciones[$cont]["estudio"]    =   $row->nombre_estudio;
        $instituciones[$cont]["cantidad"]       =   $row->cant_estudios;
        $cont++;
    } 
    echo '
        <!--  <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <strong>Se encontraron: '.$num_rows.' pacientes</strong> 
        </div>-->';

        echo '</br>';                  
    $mysql->close();
            
    return $instituciones;     
     
}


function ventas_por_instituciones_meses($fecha_act){
/*---------------------------------------------------------------------
// Obtiene la cantidad de pacientes atendidos y los divide en instituciones
y particulares por cada uno de los meses    Array['ENE',  31,      2],
                                                 ['FEB',  31,      5], etc.

// invoca:  index.php
            

// rev. 2019/12/05
---------------------------------------------------------------------*/

    date_default_timezone_set('America/Mexico_City'); 
    
    //print_r($fecha_act);
    $fecha = explode("-", $fecha_act);
     

    $year = $fecha[0];  //AÑO ACTUAL

    $mysql = new mysql();
    $link = $mysql->connect(); 

    $datos = array();
    
    
    $meses = array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");

    // print_r($meses);

    $cont_meses = 1;

    for($i=0; $i<12; $i++){
        
        $total_particular = 0;
        $total_instituciones = 0;

        $sql = $mysql->query($link, "SELECT instituciones.nombre
                                        FROM instituciones
                                        WHERE instituciones.tipo = 'PARTICULAR'");
        
        $fecha_inicio = $year.'-'.$cont_meses.'-'.'01';
        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));

        $fecha_fin = last_month_day($fecha_inicio);

        // echo $fecha_inicio.'   /n  ';
        // echo $fecha_fin.'   /n  ';

        while ($instituciones = $mysql->f_obj($sql)) {
            // echo $instituciones->nombre;

            $institucion = mb_strtolower($instituciones->nombre, "UTF-8" );
            $institucion = str_replace("_", " ", $institucion);
            $estatus = 'ATENDIDO';

            // echo $institucion;

            $sql2 = $mysql->query($link, "SELECT COUNT(t1.idpacientes)  AS total
                                            FROM pacientes t1 
                                            WHERE (t1.fecha >= '$fecha_inicio' AND  t1.fecha <= '$fecha_fin') and t1.institucion= '$institucion' and t1.estatus = '$estatus'");

            $row = $mysql->f_obj($sql2);
                
            $total_particular += $row->total;
            // echo'<pre>';
            // print_r($row);    
            // echo'</pre>';
        }


        /**********************
        **********************/

        $sql = $mysql->query($link, "SELECT instituciones.nombre
                                        FROM instituciones
                                        WHERE instituciones.tipo != 'PARTICULAR'");
        
        $fecha_inicio = $year.'-'.$cont_meses.'-'.'01';
        $fecha_inicio = date('Y-m-d', strtotime($fecha_inicio));

        $fecha_fin = last_month_day($fecha_inicio);

        // echo $fecha_inicio.'   /n  ';
        // echo $fecha_fin.'   /n  ';

        while ($instituciones = $mysql->f_obj($sql)) {
            //echo $instituciones->nombre;

            $institucion = mb_strtolower($instituciones->nombre, "UTF-8" );
            $institucion = str_replace("_", " ", $institucion);
            $estatus = 'ATENDIDO';

            // echo $institucion;

            $sql3 = $mysql->query($link, "SELECT COUNT(t1.idpacientes)  AS total
                                            FROM pacientes t1 
                                            WHERE (t1.fecha >= '$fecha_inicio' AND  t1.fecha <= '$fecha_fin') and t1.institucion= '$institucion' and t1.estatus = '$estatus'");

            $row2 = $mysql->f_obj($sql3);
                
            $total_instituciones += $row2->total;
            // echo'<pre>';
            // print_r($row);    
            // echo'</pre>';
        }




        $datos[$i]['Mes'] = $meses[$i];
        $datos[$i]['Particulares'] = $total_particular;
        $datos[$i]['Instituciones'] = $total_instituciones;
        
        $cont_meses ++;
        // echo $meses[$i].':  '.$total.'      ';

    }
    // echo'<pre>';
    // print_r($datos);
    // echo'</pre>';    


    return $datos;
}

function obtener_usuario(){
/*--------------------------------------------------------------------
// Con esta rutina se obtiene el nombre del usuario a partir de la session

// invoca: view_reimprimir_recibo_anticipo.php

// rev. 2019/07/12
--------------------------------------------------------------------*/
    
    include_once "mysql.php";

    $usuario = $_SESSION['usuario'];
    $idusuario = $_SESSION ['id'];

    $mysql =new mysql();
    $link = $mysql->connect();

    $sql = $mysql->query($link,"SELECT concat(t1.nombre,' ',t1.ap_paterno, ' ',t1.ap_materno) as nombre
                                FROM users t1
                                WHERE idusuario = $idusuario");

    $row = $mysql->f_obj($sql);
    return $row;
}

function fecha_letras($fecha_estudio){
/*--------------------------------------------------------------------
// Convierte la fecha por ejemplo 11/07/2019 a Jueves, 11 de Julio de 2019 . 

// invoca:  viewmod_ver_corte_caja_por_dia.php
//          viewmod_imprimir_hoja_tratamientos.php
//          viewmod_ver_pacientes_del_dia.php
//          viewmod_ver_pacientes_por_semana.php

// rev. 2019/07/12
--------------------------------------------------------------------*/

        date_default_timezone_set('America/Mexico_City');
        //Variable nombre del mes 
        $nommes = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"); 
        //variable nombre día 
        $nomdia = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sábado"); 

        /*  date(j) toma valores de 1 al 31 segun el dia del mes 
            date(n) devuelve numero del 1 al 12 segun el mes 
            date(w) devuelve 0 a 6 del dia de la semana empezando el domingo 
            date(Y) devuelve el año en 4 digitos */ 

        $dia        = date("j",strtotime($fecha_estudio)); //Dia del mes en numero 
        $mes        = date("n",strtotime($fecha_estudio)); //Mes actual en numero 
        $diasemana  = date("w",strtotime($fecha_estudio)); //Dia de semana en numero 
        $año        = date("Y",strtotime($fecha_estudio));

        $fecha_estudio2 = $nomdia[$diasemana].", ".$dia." de ".$nommes[$mes-1]." de ".$año; 
        return $fecha_estudio2;
}

function obtener_semana($day, $month, $year){
/*--------------------------------------------------------------------
// Obtiene el los dias de la semana y retorna una lista con cada uno
// de los dias.

// invoca:  viewmod_ver_pacientes_por_semana.php

// rev. 2019/07/12

--------------------------------------------------------------------*/
    //          ¡Cuidado! con los echo
    $year=$year;
    $month=$month;
    $day=$day;
    date_default_timezone_set("America/Mexico_City");
    # Obtenemos el numero de la semana
    $semana=date("W",mktime(0,0,0,$month,$day,$year));
    //echo 'semana:'.$semana;  //21
    # Obtenemos el día de la semana de la fecha dada
    $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
    //echo 'dia semana: '.$diaSemana; //2
    # el 0 equivale al domingo...
    if($diaSemana==0)
        $diaSemana=7;
 
    # A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
    $lunes      =date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
    $martes     =date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+2,$year));
    $miercoles  =date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+3,$year));
    $jueves     =date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+4,$year));
    $viernes    =date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+5,$year));
    $sabado     =date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+6,$year));
    $domingo    =date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+7,$year));

    $lista = array( 'lunes'=> $lunes,
                    'martes'=> $martes,
                    'miercoles'=> $miercoles,
                    'jueves'=> $jueves,
                    'viernes'=> $viernes,
                    'sabado'=> $sabado,
                    'domingo'=> $domingo
                    );
    # A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
    //$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
 
   /* echo "<br>Semana: ".$semana." - año: ".$year;
    echo "<br>Primer día ".$primerDia;
    echo "<br>Ultimo día ".$ultimoDia;*/
    return $lista;
}

function obtener_mes($fecha){
/*--------------------------------------------------------------------
// Convierte la fecha por ejemplo 11/07/2019 a JULIO. 

// invoca: viewmod_reimprimir_recibo.php
//         viewmod_ver_corte_caja_por_mes.php

// rev. 2019/07/12
--------------------------------------------------------------------*/
    date_default_timezone_set('America/Mexico_City');
    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
    return $mes;
}

function pasarMayusculas($cadena) { 
/*--------------------------------------------------------------------
// Convierte minusculas a MAYUSCULAS. 

// invoca:  viewmod_reimprimir_recibo.php
//          viewmod_ver_corte_caja_por_mes.php
//          viewmod_ver_lista_precios_instituciones.php
//          controlador_citar_pacientes_institucion.php

// rev. 2019/07/12
--------------------------------------------------------------------*/
        $cadena = strtoupper($cadena); 
        $cadena = str_replace("á", "Á", $cadena); 
        $cadena = str_replace("é", "É", $cadena); 
        $cadena = str_replace("í", "Í", $cadena); 
        $cadena = str_replace("ó", "Ó", $cadena); 
        $cadena = str_replace("ú", "Ú", $cadena); 
        $cadena = str_replace("ñ", "Ñ", $cadena);
        $cadena = str_replace("ü", "Ü", $cadena);
        return ($cadena); 
} 

function last_month_day($fecha) { 
/*--------------------------------------------------------------------
// Obtiene el ultimo día del mes. 

// invoca: funciones_consultas  --> reimprimir_recibos()

// rev. 2019/07/12
--------------------------------------------------------------------*/ 
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);
        /*$month = date('m');
        $year = date('Y');*/
        $year = $fecha[0];
        $month= $fecha[1];
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
        return date('Y-m-d', (mktime(0,0,0,$month+1,1,$year)-1) );
};
 
function first_month_day($fecha) {
/*--------------------------------------------------------------------
// Obtiene el primer día del mes. 

// invoca: funciones_consultas  --> reimprimir_recibos()

// rev. 2019/07/12
--------------------------------------------------------------------*/   
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);
        /*$month = date('m');
        $year = date('Y');*/
        $year = $fecha[0];
        $month= $fecha[1];
        return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
}

//////////////////////////////////////////
function primer_dia_semestre($fecha) {
/*--------------------------------------------------------------------
// Obtiene el primer día del semestre enero-junio

// invoca: index.php

// rev. 2019/07/15
--------------------------------------------------------------------*/
    date_default_timezone_set('America/Mexico_City');
    $fecha = explode("-", $fecha);
    /*$month = date('m');
    $year = date('Y');*/
    $year = $fecha[0];
    
    return date('Y-m-d', mktime(0,0,0, 1, 1, $year));
}

function ultimo_dia_semestre($fecha) { 
/*--------------------------------------------------------------------
// Obtiene el ultimo día del semestre enero-junio

// invoca: index.php

// rev. 2019/07/15
--------------------------------------------------------------------*/
    date_default_timezone_set('America/Mexico_City');
    $fecha = explode("-", $fecha);
    /*$month = date('m');
    $year = date('Y');*/
    $year = $fecha[0];
    //$month= $fecha[1];
    $day = date("d", mktime(0,0,0, 6, 0, $year));

    return date('Y-m-d', (mktime(0,0,0,6+1,1,$year)-1) );
};

function primer_dia_segundo_semestre($fecha) {
/*--------------------------------------------------------------------
// Obtiene el primer día del segundo semestre julio-diciembre

// invoca: index.php

// rev. 2019/07/15
--------------------------------------------------------------------*/
    date_default_timezone_set('America/Mexico_City');
    $fecha = explode("-", $fecha);
    /*$month = date('m');
    $year = date('Y');*/
    $year = $fecha[0];
    
    return date('Y-m-d', mktime(0,0,0, 7, 1, $year));
}

function ultimo_dia_segundo_semestre($fecha) {
/*--------------------------------------------------------------------
// Obtiene el último día del segundo semestre julio-diciembre

// invoca: index.php

// rev. 2019/07/15
--------------------------------------------------------------------*/ 
    date_default_timezone_set('America/Mexico_City');
    $fecha = explode("-", $fecha);
    /*$month = date('m');
    $year = date('Y');*/
    $year = $fecha[0];
    //$month= $fecha[1];
    $day = date("d", mktime(0,0,0, 12, 0, $year));

    return date('Y-m-d', (mktime(0,0,0,12+1,1,$year)-1) );
};



function graficas_semestrales_inicio($pagina){

echo'<div class="row">';

/******************************************************************************************
// INICIO  PRIMER SEMESTRE
*****************************************************************************************/    

    date_default_timezone_set('America/Mexico_City'); 
    $fecha_act = date('Y-m-d');

    $fecha_fin = ultimo_dia_semestre($fecha_act);
    $fecha_ini = primer_dia_semestre($fecha_act); 

    $fecha_ini = date("d-m-Y",strtotime($fecha_ini));
    $fecha_fin = date("d-m-Y",strtotime($fecha_fin));                                    
    
    
    $fecha = explode("-", $fecha_act);
    $year = $fecha[0];


echo'
    <div class="col-md-6">
        <button type="submit" class="btn btn-success btn-lg btn-block"  aria-label="Left Align">'; 
            $año= 'GRÁFICAS DEL PRIMER SEMESTRE DEL '.$year;
            echo $año; 
echo'                    
        </button>
        <br>';

echo '  <div class="panel panel-success">

            <!--    inicio panel heading   -->

            <div class="panel-heading">';
echo'           <h3 class="panel-title">PACIENTES ATENDIDOS del '.$fecha_ini.' al '.$fecha_fin.'</h3>
            </div>
            
            <!--    fin panel heading   -->

            <!--    inicio panel body   -->';
echo'
            <div class="panel-body">
                <!-- CHART Aquí se invoca   -->
                
                    <div id="primer_semestre" class="chart"></div>
                
                <!-- Fin CHART   -->
                ';
            
                //$datos = array();
                $estatus = 'ATENDIDO';
                $fecha_ini = date('Y-m-d',strtotime($fecha_ini));
                $fecha_fin = date('Y-m-d',strtotime($fecha_fin));
                // echo $fecha_ini.'   '. $fecha_fin;
                $datos_atendido_institucion_1 = cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus );
            
                // echo '<pre>';
                // print_r($datos_atendido_institucion_1);
                // echo '</pre>';
                $num = count($datos_atendido_institucion_1);
            

            echo '<div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myClass" >
                        <thead>
                            <tr>
                                <th>Institucion</th>
                                <th>Cantidad</th>';
                    
                    echo '  </tr>
                        </thead>
                        <tbody>';
                            $total_de_estudios=0;

                            for($i=0; $i < $num; $i++){
                                echo '<tr>';
                                echo        '<td>'.$datos_atendido_institucion_1[$i]['institucion'].'</td>';
                                echo        '<td>'.$datos_atendido_institucion_1[$i]['cantidad'].'</td>';
                                echo '</tr>';
                                $total_de_estudios +=$datos_atendido_institucion_1[$i]['cantidad'];
                            }
                                echo '<tr>';
                                echo '      <td> <strong>Total de estudios atendidos (sumatoria):</strong> </td>';
                                echo '      <td> <strong>'.$total_de_estudios.'</strong> </td>';
                                echo '</tr>';

                    echo '</tbody>
                    </table>
                </div>';
         

            // echo '<pre>';
            // print_r($datos_atendido_institucion_1);
            // echo '</pre>';
                        
        echo'</div>
            
            <!-- /. fin panel body -->

        </div>

        <!-- /. fin panel  -->

    <!--    **********************  FIN PACIENTES POR INSTITUCION **********************-->';

            
echo'    
    </div>

    <!--  fin Col-6 -->';

/******************************************************************************************
// INICIO SEGUNDO SEMESTRE
*****************************************************************************************/

date_default_timezone_set('America/Mexico_City'); 
$fecha_act = date('Y-m-d');

$fecha_fin = ultimo_dia_segundo_semestre($fecha_act);
$fecha_ini = primer_dia_segundo_semestre($fecha_act); 

$fecha_ini = date("d-m-Y",strtotime($fecha_ini));
$fecha_fin = date("d-m-Y",strtotime($fecha_fin)); 

$fecha = explode("-", $fecha_act);
$year = $fecha[0];

echo'
    <div class="col-md-6">
        <button type="submit" class="btn btn-success btn-lg btn-block"  aria-label="Left Align">'; 
            $año= 'GRÁFICAS DEL SEGUNDO SEMESTRE DEL '.$year;
            echo $año; 
echo'                    
        </button>
        <br>';

echo '  <div class="panel panel-success">

            <!--    inicio panel heading   -->

            <div class="panel-heading">';
echo'           <h3 class="panel-title">PACIENTES ATENDIDOS del '.$fecha_ini.' al '.$fecha_fin.'</h3>
            </div>
            
            <!--    fin panel heading   -->

            <!--    inicio panel body   -->';
echo'
            <div class="panel-body">
                <!-- CHART Aquí se invoca   -->
                
                    <div id="segundo_semestre" class="chart"></div>
                
                <!-- Fin CHART   -->
                ';
            
                //$datos = array();
                $estatus = 'ATENDIDO';
                $fecha_ini = date('Y-m-d',strtotime($fecha_ini));
                $fecha_fin = date('Y-m-d',strtotime($fecha_fin));
                $datos_atendido_institucion_1 = cantidad_de_pacientes_por_mes($fecha_ini, $fecha_fin,$_POST['pagina'],$estatus );
            
                // echo '<pre>';
                // print_r($datos_atendido_institucion_1);
                // echo '</pre>';
                $num = count($datos_atendido_institucion_1);
            

            echo '<div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="myClass2" >
                        <thead>
                            <tr>
                                <th>Institucion</th>
                                <th>Cantidad</th>';
                    
                    echo '  </tr>
                        </thead>
                        <tbody>';
                            $total_de_estudios=0;

                            for($i=0; $i < $num; $i++){
                                echo '<tr>';
                                echo        '<td>'.$datos_atendido_institucion_1[$i]['institucion'].'</td>';
                                echo        '<td>'.$datos_atendido_institucion_1[$i]['cantidad'].'</td>';
                                echo '</tr>';
                                $total_de_estudios +=$datos_atendido_institucion_1[$i]['cantidad'];
                            }
                                echo '<tr>';
                                echo '      <td> <strong>Total de estudios atendidos (sumatoria):</strong> </td>';
                                echo '      <td> <strong>'.$total_de_estudios.'</strong> </td>';
                                echo '</tr>';

                    echo '</tbody>
                    </table>
                </div>';
         

            // echo '<pre>';
            // print_r($datos_atendido_institucion_1);
            // echo '</pre>';
                        
        echo'</div>
            
            <!-- /. fin panel body -->

        </div>

        <!-- /. fin panel  -->';            
echo'    
    </div>

    <!--  fin Col-6 -->';

echo'   </div>
    
    <!--  fin row -->';


}



//////////////////////////////////////////

// agregado el 28 de Noviembre de 2017 para las funciones de graficación
function last_year_day($fecha) { 
        
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);
        /*$month = date('m');
        $year = date('Y');*/
        $year = $fecha[0];
        $month= $fecha[1];
        
        //$day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
        return date('Y-m-d', mktime(0,0,0, 12, 31, $year));
};
function first_year_day($fecha) {  
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);   
        $year = $fecha[0];
  
        return date('Y-m-d', mktime(0,0,0, 1, 1, $year));
}

function fecha_letras_sin_dia($fecha_estudio){

        date_default_timezone_set('America/Mexico_City');
        //Variable nombre del mes 
        $nommes = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"); 
        //variable nombre día 
        $nomdia = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"); 

        /*  date(j) toma valores de 1 al 31 segun el dia del mes 
            date(n) devuelve numero del 1 al 12 segun el mes 
            date(w) devuelve 0 a 6 del dia de la semana empezando el domingo 
            date(Y) devuelve el año en 4 digitos */ 

        $dia        = date("j",strtotime($fecha_estudio)); //Dia del mes en numero 
        $mes        = date("n",strtotime($fecha_estudio)); //Mes actual en numero 
        $diasemana  = date("w",strtotime($fecha_estudio)); //Dia de semana en numero 
        $año        = date("Y",strtotime($fecha_estudio));

        $fecha_estudio2 = $dia." de ".$nommes[$mes-1]." del ".$año; 
        return $fecha_estudio2;
}

?>