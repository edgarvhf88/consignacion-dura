<?php include("conexion.php"); 
				  
	  if (isset($_POST['id_user'])){
      $id_user = $_POST['id_user'];
		}
     	lista_partidas_pedido($id_user);

function lista_partidas_pedido($id_user){ 
global  $conex;
		
		$consulta = "SELECT pdet.id_microsip as articulo_id, pdet.clave_microsip as clave_microsip, pdet.articulo as articulo, pdet.cantidad as cantidad, pdet.precio_unitario as precio_unitario, pdet.precio_total as precio_total, pdet.unidad_medida as unidad_medida, pdet.id as id, p.id_pedido as id_pedido, p.requisitor as requisitor, p.almacen_id as almacen_id, p.id_pedido_cliente as id_pedido_cliente, a.id_microsip_nef as articulo_id_nef, pdet.id_articulo as id_articulo
					FROM pedido_nef p
					INNER JOIN pedido_nef_det pdet ON pdet.id_pedido = p.id_pedido
					INNER JOIN articulos a ON a.id = pdet.id_articulo
					WHERE p.id_usuario = '$id_user' and p.estatus = '0' ";			
		

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

	if ($total_rows > 0)
	{ // con resultados
		echo '
		<table id="tabla_pedet_nef" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-primary">
				
				<th>Clave</th>
				<th>Articulo</th>
				<th>Cant. Req.</th>
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
		$clase = "";
		$id_det = $row['id'];
		$id_pedido = $row['id_pedido'];
		$almacen_id = $row['almacen_id'];
		$requisitor = $row['requisitor'];
		$id_pedido_cliente = $row['id_pedido_cliente'];
		$id_articulo = $row['id_articulo'];
		$articulo_id = $row['articulo_id'];
		$articulo_id_nef = $row['articulo_id_nef'];
		if ($articulo_id_nef != ""){
			$existencia = ExistenciaMicrosipNef($articulo_id_nef,19); 
			$nombre_articulo_nef = "<h6>(".$row['articulo_id_nef'].")</h6>";
			$clase = "success";	
		}
		else
		{
			$clase = "danger";
			$existencia = "";
			$nombre_articulo_nef = "";
		}
		
		
		$clave_microsip = $row['clave_microsip'];
		$articulo = $row['articulo']." ".$nombre_articulo_nef;
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
			$recomienda_surtir = $existencia;
		}	
		$title_addartnef = 'Para seleccionar el Articulo equivalente de la Empresa Nef de un click sobre el Nombre del Articulo en la lista';
			
		echo '<tr class="" title="">
		
		<td class="elemen_pedidodet " id="tdclaveart_'.$id_det.'" >'.$clave_microsip.'</td>
		<td class="elemen_pedidodet '.$clase.'" id="tdarticulo_'.$id_det.'" title="'.$title_addartnef.'" onclick="asignar_articulonef('.$id_articulo.');">'.$articulo.'</td>
		<td class="elemen_pedidodet" id="tdcantidad_'.$id_det.'">'.$cantidad.'</td>
		<td class="elemen_pedidodet" id="tdexis_'.$id_det.'">'.$existencia.'</td>
		<td class="elemen_pedidodet" id="tdsurtir_'.$id_det.'"><input type="number" value="'.$recomienda_surtir.'" id="surtir_'.$id_det.'" class="form-control "></td>
		<td class="elemen_pedidodet" id="tdunidmedi_'.$id_det.'">'.$unidad_medida.'</td> 
		<td class="elemen_pedidodet" id="tdprecioun_'.$id_det.'">'.$precio_unitario.'</td>
		<td class="elemen_pedidodet" id="tdprecioto_'.$id_det.'">'.$precio_total.'</td>
		<td class="elemen_pedidodet" id="tdeliminar_'.$id_det.'"><input type="button" id="btn_delete_'.$id_det.'" class="btn btn-danger" onclick="borrar_partidaNef('.$id_det.');" value="X"/></td>
		
		</tr>';                    							
		      		
		                    							
		}	
		$permitir_agregar = '';
		if ($id_pedido_cliente == ""){
			$permitir_agregar = '$("#div_add_art_pedido").show();';
		}else{
			$permitir_agregar = '$("#div_add_art_pedido").hide();';
		}	
		
		echo ' </tbody></table>';
		echo '<script> 
			$(document).ready(function(){
				$("#tabla_pedet_nef").DataTable({});
				/*
						"order": [[ 1, "asc" ]]
					*/
					'.$permitir_agregar.'
				$("#txt_id_pedido_nef").val("'.$id_pedido.'");	
				$("#txt_requisitor_pedido").val("'.$requisitor.'");	
				//$("#select_almacen_pedido").val("'.$almacen_id.'");	
               
				
			
				});
			</script>';  
	}
	else
	{
		echo '<script> 
			$(document).ready(function(){
				$("#div_add_art_pedido").show();
				$("#txt_id_pedido_nef").val("");	
				$("#txt_requisitor_pedido").val("");		
				});
			</script>'; 
	}
}	
?>