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
		
		$this->CreateTextBox('FOLIOS DE FACTURAS CORRESPONDIENTE AL MES DE  '.$mes, -6,8, 80, 10, 11, 'B');
		$this->CreateTextBox('SÓLO DE PACIENTES PARTICULARES Y DR GOMEZ MAZA', -6,15, 80, 10, 11, 'B');
		
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
		$this->Textbox('Institucion', 				$x+135,	45, 20,  7, 1, 'C',0);
		$this->Textbox('Precio', 		    $x+155,	45, 20,  7, 1, 'C',1);
		$this->Textbox('¿Requiere Fact?', 		$x+175,	45, 20,  7, 1, 'C');
		$this->Textbox('¿Facturado?', 		$x+195,	45, 20,  7, 1, 'C');
		$this->Textbox('Folio Factura', 				$x+215,	45, 40,  7, 1, 'C');
		//$this->Textbox('Recibo', 				$x+225,	45, 10,  7, 1, 'C');
		//$this->Textbox('Desc.', 				$x+235,	45, 10,  7, 1, 'C');

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
				
				//$suma += $datos[$i][6];
				//$datos[$i][6] =number_format($datos[$i][6],2);

				$this->Textbox($datos[$i][0],		$x+0,	$y+45, 15,  7, 1, 'C');	//fecha	
				$this->Textbox($datos[$i][1], 		$x+15,	$y+45, 60,  7, 1, 'C'); //nombre
				$this->Textbox($datos[$i][2],   	$x+75,	$y+45, 60,  7, 1, 'C'); //estudio
				$this->Textbox($datos[$i][3], 		$x+135,	$y+45, 20,  7, 1, 'C'); //precio
				$this->Textbox($datos[$i][4], 		$x+155,	$y+45, 20,  7, 1, 'C'); //por pagar
				$this->Textbox($datos[$i][5], 		$x+175,	$y+45, 20,  7, 1, 'C');	//forma de pago
				$this->Textbox($datos[$i][6], 	$x+195,	$y+45, 20,  7, 1, 'C'); //anticipo
				$this->Textbox($datos[$i][7], 		$x+215,	$y+45, 40,  7, 1, 'C'); //factura
				//$this->Textbox($datos[$i][5].'', 	$x+225,	$y+45, 10,  7, 1, 'C'); //recibo
				//$this->Textbox($datos[$i][6], 		$x+235,	$y+45, 10,  7, 1, 'C'); //desc.

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
				$this->Textbox('Institucion', 				$x+135,	45, 20,  7, 1, 'C',0);
				$this->Textbox('Precio', 		    $x+155,	45, 20,  7, 1, 'C',1);
				$this->Textbox('¿Requiere Fact?', 		$x+175,	45, 20,  7, 1, 'C');
				$this->Textbox('¿Facturado?', 				$x+195,	45, 20,  7, 1, 'C');
				$this->Textbox('Folio Factura', 				$x+215,	45, 40,  7, 1, 'C');
				//$this->Textbox('Recibo', 				$x+225,	45, 10,  7, 1, 'C');
				//$this->Textbox('Desc.', 				$x+235,	45, 10,  7, 1, 'C');

				$filas_por_hoja = 18;
				$y = 7;
				$this->SetFont('helvetica', '', 8);
			}

			
		}

		//$suma =number_format($suma,2);
		//$this->Textbox('TOTAL: ', 	$x+175,	$y+45, 20,  7, 1, 'C');
		//$this->Textbox('$ '.$suma, 	$x+195,	$y+45, 20,  7, 1, 'C');
	
	}
}
	// create a PDF object
	//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
	// set document (meta) information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Numedics (Medicina Nuclear)');
	$pdf->SetTitle('Folios de facturas');
	$pdf->SetSubject('Folios de facturas de pacientes particulares');
 
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

		$sqltest = $mysql->query($link,"SELECT  distinct t1.idpacientes,
												t5.pacientes_idpacientes,
												t1.fecha,
												concat(t1.nombre,' ',t1.ap_paterno,' ',t1.ap_materno) as nombre,
												(Select concat(t2.tipo,' ',t2.nombre) as estudio 
													from estudio t2 
													where idgammagramas = t1.estudio_idgammagramas) as estudio,

												
												t1.institucion,
												
												t1.estatus,
												t1.indicaciones_tratamiento,
												
												(SELECT t6.requiere_factura
													FROM facturas t6
													WHERE t6.idpaciente =t1.idpacientes)as requiere_factura,
												
												(SELECT t6.facturado
													FROM facturas t6
													WHERE t6.idpaciente =t1.idpacientes)as facturado,
												
												(SELECT numero_facturas 
													FROM facturas 
													WHERE idpaciente= t1.idpacientes) as num_facturas

										FROM pacientes t1 inner join pacientes_has_anticipos t5
										WHERE (fecha >= '$fecha_ini' AND fecha <= '$fecha_fin') and  t5.pacientes_idpacientes= t1.idpacientes  ORDER BY fecha");
		$cont = 0;
                    
        while ($row = $mysql->f_obj($sqltest)) { 
        	//   busca el precio del estudio de acuerdo al tipo de 
			//   institución particular
				$link   = $mysql->connect();
                $sql    = $mysql->query($link,"SELECT 	institucion,
                										estudio_idgammagramas
                                                    FROM pacientes
                                                    WHERE idpacientes = $row->idpacientes ;");

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

            $precio         =number_format($row->precio,2);
			
			//$monto_anticipo =number_format($monto_anticipo,2);
			date_default_timezone_set('America/Mexico_City');
			$row->fecha = date('d-m-Y', strtotime($row->fecha));
			$a[$cont]= array(
						$row->fecha,
                        $row->nombre,
						$row->estudio,
						$row->institucion,
						'$'.$precio,
						$row->requiere_factura,
						$row->facturado,
                        $row->num_facturas);

                        $cont++;
		}

		$mysql->close();

		if(isset($a)){
			return $a;	
		}else{
			echo '	<script type="text/javascript"> 
						alert("No hay folios por lo tanto no imprime el reporte");
					</script>';
			//sleep(10);
			header("Location: controlador_ver_folios_facturas.php.php");
			
		}
		
	}
?>
<?php
	
?>