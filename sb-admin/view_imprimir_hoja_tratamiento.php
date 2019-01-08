<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 

	if(!isset($_POST['fecha'])){
		header("Location: index.php");
	}
	

class MYPDF extends TCPDF {
	public function Header() {
		//$this->setJPEGQuality(90);
		
		// set bacground image
        //$this->Image('images/hoja_membretada.jpg', $x = 0, $y = 15, $w = 230, $h = 297, '', '', $align='center', false, 300, '', false, false, 0);
		$this->ImageSVG($file='images/logo_numedics.svg', $x=142, $y=5, $w='60', $h='30', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		$this->CreateTextBox('MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V', -6,8, 80, 10, 11, 'B');
		$this->CreateTextBox('DR. JORGE LUIS CISNEROS ENCALADA', 5,17, 80, 10, 11, '');		
		$this->CreateTextBox('Cédula profesional: 1576031     ESP. AECEM-24430.', 0,23, 80, 10, 10, '');		

		$style = array('width' => 0.8, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(4,94,87, 0));
		$this->Line(50,260,205,260,$style);
		$this->CreateTextBox('14 pte. nte. no. 216 esq. Circunvalación pichucalco, Col. Moctezuma, C.P. 29030, Tuxtla Gutiérrez, Chiapas.', 30,262, 80, 10, 9, '');		
		$this->CreateTextBox('Tel.: 60 29 211 y 60 29 479                   E-mail: jorge@numedics.com.mx                   www.numedics.com.mx', 30,266, 80, 10, 9, '');		
				
		$this->ImageSVG($file='images/logo.svg', $x=-70, $y=200, $w='135', $h='135', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	}
	public function Footer() {
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 5, $fontsize = 11, $fontstyle = 'ARIAL', $align = 'L') {
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align,'','',$strech=0);
	}
	public function Textbox($textval, $x=0, $y, $w=0,$h=3,$b=0,$align='L',$fontsize = 12,$fontstyle='' ){
		$this->SetXY($x+20, $y); // 20 = margin left
		//$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($w, $h, $textval, $b, 2, $align,'','',$strech=1);
	}
	public function tratamiento(){
		
		include_once "include/funciones_consultas.php";
		
	    //print_r($_POST);
		$fecha = $_POST['fecha'];
		$fecha = 'TUXTLA GUTIÉRREZ, CHIAPAS A '.fecha_letras_sin_dia($fecha);
		$paciente = $_POST['nombre_paciente'];
		$dr = $_POST['nombre_medico'];
		$mci = $_POST['cantidad_i131'];
		$institucion = $_POST['institucion'];

		if($mci >=100){
			$dias = 8;
		}else{
			$dias = 3;
		}

		$this->TextBox($fecha, 75,55, 100, 5, 0, 'R');

		$this->SetFont('Helvetica', 'B', 12, '', 'false');

		$this->TextBox($paciente, 0,75, 100, 5, 0, 'L');
		$this->TextBox('Dosis: '.$mci.' mCi', 100,75, 50, 5, 0, 'L');
		$this->SetFont('Helvetica', '', 12, '', 'false');
		$this->TextBox('INSTITUCIÓN: '.$institucion, 0,82, 100, 5, 0, 'L');

		$this->SetFont('Helvetica', '', 12, '', 'false');

		$text = '1.- Su tratamiento fue indicado por su médico especialista. '.$dr;
		$this->TextBox($text, 0,91, 180, 10, 0, 'L');
		$text = '2.- El material radiactivo se elimina en unos días, por orina, excremento, y sudor.';
		$this->TextBox($text, 0,97, 180, 10, 0, 'L');
		$text = '3.- Para su seguridad y de las personas que conviven con usted, se le sugiere que';
		$this->TextBox($text, 0,103, 180, 10, 0, 'L');
		$text = '     haga lo siguiente por lo menos los siguientes ';
		$this->TextBox($text, 0,115, 180, 10, 0, 'L');

		$this->SetFont('Helvetica', 'B', 12, '', 'false');
		$text = $dias.' días.';
		$this->TextBox($text, 92,115, 180, 10, 0, 'L');

		$this->SetFont('Helvetica', '', 12, '', 'false');
		$text = 'a) Mantenerse a una distancia mayor a un metro de bebes, niños, y mujeres embarazadas.' ;
		$this->TextBox($text, 10,121, 170, 10, 0, 'L');
		$text = 'b) De ser posible duerma solo(a) en una cama aunque en la recámara duerman otras personas.' ;
		$this->TextBox($text, 10,127, 170, 10, 0, 'L');
		$text = 'c) De ser posible lavar sus trastes y ropa separada  a las del resto de su familia.' ;
		$this->TextBox($text, 10,133, 170, 10, 0, 'L');
		$text = 'd) Evite tener relaciones sexuales durante el periodo indicado.' ;
		$this->TextBox($text, 10,139, 170, 10, 0, 'L');
		$text = 'e) Después de ir al baño jale al escusado tres veces.' ;
		$this->TextBox($text, 10,145, 170, 10, 0, 'L');
		$text = 'f) Tome su medicina como se lo indique su médico y acuda a su cita.' ;
		$this->TextBox($text, 10,151, 170, 10, 0, 'L');
		$text = 'g) Tome suficientes líquidos por los siguientes';
		$this->TextBox($text, 10,157, 170, 10, 0, 'L');
		
		$this->SetFont('Helvetica', 'B', 12, '', 'false');
		$text = $dias.' días.';
		$this->TextBox($text, 99,157, 170, 10, 0, 'L');

		$this->SetFont('Helvetica', '', 12, '', 'false');
		$text = 'h) Si tiene dolor en el cuello, o por debajo de las orejas, acuda al departamento de' ;
		$this->TextBox($text, 10,163, 170, 10, 0, 'L');
		$text = '    medicina nuclear en horas hábiles.' ;
		$this->TextBox($text, 10,169, 170, 10, 0, 'L');
		$text = 'i) De ser posible evite frecuentar lugares y transportes públicos.' ;
		$this->TextBox($text, 10,174, 170, 10, 0, 'L');

		$text = 'NOM-013-NUCL-1995' ;
		$this->TextBox($text, 10,185, 170, 10, 0, 'L');

		if($mci >= 100){
			$this->SetFont('Helvetica', 'B', 12, '', 'false');
			$text = 'NOTA: Es necesario que el paciente se presente en la clínica dentro de 8 días a partir' ;
			
			$this->TextBox($text, 10,206, 170, 10, 0, 'L');
			
			$text = 'de la fecha de alta para que se le realice el Rastreo Tiroideo.' ;
			$this->TextBox($text, 10,211, 170, 10, 0, 'L');

			$this->SetFont('Helvetica', '', 12, '', 'false');
		}
		

		$this->Image('images/firma/firma.png', $x = 100, $y = 210, $w = 40, $h =45, '', '', $align='center', $resize='true', $dpi=600, '', false, false, $border= 0);
		$text = 'DR. JORGE LUIS CISNEROS ENCALADA' ;
		$this->TextBox($text, 10,240, 170, 10, 0, 'C');
		$text = 'MÉDICO NUCLEAR' ;
		$this->TextBox($text, 10,245, 170, 10, 0, 'C');
	}
	public function tratamiento_copia_recepcion(){
		
		include_once "include/funciones_consultas.php";
		
	    //print_r($_POST);
		$fecha = $_POST['fecha'];
		$fecha = 'TUXTLA GUTIÉRREZ, CHIAPAS A '.fecha_letras_sin_dia($fecha);
		$paciente = $_POST['nombre_paciente'];
		$dr = $_POST['nombre_medico'];
		$mci = $_POST['cantidad_i131'];
		$institucion = $_POST['institucion'];

		if($mci >=100){
			$dias = 8;
		}else{
			$dias = 3;
		}

		$this->TextBox($fecha, 75,55, 100, 5, 0, 'R');

		$this->SetFont('Helvetica', 'B', 12, '', 'false');

		$this->TextBox($paciente, 0,75, 100, 5, 0, 'L');
		$this->TextBox('Dosis: '.$mci.' mCi', 100,75, 50, 5, 0, 'L');
		$this->SetFont('Helvetica', '', 12, '', 'false');
		$this->TextBox('INSTITUCIÓN: '.$institucion, 0,82, 100, 5, 0, 'L');

		$this->SetFont('Helvetica', '', 12, '', 'false');

		$text = '1.- Su tratamiento fue indicado por su médico especialista. '.$dr;
		$this->TextBox($text, 0,91, 180, 10, 0, 'L');
		$text = '2.- El material radiactivo se elimina en unos días, por orina, excremento, y sudor.';
		$this->TextBox($text, 0,97, 180, 10, 0, 'L');
		$text = '3.- Para su seguridad y de las personas que conviven con usted, se le sugiere que';
		$this->TextBox($text, 0,103, 180, 10, 0, 'L');
		$text = '     haga lo siguiente por lo menos los siguientes ';
		$this->TextBox($text, 0,115, 180, 10, 0, 'L');

		$this->SetFont('Helvetica', 'B', 12, '', 'false');
		$text = $dias.' días.';
		$this->TextBox($text, 92,115, 180, 10, 0, 'L');

		$this->SetFont('Helvetica', '', 12, '', 'false');
		$text = 'a) Mantenerse a una distancia mayor a un metro de bebes, niños, y mujeres embarazadas.' ;
		$this->TextBox($text, 10,121, 170, 10, 0, 'L');
		$text = 'b) De ser posible duerma solo(a) en una cama aunque en la recámara duerman otras personas.' ;
		$this->TextBox($text, 10,127, 170, 10, 0, 'L');
		$text = 'c) De ser posible lavar sus trastes y ropa separada  a las del resto de su familia.' ;
		$this->TextBox($text, 10,133, 170, 10, 0, 'L');
		$text = 'd) Evite tener relaciones sexuales durante el periodo indicado.' ;
		$this->TextBox($text, 10,139, 170, 10, 0, 'L');
		$text = 'e) Después de ir al baño jale al escusado tres veces.' ;
		$this->TextBox($text, 10,145, 170, 10, 0, 'L');
		$text = 'f) Tome su medicina como se lo indique su médico y acuda a su cita.' ;
		$this->TextBox($text, 10,151, 170, 10, 0, 'L');
		$text = 'g) Tome suficientes líquidos por los siguientes';
		$this->TextBox($text, 10,157, 170, 10, 0, 'L');
		
		$this->SetFont('Helvetica', 'B', 12, '', 'false');
		$text = $dias.' días.';
		$this->TextBox($text, 99,157, 170, 10, 0, 'L');

		$this->SetFont('Helvetica', '', 12, '', 'false');
		$text = 'h) Si tiene dolor en el cuello, o por debajo de las orejas, acuda al departamento de' ;
		$this->TextBox($text, 10,163, 170, 10, 0, 'L');
		$text = '    medicina nuclear en horas hábiles.' ;
		$this->TextBox($text, 10,169, 170, 10, 0, 'L');
		$text = 'i) De ser posible evite frecuentar lugares y transportes públicos.' ;
		$this->TextBox($text, 10,174, 170, 10, 0, 'L');

		$text = 'NOM-013-NUCL-1995' ;
		$this->TextBox($text, 10,185, 170, 10, 0, 'L');

		if($mci >= 100){
			$this->SetFont('Helvetica', 'B', 12, '', 'false');
			$text = 'NOTA: Es necesario que el paciente se presente en la clínica dentro de 8 días a partir' ;
			
			$this->TextBox($text, 10,206, 170, 10, 0, 'L');
			
			$text = 'de la fecha de alta para que se le realice el Rastreo Tiroideo.' ;
			$this->TextBox($text, 10,211, 170, 10, 0, 'L');

			$this->SetFont('Helvetica', '', 12, '', 'false');
		}
		
		$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
	    $this->Line(35, 245, 90, 245, $style);
		$text = 'Nombre y firma' ;
		$this->TextBox($text, 25,243, 170, 10, 0, 'L');

		//$this->Image('images/firma/firma.png', $x = 100, $y = 220, $w = 25, $h =30, '', '', $align='center', $resize='true', $dpi=600, '', false, false, $border= 0);
		$text = 'DR. JORGE LUIS CISNEROS ENCALADA' ;
		$this->TextBox($text, 55,240, 170, 10, 0, 'C');
		$text = 'MÉDICO NUCLEAR' ;
		$this->TextBox($text, 55,245, 170, 10, 0, 'C');
	}	
	
}
	// create a PDF object
	//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
	// set document (meta) information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Numedics (Medicina Nuclear)');
	$pdf->SetTitle('Hoja de alta');
	$pdf->SetSubject('Hoja de alta');
 
	// add a page
	$pdf->AddPage();
	$pdf->tratamiento();
	$pdf->AddPage();
	$pdf->tratamiento_copia_recepcion();
	//Close and output PDF document
	$pdf->Output('hoja_tratamiento.pdf', 'I');
?>
