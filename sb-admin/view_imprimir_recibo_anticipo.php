<?php
 
// Include the main TCPDF library (search for installation path).
//error_reporting(0);
require_once('include/tcpdf/tcpdf.php');
include_once "include/mysql.php";
session_start();
//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 
	if(!isset($_POST['idpaciente'])){
		header("Location: index.php");
	}
	else{
		
		$ag = new anticipo();
		$ag->insertar_anticipo();
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
					
                    $nombre             = $_POST['nombre_paciente'];
                    $estudio            = $_POST['estudio'];
					$monto_anticipo     = $_POST['monto_anticipo'];
					date_default_timezone_set('America/Mexico_City');
                    $fecha_anticipo     = date("Y-m-d");
                    
                    $anticipo = '$ '.number_format($monto_anticipo,2);

                    //test
                    //$this->CreateTextBox($anticipo, 0,0, 80, 10, 10, '');
                    $this->CreateTextBox($anticipo, 145,28, 80, 10, 10, '');
                    $this->CreateTextBox($nombre, 35,35, 80, 10, 10, '');

                    include "include/num_to_letter.php";
					$V=ValorEnLetras($monto_anticipo, 'pesos');

					$this->CreateTextBox($V, 35,44, 80, 10, 10, '');
					$this->SetFont('helvetica', '', 10);
                    //$this->CreateTextBox($estudio, 35,65, 140, 10, 10, ''); update <-- this for next
                    $this->MultiCell(140, 10, $estudio, 0, 'L', 0, 0, $x=55, $y=74, true);
                    
                    
                  	$fecha = explode('-', $fecha_anticipo);
                  	$año = str_split($fecha[0], 2); // divido en dos 2015 --->  [0]=20   [1]=15
					

                  	$this->CreateTextBox($fecha[2], 80,79, 80, 10, 10, '');
                  	$this->CreateTextBox($fecha[1], 114,79, 80, 10, 10, '');
                  	$this->CreateTextBox($año[1], 165,79, 80, 10, 10, '');


					//$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
					//$this->Line(80, 125, 140, 125, $style);
					
					$id_usuario = $_SESSION['id'];
					$mysql = new mysql();
        			$link = $mysql->connect(); 
        			$datos_usuario = $mysql->query($link,"SELECT concat(nombre,' ',ap_paterno,' ', ap_materno) as nombre
															FROM users 
															WHERE idusuario=$id_usuario");        
        			$row4 = $mysql->f_obj($datos_usuario);

					$this->CreateTextBox($row4->nombre, 55,100, 80, 10, 10, '');		
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
$pdf->SetTitle('Recibo Anticipo');
$pdf->SetSubject('Recibo de anticipo');
 
// add a page
$pdf->AddPage();
$pdf->Recibo_paciente();

//Close and output PDF document
$fecha_anticipo     = $_POST['fecha_actual'];
$nombre             = $_POST['nombre_paciente'];

$nombre_pdf = $fecha_anticipo.'_'.$nombre.'.pdf';                    
$pdf->Output($nombre_pdf, 'I');

?>

<?php
class anticipo{
	function insertar_anticipo(){
		
		$tipo_pago 		= $_POST['tipo_pago']; 		//dep_banamex, pago_santander, anticipo_efe, sr_pago
		$monto_anticipo = $_POST['monto_anticipo'];
		$no_recibo      = $_POST['no_recibo'];
		$factura  		= $_POST['factura'];
		$idanticipo     = $_POST['idanticipo'];
		$fecha_anticipo = $_POST['fecha_actual'];
		$debe 			= $_POST['debe'];
		$idpaciente 	= $_POST['idpaciente'];
		$descuento 		= $_POST['descuento']; 

		$monto_restante = $debe - $monto_anticipo;

		$fecha_estudio = $_POST['fecha_estudio'];


		if($tipo_pago != 'dep_banamex' && $tipo_pago != 'pago_santander' && $tipo_pago != 'pago_cheque' && $tipo_pago != 'transferencia' && $tipo_pago != 'sr_pago'){
			$colum_set = $tipo_pago;} //es efectivo

		if($tipo_pago != 'dep_banamex' && $tipo_pago != 'pago_santander' && $tipo_pago != 'pago_cheque' && $tipo_pago != 'anticipo_efe' && $tipo_pago != 'sr_pago'){
			$colum_set = $tipo_pago;} //transferencia

		if($tipo_pago != 'dep_banamex' && $tipo_pago != 'pago_santander' && $tipo_pago != 'transferencia' && $tipo_pago != 'anticipo_efe' && $tipo_pago != 'sr_pago'){
			$colum_set = $tipo_pago;} //pago cheque

        if($tipo_pago != 'dep_banamex' && $tipo_pago != 'pago_cheque' && $tipo_pago != 'transferencia' && $tipo_pago != 'anticipo_efe' && $tipo_pago != 'sr_pago'){
			$colum_set = $tipo_pago;} //pago santander                  

		if($tipo_pago != 'pago_santander' && $tipo_pago != 'pago_cheque' && $tipo_pago != 'transferencia' && $tipo_pago != 'anticipo_efe' && $tipo_pago != 'sr_pago'){
			$colum_set = $tipo_pago;} //pago banamex
		
		if($tipo_pago != 'dep_banamex' && $tipo_pago != 'pago_santander' && $tipo_pago != 'pago_cheque' && $tipo_pago != 'transferencia' && $tipo_pago != 'anticipo_efe'){
			$colum_set = $tipo_pago;} //sr_pago
		
		/*echo $debe;
		echo $colum_set;
		echo '---'.$monto_restante;
		echo '---'.$monto_anticipo;*/
		$mysql = new mysql();
        $link = $mysql->connect(); 

		$sql = $mysql->query($link,"UPDATE anticipos 
									SET $colum_set = $monto_anticipo,
										factura = '$factura',
										no_recibo = '$no_recibo'
									WHERE idanticipos = $idanticipo");

		$sql = $mysql->query($link,"UPDATE pacientes_has_anticipos 
									SET fecha_anticipo = '$fecha_anticipo',
										monto_restante = $monto_restante
									WHERE  pacientes_idpacientes = $idpaciente AND anticipos_idanticipos = $idanticipo");

		if($monto_restante != '0.00'){
			/*echo '---'.$monto_restante;}*/
			$sql = $mysql->query($link,"INSERT INTO anticipos (idanticipos, dep_banamex,
															   pago_santander, pago_cheque,
															   transferencia, anticipo_efe, sr_pago,
															   factura, no_recibo)
										VALUES('', 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '$factura','')");
			
			$idanticipo = mysqli_insert_id($link);

			$sql = $mysql->query($link,"INSERT INTO pacientes_has_anticipos (pacientes_idpacientes,
																			anticipos_idanticipos,
															   				fecha_anticipo,
															   				fecha_estudio,
															   				monto_restante)
										VALUES($idpaciente, $idanticipo, '0000-00-00', '$fecha_estudio', $monto_restante)");

			/*   Actualización 21/01/2016   
			*  Busco si esta en la tabla facturas ya que se hizo reestructuración
			de la base de datos insertando la tabla facturas esa llevara el control 
			de quien quiere factura y aquien ya se le facturó
			*/
			$link = $mysql->connect(); 
            $sql2 = $mysql->query($link,"SELECT idfactura 
										FROM facturas 
										WHERE idpaciente = $idpaciente");
                        
		    $row3 =  $mysql->f_obj($sql2);
		    
		    $num = $mysql->f_num($sql2);
		    

			if($num){
				$sql = $mysql->query($link,"UPDATE facturas 
												SET requiere_factura= '$factura'
												WHERE idfactura = $row3->idfactura");
			}
			else{
				$sql = $mysql->query($link,"INSERT INTO facturas (idfactura,
																idpaciente,
																requiere_factura,
																facturado,
																numero_facturas)
										VALUES('', $idpaciente, '$factura', 'NO','')");
			}
			/*******************************************************/
			/*******************************************************/
		}

		if($descuento != 0){
			$sql = $mysql->query($link,"INSERT INTO descuentos (iddescuento,
																idpaciente,
																descuento)
										VALUES('', $idpaciente, $descuento)");
		}


		/*   Actualización 21/01/2016   
			*  Busco si esta en la tabla facturas ya que se hizo reestructuración
			de la base de datos insertando la tabla facturas esa llevara el control 
			de quien quiere factura y aquien ya se le facturó
			*/
			$link = $mysql->connect(); 
            $sql2 = $mysql->query($link,"SELECT idfactura 
										FROM facturas 
										WHERE idpaciente = $idpaciente");
                        
		    $row3 =  $mysql->f_obj($sql2);
		    
		    $num = $mysql->f_num($sql2);
		    

			if($num){
				$sql = $mysql->query($link,"UPDATE facturas 
												SET requiere_factura= '$factura'
												WHERE idfactura = $row3->idfactura");
			}
			else{
				$sql = $mysql->query($link,"INSERT INTO facturas (idfactura,
																idpaciente,
																requiere_factura,
																facturado,
																numero_facturas)
										VALUES('', $idpaciente, '$factura', 'NO','')");
			}
			/*******************************************************/
			/*******************************************************/

		
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

