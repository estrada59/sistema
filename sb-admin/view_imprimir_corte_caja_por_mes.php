<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');
include_once "include/funciones_consultas.php";
//comprueba que halla datos
$_POST["pagina"]="imrpimir"; 

	if(!isset($_POST['fecha_corte'])){
		header("Location: index.php");
	}
	

class MYPDF extends TCPDF {
	public function Header() {
		//$this->setJPEGQuality(90);
		
		// set bacground image
        //$this->Image('images/hoja_membretada.jpg', $x = 0, $y = 15, $w = 230, $h = 297, '', '', $align='center', false, 300, '', false, false, 0);
		$this->ImageSVG($file='images/logo_numedics.svg', $x=215, $y=4, $w='60', $h='30', $link='', $align='', $palign='', $border=0, $fitonpage=false);

		$mes= obtener_mes($_POST['fecha_corte']);
		$mes = pasarMayusculas($mes);
		
		$this->CreateTextBox('CORTE CAJA MENSUAL CORRESPONDIENTE AL MES DE  '.$mes, -6,8, 80, 10, 11, 'B');
		
		/*
		$this->CreateTextBox($fecha_corte,  -6,17, 80, 10, 11, '');	
		$this->CreateTextBox('MEDICINA NUCLEAR DE CHIAPAS S. DE R. L. DE C. V', -6,8, 80, 10, 11, 'B');
		$this->CreateTextBox('DR. JORGE LUIS CISNEROS ENCALADA', 5,17, 80, 10, 11, '');		
		$this->CreateTextBox('Cédula profesional: 1576031     ESP. AECEM-24430.', 0,23, 80, 10, 10, '');		
		*/
		$this->ImageSVG($file='images/logo.svg', $x=-70, $y=120, $w='135', $h='135', $link='', $align='', $palign='', $border=0, $fitonpage=false);
		
	}
	public function Footer() {
		$style = array('width' => 0.8, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(4,94,87, 0));
		$this->Line(50,200,260,200,$style);
		$this->CreateTextBox('14 pte. nte. no. 216 esq. Circunvalación pichucalco, Col. Moctezuma, C.P. 29030, Tuxtla Gutiérrez, Chiapas.', 30,200, 80, 10, 9, '');		
		$this->CreateTextBox('Tel.: 60 29 211 y 60 29 479                   E-mail: jorge@numedics.com.mx                   www.numedics.com.mx', 30,205, 80, 10, 9, '');
		//$this->ImageSVG($file='images/logo.svg', $x=-70, $y=120, $w='135', $h='135', $link='', $align='', $palign='', $border=0, $fitonpage=false);
	}
	public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 5, $fontsize = 11, $fontstyle = 'ARIAL', $align = 'L') {
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
		$this->Cell($width, $height, $textval, 0, false, $align,'','',$strech=0);
	}
	public function Textbox($textval, $x=0, $y, $w=0,$h=0,$b=0,$align='L' ){
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->Cell($w, $h, $textval, $b, 2, $align,'','',$strech=1);
	}
	public function Multibox($textval, $x=0, $y, $w=0,$h=0,$b=0,$align='L',$ln=0 ){
		$this->SetXY($x+20, $y); // 20 = margin left
		$this->MultiCell($w, $h, $textval, $b, $align,'0','',$x+20, $y, $strech=1);
	}
	public function imprimir_lista() {
		// set font
		$this->SetFont('helvetica', 'B', 9);
		$x=-6;
		$this->Textbox('Fecha',					$x+0,	45,	15,  7, 1, 'C');		
		$this->Textbox('Nombre del paciente', 	$x+15,	45, 60,  7, 1, 'C');
		$this->Textbox('Estudio', 				$x+75,	45, 60,  7, 1, 'C');
		$this->Textbox('Precio', 				$x+135,	45, 20,  7, 1, 'C',0);
		$this->Textbox('Por cobrar', 		    $x+155,	45, 20,  7, 1, 'C',1);
		$this->Textbox('Forma de pago', 		$x+175,	45, 20,  7, 1, 'C');
		$this->Textbox('Monto cobrado', 		$x+195,	45, 20,  7, 1, 'C');
		$this->Textbox('Fact.', 				$x+215,	45, 10,  7, 1, 'C');
		$this->Textbox('Recibo', 				$x+225,	45, 10,  7, 1, 'C');
		$this->Textbox('Desc.', 				$x+235,	45, 10,  7, 1, 'C');

		$fecha_corte = $_POST['fecha_corte'];
		$datos = get_data($fecha_corte);
		
		$cont = count($datos);

		$filas_por_hoja = 18;
		$i=0;
		$y = 7;
		$suma=0;
		$this->SetFont('helvetica', '', 8);

		while ($i<$cont){

			if($filas_por_hoja != 0){
				//echo $filas_por_hoja.'	';
				$suma += $datos[$i][6];
				$datos[$i][6] =number_format($datos[$i][6],2);

				$this->Textbox($datos[$i][0],		$x+0,	$y+45, 15,  7, 1, 'C');	//fecha	
				$this->Textbox($datos[$i][1], 		$x+15,	$y+45, 60,  7, 1, 'C'); //nombre
				$this->Textbox($datos[$i][2],   	$x+75,	$y+45, 60,  7, 1, 'C'); //estudio
				$this->Textbox($datos[$i][3], 		$x+135,	$y+45, 20,  7, 1, 'C'); //precio
				$this->Textbox($datos[$i][4], 		$x+155,	$y+45, 20,  7, 1, 'C'); //por pagar
				$this->Textbox($datos[$i][5], 		$x+175,	$y+45, 20,  7, 1, 'C');	//forma de pago
				$this->Textbox('$ '.$datos[$i][6], 	$x+195,	$y+45, 20,  7, 1, 'C'); //anticipo
				$this->Textbox($datos[$i][7], 		$x+215,	$y+45, 10,  7, 1, 'C'); //factura
				$this->Textbox($datos[$i][8].'', 	$x+225,	$y+45, 10,  7, 1, 'C'); //recibo
				$this->Textbox($datos[$i][9], 		$x+235,	$y+45, 10,  7, 1, 'C'); //desc.

				$y+=7;
				$filas_por_hoja--;

				$i++;
			}
			else{
				$this->AddPage();
				
				$this->SetFont('helvetica', 'B', 9);
				$x=-6;
				$this->Textbox('Fecha',					$x+0,	45,	15,  7, 1, 'C');		
				$this->Textbox('Nombre del paciente', 	$x+15,	45, 60,  7, 1, 'C');
				$this->Textbox('Estudio', 				$x+75,	45, 60,  7, 1, 'C');
				$this->Textbox('Precio', 				$x+135,	45, 20,  7, 1, 'C',0);
				$this->Textbox('Por pagar', 		    $x+155,	45, 20,  7, 1, 'C',1);
				$this->Textbox('Forma de pago', 		$x+175,	45, 20,  7, 1, 'C');
				$this->Textbox('Anticipo', 				$x+195,	45, 20,  7, 1, 'C');
				$this->Textbox('Fact.', 				$x+215,	45, 10,  7, 1, 'C');
				$this->Textbox('Recibo', 				$x+225,	45, 10,  7, 1, 'C');
				$this->Textbox('Desc.', 				$x+235,	45, 10,  7, 1, 'C');

				$filas_por_hoja = 18;
				$y = 7;
				$this->SetFont('helvetica', '', 8);
			}

			
		}

		$suma =number_format($suma,2);
		$this->Textbox('TOTAL: ', 	$x+175,	$y+45, 20,  7, 1, 'C');
		$this->Textbox('$ '.$suma, 	$x+195,	$y+45, 20,  7, 1, 'C');
	
	}
}
	// create a PDF object
	//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
	// set document (meta) information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Numedics (Medicina Nuclear)');
	$pdf->SetTitle('Corte de caja mensual');
	$pdf->SetSubject('Corte de caja pacientes particulares');
 
	// add a page
	$pdf->AddPage();
	$pdf->imprimir_lista();

	$fecha_corte = $_POST['fecha_corte'];
	//Close and output PDF document
	$pdf->Output($fecha_corte.'_corte_caja.pdf', 'I');
?>

<?php
	function get_data( $fecha_corte){
		
		include_once "include/mysql.php";
		
		$fecha_ini = first_month_day($_POST['fecha_corte']);
        $fecha_fin = last_month_day($_POST['fecha_corte']);

		$mysql = new mysql();
		$link = $mysql->connect(); 

		$sqltest = $mysql->query($link,"SELECT  t1.pacientes_idpacientes as idpaciente,

                                                t1.anticipos_idanticipos as idanticipo,
												t1.monto_restante as debe,
                                                t1.fecha_anticipo as fecha,

                                                (SELECT concat(t2.nombre,' ',t2.ap_paterno,' ',t2.ap_materno) as nombre
                                                	FROM pacientes t2
                                                    WHERE idpacientes = t1.pacientes_idpacientes) as nombre,
                                                            
                                                (SELECT concat(t4.tipo,' ',t4.nombre) as estudio
                                                	FROM pacientes t3 inner join estudio t4 
                                                    	ON t4.idgammagramas = t3.estudio_idgammagramas
                                                    WHERE idpacientes = t1.pacientes_idpacientes ) as estudio,

												(SELECT t8.descuento
                                                	FROM descuentos t8
                                                    WHERE t8.idpaciente = t1.pacientes_idpacientes) as descuento,

												t7.dep_banamex,
                                                t7.pago_santander,
                                                t7.anticipo_efe,
                                                t7.transferencia,
                                                t7.pago_cheque,
                                                t7.factura,
                                                t7.no_recibo

                                            FROM pacientes_has_anticipos t1 inner join anticipos t7 
                                            	on idanticipos = t1.anticipos_idanticipos
                                            WHERE 
                                            	fecha_anticipo >= '$fecha_ini' AND fecha_anticipo <= '$fecha_fin'
											ORDER BY t7.no_recibo;  ");
		$cont = 0;
                    
        while ($row = $mysql->f_obj($sqltest)) { 
        	//   busca el precio del estudio de acuerdo al tipo de 
			//   institución particular
				$link   = $mysql->connect();
                $sql    = $mysql->query($link,"SELECT 	institucion,
                										estudio_idgammagramas
                                                    FROM pacientes
                                                    WHERE idpacientes = $row->idpaciente ;");

				$row2  = $mysql->f_obj($sql);

                $institucion = mb_strtolower($row2->institucion, "UTF-8" );
				$institucion = str_replace(" ", "_", $institucion);

                $link = $mysql->connect(); 
                $sql2 = $mysql->query($link,"SELECT nombre
                                                FROM instituciones
                                                WHERE nombre = '$institucion'");
                        
				$row3 =  $mysql->f_obj($sql2);

				$link = $mysql->connect(); 
				$sql3 = $mysql->query($link,"SELECT $row3->nombre as precio
                                                FROM  estudio 
                                                WHERE idgammagramas = $row2->estudio_idgammagramas");
				$row4 =  $mysql->f_obj($sql3);

                $row->precio = $row4->precio;

            // fin búsqueda precio de acuerdo a la institucion.

            if($row->descuento > 0){
				$descuento_set = ' '.$row->descuento.' %';
            }
            else{
            	if(!isset($row->descuento)){
                	$descuento_set = '0%';  //0% de descuento
                }
            } 

			if($row->descuento == 100){
				$colum_set = 'Ninguno';
				$monto_anticipo = $row->anticipo_efe;
			}
			else{

				if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->transferencia == '0.00'){
					$colum_set = 'Efectivo';
					$monto_anticipo = $row->anticipo_efe;} //es efectivo

				if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->pago_cheque=='0.00' && $row->anticipo_efe == '0.00'){
					$colum_set = 'Transferencia';
					$monto_anticipo = $row->transferencia;} //transferencia

				if($row->dep_banamex == '0.00' && $row->pago_santander=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
					$colum_set = 'Pago con cheque';
					$monto_anticipo = $row->pago_cheque;} //pago cheque

				if($row->dep_banamex == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
					$colum_set = 'Pago santander';
					$monto_anticipo = $row->pago_santander;} //pago santander

				if($row->pago_santander == '0.00' && $row->pago_cheque=='0.00' && $row->transferencia=='0.00' && $row->anticipo_efe == '0.00'){
					$colum_set = 'Pago banamex';
					$monto_anticipo = $row->dep_banamex;}

			}


            $precio         =number_format($row->precio,2);
			$debe           =number_format($row->debe,2);
			//$monto_anticipo =number_format($monto_anticipo,2);
			date_default_timezone_set('America/Mexico_City');
			$row->fecha = date('d-m-Y', strtotime($row->fecha));
			$a[$cont]= array(
						$row->fecha,
                        $row->nombre,
                        $row->estudio,
						'$'.$precio,
                        '$'.$debe,
                        $colum_set,
                        $monto_anticipo,
                        $row->factura,
                        $row->no_recibo,
                        $descuento_set);

                        $cont++;
		}

		$mysql->close();

		if(isset($a)){
			return $a;	
		}else{
			echo '	<script type="text/javascript"> 
						alert("No hay anticipos por lo tanto no imprime el reporte");
					</script>';
			//sleep(10);
			header("Location: controlador_ver_corte_caja_por_mes.php");
			
		}
		
	}
?>
<?php
	
?>