<?php
// Include the main TCPDF library (search for installation path).
	require_once('include/tcpdf/tcpdf.php');
	
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
	public function Recibo_paciente_gammagrama($ax_x, $ax_y, $row) {

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

					include_once 'include/funciones_consultas.php';
					$id_paciente 	= $row->idpacientes;
					$nombre         = $row->nombre;
                   
                    $idestudio      = $row->estudio;
                    $fecha_estudio  = $row->fecha;
                   
                    $fecha_estudio2 = fecha_letras($fecha_estudio);

                    $hora          = $row->hora;
                    $horas = DATE("g:i a", STRTOTIME($hora));
                    $indicaciones   = $row->indicaciones;

                    $indicaciones = pasarMayusculas($indicaciones);
                    $nombre = pasarMayusculas($nombre);

                    $fecha_estudio2 = pasarMayusculas($fecha_estudio2);

                    $horas = pasarMayusculas($horas);


					$this->CreateTextBox('Nombre del paciente:  ', 0, 60, 80, 10, 10, 'B');
					$this->CreateTextBox($nombre, 40, 60, 80, 10, 10, '');

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

					
					if(isset($id_paciente)){
						
						$mysql = new mysql();
						$link = $mysql->connect(); 
						$sql = $mysql->query($link,"SELECT concat(users.nombre,' ',users.ap_paterno,' ',users.ap_materno) as nombre 
													FROM users 
													WHERE users.idusuario = (SELECT pacientes.atendio FROM pacientes WHERE idpacientes=$id_paciente)");

						$row = $mysql->f_obj($sql);
						if(isset($row->nombre)){
							//$this->CreateTextBox($row->nombre, 110+$eje_x,122+$eje_y, 80, 10, 10, '');
							$this->CreateTextBox('Atendió:'.$row->nombre, 110,125, 80, 10, 10, '');	
						}else{
							$this->CreateTextBox(' ', 110,125, 80, 10, 10, '');	
						}
						

					}else{
						$this->CreateTextBox('', 110,125, 80, 10, 10, '');
					}
					//$this->CreateTextBox('Andrea de Jesús Borralles Cigarroa', 110,125, 80, 10, 10, '');		
	}
	public function Recibo_interno_gammagrama($ax_x, $ax_y, $row) {

		$this->ImageSVG($file='images/logo_numedics.svg', $x=6+$ax_x, $y=13+$ax_y, $w='100', $h='35', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		//	Marco.
		$this	->SetFillColor(0,0,0,0);
		$this	->SetDrawColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 10+$ax_y, 206, 125);
		$this	->SetFillColor(4,94,87, 0);
		$this->Rect(5+$ax_x, 75+$ax_y, 4.5, 60,'DF');
		$this->Rect(206+$ax_x, 10+$ax_y, 5, 66,'DF');

					
					$nombre         = $row->nombre;
					$edad = $row->edad;
					$tipo_edad = $row->tipo_edad;

					if($tipo_edad=='AÑOS'){
						$tipo_edad2='Año(s)';
					}else{
						$tipo_edad2='Mes(es)';
					}

					$fecha_nacimiento = $row->fecha_nacimiento;

					date_default_timezone_set('America/Mexico_City');

					if($fecha_nacimiento == '0000-00-00 00:00:00'){
						$fecha_nacimiento2 = '00-00-0000';
					}else{
						$fecha_nacimiento2 = fecha_letras($fecha_nacimiento);
					}
					
                   
                    $idestudio      = $row->estudio;
                    $fecha_estudio  = $row->fecha;
                    $tel_local		= $row->num_tel;
                    $tel_cel		= $row->num_tel2;
                   
                    $fecha_estudio2 = fecha_letras($fecha_estudio);

                    $hora          = $row->hora;
                    $horas = DATE("g:i a", STRTOTIME($hora));
                    $indicaciones   = $row->indicaciones;

                    
                   	$medico = $row->nombre_medico;
                 
                    
                    $nombre 		= pasarMayusculas($nombre);
                    $horas 			= pasarMayusculas($horas);
                    $indicaciones 	= pasarMayusculas($indicaciones);
                    $fecha_estudio2 = pasarMayusculas($fecha_estudio2);
                    $medico 		= pasarMayusculas($medico);

                 
        			$this->CreateTextBox('Fecha: ', 90+$ax_x, 30+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox($fecha_estudio2, 110-$ax_x,30+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox('Hora: ', 90+$ax_x,40+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox($horas, 110+$ax_x,40+$ax_y, 80, 10, 10, 'B');

			        $this->CreateTextBox('Nombre del paciente: ', 0+$ax_x,50+$ax_y, 80, 10, 10, 'B');
        			$this->CreateTextBox($nombre, 40+$ax_x, 50+$ax_y, 80, 10, 10, '');

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
					$this->CreateTextBox($medico, 40+$ax_x,95+$ax_y, 80, 10, 10, '');

					$this->CreateTextBox('Teléfono: ', 50+$ax_x,105+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox($tel_local, 70+$ax_x,105+$ax_y, 80, 10, 10, '');

					$this->CreateTextBox('Teléfono: ', 0+$ax_x,105+$ax_y, 80, 10, 10, 'B');
					$this->CreateTextBox($tel_cel, 20+$ax_x,105+$ax_y, 80, 10, 10, '');

					$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					$this->Line(124, 250, 200, 250, $style);
	}
	public function Recibo_paciente_tratamiento($ax_x, $ax_y, $row) {

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
					
					include_once 'include/funciones_consultas.php';
					include_once 'include/mysql.php';
					$id_paciente 	= $row->idpacientes;
					$nombre         = $row->nombre;
					$edad = $row->edad;
					$tipo_edad = $row->tipo_edad;

					if($tipo_edad=='AÑOS'){
						$tipo_edad2='Año(s)';
					}else{
						$tipo_edad2='Mes(es)';
					}

					$fecha_nacimiento = $row->fecha_nacimiento;
					
					
					date_default_timezone_set('America/Mexico_City');

					if($fecha_nacimiento == '0000-00-00 00:00:00'){
						$fecha_nacimiento2 = '00-00-0000';
					}else{
						$fecha_nacimiento2 = DATE("d-m-Y", STRTOTIME($fecha_nacimiento));
					}
					
					
                   
                    $idestudio      = $row->estudio;
                    $fecha_estudio  = $row->fecha;
                    $tel_local		= $row->num_tel;
                    $tel_cel		= $row->num_tel2;
                   
                    $fecha_estudio2 = fecha_letras($fecha_estudio);

                    $hora          = $row->hora;
                    $horas = DATE("g:i a", STRTOTIME($hora));
                    $indicaciones   = $row->indicaciones;
                    $cantidad_i131  = 	$row->indicaciones_tratamiento;

                    
                   	$medico = $row->nombre_medico;
                 
                    
                    $nombre 		= pasarMayusculas($nombre);
                    $horas 			= pasarMayusculas($horas);
                    $indicaciones 	= pasarMayusculas($indicaciones);
                    $fecha_estudio2 = pasarMayusculas($fecha_estudio2);
                    $medico 		= pasarMayusculas($medico);
                   	$cantidad_i131 = pasarMayusculas($cantidad_i131);
                  
                    //$indicaciones   = $_POST['indicaciones'];
                    $indicaciones = 'Suspender eutirox o levotiroxina 4 semanas, tapasol o tiamazol 10 dias.';

                    //$eje_y=0;
					$this->CreateTextBox('Nombre del paciente:  ', 0+$eje_x, 45+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($nombre, 40+$eje_x, 45+$eje_y, 80, 10, 10, '');

					$this->CreateTextBox('Fecha de nacimiento: ', 0+$eje_x, 50+$eje_y, 80, 10, 10, 'B');
					$this->CreateTextBox($fecha_nacimiento2, 40+$eje_x, 50+$eje_y, 80, 10, 10, '');

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

					if($medico == ' '){
						$this->CreateTextBox('A quien corresponda', 40+$eje_x, 77+$eje_y, 80, 10, 10, '');	
					}else{
						$this->CreateTextBox($medico, 40+$eje_x, 77+$eje_y, 80, 10, 10, '');	
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
						if(isset($id_paciente)){
							
							$mysql = new mysql();
							$link = $mysql->connect(); 
							$sql = $mysql->query($link,"SELECT concat(users.nombre,' ',users.ap_paterno,' ',users.ap_materno) as nombre 
														FROM users 
														WHERE users.idusuario = (SELECT pacientes.atendio FROM pacientes WHERE idpacientes=$id_paciente)");

							$row3 = $mysql->f_obj($sql);
							
							if(!isset($row3)){
								$this->CreateTextBox('', 110+$eje_x,122+$eje_y, 80, 10, 10, '');
							}else{
								$this->CreateTextBox('Atendió:'.$row3->nombre, 110+$eje_x,122+$eje_y, 80, 10, 10, '');	
							}

						}else{
							$this->CreateTextBox('', 110+$eje_x,122+$eje_y, 80, 10, 10, '');
						}
							
						$this->cont++;
					}
	}

}

//comprueba que halla datos
	$_POST["pagina"]="imrpimir"; 

	if(!isset($_POST['idpaciente'])){
		header("Location: index.php");
	}
	else{
		//print_r($_POST);
		$idpaciente = $_POST['idpaciente'];

		include_once "include/mysql.php";
       
        $mysql = new mysql();
        $link = $mysql->connect(); 
        $sql = $mysql->query($link,"SELECT indicaciones_tratamiento,
											estudio_idgammagramas
                                            FROM pacientes
                                            WHERE idpacientes = '$idpaciente'");

		$row = $mysql->f_obj($sql);
		

        if($row->indicaciones_tratamiento == '' && $row->estudio_idgammagramas != 40){
        	$row = gammagrama($idpaciente);
			 
			//print_r($row);

			//errores de impresion por consulta y repeticion de datos
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
			$pdf->Recibo_paciente_gammagrama(0,0,$row);
			$pdf->Recibo_interno_gammagrama(0,130,$row);


					$nombre         = $row->nombre;
                    $fecha_estudio  = $row->fecha;

			//Close and output PDF document
			$nombre_pdf = $fecha_estudio.'_'.$nombre.'.pdf';                    
			$pdf->Output($nombre_pdf, 'I');


        }
        else{
        	
        	$row = tratamiento($idpaciente);

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
			$pdf->Recibo_paciente_tratamiento(0,0,$row);
			$pdf->Recibo_paciente_tratamiento(0,127,$row);
					$nombre         = $row->nombre;
                    $fecha_estudio  = $row->fecha;

			//Close and output PDF document
			$nombre_pdf = $fecha_estudio.'_'.$nombre.'.pdf';                    
			$pdf->Output($nombre_pdf, 'I');
        }
		
	}





?>
<?php

function gammagrama($idpaciente){
	$mysql = new mysql();
    $link = $mysql->connect(); 
    $sql = $mysql->query($link,"SELECT 	t1.idpacientes,
										t1.doctores_iddoctores,
    									t1.estudio_idgammagramas,
    									t1.fecha,
    									t1.hora,
    									concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
    									t1.num_tel,
    									t1.num_tel2,
                                        t1.institucion,
                                        t1.indicaciones,
                                        (SELECT concat(t2.grado,' ',t2.nombre,' ',t2.ap_paterno,' ',t2.ap_materno) as nombre
                                        FROM doctores t2
                                        WHERE iddoctores =t1.doctores_iddoctores )as nombre_medico,

    									(select concat(t3.tipo,' ',t3.nombre) as nombre
                                        from estudio t3
                                        where idgammagramas =t1.estudio_idgammagramas )as estudio,
										t1.edad,
										t1.fecha_nacimiento,
										(select descripcion
										 from tblc_edad 
										 where id_edad = (select id_edad 
										 					from tbl_edad_paciente 
															where idpacientes= $idpaciente) ) as tipo_edad

                                    FROM pacientes t1
                                    WHERE idpacientes = '$idpaciente'");
	$row = $mysql->f_obj($sql);
	//print_r($row);

    return $row;
}

function tratamiento($idpaciente){
	$mysql = new mysql();
    $link = $mysql->connect(); 
    $sql = $mysql->query($link,"SELECT 	t1.idpacientes,
										t1.doctores_iddoctores,
    									t1.estudio_idgammagramas,
    									t1.fecha,
    									t1.hora,
    									concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
    									t1.num_tel,
    									t1.num_tel2,
                                        t1.institucion,
                                        t1.indicaciones,
                                        t1.indicaciones_tratamiento,
                                        (SELECT concat(t2.grado,' ',t2.nombre,' ',t2.ap_paterno,' ',t2.ap_materno) as nombre
                                        FROM doctores t2
                                        WHERE iddoctores =t1.doctores_iddoctores )as nombre_medico,

    									(select concat(t3.tipo,' ',t3.nombre) as nombre
                                        from estudio t3
                                        where idgammagramas =t1.estudio_idgammagramas )as estudio,
										t1.edad,
										t1.fecha_nacimiento,
										(select descripcion
										 from tblc_edad 
										 where id_edad = (select id_edad 
										 					from tbl_edad_paciente 
															where idpacientes= $idpaciente) ) as tipo_edad
										

                                    FROM pacientes t1
                                    WHERE idpacientes = '$idpaciente'");
    $row = $mysql->f_obj($sql);

    return $row;
}
?>

