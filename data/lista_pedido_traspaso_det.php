<?php include("conexion.php"); 
				  
	  if (isset($_POST['id_user'])){
      $id_user = $_POST['id_user'];
		}
     	lista_partidas_pedido($id_user);

function lista_partidas_pedido($id_user){ 
global  $conex;
		
		$consulta = "SELECT pdet.id_microsip as articulo_id, pdet.clave_microsip as clave_microsip, pdet.articulo as articulo, pdet.cantidad as cantidad, pdet.precio_unitario as precio_unitario, pdet.precio_total as precio_total, pdet.unidad_medida as unidad_medida, pdet.id as id, p.id_pedido as id_pedido, p.requisitor as requisitor, p.almacen_id as almacen_id, p.id_pedido_cliente as id_pedido_cliente
					FROM pedido_traspaso p
					INNER JOIN pedido_traspaso_det pdet ON pdet.id_pedido = p.id_pedido
					WHERE p.id_usuario = '$id_user' and p.estatus = '0' ";			
		

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

	if ($total_rows > 0)
	{ // con resultados
		echo '
		<table id="tabla_pedet" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-primary">
				
				<th>Clave</th>
				<th>Articulo</th>
				<th>Cantidad</th>
				<th>Exis.</th>
				<th>Surtir</th>
				<th>Unid. Med.</th>
				<th>Precio Unit.</th>
				<th>Precio total</th>
				<th><i class="fa fa-minus-square" aria-hidden="true"></i></th>
				
			</tr>
		</thead><tbody >';
		while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
		{	
		$id_det = $row['id'];
		$id_pedido = $row['id_pedido'];
		$almacen_id = $row['almacen_id'];
		$requisitor = $row['requisitor'];
		$id_pedido_cliente = $row['id_pedido_cliente'];
		$articulo_id = $row['articulo_id'];
		$existencia = ExistenciaMicrosip($articulo_id,19); 
		
		$clave_microsip = $row['clave_microsip'];
		$articulo = $row['articulo'];
		$cantidad = $row['cantidad'];
		$unidad_medida = $row['unidad_medida'];
		
		if ($row['precio_total'] > 0){ $precio_total = number_format($row['precio_total'],2);	}
		else
		{	$precio_total = "";	}
		if ($row['precio_unitario'] > 0){ $precio_unitario = number_format($row['precio_unitario'],2);	}
		else
		{	$precio_unitario = "";	}
	
		$recomienda_surtir = '';
		if ($existencia >= $cantidad){
			$recomienda_surtir = $cantidad;
		}else{
			$recomienda_surtir = number_format($existencia, 0);
		}	
			
		echo '<tr class="" title="">
		
		<td class="elemen_pedidodet" id="tdclaveart_'.$id_det.'">'.$clave_microsip.'</td>
		<td class="elemen_pedidodet" id="tdarticulo_'.$id_det.'">'.$articulo.'</td>
		<td class="elemen_pedidodet" id="tdcantidad_'.$id_det.'">'.$cantidad.'<input type="hidden" id="inputcant_'.$id_det.'" value="'.$cantidad.'" /></td>
		<td class="elemen_pedidodet" id="tdexis_'.$id_det.'">'.$existencia.' <input type="hidden" id="inputexis_'.$id_det.'" value="'.$existencia.'" /></td>
		<td class="elemen_pedidodet" id="tdsurtir_'.$id_det.'"><input type="number" value="'.$recomienda_surtir.'" id="surtir_'.$id_det.'" class="form-control " name="txt_cant_surtir[]"></td>
		<td class="elemen_pedidodet" id="tdunidmedi_'.$id_det.'">'.$unidad_medida.'</td> 
		<td class="elemen_pedidodet" id="tdprecioun_'.$id_det.'">'.$precio_unitario.'</td>
		<td class="elemen_pedidodet" id="tdprecioto_'.$id_det.'">'.$precio_total.'</td>
		<td class="elemen_pedidodet" id="tdeliminar_'.$id_det.'"><input type="button" id="btn_delete_'.$id_det.'" class="btn btn-danger" onclick="borrar_partida('.$id_det.');" value="X"/></td>
		
		</tr>';                    							
		     
		                		
		                    							
		}	
		$permitir_agregar = '';
		if ($id_pedido_cliente == ""){
			$permitir_agregar = '$("#div_add_art_traspaso").show(); $("#msj_pedido_relacionado").hide();';
		}else{
			$permitir_agregar = '$("#div_add_art_traspaso").hide(); $("#msj_pedido_relacionado").show();';
		}	
		
		echo ' </tbody></table>';
		echo '<script> 
			$(document).ready(function(){
				$("#tabla_pedet").DataTable({});
				/*
						"order": [[ 1, "asc" ]]
					*/
					'.$permitir_agregar.'
				$("#txt_id_pedido_traspaso").val("'.$id_pedido.'");	
				$("#txt_requisitor_traspaso").val("'.$requisitor.'");	
				$("#select_almacen_traspaso").val("'.$almacen_id.'");	
				
               
				
			
				});
			</script>';  
	}
	else
	{
		echo '<script> 
			$(document).ready(function(){
				$("#div_add_art_traspaso").show();
				$("#msj_pedido_relacionado").hide();
				$("#txt_id_pedido_traspaso").val("");	
				$("#txt_requisitor_traspaso").val("");		
				});
			</script>'; 
	}
}	
?>