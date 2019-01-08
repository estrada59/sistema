<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 

	if(!isset($_POST['nombre'])){
		header("Location: index.php");
	}
	else{
		$institucion    = $_POST['institucion'];
		
		if ($institucion == 'PARTICULAR'){
			//son sólo pacientes particulares
//			$ag = new agenda();
//			$ag->agendar_pac_particular();	
		}
		else{
			//son de cualquier otra institución menos particular
//			$ag = new agenda();
//			$ag->agendar_pac();	
		}
	}

class MYPDF extends TCPDF {
	var $cont = 0; // update 2015_07_01
	public function Header() {
	}
	public function Footer() {
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align,'','',$strech=0);
	}
	public function Recibo_paciente_yodo($ax_x, $ax_y) {

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
					$this->CreateTextBox('Nombre del paciente:  ', 0+$eje_x, 55+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($nombre.' '.$apepat.' '.$apemat, 40+$eje_x, 55+$eje_y, 80, 10, 10, '');

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
					$this->Line(124+$eje_x, 123+$eje_y, 200+$eje_x, 123+$eje_y, $style);
					
					if($this->cont == 0){ //update 2015_07_01
						$this->CreateTextBox('Andrea de Jesús Borralles Cigarroa', 110+$eje_x,122+$eje_y, 80, 10, 10, '');		
						$this->cont++;
					}
	}
	public function Recibo_paciente_samario($ax_x, $ax_y) {

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
                    
                    //$eje_y=0;
					$this->CreateTextBox('Nombre del paciente:  ', 0+$eje_x, 55+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($nombre.' '.$apepat.' '.$apemat, 40+$eje_x, 55+$eje_y, 80, 10, 10, '');

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
					$textval="1. El material radiactivo se elimina en unos días, por la orina y por su seguridad y la de las personas que conviven con usted se le sugiere haga lo siguiente por lo menos DURANTE LOS SIGUIENTES 3 DIAS:";
					
					$this->MultiCell(180, 10, $textval, 0, 'L', 0, 0, $x=50+$eje_x, $y=88+$eje_y, true);
					
					$text ='Nota: si está en edad fértil, favor de presentar su prueba de embarazo el día de su tratamiento';
					$this->CreateTextBox($text, 0+$eje_x, 105+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox('', 0+$eje_x, 95+$eje_y, 80, 10, 10, '');


					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(124+$eje_x, 123+$eje_y, 200+$eje_x, 123+$eje_y, $style);
					
					if($this->cont == 0){ //update 2015_07_01
						$this->CreateTextBox('Andrea de Jesús Borralles Cigarroa', 110+$eje_x,122+$eje_y, 80, 10, 10, '');		
						$this->cont++;
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

$idestudio = $_POST['idestudio'];

if($idestudio      = 72){  //si es samario cambian las
 	$pdf->Recibo_paciente_samario(0,0);
	$pdf->Recibo_paciente_samario(0,127);
}
else{
	$pdf->Recibo_paciente_yodo(0,0);
	$pdf->Recibo_paciente_yodo(0,127);
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
		
		include "include/mysql.php";

        $med     			= $_POST['med'];
        $nombre_med 	    = $_POST['nombre_med'];
		$apepat_med     	= $_POST['apepat_med'];
		$apemat_med     	= $_POST['apemat_med'];
		$especialidad_med   = $_POST['especialidad_med'];
		$aquien_corresponda = $_POST['aquien_corresponda'];

		//convierte a mayúsculas
		$med 				= pasarMayusculas($med);
		$nombre_med 	    = pasarMayusculas($nombre_med);
		$apepat_med     	= pasarMayusculas($apepat_med);
		$apemat_med     	= pasarMayusculas($apemat_med);
		$especialidad_med   = pasarMayusculas($especialidad_med);
		$aquien_corresponda = pasarMayusculas($aquien_corresponda);

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

					$fecha_estudio  = $_POST['fecha_estudio']; 
                    $horas          	= $_POST['hora'];
                    date_default_timezone_set('America/Mexico_City');
                    $hora = DATE("H:i:s", STRTOTIME($horas));
                    
					$nombre         = $_POST['nombre'];
                    $apepat         = $_POST['apepat'];
                    $apemat         = $_POST['apemat'];
                    $tel_local      = $_POST['tel_local'];
                    $tel_cel        = $_POST['tel_cel'];
                    $email          = $_POST['email'];
                    $institucion    = $_POST['institucion'];
                    $indicaciones   = $_POST['indicaciones'];
                    $cantidad_i131  = $_POST['cantidad_i131'];

                    //convierte a mayúsculas
                    $nombre         = pasarMayusculas($nombre);
                    $apepat         = pasarMayusculas($apepat);
                    $apemat         = pasarMayusculas($apemat);
                    $tel_local      = pasarMayusculas($tel_local);
                    $tel_cel        = pasarMayusculas($tel_cel);
                    $email          = pasarMayusculas($email);
                    $institucion    = pasarMayusculas($institucion);
                    $indicaciones   = pasarMayusculas($indicaciones);
                    $cantidad_i131  = pasarMayusculas($cantidad_i131);

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
											observaciones)
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
											''); ");
					//echo ' filas afectadas: (Se insertó): '.$mysql->affect_row().' registro completo';
					$idpaciente =  mysqli_insert_id($link);

					$sql = $mysql->query($link,"UPDATE doctores 
												SET idpaciente='$idpaciente'
											    WHERE iddoctores=$iddoctores");

		$mysql->close();
	}

	function agendar_pac_particular(){
		
		include "include/mysql.php";

        $med     			= $_POST['med'];
        $nombre_med 	    = $_POST['nombre_med'];
		$apepat_med     	= $_POST['apepat_med'];
		$apemat_med     	= $_POST['apemat_med'];
		$especialidad_med   = $_POST['especialidad_med'];
		$aquien_corresponda = $_POST['aquien_corresponda'];

		//convierte a mayúsculas
		$med 				= pasarMayusculas($med);
		$nombre_med 	    = pasarMayusculas($nombre_med);
		$apepat_med     	= pasarMayusculas($apepat_med);
		$apemat_med     	= pasarMayusculas($apemat_med);
		$especialidad_med   = pasarMayusculas($especialidad_med);
		$aquien_corresponda = pasarMayusculas($aquien_corresponda);


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

					$fecha_estudio  = $_POST['fecha_estudio']; 
                    $horas          	= $_POST['hora'];
                    date_default_timezone_set('America/Mexico_City');
                    $hora = DATE("H:i:s", STRTOTIME($horas));
                    
					$nombre         = $_POST['nombre'];
                    $apepat         = $_POST['apepat'];
                    $apemat         = $_POST['apemat'];
                    $tel_local      = $_POST['tel_local'];
                    $tel_cel        = $_POST['tel_cel'];
                    $email          = $_POST['email'];
                    $institucion    = $_POST['institucion'];
                    $indicaciones   = $_POST['indicaciones'];
                    $cantidad_i131  = $_POST['cantidad_i131'];

                    //convierte a mayúsculas
                    $nombre         = pasarMayusculas($nombre);
                    $apepat         = pasarMayusculas($apepat);
                    $apemat         = pasarMayusculas($apemat);
                    $tel_local      = pasarMayusculas($tel_local);
                    $tel_cel        = pasarMayusculas($tel_cel);
                    $email          = pasarMayusculas($email);
                    $institucion    = pasarMayusculas($institucion);
                    $indicaciones   = pasarMayusculas($indicaciones);
                    $cantidad_i131  = pasarMayusculas($cantidad_i131);

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
											observaciones)
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
											''); ");
					//echo ' filas afectadas: (Se insertó): '.$mysql->affect_row().' registro completo';
					$idpaciente =  mysqli_insert_id($link);

					$sql = $mysql->query($link,"UPDATE doctores 
												SET idpaciente='$idpaciente'
											    WHERE iddoctores=$iddoctores");

					//inserto anticipos (creo el registro de una venta particular)
					$sql = $mysql->query($link,"INSERT INTO anticipos (idanticipos,
											dep_banamex,
											pago_santander,
											pago_cheque,
											transferencia,
											anticipo_efe,
											factura,
											no_recibo)
											VALUES ('', '0.00', '0.00', '0.00', '0.00', '0.00', ' ', ' '); ");

					$idanticipo = mysqli_insert_id($link);

					//BUSCO PRECIO DEL ESTUDIO
					$sql = $mysql->query($link, "SELECT precio 
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
	function pasarMayusculas($cadena) { 
		$cadena = strtoupper($cadena); 
		$cadena = str_replace("á", "Á", $cadena); 
		$cadena = str_replace("é", "É", $cadena); 
		$cadena = str_replace("í", "Í", $cadena); 
		$cadena = str_replace("ó", "Ó", $cadena); 
		$cadena = str_replace("ú", "Ú", $cadena); 
		return ($cadena); 
	}  

?>