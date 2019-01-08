<?php session_start(); ?>
<?php
 
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
include_once 'include/funciones_consultas.php';

//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 
	if(!isset($_POST['idpaciente'])){
		header("Location: index.php");
	}
	else{
	
	}


class MYPDF extends TCPDF {
	public function Header() {
	
		// set bacground image
		//$this->ImageSVG($file='images/logo_numedics.svg', $x=1, $y=18, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		//	Marco.
		/*
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(0, 15, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(0, 80, 4.5, 60,'DF');
		$this->Rect(201, 15, 5, 66,'DF');

		$this->CreateTextBox('MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V', 85,18, 80, 10, 10, 'B');
		$this->CreateTextBox('Circunvalación pichucalco No. 216 Col. Moctezuma', 90,27, 80, 10, 10, '');		
		$this->CreateTextBox('C.P. 29030 Tuxtla Gutiérrez, Chiapas.', 100,33, 80, 10, 10, '');		

		$this->CreateTextBox('Teléfonos: (961) 60 292 11  y (961) 60 294 79 ext. 1', 90,40, 80, 10, 10, '');
		$this->CreateTextBox('www.numedics.com.mx', 112,45, 80, 10, 10, '');

		
		$this->ImageSVG($file='images/logo_numedics.svg', $x=1, $y=160, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		*/
		//	Marco.
		/*
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(0, 157, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(0, 222, 4.5, 60,'DF');
		$this->Rect(201, 157, 5, 66,'DF');
		*/
 
	}
	public function Footer() {	
		
	}
	public function CreateTextBox($textval, $x = 0, $y=0, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x+20, $y+13); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align,'','',$strech=0);
	}
	public function Textbox($textval, $x=0, $y, $w=0,$h=0,$b=0,$align='L' ){
		$this->SetXY($x+20, $y+20); // 20 = margin left
		$this->Cell($w, $h, $textval, $b, 2, $align,'','',$strech=0);
	}
	public function Recibo_paciente() {
					//print_r($_POST);
                    $nombre             = $_POST['nombre_paciente'];
                    $estudio            = $_POST['estudio'];
                    $monto_anticipo     = $_POST['monto_anticipo'];
                    $fecha_anticipo     = $_POST['fecha_anticipo'];

					//echo $monto_anticipo;
                    $anticipo = '$ '.number_format($monto_anticipo,2);

                    //test
                    //$this->CreateTextBox($anticipo, 0,0, 80, 10, 10, '');
                    $this->CreateTextBox($anticipo, 145,25, 80, 10, 10, '');
                    $this->CreateTextBox($nombre, 35,35, 80, 10, 10, '');
					//error_reporting(0);
					include "include/num_to_letter.php";
					
					$V=ValorEnLetras($monto_anticipo, 'pesos');

					$this->CreateTextBox($V, 35,45, 80, 10, 10, '');
					$this->SetFont('helvetica', '', 10);
                    //$this->CreateTextBox($estudio, 35,65, 140, 10, 10, ''); update <-- this for next
                    $this->MultiCell(140, 10, $estudio, 0, 'L', 0, 0, $x=55, $y=88, true);
                    
                    
                  	$fecha = explode('-', $fecha_anticipo);
                  	$año = str_split($fecha[2], 2); // divido en dos 2015 --->  [0]=20   [1]=15
					
                  	
                  	$this->CreateTextBox($fecha[1], 65,85, 80, 10, 10, '');
                  	$this->CreateTextBox($fecha[0], 105,85, 80, 10, 10, '');
                  	$this->CreateTextBox($año[1], 145,85, 80, 10, 10, '');


					//$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					//$this->Line(80, 125, 140, 125, $style);

					//$this->CreateTextBox('Andrea de Jesús Borralles Cigarroa', 55,100, 80, 10, 10, '');	


					//$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					//$this->Line(80, 125, 140, 125, $style);
                  	
                  	$usuario = obtener_usuario();
					$this->CreateTextBox($usuario->nombre, 55,100, 80, 10, 10, '');		
	}
}

// create a PDF object
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
 
// set document (meta) information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Numedics (Medicina Nuclear)');
$pdf->SetTitle('Recibos');
$pdf->SetSubject('Hoja de citas');
 
// add a page
$pdf->AddPage();
$pdf->Recibo_paciente();

//Close and output PDF document
$fecha_anticipo     = $_POST['fecha_anticipo'];
$nombre             = $_POST['nombre_paciente'];

$nombre_pdf = $fecha_anticipo.'_'.$nombre.'.pdf';                    
$pdf->Output($nombre_pdf, 'I');

?>


