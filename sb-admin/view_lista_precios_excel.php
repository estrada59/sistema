<?php 

include_once "include/mysql.php";

if(isset($_POST))
{
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    
    $nombre_institucion_bd     =$_POST['nombre_institucion_bd'];
    $nombre_institucion     =$_POST['nombre_institucion'];
    

   // NOMBRE DEL ARCHIVO Y CHARSET
 

    //header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
 
    header('Content-type: application/vnd.ms-excel;charset=iso-8859-15');
	header('Content-Disposition: attachment; filename="Lista de precios '.$_POST['nombre_institucion'].'.csv"');

    // SALIDA DEL ARCHIVO
    
    $salida=fopen('php://output', 'w');
    
    // ENCABEZADOS
    
    fputcsv($salida, array('No.', 'Nombre del estudio', 'Precio del estudio CON IVA'));
    
    // QUERY PARA CREAR EL REPORTE

    $mysql =new mysql();
    $link = $mysql->connect();

    $cadena = "SELECT   idgammagramas,
                        concat(tipo, ' ',nombre) AS estudio,
                        $nombre_institucion_bd as precio
                FROM estudio 
                WHERE idgammagramas=idgammagramas and $nombre_institucion_bd != 0.00 ORDER BY idgammagramas";
    

    $sql = $mysql->query($link,"$cadena");

    
	$cont = 1;
    while($row = $mysql->f_obj($sql))
    {
        
		fputcsv($salida, array($cont, 
								$row->estudio,
								$row->precio));
        $cont++;
    }
   
}

?>