<?php 

	session_start();
	//print_r($_SESSION);
?>
<?php if(count($_SESSION['detalle'])>0){?>
	<table class="table">
	    <thead>
			<tr>
				<th>Cantidad</th>
				<th>U. M.</th> 
				<th>Descripción</th>                                                        
				<th>Actividad</th>
			</tr>
	    </thead>
	    <tbody>
	    	<?php 
	    	foreach($_SESSION['detalle'] as $k => $detalle){ 
	    	?>
	        <tr>
	        	<td><?php echo $detalle['cantidad'];?></td>
	        	<td><?php echo $detalle['unidad_medida']; ?></td>
				<td><?php echo $detalle['producto']; ?></td>
				<td><?php echo $detalle['actividad'].'mCi'; ?></td>

	            <td><button type="button" class="btn btn-sm btn-danger eliminar-producto" id="<?php echo $detalle['id'];?>">Eliminar</button></td>
	        </tr>
	        <?php }?>
	    </tbody>
	</table>


<?php /*echo '<pre>';print_r($_SESSION); echo '</pre>'; echo '<pre>';print_r($_POST); echo '</pre>';*/  }else{?>
<div class="panel-body"> No hay productos agregados</div>
<?php }?>

<script type="text/javascript" src="libs/ajax_pedidos_productos.js"></script>