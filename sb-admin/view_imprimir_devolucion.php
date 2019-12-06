<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
	
	$_POST["pagina"]="imrpimir"; 
	session_start();

	if(isset($_POST['id_paciente']))
	{	
		$devolucion = new devolucion();
		$devolucion->agregar();

	}



class MYPDF extends TCPDF {
	public function Header() {
	}
	public function Footer() {
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align,'','',$strech=0);
	}
	public function Textbox($textval, $x=0, $y, $w=0,$h=3,$b=0,$align='L',$fontsize = 12,$fontstyle='' ){
		$this->SetXY($x+20, $y); // 20 = margin left
		//$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($w, $h, $textval, $b, 2, $align,'','',$strech=1);
	}
	public function Recibo_paciente($ax_x, $ax_y) {

		$this->ImageSVG($file='images/logo_numedics.svg', $x=6+$ax_x, $y=13+$ax_y, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		//	Marco.
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 10+$ax_y, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 75+$ax_y, 4.5, 60,'DF');
		$this->Rect(206+$ax_x, 10+$ax_y, 5, 66,'DF');

		$this->SetFont('Helvetica', '', 10, '', 'false');
		$this->CreateTextBox('MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V', 90+$ax_x,18+$ax_y, 80, 10, 10, 'B','');
		$this->CreateTextBox('Circunvalación pichucalco No. 216 Col. Moctezuma', 95+$ax_x,27+$ax_y, 80, 10, 10, '');		
		$this->CreateTextBox('C.P. 29030 Tuxtla Gutiérrez, Chiapas.', 105+$ax_x,33+$ax_y, 80, 10, 10, '');		

		$this->CreateTextBox('Teléfonos: (961) 60 292 11  y (961) 60 294 79 ext. 1', 95+$ax_x,40+$ax_y, 80, 10, 10, '');
		$this->CreateTextBox('www.numedics.com.mx', 117+$ax_x,45+$ax_y, 80, 10, 10, '');

		
					include_once "include/funciones_consultas.php";
					include_once "include/mysql.php";

					$monto_devolucion =number_format( $_POST['monto_devolucion'], 2 );
					$id_paciente   		= $_POST['id_paciente'];

					date_default_timezone_set('America/Mexico_City');
					$fecha = date('d-m-Y');

					$fecha_letras = fecha_letras($fecha);
                    
					$this->CreateTextBox('Tuxtla Gutiérrez, Chiapas a '.$fecha_letras, 80, 58, 80, 10, 10, 'B');
					$this->CreateTextBox('Recibí de MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V. la cantidad de: ', 0, 70, 83 , 10, 10, '');

					// $this->CreateTextBox('la cantidad de: ', 0, 75, 80, 10, 10, '');
					$this->CreateTextBox('$ '.$monto_devolucion, 140, 70, 80, 10, 10, 'B');

					include_once "include/num_to_letter.php";
					$V=ValorEnLetras($_POST['monto_devolucion'] , 'pesos');

					$this->CreateTextBox($V, 0, 80, 80, 10, 10, 'B');

					$this->CreateTextBox('Por concepto de DEVOLUCION ', 0, 90, 80, 10, 10, '');



				
					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(20, 125, 96, 125, $style);

					$this->CreateTextBox('Nombre y firma ', 30,125, 80, 10, 10, '');		

					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(124, 125, 200, 125, $style);
				
					$id_usuario = $_SESSION['id'];
					 

					$mysql = new mysql();
        			$link = $mysql->connect(); 
        			$datos_usuario = $mysql->query($link,"SELECT concat(nombre,' ',ap_paterno,' ', ap_materno) as nombre
															FROM users 
															WHERE idusuario=$id_usuario");        
        			$row4 = $mysql->f_obj($datos_usuario);

					$this->CreateTextBox('Atendio: '.$row4->nombre, 110,125, 80, 10, 10, '');		
					//echo $row4->nombre;
					$mysql->close();	
	}
	public function Recibo_interno($ax_x, $ax_y) {

		$this->ImageSVG($file='images/logo_numedics.svg', $x=6+$ax_x, $y=13+$ax_y, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		//	Marco.
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 10+$ax_y, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 75+$ax_y, 4.5, 60,'DF');
		$this->Rect(206+$ax_x, 10+$ax_y, 5, 66,'DF');

		$this->CreateTextBox('MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V', 90+$ax_x,18+$ax_y, 80, 10, 10, 'B','');
		$this->CreateTextBox('Circunvalación pichucalco No. 216 Col. Moctezuma', 95+$ax_x,27+$ax_y, 80, 10, 10, '');		
		$this->CreateTextBox('C.P. 29030 Tuxtla Gutiérrez, Chiapas.', 105+$ax_x,33+$ax_y, 80, 10, 10, '');		

		$this->CreateTextBox('Teléfonos: (961) 60 292 11  y (961) 60 294 79 ext. 1', 95+$ax_x,40+$ax_y, 80, 10, 10, '');
		$this->CreateTextBox('www.numedics.com.mx', 117+$ax_x,45+$ax_y, 80, 10, 10, '');


					include_once "include/funciones_consultas.php";
					include_once "include/mysql.php";

					$monto_devolucion =number_format( $_POST['monto_devolucion'], 2 );
					$id_paciente   		= $_POST['id_paciente'];

					date_default_timezone_set('America/Mexico_City');
					$fecha = date('d-m-Y');

					$fecha_letras = fecha_letras($fecha);
                    
					$this->CreateTextBox('Tuxtla Gutiérrez, Chiapas a '.$fecha_letras, 80+$ax_x, 58+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox('Recibí de MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V. la cantidad de: ', 0+$ax_x, 70+$ax_y, 83 , 10, 10, '');

					// $this->CreateTextBox('la cantidad de: ', 0, 75, 80, 10, 10, '');
					$this->CreateTextBox('$ '.$monto_devolucion, 140+$ax_x, 70+$ax_y, 80, 10, 10, 'B');

					include_once "include/num_to_letter.php";
					$V=ValorEnLetras($_POST['monto_devolucion'] , 'pesos');

					$this->CreateTextBox($V, 0+$ax_x, 80+$ax_y, 80, 10, 10, 'B');

					$this->CreateTextBox('Por concepto de DEVOLUCION ', 0+$ax_x, 90+$ax_y, 80, 10, 10, '');



				
					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(20+$ax_x, 120+$ax_y, 96+$ax_x, 120+$ax_y, $style);

					$this->CreateTextBox('Nombre y firma ', 30+$ax_x,118+$ax_y, 80, 10, 10, '');		

					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(124+$ax_x, 120+$ax_y, 200+$ax_x, 120+$ax_y, $style);
				
					$id_usuario = $_SESSION['id'];
					 

					$mysql = new mysql();
        			$link = $mysql->connect(); 
        			$datos_usuario = $mysql->query($link,"SELECT concat(nombre,' ',ap_paterno,' ', ap_materno) as nombre
															FROM users 
															WHERE idusuario=$id_usuario");        
        			$row4 = $mysql->f_obj($datos_usuario);

					$this->CreateTextBox('Atendio: '.$row4->nombre, 110+$ax_x,118+$ax_y, 80, 10, 10, '');		
					//echo $row4->nombre;
					$mysql->close();
	}

}

// create a PDF object
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
// set document (meta) information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Numedics (Medicina Nuclear)');
$pdf->SetTitle('Recibo devolución');
$pdf->SetSubject('Recibo devolución');

 
// add a page
$pdf->AddPage();
$pdf->Recibo_paciente(0,0);
$pdf->Recibo_interno(0,130);


				/*	$nombre         = $_POST['nombre'];
                    $apepat         = $_POST['apepat'];
                    $apemat         = $_POST['apemat'];
                    $fecha_estudio  = $_POST['fecha_estudio'];

//Close and output PDF document
$nombre_pdf = $fecha_estudio.'_'.$apepat.'_'.$apemat.'_'.$nombre.'.pdf';      */              
$pdf->Output('devolucion', 'I');

?>


<?php

class devolucion{

	public function agregar()
	{

		include_once "include/funciones_consultas.php";
		include_once "include/mysql.php";

		$monto_devolucion = $_POST['monto_devolucion'];
		$id_paciente   		= $_POST['id_paciente'];
		$id_usuario = $_SESSION['id'];

		date_default_timezone_set('America/Mexico_City');
		$fecha = date('Y-m-d H:i:s');
		

		$mysql = new mysql();
		$link = $mysql->connect(); 
		$agregar = $mysql->query($link,"INSERT INTO tbl_devoluciones (id_devolucion, 
																			idpaciente,
																			idusuario,
																			monto,
																			fecha) 
																	VALUES ('',
																			$id_paciente,
																			$id_usuario,
																			$monto_devolucion,
																			'$fecha')");        
		
		$mysql->close();	
		
	}
}
?>

<?php

?>