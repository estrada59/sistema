<?php
session_start();
if(isset($_GET["page"])){
	$page=$_GET["page"];
}else{
	$page=0;
}

switch($page){
	//se inserta un producto en el carroto de pedidos
	case 1:
		
		if (isset($_POST['id_producto']) && $_POST['id_producto']!='0' && isset($_POST['cantidad']) && $_POST['cantidad']!=' ' && isset($_POST['id_unidad_medida']) && $_POST['id_unidad_medida']!='0') {
			try {
				$cantidad = $_POST['cantidad'];
				$producto = $_POST['id_producto'];
				$unidad_medida = $_POST['id_unidad_medida'];
				if($_POST['actividad']!=''){
					$actividad = $_POST['actividad'];
				}else{
					$actividad = '0';
				}
				

				if(count($_SESSION['detalle'])>0){
					$ultimo = end($_SESSION['detalle']);
					$count = $ultimo['id']+1;
				}else{
					$count = count($_SESSION['detalle'])+1;
				}
				
				include_once "include/mysql.php";

				$mysql = new mysql();
				$link = $mysql->connect();

				$sqltest = $mysql->query($link,"SELECT 
													t1.descripcion as nombre_producto,
													(SELECT t2.descripcion_abreviada
													FROM tblc_unidad_medida t2
													WHERE id_unidad_medida= $unidad_medida) as unidad_medida
												FROM tblc_productos t1
                                                WHERE   t1.id_productos = $producto
                                                        AND t1.activo = 1;");

                $row = $mysql->f_obj($sqltest);

				$_SESSION['detalle'][$count] = array('id'=>$count,
													 'cantidad'=>$cantidad,
													 'id_producto'=>$producto,
													 'producto'=>$row->nombre_producto,
													 'id_unidad_medida'=> $unidad_medida,
													 'unidad_medida'=>$row->unidad_medida,
													 'actividad'=>$actividad);

				

				//si todo el proceso sale bien se devuelven esos datos
				$json = array();
				$json['success'] = true;
				$json['msj'] = 'Producto Agregado';

				echo json_encode($json);
	
			} catch (PDOException $e) {
				$json['msj'] = $e->getMessage();
				$json['success'] = false;
				echo json_encode($json);
			}
		}else{
			$json['msj'] = 'Ingrese unidad de medida y/o producto y/o ingrese cantidad';
			$json['success'] = false;
			echo json_encode($json);
		}
		break;
    // se elimina un producto del carrito de pedidos
	case 2:
		$json = array();
		$json['msj'] = 'Producto Eliminado';
		$json['success'] = true;
	
		if (isset($_POST['id'])) {
			try {
				unset($_SESSION['detalle'][$_POST['id']]);
				$json['success'] = true;
	
				echo json_encode($json);
	
			} catch (PDOException $e) {
				$json['msj'] = $e->getMessage();
				$json['success'] = false;
				echo json_encode($json);
			}
		}
		break;
	// se inserta a la bd todo el carrito de pedidos
	case 3:
		$json = array();
		$json['msj'] = 'Producto insertado con exito';
		$json['success'] = true;
	
		
			try {
				
				include_once "include/mysql.php";

				$id_usuario = $_SESSION['id'];
				

				$mysql = new mysql();
				$link = $mysql->connect();

				$sqltest = $mysql->query($link,"INSERT INTO tbl_pedidos (id_status_pedido, idusuario)
												VALUES (1,$id_usuario);");
				
				$id_pedidos=mysqli_insert_id($link);

					
				foreach($_SESSION['detalle'] as $k => $detalle){ 
					$cant = $detalle['cantidad'];
					$um = $detalle['id_unidad_medida'];
					$prod =$detalle['id_producto'];
					$act = $detalle['actividad'];
					
					$sqltest = $mysql->query($link,"INSERT INTO tbl_detalle_pedidos (
																					    
																						id_unidad_medida,
																						id_productos,
																						id_pedidos,
																						cantidad,
																						actividad)
													VALUES (
															
															$um,
															$prod,
															$id_pedidos,															
															$cant,
															$act);");
					//obtener el ultimo id insertado
					$id_detalle_pedido = mysqli_insert_id($link);
					$estatus_solicitud = 1;  //pendiente
					$id_empresa = 1;
					
					//se inserta la solicitd y que esta pendiente por facturar
					$cad="INSERT INTO tbl_solicitud_pedidos (id_empresas,id_detalle_pedidos,id_status_solicitudes,fecha_solicitud)
						  VALUES($id_empresa,$id_detalle_pedido,$estatus_solicitud,'0000-00-00 00:00:00');";
						  
					$sqltest2 = $mysql->query($link, $cad);

					$id_solicitud_pedidos = mysqli_insert_id($link);
					$no_facturas = '';

					$cad="INSERT INTO tbl_facturas (id_solicitud_pedidos,no_factura)
						  VALUES($id_solicitud_pedidos,'$no_facturas');";
						  
					$sqltest2 = $mysql->query($link, $cad);
				}

				
				
				$json['success'] = true;
	
				echo json_encode($json);
	
			} catch (PDOException $e) {
				$json['msj'] = $e->getMessage();
				$json['success'] = false;
				echo json_encode($json);
			}
		
		break;

}
?>