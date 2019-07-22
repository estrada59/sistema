<?php 

include_once "include/mysql.php";

if(isset($_POST))
{
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    $fecha_inicio           = $_POST['fecha_inicio'];
    $fecha_fin              = $_POST['fecha_fin'];
    $nombre_institucion     =$_POST['nombre_institucion'];
    $nombre_minusculas      =$_POST['nombre_minusculas'];
    $id_contrato            =$_POST['id_contrato'];

   // NOMBRE DEL ARCHIVO Y CHARSET

	header('Content-Type:text/csv; charset=latin1');
	header('Content-Disposition: attachment; filename="Pacientes '.$nombre_institucion.' del periodo '.$fecha_inicio.' al '.$fecha_fin.'.csv"');

    // SALIDA DEL ARCHIVO
    
    $salida=fopen('php://output', 'w');
    
    // ENCABEZADOS
    
    fputcsv($salida, array('Fecha de cita', 'Nombre del paciente', 'Estudio realizado', 'Precio del estudio CON IVA', 'Estatus', 'Observaciones'));
    
    // QUERY PARA CREAR EL REPORTE

    $mysql =new mysql();
    $link = $mysql->connect();

    $cadena = "SELECT 	
                        pacientes.fecha,
                        concat(pacientes.nombre,' ',pacientes.ap_paterno,' ',pacientes.ap_materno) as nombre,
                        concat(estudio.tipo,' ',estudio.nombre) as estudio,
                        estudio.$nombre_minusculas as precio_estudio,
                        pacientes.estatus,
                        pacientes.observaciones,
                        pacientes.indicaciones_tratamiento
                        
                        
                from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                        from tbl_montos_disponibles 
                                        where id_montos_disponibles=  $id_contrato)
                        )
                        and
                        (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                        from tbl_montos_disponibles 
                                        where id_montos_disponibles=  $id_contrato)	
                        ))
                        and
                        (pacientes.institucion = '$nombre_institucion')";
    
    $total_atendido = "SELECT 	
                        
                        sum(estudio.$nombre_minusculas) as total_atendido
                        
                from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                        from tbl_montos_disponibles 
                                        where id_montos_disponibles=  $id_contrato)
                        )
                        and
                        (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                        from tbl_montos_disponibles 
                                        where id_montos_disponibles=  $id_contrato)	
                        ))
                        and
                        (pacientes.institucion = '$nombre_institucion')
                        and 
                        (pacientes.estatus = 'ATENDIDO')";

    $total_por_atender = "SELECT 	
                        
                                sum(estudio.$nombre_minusculas) as total_atendido
                                
                        from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                        where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                from tbl_montos_disponibles 
                                                where id_montos_disponibles=  $id_contrato)
                                )
                                and
                                (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                from tbl_montos_disponibles 
                                                where id_montos_disponibles=  $id_contrato)	
                                ))
                                and
                                (pacientes.institucion = '$nombre_institucion')
                                and 
                                (pacientes.estatus = 'POR ATENDER')";
    $total_cancelado = "SELECT 	
                        
                                    sum(estudio.$nombre_minusculas) as total_atendido
                                    
                            from pacientes INNER JOIN estudio on idgammagramas = pacientes.estudio_idgammagramas

                            where  ((fecha >= (select   DATE_FORMAT(fecha_inicio, '%Y-%m-%d') as fecha_inicio 
                                                    from tbl_montos_disponibles 
                                                    where id_montos_disponibles=  $id_contrato)
                                    )
                                    and
                                    (fecha <= (select   DATE_FORMAT(fecha_fin, '%Y-%m-%d') as fecha_fin 
                                                    from tbl_montos_disponibles 
                                                    where id_montos_disponibles=  $id_contrato)	
                                    ))
                                    and
                                    (pacientes.institucion = '$nombre_institucion')
                                    and 
                                    (pacientes.estatus = 'CANCELADO')";


    $sql = $mysql->query($link,"$cadena");

    $atendido = $mysql->query($link,"$total_atendido");
    $por_atender = $mysql->query($link,"$total_por_atender");
    $cancelado = $mysql->query($link,"$total_cancelado");



	
    while($row = $mysql->f_array($sql))
    {
        if($row['indicaciones_tratamiento'] != '')
        {
            $row['estudio'] = $row['estudio'].' Dosis: '.$row['indicaciones_tratamiento'].' mCi';
        }
		fputcsv($salida, array($row['fecha'], 
								$row['nombre'],
								$row['estudio'],
								$row['precio_estudio'],
                                $row['estatus'],
                                $row['observaciones'],));
    }

    $row_atendido = $mysql->f_array($atendido);
    $row_por_atender = $mysql->f_array($por_atender);
    $row_cancelado = $mysql->f_array($cancelado);

    $total = $row_atendido['total_atendido'] + $row_por_atender['total_atendido'] + $row_cancelado['total_atendido'];

    fputcsv($salida, array(''));
    fputcsv($salida, array(''));
    fputcsv($salida, array(' ',' ',' ',$total));
    fputcsv($salida, array(''));
    fputcsv($salida, array(''));
    fputcsv($salida, array(' ',' ','Total atendido',$row_atendido['total_atendido']));
    fputcsv($salida, array(' ',' ','Total por atender',$row_por_atender['total_atendido']));
    fputcsv($salida, array(' ',' ','Total cancelado',$row_cancelado['total_atendido']));
    

   
}

?>