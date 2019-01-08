<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 

	if(!isset($_POST['fecha_estudios'])){
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
		
		$fecha = $_POST['fecha_estudios'];
		
		$fecha_fin = last_month_day($fecha);
		$fecha_ini = first_month_day($fecha);
		//echo 'Fecha inicial: '.$fecha_ini.' Fecha final: '.$fecha_fin;

		$fecha = pasarMayusculas(fecha_letras_sin_dia($fecha));

		$this->SetFont('Helvetica', 'B', 11, '', 'false');
		$this->TextBox('PACIENTES ATENDIDOS DR. EDUARDO MARTÍNEZ GAMBOA', 0,45, 180, 5, 1, 'C');
		$this->TextBox('CORRESPONDIENTE AL MES DE '.$fecha, 0,50, 180, 5, 1, 'C');

		$y=55;
		$this->TextBox('Fecha', 0,$y, 15, 5, 1, 'C');
		$this->TextBox('Nombre del paciente', 15,$y, 60, 5, 1, 'C');
		$this->TextBox('Estudio realizado', 75,$y,  40, 5, 1, 'C');
		$this->TextBox('Institución', 115,$y, 20, 5, 1, 'C');
		$this->TextBox('Médico tratante', 135, $y, 45, 5, 1, 'C');

		$y += 5; //salto de linea
		
		include_once "include/mysql.php";

        $mysql = new mysql();
        $link = $mysql->connect(); 

        //------------------------PACIENTES QUE ATENDÍO EL DR. MARTINEZ-----------------------------------------

        $sql = $mysql->query($link,"SELECT  
        									t1.fecha,
        									
        									CONCAT(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) AS nombre,
   
										    (SELECT concat(t2.tipo,' ',t2.nombre) AS estudio 
         										FROM estudio t2 
         										WHERE idgammagramas = t1.estudio_idgammagramas) AS estudio,

										    t1.institucion,

										    (SELECT concat(t3.grado,' ',t3.nombre,' ',t3.ap_paterno,' ',t3.ap_materno) AS medico 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores) AS medico

									FROM pacientes t1 
									WHERE   (estudio_idgammagramas >= 3  AND estudio_idgammagramas <= 10) 
												AND (estatus = 'ATENDIDO' AND (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin')) ORDER BY fecha;");
        
		$num_row = $mysql->f_num($sql);
		//echo $num_row;
		//cuarenta filas en una hoja como máximo
		$max_fila = 37;
		//$cont = 150;
		while($num_row > 0){
		//while($cont > 0){
			if ($max_fila > 0 ){

				$row = $mysql->f_obj($sql);
				$this->SetFont('Helvetica', '', 9, '', 'false');
				
				$this->TextBox($row->fecha, 0,$y, 15, 5, 1, 'J');
				$this->TextBox($row->nombre, 15,$y, 60, 5, 1, 'L');
				//$this->TextBox($row->estudio, 75,$y,  40, 5, 1, 'C');
				$this->TextBox("Gammagrama cardíaco", 75,$y,  40, 5, 1, 'C');
				$this->TextBox($row->institucion, 115,$y, 20, 5, 1, 'C');
				$this->TextBox($row->medico, 135,$y, 45, 5, 1, 'J');
				$y+=5;

				$num_row--;
				$max_fila--;
				//$cont--;
			}else{
				
				$max_fila = 37;
				$this->AddPage();

				$y=45;

				$this->SetFont('Helvetica', 'B', 11, '', 'false');
				$this->TextBox('PACIENTES ATENDIDOS DR. EDUARDO MARTÍNEZ GAMBOA', 0,$y, 180, 5, 1, 'C');
				$this->TextBox('CORRESPONDIENTE AL MES DE '.$fecha, 0,$y+5, 180, 5, 1, 'C');

				$y=55;
				$this->TextBox('Fecha', 0,$y, 15, 5, 1, 'C');
				$this->TextBox('Nombre del paciente', 15,$y, 60, 5, 1, 'C');
				$this->TextBox('Estudio realizado', 75,$y,  40, 5, 1, 'C');
				$this->TextBox('Institución', 115,$y, 20, 5, 1, 'C');
				$this->TextBox('Médico tratante', 135, $y, 45, 5, 1, 'C');

				$y += 5; //salto de linea
			}
		}

		//------------------------PACIENTES QUE ATENDÍO LA DR. AREVALO-----------------------------------------

		$sql = $mysql->query($link,"SELECT  
        									t1.fecha,
        									CONCAT(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) AS nombre,
   
										    (SELECT concat(t2.tipo,' ',t2.nombre) AS estudio 
         										FROM estudio t2 
         										WHERE idgammagramas = t1.estudio_idgammagramas) AS estudio,

										    t1.institucion,

										    (SELECT t3.grado 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores) AS grado,

										    (SELECT t3.nombre 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores) AS nombre_med,

											(SELECT t3.ap_paterno 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores 
          											AND t3.ap_paterno = 'AREVALO') AS ap_paterno_med,

											(SELECT t3.ap_materno 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores
          											AND t3.ap_materno = 'AGUILAR') AS ap_materno_med

									FROM pacientes t1 inner join doctores t3
									WHERE  (t3.ap_paterno = 'AREVALO' AND t3.ap_materno = 'AGUILAR') 
											AND (t1.doctores_iddoctores = t3.iddoctores)
											AND(estudio_idgammagramas >= 3  AND estudio_idgammagramas <= 10) 
											AND (estatus = 'ATENDIDO' AND (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin')) ORDER BY fecha;");

        
		$num_row = $mysql->f_num($sql);
		//echo $num_row;
		//PACIENTES QUE ATENDÍO EL DR. MARTINEZ
		//cuarenta filas en una hoja como máximo
		$max_fila = 37;
				$this->AddPage();

				$y=45;

				$this->SetFont('Helvetica', 'B', 11, '', 'false');
				$this->TextBox('PACIENTES ATENDIDOS DR. AREVALO', 0,$y, 180, 5, 1, 'C');
				$this->TextBox('CORRESPONDIENTE AL MES DE '.$fecha, 0,$y+5, 180, 5, 1, 'C');

				$y=55;
				$this->TextBox('Fecha', 0,$y, 15, 5, 1, 'C');
				$this->TextBox('Nombre del paciente', 15,$y, 60, 5, 1, 'C');
				$this->TextBox('Estudio realizado', 75,$y,  40, 5, 1, 'C');
				$this->TextBox('Institución', 115,$y, 20, 5, 1, 'C');
				$this->TextBox('Médico tratante', 135, $y, 45, 5, 1, 'C');

				$y += 5; //salto de linea

		//$cont = 150;
		while($num_row > 0){
		//while($cont > 0){
			if ($max_fila > 0 ){

				$row = $mysql->f_obj($sql);

				$this->SetFont('Helvetica', '', 9, '', 'false');
				
				$this->TextBox($row->fecha, 0,$y, 15, 5, 1, 'J');
				$this->TextBox($row->nombre, 15,$y, 60, 5, 1, 'L');
				//$this->TextBox($row->estudio, 75,$y,  40, 5, 1, 'C');
				$this->TextBox("Gammagrama cardíaco", 75,$y,  40, 5, 1, 'C');
				$this->TextBox($row->institucion, 115,$y, 20, 5, 1, 'C');
				$this->TextBox($row->grado.' '.$row->nombre_med.' '.$row->ap_paterno_med.' '.$row->ap_materno_med, 135,$y, 45, 5, 1, 'J');
				$y+=5;

				$num_row--;
				$max_fila--;
				//$cont--;
				
			}else{
				
				$max_fila = 37;
				$this->AddPage();

				$y=45;

				$this->SetFont('Helvetica', 'B', 11, '', 'false');
				$this->TextBox('PACIENTES ATENDIDOS DR. AREVALO', 0,$y, 180, 5, 1, 'C');
				$this->TextBox('CORRESPONDIENTE AL MES DE '.$fecha, 0,$y+5, 180, 5, 1, 'C');

				$y=55;
				$this->TextBox('Fecha', 0,$y, 15, 5, 1, 'C');
				$this->TextBox('Nombre del paciente', 15,$y, 60, 5, 1, 'C');
				$this->TextBox('Estudio realizado', 75,$y,  40, 5, 1, 'C');
				$this->TextBox('Institución', 115,$y, 20, 5, 1, 'C');
				$this->TextBox('Médico tratante', 135, $y, 45, 5, 1, 'C');

				$y += 5; //salto de linea
			}
		}

		//------------------------PACIENTES QUE ATENDÍO LA DRA. VIDAL-----------------------------------------

		$sql = $mysql->query($link,"SELECT  
        									t1.fecha,
        									CONCAT(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) AS nombre,
   
										    (SELECT concat(t2.tipo,' ',t2.nombre) AS estudio 
         										FROM estudio t2 
         										WHERE idgammagramas = t1.estudio_idgammagramas) AS estudio,

										    t1.institucion,

										    (SELECT t3.grado 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores) AS grado,

										    (SELECT t3.nombre 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores
          											AND t3.nombre ='SILVIA') AS nombre_med,

											(SELECT t3.ap_paterno 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores 
          											AND t3.ap_paterno = 'VIDAL') AS ap_paterno_med,

											(SELECT t3.ap_materno 
          										FROM doctores t3 
          										WHERE iddoctores = t1.doctores_iddoctores
          											AND t3.ap_materno = 'MUÑIZ') AS ap_materno_med

									FROM pacientes t1 inner join doctores t3
									WHERE (t3.nombre ='SILVIA') 
											AND (t1.doctores_iddoctores = t3.iddoctores) 
											AND (estudio_idgammagramas >= 3  AND estudio_idgammagramas <= 10) 
											AND (estatus = 'ATENDIDO' AND (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin')) ORDER BY fecha;");

        
		$num_row = $mysql->f_num($sql);
		//echo $num_row;
		//PACIENTES QUE ATENDÍO EL DR. MARTINEZ
		//cuarenta filas en una hoja como máximo
		$max_fila = 37;
				$this->AddPage();

				$y=45;

				$this->SetFont('Helvetica', 'B', 11, '', 'false');
				$this->TextBox('PACIENTES ATENDIDOS DRA. VIDAL', 0,$y, 180, 5, 1, 'C');
				$this->TextBox('CORRESPONDIENTE AL MES DE '.$fecha, 0,$y+5, 180, 5, 1, 'C');

				$y=55;
				$this->TextBox('Fecha', 0,$y, 15, 5, 1, 'C');
				$this->TextBox('Nombre del paciente', 15,$y, 60, 5, 1, 'C');
				$this->TextBox('Estudio realizado', 75,$y,  40, 5, 1, 'C');
				$this->TextBox('Institución', 115,$y, 20, 5, 1, 'C');
				$this->TextBox('Médico tratante', 135, $y, 45, 5, 1, 'C');

				$y += 5; //salto de linea

		//$cont = 150;
		while($num_row > 0){
		//while($cont > 0){
			if ($max_fila > 0 ){

				$row = $mysql->f_obj($sql);

				$this->SetFont('Helvetica', '', 9, '', 'false');
				
				$this->TextBox($row->fecha, 0,$y, 15, 5, 1, 'J');
				$this->TextBox($row->nombre, 15,$y, 60, 5, 1, 'L');
				//$this->TextBox($row->estudio, 75,$y,  40, 5, 1, 'C');
				$this->TextBox("Gammagrama cardíaco", 75,$y,  40, 5, 1, 'C');
				$this->TextBox($row->institucion, 115,$y, 20, 5, 1, 'C');
				$this->TextBox($row->grado.' '.$row->nombre_med.' '.$row->ap_paterno_med.' '.$row->ap_materno_med, 135,$y, 45, 5, 1, 'J');
				$y+=5;
			    
				$num_row--;
				$max_fila--;
				//$cont--;
				
			}else{
				
				$max_fila = 37;
				$this->AddPage();

				$y=45;

				$this->SetFont('Helvetica', 'B', 11, '', 'false');
				$this->TextBox('PACIENTES ATENDIDOS DRA. VIDAL', 0,$y, 180, 5, 1, 'C');
				$this->TextBox('CORRESPONDIENTE AL MES DE '.$fecha, 0,$y+5, 180, 5, 1, 'C');

				$y=55;
				$this->TextBox('Fecha', 0,$y, 15, 5, 1, 'C');
				$this->TextBox('Nombre del paciente', 15,$y, 60, 5, 1, 'C');
				$this->TextBox('Estudio realizado', 75,$y,  40, 5, 1, 'C');
				$this->TextBox('Institución', 115,$y, 20, 5, 1, 'C');
				$this->TextBox('Médico tratante', 135, $y, 45, 5, 1, 'C');

				$y += 5; //salto de linea
			}
		}

		$mysql->close();
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
	$pdf->tratamiento();
	//Close and output PDF document
	$pdf->Output('comisión_cardiacos.pdf', 'I');
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

	function fecha_letras_sin_dia($fecha_estudio){

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

		$fecha_estudio2 = $nommes[$mes-1]; 
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

    function last_month_day($fecha) { 
    	date_default_timezone_set('America/Mexico_City');
    	$fecha = explode("-", $fecha);
      	/*$month = date('m');
      	$year = date('Y');*/
      	$year = $fecha[0];
      	$month= $fecha[1];
      	$day = date("d", mktime(0,0,0, $month+1, 0, $year));
 
      	return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
  	};
 
  	function first_month_day($fecha) {
  		date_default_timezone_set('America/Mexico_City');
      	$fecha = explode("-", $fecha);
      	/*$month = date('m');
      	$year = date('Y');*/
		$year = $fecha[0];
		$month= $fecha[1];
      	return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
  	}
?>