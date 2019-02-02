<?php
// Include the main TCPDF library (search for installation path).
require_once('include/tcpdf/tcpdf.php');


//comprueba que halla datos
	$_POST["pagina"]="imrpimir"; 

	/*if(!isset($_POST['fecha'])){
		header("Location: index.php");
	}*/
	

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
		$this->SetFont('helvetica', '', 12);
		

		include_once 'include/funciones_consultas.php';
		include_once "mysql.php";

		$fecha =  $_POST['fecha'];
		
		$fecha_fin = ultimo_dia_semestre($fecha);
		$fecha_ini = primer_dia_semestre($fecha); 

		$fecha_ini = date("d-m-Y",strtotime($fecha_ini));
		$fecha_fin = date("d-m-Y",strtotime($fecha_fin));                                    
		
		$texto= 'Lista de estudios del semestre:'.$fecha_ini.' hasta '.$fecha_fin;
		
		$this->Textbox($texto, $x+8,30, 137, 10, 0, 'C',12,'');

		//INICIO Preparando datos para consulta ////
		
		$fecha_ini = date("Y-m-d",strtotime($fecha_ini));
		$fecha_fin = date("Y-m-d",strtotime($fecha_fin));  
		$estatus="ATENDIDO";

		//FIN Preparando datos para consulta ////
		$estadistica = new estadistica();
		$total	=	$estadistica->total_de_estudios($fecha_ini, $fecha_fin,$estatus);

		/*echo '<pre>';
		print_r($total);
		echo '</pre>';*/
		$num = count($total);
		//echo $num;
		
		$this->SetFont('helvetica', '', 9);
		$this->SetXY(20,40);

	
		
		$html ="<style>
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		th, td {
		  padding: 10px;
		}
		</style>";
		$this->writeHTML($html, true, false, true, false, '');

		$html = '
		<table  border="1" style="width:100%">
			<thead>
				<tr>
					<th  >Nombre de estudio</th>
					<th >Cantidad</th>
			  	</tr>
			</thead>
			<tbody>';

				$total_de_estudios=0;
				$tam =0;


				for($i=0; $i < $num; $i++){
					
						$html = $html.'<tr>';
						$html=        $html.'<td >'.$total[$i]['estudio'].'</td>';
						$html=        $html.'<td align="center">'.$total[$i]['cantidad'].'</td>';
						$html= $html.'</tr>';

						$total_de_estudios +=$total[$i]['cantidad'];
						$tam++;
					
					
				}

					$html= $html.'<tr>';
					$html=       $html.'<td> Total de estudios atendidos (sumatoria): </td>';
					$html=       $html.'<td> '.$total_de_estudios.' </td>';
					$html= $html.'</tr>';
					

		$html= $html.'</tbody>
		</table>';
	
		$this->writeHTML($html, true, false, true, false, '');
		


		/*$this->Ln();
		$this->CreateTextBox($texto, 20,33, 80, 10, 13, 'B');		
		$this->Ln();
		$this->Ln();*/
		

		

		/*$x=-2;
		$this->Textbox('No.', $x+0,45, 8, 10, 1, 'C',12,'');		
		$this->Textbox('Estudio.', $x+8,45, 137, 10, 1, 'C',12,'');
		$this->Textbox('Precio', $x+145,45, 30, 10, 1, 'C',12,'');	*/

		
		/*$var = new lista_precios();
		$lista = $var->lista();
		$cont  = count($lista);	
		$tam = round($cont/20);
		
		if($tam == 0){  // update 2015_07_02 todo el IF
			$tam = 1;
		}
		
		$y=55;
		$j=0;
		$tmp=0;
		$id=1;	// update 2015_07_02
		for($i = 0; $i < $tam; $i++)
		{
			$this->Textbox('No.', $x+0,45, 8, 10, 1, 'C',12,'');		
			$this->Textbox('Estudio.', $x+8,45, 137, 10, 1, 'C',12,'');
			$this->Textbox('Precio', $x+145,45, 30, 10, 1, 'C',12,'');

			if($cont>20){
				$var=20;

				for($j; $j < $var; $j++){
				//$id = $lista[$tmp]['id'];    // update 2015_07_02
				$estudio = $lista[$tmp]['estudio'];
				$precio = $lista[$tmp]['$institucion'];
			
				$this->Textbox($id, $x,$y, 8, 10, 1, 'C',10,'');		
				$this->Textbox($estudio,  $x+8,$y, 137, 10, 1, 'L',10,'');
				$this->Textbox($precio, $x+145,$y, 30, 10, 1, 'J',10,'');	
				$y+=10;
				$tmp++;
				$id++;// update 2015_07_02
				
			}
			$j=0;
			$y=55;
			$cont-=20;
			$this->AddPage();
			
			}
			if($cont<20 && $cont>0){
				
				for($j; $j < $cont; $j++){
				//$id = $lista[$tmp]['id'];
				$estudio = $lista[$tmp]['estudio'];
				$precio = $lista[$tmp]['$institucion'];
			
				$this->Textbox($id, $x,$y, 8, 10, 1, 'C',10,'');		
				$this->Textbox($estudio,  $x+8,$y, 137, 10, 1, 'L',10,'');
				$this->Textbox($precio, $x+145,$y, 30, 10, 1, 'J',10,'');	
				$y+=10;
				$tmp++;
				$id++;// update 2015_07_02
				
				}
			}
		}	*/
	}
}

	// create a PDF object
	//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf = new MYPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
	// set document (meta) information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Numedics (Medicina Nuclear)');
	$pdf->SetTitle('Reporte semestral');
	$pdf->SetSubject('Cantidad de pacientes atendidos por semestre');
 
	// add a page
	$pdf->AddPage();


	$pdf->imprimir_lista();
	//Close and output PDF document
	$pdf->Output('Lista_de_precios_'.$_POST['fecha'].'.pdf', 'I');
?>
<?php

class estadistica  {
	function total_de_estudios($fecha_ini, $fecha_fin, $estatus){
		
		include_once "include/mysql.php";
		//echo $fecha_ini.' '.$fecha_fin.' '.$estatus;
		$mysql = new mysql();
		$link = $mysql->connect();
		
		$instituciones = array();

   
		$sqltest = $mysql->query($link,"SELECT 
												CONCAT(t2.tipo,' ', t2.nombre) as nombre_estudio,
												count(*) as cant_estudios
		
											FROM pacientes t1 INNER JOIN estudio t2

											WHERE (t1.fecha >= '$fecha_ini' AND  t1.fecha <= '$fecha_fin') 
												/*and t1.institucion=   PARA OBTENER POR INSTITUCION*/   
												and t1.estatus = '$estatus'
												AND (t1.estudio_idgammagramas = t2.idgammagramas)
											group BY 
												t2.idgammagramas;");
    
		$num_rows = $mysql->f_num($sqltest);
		//echo $num_rows;
		$cont =0 ;

		while ($row = $mysql->f_obj($sqltest)) {
			$instituciones[$cont]["estudio"]    =   $row->nombre_estudio;
			$instituciones[$cont]["cantidad"]       =   $row->cant_estudios;
			$cont++;
		}
		
		$mysql->close();
		
		return $instituciones; 
	}
}
class lista_precios{

	function lista(){
		$institucion = $_POST['institucion'];
		include_once "include/mysql.php";				

		$mysql =new mysql();
		$link = $mysql->connect(); 

        $sql = $mysql->query($link," SELECT idgammagramas,
									concat(tipo, ' ',nombre) as estudio,
        							$institucion
 									FROM estudio where $institucion != 0.00
 									");

        $num_row =$mysql->f_num($sql);

        $cont = 0;
        $lista = array();
        while ($row = $mysql->f_obj($sql)) {
        	
        	$row->precio=number_format($row->$institucion,2);

        	$lista[$cont] = array(	'id' 		=> $row->idgammagramas, 
        							'estudio' 	=> $row->estudio,
        							'$institucion'	=> '$ '.$row->precio);
        	$cont +=1;
        }

        return $lista;
		//echo ' filas afectadas: (Se insertó): '.$mysql->affect_row().' registro completo';
		$mysql->close();
	}

}
?>

