$(function(){
	var ENV_WEBROOT = "../";
	
	$(".btn-agregar-producto").off("click");
	$(".btn-agregar-producto").on("click", function(e) {
		var cantidad = $("#txt_cantidad").val();
		var unidad_medida = $("#id_unidad_medida").val();
		var producto = $("#id_producto").val();
		var actividad = $("#txt_actividad").val();
		
		if(cantidad>0){ // valida que no menor o igual que 0
			
			$.ajax({
				url: 'pedidos_ac_productos.php?page=1',
				type: 'post',
				data: {'cantidad':cantidad, 'id_producto':producto, 'id_unidad_medida':unidad_medida, 'actividad':actividad},
				dataType: 'json'
			}).done(function(data){
				if(data.success==true){
					$("#txt_cantidad").val(' ');
					$("#txt_actividad").val(' ');
					$("#id_unidad_medida").val('0');
					$("#id_producto").val('0');
					alertify.success(data.msj);
					$(".detalle-producto").load('pedidos_detalle_a_productos.php');
				}else{
					alertify.error(data.msj);
				}
			})
			
		}else{
			alertify.error("¡Sólo cantidades positivas!");
		}
	});
	
	$(".eliminar-producto").off("click");
	$(".eliminar-producto").on("click", function(e) {
		var id = $(this).attr("id");
		$.ajax({
			url: 'pedidos_ac_productos.php?page=2',
			type: 'post',
			data: {'id':id},
			dataType: 'json'
		}).done(function(data){
			if(data.success==true){
				alertify.error(data.msj);
				$(".detalle-producto").load('pedidos_detalle_a_productos.php');
			}else{
				alertify.error(data.msj);
			}
		})
	});

	$(".btn-insertar-producto").off("click");
	$(".btn-insertar-producto").on("click", function(e) {
		
		$.ajax({
			url: 'pedidos_ac_productos.php?page=3',
			type: 'post',
			data:{},
			dataType: 'json'
		}).done(function(data){
			if(data.success==true){
				alertify.success(data.msj);
				$(".btn-retroceso").prop( "disabled", true );
				$(".btn-retroceso").hide();
				$(".btn-insertar-producto").hide();
				$(".btn-finalizar").show();
				
			
				//$(".detalle-producto").load('pedidos_detalle_a_productos.php');
			}else{
				alertify.error(data.msj);
			}
		})
	});

	
});