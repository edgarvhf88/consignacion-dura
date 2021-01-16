<?php include("conexion.php");

if (isset($_POST['tipo_periodo']))
{	
	$tipo_usuario = validar_usuario($_SESSION["logged_user"]);
	
	$id_empresa = id_empresa($_SESSION["logged_user"]);
	$tipo_periodo = $_POST['tipo_periodo'];
	$tipo_reporte = $_POST['tipo_reporte'];
	$valor_periodo = $_POST['valor_periodo'];
	$valor_periodo2 = $_POST['valor_periodo2'];
	$valor_evaluado = $_POST['valor_evaluado'];
	$almacen_id = $_POST['almacen_id'];
	$where_periodo = "";
	$fecha_filtro = "";
	date_default_timezone_set('America/Mexico_City');
	$fecha_actual = date("Y-m-d H:i:s");

	/// TIPO PERIODO PARA FILTRADO POR FECHAS
	if ($tipo_periodo == 1)
	{ // si es por horas entonces se restara la cantidad del valor mandado en valor periodo
		$fecha_modif = strtotime ( '-'.$valor_periodo.' hour' , strtotime ($fecha_actual)) ;
		$fecha_modif = date('Y-m-d H:i:s', $fecha_modif );
		$fecha_filtro = $fecha_modif;
		$where_periodo = "AND p.fecha_pedido_oficial >= '$fecha_filtro'";	
	}
	else if($tipo_periodo == 2)
	{ // dias
		$fecha_modif = strtotime ( '-'.$valor_periodo.' day' , strtotime ($fecha_actual)) ;
		$fecha_modif = date('Y-m-d H:i:s', $fecha_modif );
		$fecha_filtro = $fecha_modif;
		$where_periodo = "AND p.fecha_pedido_oficial >= '$fecha_filtro'";
		//echo $fecha_modif;
	}
	else if($tipo_periodo == 3)
	{ // meses 
		$fecha_modif = strtotime ( '-'.$valor_periodo.' month' , strtotime ($fecha_actual)) ;
		$fecha_modif = date('Y-m-d H:i:s', $fecha_modif );
		$fecha_filtro = $fecha_modif;
		$where_periodo = "AND p.fecha_pedido_oficial >= '$fecha_filtro'";
		//echo $fecha_modif;
		
	}
	else if($tipo_periodo == 4)
	{
		$fecha_ini = date('Y-m-d H:i:s',  strtotime ($valor_periodo ));
		$fecha_fin = date('Y-m-d H:i:s',  strtotime ($valor_periodo2));
		$where_periodo = "AND p.fecha_pedido_oficial BETWEEN '$fecha_ini' AND '$fecha_fin'";
		//echo "Entre ".$fecha_ini." y ".$fecha_fin;
	}
	////// TERMINA VALIDACION DE PERIODO Y OBTENEMOS LA VARIABLE DE FILTRO DE FECHAS
	$tabla="";
	if ($tipo_reporte == 1)
	{ // reporte de material requerido
				
		$sql = "SELECT pdet.clave_empresa as clave_empresa, pdet.articulo as articulo, sum(pdet.cantidad) as cant_total, sum(pdet.precio_total) as total  
				FROM pedidos_det pdet 
				INNER JOIN pedidos p on p.id = pdet.id_pedido
				WHERE p.id_sucursal = '$almacen_id'
				".$where_periodo."
				GROUP BY pdet.id_articulo
				ORDER BY pdet.articulo ASC";
		
		$res= mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($res);
		$totalrows = mysql_num_rows($res);
		if ($totalrows > 0){
			if ($tipo_usuario == 4){
				$coltottd = '';
			}else
			{
				$coltottd = '<th>Total</th>';
			}
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>#Part</th>
                    			<th>Item</th>
                    			<th>Qty.</th>
                    			'.$coltottd.'
                    		</tr>
                    	</thead><tbody>';
						$total = 0;
			while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
			{
				if ($tipo_usuario == 4){
					$coltottd2 = '';
					$mostrar_total_final = '';
				}else
				{
					$coltottd2 = '<td align="right">$'.number_format($row['total'],2).'</td>';
					$mostrar_total_final = '<div class="col-lg-12 " style="font-size:18px;">Total = $ '.number_format($total, 2).'</div>';
				}
			$tabla .= '<tr>
							<td>'.$row['clave_empresa'].'</td>
							<td>'.$row['articulo'].'</td>
							<td>'.$row['cant_total'].'</td>
							'.$coltottd2.'
						</tr>';	
			$total += $row['total'];			
			}
			$tabla .= ' </tbody></table>
			'.$mostrar_total_final.'
			<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[1,"asc"]]});});</script>';
		}
	} 
	else if($tipo_reporte == 2)
	{ // reporte de inventario
		$sql = "SELECT e.existencia_actual as existencia_sistema, 
				a.nombre as articulo, 
				a.clave_empresa as clave_empresa,
				a.precio as precio,
					(SELECT iidet.cantidad_contada
						FROM inventarios_det iidet
							INNER JOIN inventarios ii ON (ii.id_inventario=iidet.id_inventario)
						WHERE iidet.id_articulo = e.id_articulo 
							and ii.almacen_id = '$almacen_id' and ii.estatus = 'C'
						ORDER BY ii.fecha_hora_cierre DESC LIMIT 1) as existencia_fisica
				FROM existencias e 
				INNER JOIN articulos a on a.id = e.id_articulo
				WHERE e.almacen_id = '$almacen_id'
				";
		
		$res= mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($res);
		$totalrows = mysql_num_rows($res);
		if ($totalrows > 0){
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>#Part</th>
                    			<th>Item</th>
                    			<th>Qty. Consignment</th>
                    			<th>Qty. Inventory</th>
                    			<th>Qty. Consumed</th>
                    		</tr>
                    	</thead><tbody>';
						//$total = 0;
						$consumo = 0;
			while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
			{
				$consumo = $row['existencia_sistema'] - $row['existencia_fisica'];
			$tabla .= '<tr>
							<td>'.$row['clave_empresa'].'</td>
							<td>'.$row['articulo'].'</td>
							<td>'.$row['existencia_sistema'].'</td>
							<td>'.$row['existencia_fisica'].'</td>
							<td>'.$consumo.'</td>
						</tr>';	
			//$total += $row['total'];			
			}
			$tabla .= ' </tbody></table>
			
			<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[1,"asc"]]});});</script>';
		}	
	}
	else if($tipo_reporte == 3)
	{ // reporte consumo
		// ya no por que en el 2 de inventario se muestra el consumo tambien.
		
	}
	else if($tipo_reporte == 4)
	{ // reporte material con falta de orden de compra
		// este ya no se ejecutara por que el 5 punto de reorden, ayuda a lo mismo mostrando el material que esta de bajo stock.
				
	}
	else if($tipo_reporte == 5)
	{ // reporte material en orden de meyor porcentaje de diferencia entre punto maximo y existencia fisica
		$sql = "SELECT e.existencia_actual as existencia_sistema, 
					e.max as max, 
					e.min as min, 
					e.reorden as reorden, 
					a.nombre as articulo, 
					a.clave_empresa as clave_empresa,
					a.precio as precio,
					(SELECT iidet.cantidad_contada
						FROM inventarios_det iidet
							INNER JOIN inventarios ii ON (ii.id_inventario=iidet.id_inventario)
						WHERE iidet.id_articulo = e.id_articulo 
							and ii.almacen_id = '$almacen_id' and ii.estatus = 'C'
						ORDER BY ii.fecha_hora_cierre DESC LIMIT 1) as existencia_fisica
				FROM existencias e 
				INNER JOIN articulos a on a.id = e.id_articulo
				WHERE e.almacen_id = '$almacen_id'
				";
		
		$res= mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($res);
		$totalrows = mysql_num_rows($res);
		if ($totalrows > 0){
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>#Part</th>
                    			<th>Item</th>
                    			<th>Min</th>
                    			<th>Reorder</th>
                    			<th>Max</th>
                    			<th>Stock</th>
                    			<th>Status</th>
                    		</tr>
                    	</thead><tbody>';
						//$total = 0;
						$consumo = 0;
						$estatus = 0;
			while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
			{	
				
				$consumo = $row['existencia_sistema'] - $row['existencia_fisica'];
				if ($row['max'] == ""){
					$estatus = "-";
				}else if ($row['max'] == 0){
					$estatus = "-";
				}else if ($row['max'] > 0){
					$estatus = $row['existencia_fisica'] / $row['max'];
					$estatus = number_format($estatus,2);
					if ($estatus >= 1){
						$estatus = str_replace(".","",$estatus);
						$estatus = $estatus."%";
					}else if ($estatus < 1){
						$estatus = str_replace("0.","",$estatus);
						$estatus = $estatus."%";
					}
					
					// valid
				}
				
				
			$tabla .= '<tr>
							<td>'.$row['clave_empresa'].'</td>
							<td>'.$row['articulo'].'</td>
							<td>'.$row['min'].'</td>
							<td>'.$row['reorden'].'</td>
							<td>'.$row['max'].'</td>
							<td>'.$row['existencia_fisica'].'</td>
							<td>'.$estatus.'</td>
						</tr>';	
			//$total += $row['total'];			
			}
			$tabla .= ' </tbody></table>
			
			<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[6,"asc"]]});});</script>';
		}
		
	}
	
	if ($totalrows <= 0){
		echo "Not data found";
	}else{
		echo $tabla;
	}
	
	

}





?>