<?php
function ver_pacientes_del_dia($fecha_act, $pagina){

/*---------------------------------------------------------------------
//Permite ver a los pacientes que se tienen citados en determinada fecha
//para eso sele pasa como parametro la fecha en la que queremos saber
//que pacientes hay citados, ademas de que esta rutina se emplea para
//el área de recepción ya que le indica con franjas de colores que paciente
//deben (franaja color rojo) y cuales no (franja color verde indica que 
ya pagaron el total)
---------------------------------------------------------------------*/
    echo '
        <div class ="table-responsive">
            <table class ="table table-bordered table-hover table-striped">
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

function ver_pacientes_del_mes($fecha_ini, $fecha_fin, $pagina){
    
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

function ver_pacientes_del_dia_operario($fecha_act, $pagina){
/*---------------------------------------------------------------------
//Permite ver a los pacientes que se tienen citados en determinada fecha
//para eso sele pasa como parametro la fecha en la que queremos saber
//que pacientes hay citados, ademas de que esta rutina se emplea para
//el área en donde está la técnico. Aquí no se indica si debe o no.
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
---------------------------------------------------------------------*/
    echo '
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th data-field="id">Fecha</th>
                        <th data-field="name">Nombre de la paciente</th>
                        <th data-field="price">Nombre del estudio</th>
                        <th data-field="price">Institución</th>
                        <th data-field="estatus">Estatus</th>
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
                            mod_estatus_pac($row,$pagina);
                            echo '</tr>';
                        }
                        else{
                            if($row->institucion == 'PARTICULAR'){
                                echo '<tr class ="success" >'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';    
                                echo '<td>'.$row->institucion.'</td>';
                                mod_estatus_pac($row,$pagina);
                                //editar_datos_pac($row);
                                echo '</tr>';
                            }
                            else{
                                echo '<tr>'; 
                                echo '<td>'.$row->fecha.'</td>';
                                echo '<td>'.$row->nombre.'</td>';
                                echo '<td>'.$row->estudio.'</td>';    
                                echo '<td>'.$row->institucion.'</td>';
                                mod_estatus_pac($row,$pagina);
                                
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

function mod_estatus_pac($row, $pagina){
    //echo $pagina;
    echo'<td>
            <form role="form" id="estatus'.$row->idpacientes.'" method="post" action="controlador_actualizar_estatus.php">
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "estatus" value="'.$row->estatus.'"/>
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "pagina_destino" value="'.$pagina.'"/>

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

function mod_estatus_pac_requiere_factura($row, $pagina){
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
                        if($_SESSION['nivel_acceso']<=2){
                             echo '
                            <button   class="btn btn-default btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3){
                            echo '
                            <button type="submit" form="estatus_factura'.$row->idpacientes.'" name="editar" class="btn btn-default btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                    }
                    
                }
                if($row->requiere_factura == 'SI'){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=2){
                            echo '
                            <button  class="btn btn-danger  btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_factura'.$row->idpacientes.'" name="editar" class="btn btn-danger btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                    }
                } 
                if($row->requiere_factura == NULL){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=2){
                            echo '
                            <button  class="btn btn-info  btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->requiere_factura.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_factura'.$row->idpacientes.'" name="editar" class="btn btn-info  btn-block" >
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
                        if($_SESSION['nivel_acceso']<=2){
                             echo '
                            <button   class="btn btn-default btn-block " >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3){
                            echo '
                            <button type="submit" form="estatus_facturado'.$row->idpacientes.'" name="editar" class="btn btn-default btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                    }
                    
                }
                if($row->facturado == 'SI'){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=2){
                            echo '
                            <button  class="btn btn-danger  btn-block " >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_facturado'.$row->idpacientes.'" name="editar" class="btn btn-danger btn-block" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                    }
                } 
                if($row->facturado == NULL){
                    if(isset($_SESSION) ) {
                        if($_SESSION['nivel_acceso']<=2){
                            echo '
                            <button  class="btn btn-info  btn-block disabled" >
                                <span class="glyphicon " aria-hidden="true"></span>
                                '.$row->facturado.'
                            </button>';
                        }
                        elseif($_SESSION['nivel_acceso']<=3) {
                            echo '
                            <button type="submit" form="estatus_facturado'.$row->idpacientes.'" name="editar" class="btn btn-info  btn-block" >
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


function editar_datos_pac($row){
     echo'   <td>
                <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_editar_pacientes_por_fecha.php">
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="idpaciente" value="'.$row->idpacientes.'"/>
                    <input type="hidden" form="edicion'.$row->idpacientes.'" name="fecha_estudio" value="'.$row->fecha.'"/>
                                        
                    <button type="submit" form="edicion'.$row->idpacientes.'" name="editar" class="btn btn-warning btn-default btn-block" >
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        Editar
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
//---------------------------------------------------------------------
//Despliega una lista de pacientes que estan agendados en la $fecha_act.    
//posteriormente muestra datos del paciente y la opción editar para
//cambiar el estudio tanto a pacientes de institucion Particular
//como a los de institución Pública.
//---------------------------------------------------------------------
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
//---------------------------------------------------------------------
//Si un paciente que tiene subrogado de cualquier institución requiere
//pagar el  estudio por su propia cuenta y ya no hacer uso del subrogado 
//que la institucion le emitio puede hacerlo solo hay que crearle una 
//cuenta como paciente particular para poder cobrarle.    
//---------------------------------------------------------------------

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

                            //mod_estatus($row);
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
                                    WHERE tipo='PUBLICA' AND estatus='ACTIVO';");

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

                                                            (SELECT t3.monto_restante 
                                                                FROM pacientes_has_anticipos t3 
                                                                WHERE t3.pacientes_idpacientes = t1.idpacientes and t3.fecha_anticipo='0000-00-00' ) as debe,
                                                            
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
                                    if($row->debe > '0.00'){    //si no debe no se muestra
                                        echo'<td>
                                                <form role="form" id="edicion'.$row->idpacientes.'" method="post" action="controlador_agregar_anticipos_por_fecha.php">
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name = "idpaciente" value="'.$row->idpacientes.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name = "fecha_estudio" value="'.$fecha_estudio.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name = "idanticipo" value="'.$row->idanticipo.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name="nombre_paciente" value="'.$row->nombre.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name="estudio" value="'.$row->estudio.'"/>';
                                       
                                        echo'   <input type="hidden" form="edicion'.$row->idpacientes.'" name="precio" value="'.$row->precio.'"/>';
                                
                                
                                        echo'   <input type="hidden" form="edicion'.$row->idpacientes.'" name="hora" value="'.$row->hora.'"/>
                                                <input type="hidden" form="edicion'.$row->idpacientes.'" name="debe" value="'.$row->debe.'"/>
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
                                                t7.factura,
                                                t7.no_recibo

                                        FROM pacientes_has_anticipos t1 inner join anticipos t7 
                                        on idanticipos = t1.anticipos_idanticipos
                                        WHERE fecha_anticipo = '$fecha_anticipo' ORDER BY t7.no_recibo ");
            
                    
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

                    if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00'){
                        $colum_set = 'Efectivo';
                        $monto_anticipo = $row->anticipo_efe;} //es efectivo

                    if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00'){
                        $colum_set = 'Transferencia';
                        $monto_anticipo = $row->transferencia;} //transferencia

                    if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
                        $colum_set = 'Pago con cheque';
                        $monto_anticipo = $row->pago_cheque;} //pago cheque

                    if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
                        $colum_set = 'Pago santander';
                        $monto_anticipo = $row->pago_santander;} //pago santander

                    if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
                        $colum_set = 'Pago banamex';
                        $monto_anticipo = $row->dep_banamex;}
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

                    include_once "mysql.php";

                    $fecha_ini = first_month_day($fecha_estudio);
                    $fecha_fin = last_month_day($fecha_estudio);


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
                                                            t7.transferencia,
                                                            t7.pago_cheque,
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

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00'){
                                $colum_set = 'Efectivo';
                                $monto_anticipo = $row->anticipo_efe;} //es efectivo

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00'){
                                $colum_set = 'Transferencia';
                                $monto_anticipo = $row->transferencia;} //transferencia

                            if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
                                $colum_set = 'Pago con cheque';
                                $monto_anticipo = $row->pago_cheque;} //pago cheque

                            if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
                                $colum_set = 'Pago santander';
                                $monto_anticipo = $row->pago_santander;} //pago santander

                            if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
                                $colum_set = 'Pago banamex';
                                $monto_anticipo = $row->dep_banamex;}
                        }

                        echo '<tr>'; 
                        
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
                        echo '<td> '.$row->factura.'</td>';
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

function ver_anticipos_anteriores($idpaciente){
    
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
                                            no_recibo
                                    FROM anticipos
                                    WHERE idanticipos = $idanticipo_pac");
                                                    
        $row = $mysql->f_obj($sql);
                                                        
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00'){
            $colum_set = 'Efectivo';
            $monto_anticipo = $row->anticipo_efe;} //es efectivo

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Transferencia';
            $monto_anticipo = $row->transferencia;} //transferencia

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Pago con cheque';
            $monto_anticipo = $row->pago_cheque;} //pago cheque

        if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Pago santander';
            $monto_anticipo = $row->pago_santander;} //pago santander

        if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Pago banamex';        //pago banamex
            $monto_anticipo = $row->dep_banamex;}
                                                
        $monto_anticipo     =   number_format($monto_anticipo,2);

        echo '
                            <tr>
                                <td align="center">'.$fecha_anticipo_pac.'</td>
                                <td>'.$colum_set.'</td>
                                <td align="center">$ '.$monto_anticipo.'</td>
                            </tr>';
    }  
    echo'               </table>
                    </div>
                </div>'; 
}

function sumatoria_de_anticipos($idpaciente){
    
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
                                            no_recibo
                                    FROM anticipos
                                    WHERE idanticipos = $idanticipo");
                                                    
        $row = $mysql->f_obj($sql);
                                                        
        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00'){
            $colum_set = 'Efectivo';
            $monto_anticipo = $row->anticipo_efe;} //es efectivo

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Transferencia';
            $monto_anticipo = $row->transferencia;} //transferencia

        if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Pago con cheque';
            $monto_anticipo = $row->pago_cheque;} //pago cheque

        if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Pago santander';
            $monto_anticipo = $row->pago_santander;} //pago santander

        if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
            $colum_set = 'Pago banamex';        //pago banamex
            $monto_anticipo = $row->dep_banamex;}
                                                
        $sumatoria_anticipos += $monto_anticipo;
    }
    $mysql->close();
    return $sumatoria_anticipos;   
}

function ver_tratamientos_del_dia($fecha_act){

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
                <input type="hidden" form="estatus'.$row->idpacientes.'" name = "fecha" value="'.$row->fecha.'"/>';
                
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
    
    echo '
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
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
}

function fecha_letras($fecha_estudio){

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

    $lista = array( 'lunes'=> $lunes,
                    'martes'=> $martes,
                    'miercoles'=> $miercoles,
                    'jueves'=> $jueves,
                    'viernes'=> $viernes,
                    'sabado'=> $sabado
                    );
    # A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
    //$ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
 
   /* echo "<br>Semana: ".$semana." - año: ".$year;
    echo "<br>Primer día ".$primerDia;
    echo "<br>Ultimo día ".$ultimoDia;*/
    return $lista;
}

function obtener_mes($fecha){
    date_default_timezone_set('America/Mexico_City');
    $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
    $mes = $mes[(date('m', strtotime($fecha))*1)-1];
    return $mes;
}

function pasarMayusculas($cadena) { 
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
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);
        /*$month = date('m');
        $year = date('Y');*/
        $year = $fecha[0];
        $month= $fecha[1];
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
        return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
};
 
function first_month_day($fecha) {
        date_default_timezone_set('America/Mexico_City');
        $fecha = explode("-", $fecha);
        /*$month = date('m');
        $year = date('Y');*/
        $year = $fecha[0];
        $month= $fecha[1];
        return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
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