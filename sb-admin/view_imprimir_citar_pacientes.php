<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
include_once 'include/funciones_consultas.php';
include_once "include/mysql.php";
session_start();
//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 
	if(!isset($_POST['nombre'])){
		header("Location: index.php");
	}
	else{
		
		$institucion = $_POST['institucion'];
		
		$institucion = mb_strtolower($institucion, "UTF-8" );
        $institucion = str_replace(" ", "_", $institucion);
        
        include_once "include/mysql.php";
       
        $mysql = new mysql();
        $link = $mysql->connect(); 
        $sqltest2 = $mysql->query($link,"SELECT (t5.tipo )  as tipo
                                            FROM instituciones t5 
                                            WHERE t5.nombre = '$institucion'");

                            
        $row2 = $mysql->f_obj($sqltest2);
                           

        if($row2->tipo == 'PARTICULAR'){
        	//son sólo pacientes particulares
			$ag = new agenda();
			$ag->agendar_pac_particular($institucion);
        }
		else{
			if($row2->tipo == 'PUBLICA'){
				//son de cualquier otra institución menos particular
				$ag = new agenda();
				$ag->agendar_pac();
			}	
		}
		$mysql->close();	
	}

class MYPDF extends TCPDF {
	var $cont = 0;
	public function Header() {
	}
	public function Footer() {
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align,'','',$strech=0);
	}
	public function Recibo_gammagrama_paciente($ax_x, $ax_y) {

		$this->ImageSVG($file='images/logo_numedics.svg', $x=6+$ax_x, $y=13+$ax_y, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		//	Marco.
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 10+$ax_y, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 75+$ax_y, 4.5, 60,'DF');
		$this->Rect(206+$ax_x, 10+$ax_y, 5, 66,'DF');

		$this->CreateTextBox('MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V', 90+$ax_x,18+$ax_y, 80, 10, 10, 'B');
		$this->CreateTextBox('Circunvalación pichucalco No. 216 Col. Moctezuma', 95+$ax_x,27+$ax_y, 80, 10, 10, '');		
		$this->CreateTextBox('C.P. 29030 Tuxtla Gutiérrez, Chiapas.', 105+$ax_x,33+$ax_y, 80, 10, 10, '');		

		$this->CreateTextBox('Teléfonos: (961) 60 292 11  y (961) 60 294 79 ext. 1', 95+$ax_x,40+$ax_y, 80, 10, 10, '');
		$this->CreateTextBox('www.numedics.com.mx', 117+$ax_x,45+$ax_y, 80, 10, 10, '');

					$nombre         = $_POST['nombre'];
                    $apepat         = $_POST['apepat'];
                    $apemat         = $_POST['apemat'];
                   
                    $idestudio      = $_POST['estudio'];
                    $institucion    = $_POST['institucion'];
                    $fecha_estudio  = $_POST['fecha_estudio'];
                   
                    $fecha_estudio2 = fecha_letras($fecha_estudio);

                    $hora          = $_POST['hora'];
                    $horas = DATE("g:i a", STRTOTIME($hora));
                    $indicaciones   = $_POST['indicaciones'];

                    $indicaciones = pasarMayusculas($indicaciones);
                    $nombre = pasarMayusculas($nombre);
                    $apepat = pasarMayusculas($apepat);
                    $apemat = pasarMayusculas($apemat);

                    $fecha_estudio2 = pasarMayusculas($fecha_estudio2);

                    $horas = pasarMayusculas($horas);


					$this->CreateTextBox('Nombre del paciente:  ', 0, 60, 80, 10, 10, 'B');
					$this->CreateTextBox($nombre.' '.$apepat.' '.$apemat, 40, 60, 80, 10, 10, '');

					$this->CreateTextBox('Fecha del estudio: ', 0, 70, 80, 10, 10, 'B');
					$this->CreateTextBox($fecha_estudio2, 40, 70, 80, 10, 10, '');

					$this->CreateTextBox('Hora: ', 115, 70, 80, 10, 10, 'B');
					$this->CreateTextBox($horas, 130, 70, 80, 10, 10, '');

					$this->CreateTextBox('Tipo de estudio: ', 0, 80, 80, 10, 10, 'B');
					//$pdf->CreateTextBox($idestudio, 40, 80, 80, 10, 10, '');
					$this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
					$this->MultiCell(140, 10, $idestudio, 0, 'L', 0, 0, $x=60, $y=83, true);

					$this->CreateTextBox('Indicaciones: ', 0, 90, 80, 10, 10, 'B');
					//$pdf->CreateTextBox($indicaciones, 40, 90, 80, 10, 10, 'B');
					// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
					$this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
					$this->MultiCell(180, 10, $indicaciones, 0, 'L', 0, 0, $x=20, $y=100, true);

					$this->SetFont(PDF_FONT_NAME_MAIN, '', 9);
					$this->MultiCell(100, 10, 'Para cambio de cita y cancelaciones comunicarse 48 hrs. antes del día de su cita.', 0, 'C', 0, 0, $x=20, $y=124, true);

					$this->SetFont(PDF_FONT_NAME_MAIN, '', 10);

					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(124, 125, 200, 125, $style);
					
			
					$id_usuario = $_SESSION['id'];
					$mysql = new mysql();
        			$link = $mysql->connect(); 
        			$datos_usuario = $mysql->query($link,"SELECT concat(nombre,' ',ap_paterno,' ', ap_materno) as nombre
															FROM users 
															WHERE idusuario=$id_usuario");        
        			$row4 = $mysql->f_obj($datos_usuario);

					$this->CreateTextBox('Atendió:'.$row4->nombre, 110,125, 80, 10, 10, '');	

					$mysql->close();	
	}
	public function Recibo_gammagrama_interno($ax_x, $ax_y) {

		$this->ImageSVG($file='images/logo_numedics.svg', $x=6+$ax_x, $y=13+$ax_y, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		//	Marco.
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 10+$ax_y, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 75+$ax_y, 4.5, 60,'DF');
		$this->Rect(206+$ax_x, 10+$ax_y, 5, 66,'DF');

					$nombre         	= $_POST['nombre'];
                    $apepat         	= $_POST['apepat'];
                    $apemat         	= $_POST['apemat'];
					$fecha_estudio  	= $_POST['fecha_estudio']; 
					$fecha_nacimiento  	= $_POST['fecha_nacimiento'];
					$edad 				= $_POST['edad'];
					$tipo_edad 			= $_POST['tipo_edad'];

					if($tipo_edad == 'AÑOS'){
                        $tipo_edad2='Año(s)';
                    }else{
                        $tipo_edad2='Mes(es)';
                    }

					$fecha_estudio2 = fecha_letras($fecha_estudio);
					
					if($fecha_nacimiento != ''){
						date_default_timezone_set('America/Mexico_City');
						$fecha_nacimiento2 = DATE("d-m-Y", STRTOTIME($fecha_nacimiento));
							
					}else{
						$fecha_nacimiento2='';
					}
					
					if(!isset($edad)){
						$edad= '';
					}
					

                    $horas         	= $_POST['hora'];
                    //$horas = DATE("g:i a", STRTOTIME($hora));
                   	$idestudio      = $_POST['estudio'];

                   	$med     		= $_POST['med'];
                   	$nombre_med     = $_POST['nombre_med'];
                    $apepat_med     = $_POST['apepat_med'];
                    $apemat_med     = $_POST['apemat_med'];
                    $especialidad_med   = $_POST['especialidad_med'];
                    $aquien_corresponda = $_POST['aquien_corresponda'];
                    
                    if($aquien_corresponda =='NO'){
                    	$medico = $nombre_med.' '.$apepat_med.' '.$apemat_med;
                    }else{
                    	$medico="A quien corresponda";
                    }

		 			$tel_local      = $_POST['tel_local'];
                    $tel_cel        = $_POST['tel_cel'];
                    $email          = $_POST['email'];

                    $nombre = pasarMayusculas($nombre);
                    $apepat = pasarMayusculas($apepat);
                    $apemat = pasarMayusculas($apemat);

                    $med = pasarMayusculas($med);
                    $medico = pasarMayusculas($medico);
                 
                    $fecha_estudio2 = pasarMayusculas($fecha_estudio2);
                    $horas = pasarMayusculas($horas);

        			$this->CreateTextBox('Fecha: ', 90+$ax_x, 30+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox($fecha_estudio2, 110-$ax_x,30+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox('Hora: ', 90+$ax_x,40+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox($horas, 110+$ax_x,40+$ax_y, 80, 10, 10, 'B');

			        $this->CreateTextBox('Nombre del paciente: ', 0+$ax_x,50+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox($nombre.' '.$apepat.' '.$apemat, 40+$ax_x, 50+$ax_y, 80, 10, 10, '');

        			$this->CreateTextBox('Nombre del estudio: ', 0+$ax_x,60+$ax_y, 80, 10, 10, 'B');

			        $this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
					$this->MultiCell(140, 10, $idestudio, 0, 'L', 0, 0, $x=60+$ax_x, $y=63+$ax_y, true);

					$this->CreateTextBox('Peso: ', 0+$ax_x,75+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox('Estatura: ', 70+$ax_x,75+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox('Fecha de nacimiento: ', 0+$ax_x,85+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox($fecha_nacimiento2, 40+$ax_x,85+$ax_y, 80, 10, 10, '');

					$this->CreateTextBox('Edad: ', 110+$ax_x,85+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox($edad.' '.$tipo_edad2, 124+$ax_x,85+$ax_y, 80, 10, 10, '');

					$this->CreateTextBox('Médico tratante: ', 0+$ax_x,95+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox($med.' '.$medico, 40+$ax_x,95+$ax_y, 80, 10, 10, '');

					$this->CreateTextBox('Teléfono: ', 50+$ax_x,105+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox($tel_local, 70+$ax_x,105+$ax_y, 80, 10, 10, '');

					$this->CreateTextBox('Teléfono: ', 0+$ax_x,105+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox($tel_cel, 20+$ax_x,105+$ax_y, 80, 10, 10, '');

					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(124, 240, 200, 240, $style);

					$this->CreateTextBox('Firma familiar o paciente recibí indicaciones', 105+$ax_x,110+$ax_y, 80, 10, 10, '');

					$id_usuario = $_SESSION['id'];
					$mysql = new mysql();
        			$link = $mysql->connect(); 
        			$datos_usuario = $mysql->query($link,"SELECT concat(nombre,' ',ap_paterno,' ', ap_materno) as nombre
															FROM users 
															WHERE idusuario=$id_usuario");        
        			$row4 = $mysql->f_obj($datos_usuario);

					$this->CreateTextBox('Atendió:'.$row4->nombre, 0+$ax_x,116+$ax_y, 80, 10, 10, 'B');	

					$mysql->close();

	}
	public function Recibo_tratamiento($ax_x, $ax_y) {

		$this->ImageSVG($file='images/logo_numedics.svg', $x=6+$ax_x, $y=13+$ax_y, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		//	Marco.
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 10+$ax_y, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 75+$ax_y, 4.5, 60,'DF');
		$this->Rect(206+$ax_x, 10+$ax_y, 5, 66,'DF');

		$this->CreateTextBox('MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V', 90+$ax_x,18+$ax_y, 80, 10, 10, 'B');
		$this->CreateTextBox('Circunvalación pichucalco No. 216 Col. Moctezuma', 95+$ax_x,27+$ax_y, 80, 10, 10, '');		
		$this->CreateTextBox('C.P. 29030 Tuxtla Gutiérrez, Chiapas.', 105+$ax_x,33+$ax_y, 80, 10, 10, '');		

		$this->CreateTextBox('Teléfonos: (961) 60 292 11  y (961) 60 294 79 ext. 1', 95+$ax_x,40+$ax_y, 80, 10, 10, '');
		$this->CreateTextBox('www.numedics.com.mx', 117+$ax_x,45+$ax_y, 80, 10, 10, '');

					
					$eje_y=$ax_y;
					$eje_x=$ax_x;

					$nombre         =	$_POST['nombre'];
                    $apepat         = 	$_POST['apepat'];
					$apemat         = 	$_POST['apemat'];
					
					$edad          		= $_POST['edad'];
					$tipo_edad 			= $_POST['tipo_edad'];

					if($tipo_edad == 'AÑOS'){
                        $tipo_edad2='Año(s)';
                    }else{
                        $tipo_edad2='Mes(es)';
                    }
					$fecha_nacimiento   = $_POST['fecha_nacimiento'];
					$fecha_nacimiento = DATE("d-m-Y", STRTOTIME($fecha_nacimiento));
					//$fecha_nacimiento = fecha_letras($fecha_nacimiento);
                   
                    $idestudio      = 	$_POST['estudio'];
                    $cantidad_i131  = 	$_POST['cantidad_i131'];
                    $institucion    = 	$_POST['institucion'];
                    $fecha_estudio  = 	$_POST['fecha_estudio'];

                    $med 			= $_POST['med'];
                    $nombre_med		= $_POST['nombre_med'];
                    $apepat_med		= $_POST['apepat_med'];
                    $apemat_med		= $_POST['apemat_med'];
                    
                    $fecha_estudio2 = 	fecha_letras($fecha_estudio);
                    
                    $hora          = 	$_POST['hora'];
                    $horas = DATE("g:i a", STRTOTIME($hora));

                    $nombre = pasarMayusculas($nombre);
                    $apepat = pasarMayusculas($apepat);
                    $apemat = pasarMayusculas($apemat);

                    $cantidad_i131 = pasarMayusculas($cantidad_i131);
                    $institucion = pasarMayusculas($institucion);
                    $fecha_estudio = pasarMayusculas($fecha_estudio);

                    $med = pasarMayusculas($med);
                    $nombre_med = pasarMayusculas($nombre_med);
                    $apepat_med = pasarMayusculas($apepat_med);
                    $apemat_med = pasarMayusculas($apemat_med);

                    $fecha_estudio2 = pasarMayusculas($fecha_estudio2);

                    $horas = pasarMayusculas($horas);
                    
                    //$indicaciones   = $_POST['indicaciones'];
                    $indicaciones = 'Suspender eutirox o levotiroxina 4 semanas, tapasol o tiamazol 10 dias.';

					//$eje_y=0;
					$this->CreateTextBox('Nombre del paciente:  ', 0+$eje_x, 45+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($nombre.' '.$apepat.' '.$apemat, 40+$eje_x, 45+$eje_y, 80, 10, 10, '');

					$this->CreateTextBox('Fecha de nacimiento: ', 0+$eje_x, 50+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($fecha_nacimiento, 40+$eje_x, 50+$eje_y, 80, 10, 10, '');

					$this->CreateTextBox('Edad: ', 0+$eje_x, 55+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($edad.' '.$tipo_edad2, 40+$eje_x, 55+$eje_y, 80, 10, 10, '');

					$this->CreateTextBox('Fecha del estudio: ', 0+$eje_x, 61+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($fecha_estudio2, 40+$eje_x, 61+$eje_y, 80, 10, 10, '');

					$this->CreateTextBox('Hora: ', 115+$eje_x, 61+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($horas, 130+$eje_x, 61+$eje_y, 80, 10, 10, '');

					$this->CreateTextBox('Tipo de estudio: ', 0+$eje_x, 67+$eje_y, 80, 10, 10, 'B');
					//$pdf->CreateTextBox($idestudio, 40, 80, 80, 10, 10, '');
					$this->SetFont(PDF_FONT_NAME_MAIN, '', 10);

					if($cantidad_i131 == ''){

						$this->MultiCell(140, 10, $idestudio, 0, 'L', 0, 0, $x=60+$eje_x, $y=70+$eje_y, true);
					}else{
						$this->MultiCell(140, 10, $idestudio.' (Dosis a suministrar '.$cantidad_i131.' mCi )', 0, 'L', 0, 0, $x=60+$eje_x, $y=70+$eje_y, true);
					}

					$this->CreateTextBox('Médico tratante: ', 0+$eje_x, 77+$eje_y, 80, 10, 10, 'B');

					if($med == ' '){
						$this->CreateTextBox('A quien corresponda', 40+$eje_x, 77+$eje_y, 80, 10, 10, '');	
					}else{
						$this->CreateTextBox($med.' '.$nombre_med.' '.$apepat_med.' '.$apemat_med, 40+$eje_x, 77+$eje_y, 80, 10, 10, '');	
					}
					
					
					$this->CreateTextBox('Indicaciones: ', 0+$eje_x, 85+$eje_y, 80, 10, 10, 'B');
					//$pdf->CreateTextBox($indicaciones, 40, 90, 80, 10, 10, 'B');
					// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
					$this->SetFont(PDF_FONT_NAME_MAIN, '', 10);
					$this->MultiCell(180, 10, $indicaciones, 0, 'L', 0, 0, $x=50+$eje_x, $y=88+$eje_y, true);
					$text ='Evitar alimentos con yodo por un término de 10 dias tales como rábanos, mariscos, sal, pan, refrescos embotellados y alimentos industrializados; así como jarábes para la tos que contengan yodo.';
					$this->MultiCell(180, 10, $text, 0, 'J', 0, 0, $x=20+$eje_x, $y=95+$eje_y, true);

					$text ='Nota: si está en edad fértil, favor de presentar su prueba de embarazo el día de su tratamiento';
					$this->CreateTextBox($text, 0+$eje_x, 105+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox('', 0+$eje_x, 95+$eje_y, 80, 10, 10, '');

					$text ='Traer cosas personales (Jabón, Shampoo, Toalla, Sandalias, etc.). Traer dulces (chicles y caramelos). El paciente quéda sólo.';
					$this->SetFont('helvetica', '', 8);
					$this->MultiCell(60, 10, $text, 0, 'J', 0, 0, $x=20+$eje_x, $y=115+$eje_y, true,1);


					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(124+$eje_x, 124+$eje_y, 200+$eje_x, 124+$eje_y, $style);

					
					if($this->cont == 0){ //update 2015_07_01
						$id_usuario = $_SESSION['id'];
						$mysql = new mysql();
						$link = $mysql->connect(); 
						$datos_usuario = $mysql->query($link,"SELECT concat(nombre,' ',ap_paterno,' ', ap_materno) as nombre
																FROM users 
																WHERE idusuario = $id_usuario");        
        				$row4 = $mysql->f_obj($datos_usuario);

						$this->CreateTextBox('Atendió:'.$row4->nombre, 110+$eje_x,126+$eje_y, 80, 10, 10, 'B');		
						$this->cont++;

						$mysql->close();
					}else{
						$id_usuario = $_SESSION['id'];
						$mysql = new mysql();
						$link = $mysql->connect(); 
						$datos_usuario = $mysql->query($link,"SELECT concat(nombre,' ',ap_paterno,' ', ap_materno) as nombre
																FROM users 
																WHERE idusuario = $id_usuario");        
						$row4 = $mysql->f_obj($datos_usuario);
						
						$this->CreateTextBox('Firma familiar o paciente recibí indicaciones', 108+$ax_x,122+$ax_y, 80, 10, 10, '');

						$this->CreateTextBox('Atendió:'.$row4->nombre, 0+$eje_x,122+$eje_y, 80, 10, 10, 'B');		
						$this->cont++;

						$mysql->close();
					}
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

$cantidad_i131  = $_POST['cantidad_i131'];

//DECIDE SI ES GAMMAGRAMA O TRATAMIENTO

if($cantidad_i131 == ''){
	$pdf->Recibo_gammagrama_paciente(0,0);
	$pdf->Recibo_gammagrama_interno(0,130);	
}else{
	$pdf->Recibo_tratamiento(0,0);
	$pdf->Recibo_tratamiento(0,127);	
}

$nombre         = $_POST['nombre'];
$apepat         = $_POST['apepat'];
$apemat         = $_POST['apemat'];
$fecha_estudio  = $_POST['fecha_estudio'];

//Close and output PDF document
$nombre_pdf = $fecha_estudio.'_'.$apepat.'_'.$apemat.'_'.$nombre.'.pdf';                    
$nombre_pdf = pasarMayusculas($nombre_pdf); 
$pdf->Output($nombre_pdf, 'I');

?>

<?php
class agenda{

	function agendar_pac(){
		/*echo'<pre>';
		print_r($_POST);
		echo'</pre>';*/

		//DATOS MÉDICO
        $med     			= pasarMayusculas($_POST['med']);
        $nombre_med 	    = pasarMayusculas($_POST['nombre_med']);
		$apepat_med     	= pasarMayusculas($_POST['apepat_med']);
		$apemat_med     	= pasarMayusculas($_POST['apemat_med']);
		$especialidad_med   = pasarMayusculas($_POST['especialidad_med']);
		$aquien_corresponda = pasarMayusculas($_POST['aquien_corresponda']);

		//DATOS PACIENTE
		$nombre         = pasarMayusculas($_POST['nombre']);
		$apepat         = pasarMayusculas($_POST['apepat']);
		$apemat         = pasarMayusculas($_POST['apemat']);
		$tel_local      = $_POST['tel_local'];
		$tel_cel        = $_POST['tel_cel'];
		$email          = $_POST['email'];
		$edad          	= $_POST['edad'];
		if($edad == ''){
			$edad = 0;
		}
		$tipo_edad      = pasarMayusculas($_POST['tipo_edad']);
		$fecha_nacimiento   = $_POST['fecha_nacimiento'];
		$fecha_nacimiento = $fecha_nacimiento.' 00:00:00';

		//DATOS ESTUDIO
		$fecha_estudio  = $_POST['fecha_estudio']; 
		$horas          	= $_POST['hora'];
		date_default_timezone_set('America/Mexico_City');
		$hora = DATE("H:i:s", STRTOTIME($horas));
		$institucion    = pasarMayusculas($_POST['institucion']);
		$indicaciones   = pasarMayusculas($_POST['indicaciones']);
		$cantidad_i131  = $_POST['cantidad_i131'];

		//DATOS DE QUIEN AGENDÓ
		$id_usuario = $_SESSION['id'];
		

		$mysql =new mysql();
		$link = $mysql->connect(); 

		$sql = $mysql->query($link,"INSERT INTO doctores 
											(iddoctores,
											 grado,
											 nombre,
											 ap_paterno,
											 ap_materno,
											 especialidad,
											 aquiencorresponda) 
									VALUES ('',
											'$med',
											'$nombre_med',
											'$apepat_med',
											'$apemat_med',
											'$especialidad_med',
											'$aquien_corresponda') ");

					$iddoctores =  mysqli_insert_id($link);
					$idestudio      = $_POST['idestudio'];
				
				/* 	//VERIFICACIÓN DE DATOS
					echo '$iddoctores '.$iddoctores;
					echo '$id_estudio '.$idestudio;
					echo '$fecha '.$fecha_estudio;
					echo '$hora '.$hora;
					echo 'id_usuario '.$id_usuario;
					echo 'nombre '.$nombre;
					echo 'ap_pat '.$apepat;
					echo 'ap_mat '.$apemat;
					echo 'tel_local '.$tel_local;
					echo 'tel_cel '.$tel_cel;
					echo 'email '.$email;
					echo 'edad '.$edad;
					echo 'tipo edad '.$tipo_edad;
					echo 'fecha nac '.$fecha_nacimiento;
					echo 'institucion '.$institucion;
					echo 'indicaciones '.$indicaciones;
					echo 'cantidad de 1-131 '.$cantidad_i131;
				*/

					$sql = $mysql->query($link,"INSERT INTO pacientes (idpacientes,
											doctores_iddoctores,
											estudio_idgammagramas,
											fecha,
											hora,
											nombre,
											ap_paterno,
											ap_materno,
											num_tel,
											num_tel2,
											email,
											institucion,
											indicaciones,
											indicaciones_tratamiento,
											estatus,
											observaciones,
											atendio,
											edad,
											fecha_nacimiento)
									VALUES ('',
											$iddoctores,
											$idestudio,
											'$fecha_estudio',
											'$hora',
											'$nombre',
											'$apepat',
											'$apemat',
											'$tel_local',
											'$tel_cel',
											'$email',
											'$institucion',
											'$indicaciones',
											'$cantidad_i131',
											'POR ATENDER',
											'',
											$id_usuario,
											$edad,
											'$fecha_nacimiento'); ");
					//echo ' filas afectadas: (Se insertó): '.$mysql->affect_row().' registro completo';
					$idpaciente =  mysqli_insert_id($link);

					$sql = $mysql->query($link,"UPDATE doctores 
												SET idpaciente='$idpaciente'
												WHERE iddoctores=$iddoctores");

					$sql = $mysql->query($link,"SELECT id_edad
												FROM tblc_edad
												WHERE descripcion='$tipo_edad'; ");
					$row = $mysql->f_obj($sql);


					$sql = $mysql->query($link,"INSERT INTO tbl_edad_paciente (id_edad_paciente,
												id_edad,
												idpacientes)
										VALUES ('',
												$row->id_edad,
												$idpaciente); ");

		$mysql->close();
	}

	function agendar_pac_particular($institucion_bd){
		/*echo'<pre>';
		print_r($_POST);
		echo'</pre>';*/
		
		//DATOS DEL MÉDICO
        $med     			= pasarMayusculas($_POST['med']);
        $nombre_med 	    = pasarMayusculas($_POST['nombre_med']);
		$apepat_med     	= pasarMayusculas($_POST['apepat_med']);
		$apemat_med     	= pasarMayusculas($_POST['apemat_med']);
		$especialidad_med   = pasarMayusculas($_POST['especialidad_med']);
		$aquien_corresponda = pasarMayusculas($_POST['aquien_corresponda']);

		//DATOS DEL PACIENTE
		$nombre         	= pasarMayusculas($_POST['nombre']);
		$apepat         	= pasarMayusculas($_POST['apepat']);
		$apemat         	= pasarMayusculas($_POST['apemat']);
		$tel_local      	= $_POST['tel_local'];
		$tel_cel        	= $_POST['tel_cel'];
		$email          	= pasarMayusculas($_POST['email']);	
		$edad          		= $_POST['edad'];
		if($edad == ''){
			$edad = 0;
		}
		$tipo_edad      	= pasarMayusculas($_POST['tipo_edad']);
		$fecha_nacimiento   = $_POST['fecha_nacimiento'];
		$fecha_nacimiento   = $fecha_nacimiento.' 00:00:00';

		//DATOS DEL ESTUDIO
		$fecha_estudio  = $_POST['fecha_estudio']; 
		$horas          	= $_POST['hora'];
		date_default_timezone_set('America/Mexico_City');
		$hora = DATE("H:i:s", STRTOTIME($horas));
		$idestudio      = $_POST['idestudio'];	
		$institucion    	= pasarMayusculas($_POST['institucion']);
		$indicaciones   	= pasarMayusculas($_POST['indicaciones']);
		$cantidad_i131  	= $_POST['cantidad_i131'];

		//DATOS DE QUIEN AGENDÓ
		$id_usuario = $_SESSION['id'];


		$mysql =new mysql();
		$link = $mysql->connect(); 

		$sql = $mysql->query($link,"INSERT INTO doctores 
											(iddoctores,
											 grado,
											 nombre,
											 ap_paterno,
											 ap_materno,
											 especialidad,
											 aquiencorresponda) 
									VALUES ('',
											'$med',
											'$nombre_med',
											'$apepat_med',
											'$apemat_med',
											'$especialidad_med',
											'$aquien_corresponda') ");

					$iddoctores =  mysqli_insert_id($link);
					

					$sql = $mysql->query($link,"INSERT INTO pacientes (idpacientes,
											doctores_iddoctores,
											estudio_idgammagramas,
											fecha,
											hora,
											nombre,
											ap_paterno,
											ap_materno,
											num_tel,
											num_tel2,
											email,
											institucion,
											indicaciones,
											indicaciones_tratamiento,
											estatus,
											observaciones,
											atendio,
											edad,
											fecha_nacimiento)
									VALUES ('',
											$iddoctores,
											$idestudio,
											'$fecha_estudio',
											'$hora',
											'$nombre',
											'$apepat',
											'$apemat',
											'$tel_local',
											'$tel_cel',
											'$email',
											'$institucion',
											'$indicaciones',
											'$cantidad_i131',
											'POR ATENDER',
											'',
											$id_usuario,
											$edad,
											'$fecha_nacimiento'); ");
					//echo ' filas afectadas: (Se insertó): '.$mysql->affect_row().' registro completo';
					$idpaciente =  mysqli_insert_id($link);

					$sql = $mysql->query($link,"UPDATE doctores 
												SET idpaciente='$idpaciente'
												WHERE iddoctores=$iddoctores");
					
					//edad de paciente
					$sql = $mysql->query($link,"SELECT id_edad
												FROM tblc_edad
												WHERE descripcion='$tipo_edad'; ");
					$row =$mysql->f_obj($sql);


					$sql = $mysql->query($link,"INSERT INTO tbl_edad_paciente (id_edad_paciente,
												id_edad,
												idpacientes)
										VALUES ('',
												$row->id_edad,
												$idpaciente); ");

					//inserto anticipos (creo el registro de una venta particular)
					$sql = $mysql->query($link,"INSERT INTO anticipos (idanticipos,
											dep_banamex,
											pago_santander,
											pago_cheque,
											transferencia,
											anticipo_efe,
											factura,
											no_recibo)
											VALUES ('','0.00','0.00','0.00','0.00','0.0',' ',' '); ");

					$idanticipo = mysqli_insert_id($link);

					//BUSCO PRECIO DEL ESTUDIO
					$sql = $mysql->query($link, "SELECT ($institucion_bd ) as precio
												FROM estudio
												WHERE idgammagramas = $idestudio;" );
					$fila = $mysql->f_obj($sql);
					$precio_estudio = $fila->precio;
					//relaciono el anticipo con el paciente (creo el registro de una venta particular)
					$sql = $mysql->query($link,"INSERT INTO pacientes_has_anticipos (pacientes_idpacientes,
											anticipos_idanticipos,
											fecha_anticipo,
											fecha_estudio,
											monto_restante)
											VALUES ($idpaciente,
												   	$idanticipo,
												   	'0000-00-00',
												   	'$fecha_estudio',
												   	$precio_estudio); ");

					/*  actualizacion 2016/01/21  */

					$sql = $mysql->query($link,"INSERT INTO facturas (idfactura, idpaciente, requiere_factura, facturado, numero_facturas)
												VALUES ('',
														$idpaciente,
												   		'NO',
												   		'NO',
												   		''); ");

		$mysql->close();
	}
}
?>
