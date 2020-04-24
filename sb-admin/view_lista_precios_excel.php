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
    //  La página funciona con utf-8 por lo que hay que convertir a utf-16le

    
    header("Content-Type: application/vnd.ms-excel; charset=UTF-16LE");
    header('Content-Disposition: attachment; filename="Lista de precios '.$_POST['nombre_institucion'].'.csv"');
    
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);

    


    // SALIDA DEL ARCHIVO
    
    $salida=fopen('php://output', 'w');
    
    // ENCABEZADOS
    $encabezado = array('No.', 'Nombre del estudio', 'Precio del estudio CON IVA');
    $encabezado = mb_convert_encoding($encabezado, 'UTF-16LE', 'UTF-8');
    fputcsv($salida, $encabezado);
    
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
        $row->estudio = mb_convert_encoding($row->estudio, 'UTF-16LE', 'UTF-8');
        $row->precio = mb_convert_encoding($row->precio, 'UTF-16LE', 'UTF-8');

		fputcsv($salida, array($cont, 
								$row->estudio,
								$row->precio));
        $cont++;
    }
   
}

?>