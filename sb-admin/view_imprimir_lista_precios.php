<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 

	if(!isset($_POST['imprimir_precios'])){
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
	public function Textbox($textval, $x=0, $y, $w=0,$h=3,$b=0,$align='L',$fontsize = 11,$fontstyle='' ){
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($w, $h, $textval, $b, 2, $align,'','',$strech=1);
	}
	public function imprimir_lista() {
		// set font
		
		$this->Ln();
		$this->CreateTextBox('Lista de precios', 65,33, 80, 10, 13, 'B');		
		$this->Ln();
		$this->Ln();
		$this->SetFont('helvetica', '', 8);

		$x=-2;
		$this->Textbox('No.', $x+0,45, 8, 10, 1, 'C',12,'');		
		$this->Textbox('Estudio.', $x+8,45, 137, 10, 1, 'C',12,'');
		$this->Textbox('Precio', $x+145,45, 30, 10, 1, 'C',12,'');	

		$var = new lista_precios();
		$lista = $var->lista();
		$cont  = count($lista);	
		$tam = round($cont/20);
		
		$y=55;
		$j=0;
		$tmp=0;
		for($i = 0; $i < $tam; $i++)
		{
			$this->Textbox('No.', $x+0,45, 8, 10, 1, 'C',12,'');		
			$this->Textbox('Estudio.', $x+8,45, 137, 10, 1, 'C',12,'');
			$this->Textbox('Precio', $x+145,45, 30, 10, 1, 'C',12,'');

			if($cont>20){
				$var=20;

				for($j; $j < $var; $j++){
				$id = $lista[$tmp]['id'];
				$estudio = $lista[$tmp]['estudio'];
				$precio = $lista[$tmp]['precio'];
			
				$this->Textbox($id, $x,$y, 8, 10, 1, 'C',10,'');		
				$this->Textbox($estudio,  $x+8,$y, 137, 10, 1, 'L',10,'');
				$this->Textbox($precio, $x+145,$y, 30, 10, 1, 'J',10,'');	
				$y+=10;
				$tmp++;
				
			}
			$j=0;
			$y=55;
			$cont-=20;
			$this->AddPage();
			
			}
			if($cont<20 && $cont>0){
				
				for($j; $j < $cont; $j++){
				$id = $lista[$tmp]['id'];
				$estudio = $lista[$tmp]['estudio'];
				$precio = $lista[$tmp]['precio'];
			
				$this->Textbox($id, $x,$y, 8, 10, 1, 'C',10,'');		
				$this->Textbox($estudio,  $x+8,$y, 137, 10, 1, 'L',10,'');
				$this->Textbox($precio, $x+145,$y, 30, 10, 1, 'J',10,'');	
				$y+=10;
				$tmp++;
				
				}
			}
		}	
	}
}

	// create a PDF object
	//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
	// set document (meta) information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Numedics (Medicina Nuclear)');
	$pdf->SetTitle('Lista de precios');
	$pdf->SetSubject('Lista de precios particular');
 
	// add a page
	$pdf->AddPage();
	$pdf->imprimir_lista();
	//Close and output PDF document
	$pdf->Output('Lista_de_precios_particular.pdf', 'I');
?>
<?php
class lista_precios{

	function lista(){
		
		include_once "include/mysql.php";				

		$mysql =new mysql();
		$link = $mysql->connect(); 

        $sql = $mysql->query($link," SELECT idgammagramas,
									concat(tipo, ' ',nombre) as estudio,
        							precio
 									FROM estudio where 1
 									");
        $num_row =$mysql->f_num($sql);

        $cont = 0;
        $lista = array();
        while ($row = $mysql->f_obj($sql)) {
        	
        	$row->precio=number_format($row->precio,2);

        	$lista[$cont] = array(	'id' 		=> $row->idgammagramas, 
        							'estudio' 	=> $row->estudio,
        							'precio'	=> '$ '.$row->precio);
        	$cont +=1;
        }

        return $lista;
		//echo ' filas afectadas: (Se insertó): '.$mysql->affect_row().' registro completo';
		$mysql->close();
	}

}
?>

<?php
	function fecha_letras($fecha_estudio){

		date_default_timezone_set('America/Mexico_City');
    	//Variable nombre del mes 
		$nommes = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"); 
		//variable nombre día 
		$nomdia = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"); 

		/* 	date(j) toma valores de 1 al 31 segun el dia del mes 
			date(n) devuelve numero del 1 al 12 segun el mes 
			date(w) devuelve 0 a 6 del dia de la semana empezando el domingo 
			date(Y) devuelve el año en 4 digitos */ 

		$dia 		= date("j",strtotime($fecha_estudio)); //Dia del mes en numero 
		$mes 		= date("n",strtotime($fecha_estudio)); //Mes actual en numero 
		$diasemana 	= date("w",strtotime($fecha_estudio)); //Dia de semana en numero 
		$año        = date("Y",strtotime($fecha_estudio));

		$fecha_estudio2 = $nomdia[$diasemana].", ".$dia." de ".$nommes[$mes-1]." del ".$año; 
		return $fecha_estudio2;
	}
?>