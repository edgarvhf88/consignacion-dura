<?php
function MostrarTabla($tipo_aplicado,$can_cols,$datos){
	$tabla = 'sin coincidencias';
	if($tipo_aplicado == 1)// articulos  -------------------------------------------------------------------
	{
		if($can_cols == 5){ // totales 5 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Clave</th>
                    			<th>Articulo</th>
                    			<th>Cantidad</th>
                    			<th>Total</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['clave'].'</td>
							<td>'.$lista['articulo'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
						</tr>';	
			}
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[1,"desc"]]});});</script>';
		}
		else if($can_cols == 16){ // detalles 16 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Clave</th>
                    			<th>Articulo</th>
                    			<th>Fecha pedido</th>
                    			<th>Folio</th>
                    			<th>Cant. Total</th>
                    			<th>Precio Unitario</th>
                    			<th>Precio Total</th>
                    			<th>Comprador</th>
                    			<th>Recolector</th>
                    			<th>Fecha surtido</th>
                    			<th>Centro Costos</th>
                    			<th>Departamento</th>
                    			<th>Autorizo</th>
                    			<th>Fecha Autorizado</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['clave'].'</td>
							<td>'.$lista['articulo'].'</td>
							<td>'.$lista['fecha_pedido'].'</td>
							<td>'.$lista['folio'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_unitario'],2).'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
							<td>'.$lista['comprador'].'</td>
							<td>'.$lista['recolector'].'</td>
							<td>'.$lista['fecha_surtido'].'</td>
							<td>'.$lista['nombre_cc'].'</td>
							<td>'.$lista['departamento'].'</td>
							<td>'.$lista['autorizo'].'</td>
							<td>'.$lista['fecha_autorizado'].'</td>
						</tr>';	
			}
			 
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[3,"desc"]]});});</script>';
		}
	}
	else if($tipo_aplicado == 2)// centros de costos---------------------------------------------------------------------
	{
		if($can_cols == 5){ // totales 5 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Centro de Costos</th>
                    			<th>Cant. Pedidos</th>
                    			<th>Total</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['centro_costo'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
						</tr>';	
			}
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[1,"desc"]]});});</script>';
		}
		else if($can_cols == 16){ // detalles 16 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Centro Costos</th>
                    			<th>Fecha pedido</th>
                    			<th>Folio</th>
                    			<th>Articulo</th>
                    			<th>Cant. Total</th>
                    			<th>Precio Unitario</th>
                    			<th>Precio Total</th>
                    			<th>Comprador</th>
                    			<th>Recolector</th>
                    			<th>Fecha surtido</th>
                    			<th>Departamento</th>
                    			<th>Autorizo</th>
                    			<th>Fecha Autorizado</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['nombre_cc'].'</td>
							<td>'.$lista['fecha_pedido'].'</td>
							<td>'.$lista['folio'].'</td>
							<td>'.$lista['articulo'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_unitario'],2).'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
							<td>'.$lista['comprador'].'</td>
							<td>'.$lista['recolector'].'</td>
							<td>'.$lista['fecha_surtido'].'</td>
							<td>'.$lista['departamento'].'</td>
							<td>'.$lista['autorizo'].'</td>
							<td>'.$lista['fecha_autorizado'].'</td>
						</tr>';	
			}
			 
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[2,"desc"]]});});</script>';
		}
	}
	else if($tipo_aplicado == 3)// departamentos    --------------------------------------------------------------------
	{
		if($can_cols == 5){ // totales 5 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Departamento</th>
                    			<th>Cant. Pedidos</th>
                    			<th>Total</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['departamento'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
						</tr>';	
			}
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[1,"desc"]]});});</script>';
		}
		else if($can_cols == 16){ // detalles 16 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Departamento</th>
                    			<th>Fecha pedido</th>
                    			<th>Folio</th>
                    			<th>Centro Costos</th>
                    			<th>Articulo</th>
                    			<th>Cant. Total</th>
                    			<th>Precio Unitario</th>
                    			<th>Precio Total</th>
                    			<th>Comprador</th>
                    			<th>Recolector</th>
                    			<th>Fecha surtido</th>
                    			<th>Autorizo</th>
                    			<th>Fecha Autorizado</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['departamento'].'</td>
							<td>'.$lista['fecha_pedido'].'</td>
							<td>'.$lista['folio'].'</td>
							<td>'.$lista['nombre_cc'].'</td>
							<td>'.$lista['articulo'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_unitario'],2).'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
							<td>'.$lista['comprador'].'</td>
							<td>'.$lista['recolector'].'</td>
							<td>'.$lista['fecha_surtido'].'</td>
							<td>'.$lista['autorizo'].'</td>
							<td>'.$lista['fecha_autorizado'].'</td>
						</tr>';	
			}
			 
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[2,"desc"]]});});</script>';
		}
	}
	else if($tipo_aplicado == 4)// usuarios    --------------------------------------------------------------------
	{
		if($can_cols == 5){ // totales 5 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Comprador</th>
                    			<th>Cant. Pedidos</th>
                    			<th>Total</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['comprador'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
						</tr>';	
			}
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[1,"desc"]]});});</script>';
		}
		else if($can_cols == 16){ // detalles 16 campos en la lista datos *********************************************
			$tabla = '<table id="tabla_datos" class="table table-striped table-bordered table-hover responsive display">
                    	<thead>
                    		<tr class="info">
                    			<th>Comprador</th>
                    			<th>Fecha pedido</th>
                    			<th>Folio</th>
                    			<th>Departamento</th>
                    			<th>Centro Costos</th>
                    			<th>Articulo</th>
                    			<th>Cant. Total</th>
                    			<th>Precio Unitario</th>
                    			<th>Precio Total</th>
                    			<th>Recolector</th>
                    			<th>Fecha surtido</th>
                    			<th>Autorizo</th>
                    			<th>Fecha Autorizado</th>
                    		</tr>
                    	</thead><tbody>';
			foreach ($datos as $lista){ 
			$tabla .= '<tr>
							<td>'.$lista['comprador'].'</td>
							<td>'.$lista['fecha_pedido'].'</td>
							<td>'.$lista['folio'].'</td>
							<td>'.$lista['departamento'].'</td>
							<td>'.$lista['nombre_cc'].'</td>
							<td>'.$lista['articulo'].'</td>
							<td>'.$lista['cantidad'].'</td>
							<td align="right">$'.number_format($lista['precio_unitario'],2).'</td>
							<td align="right">$'.number_format($lista['precio_total'],2).'</td>
							<td>'.$lista['recolector'].'</td>
							<td>'.$lista['fecha_surtido'].'</td>
							<td>'.$lista['autorizo'].'</td>
							<td>'.$lista['fecha_autorizado'].'</td>
						</tr>';	
			}
			 
			$tabla .= ' </tbody></table>
		<script> $(document).ready(function(){$("#tabla_datos").DataTable({"order":[[2,"desc"]]});});</script>';
		}
	}

	return $tabla;
}


	////// APLICADO A: ARTICULO, CC, DEP, USUARIO
	if ($aplicado_a == 1)
	{ // ARTICULOS//////////////////////////////////////////////////
		
		if ($detalle_totales == 1)// DETALLES 
		{ 
			if ($uno_todos == 1)// TODOS
			{	
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, u.nombre as nombre_c, u.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_departamento as id_departamento, p.con_autorizacion as autorizado, p.id as id_pedido, p.id_cc as id_cc
								FROM articulos a 
								LEFT JOIN pedidos_det pd on pd.id_articulo = a.id
								LEFT JOIN pedidos p on p.id = pd.id_pedido
								INNER JOIN usuarios u on u.id = p.id_usuario
																
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo; 
			}
			else if ($uno_todos == 2)// UNO
			{ 
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, u.nombre as nombre_c, u.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_departamento as id_departamento, p.con_autorizacion as autorizado, p.id as id_pedido, p.id_cc as id_cc
								FROM articulos a 
								LEFT JOIN pedidos_det pd on pd.id_articulo = a.id
								LEFT JOIN pedidos p on p.id = pd.id_pedido
								INNER JOIN usuarios u on u.id = p.id_usuario
																
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
								AND pd.id_articulo = '$valor_evaluado'";
			}
			
				// reporte de todos los articulos con detalles
				
				$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
				//$row = mysql_fetch_assoc($resultado);
				$total_rows = mysql_num_rows($resultado);
				if ($total_rows > 0){
					$valor_autorizaciones = '';
					//echo $total_rows;
					while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                    {
						$recolector = '';
						if ($row['id_recolector'] == 0){ // si fue personalmente
							$recolector = 'Personalmente';
						}
						else
						{
							$recolector = Nombre($row['id_recolector']);
						}
						$centro_costos = '';
						if ($row['id_cc'] == 0){ // sin centro de costos
							$centro_costos = '-';
						}
						else
						{
							$centro_costos = CC_NOMBRE($row['id_cc']);
						}
						$departamento = '';
						if ($row['id_departamento'] == 0){ // sin departamento
							$departamento = '-';
						}
						else
						{
							$departamento = DEPARTAMENTO_NOMBRE($row['id_departamento']);
						}
						$valor_autorizaciones = "";
						$autorizo = "";
						$fecha_autorizo = "";
						if ($row['autorizado'] == 1){
						$valor_autorizaciones =  verific_autorizacion($row['id_pedido']);
						$arr_autoriz = explode("_",$valor_autorizaciones);
						$autorizo = $arr_autoriz[0];
						$fecha_autorizo = $arr_autoriz[1];
						//echo '</br>'.$fecha_autorizo.' -> '.$row['articulo'].' Autorizo -> '.$autorizo;
						}
						$lista_resultados[] = array(
								"value" => $row['id_det'], 
								"clave" => $row['clave'], 
							    "articulo" =>  $row['articulo'], 
							    "fecha_pedido" => $row['fecha_pedido'], 
							    "folio" => $row['folio'], 
							    "cantidad" => $row['cantidad'], 
							    "precio_unitario" => $row['precio_unitario'], 
							    "precio_total" => $row['precio_total'], 
							    "comprador" => $row['nombre_c'].' '.$row['apellido_c'], 
							    "recolector" => $recolector, 
							    "fecha_surtido" => $row['fh_entregado'], 
							    "nombre_cc" => $centro_costos, 
							    "departamento" => $departamento, 
							    "autorizado" => $row['autorizado'], 
							    "autorizo" => $autorizo, 
							    "fecha_autorizado" => $fecha_autorizo);
						 	
					}
					 echo MostrarTabla($aplicado_a,16,$lista_resultados); 
					
					/* foreach($lista_resultados as $lista){
						echo $lista['clave'].' / '.$lista['articulo'].' / '.$lista['precio_total'].' / '.$lista['cantidad'].' </br>';
					} */
				}				
				//echo $sql_reporte;				
		}
		else if($detalle_totales == 2)// TOTALES
		{
			// reporte de todos los articulos solo totales
			if ($uno_todos == 1)// TODOS
			{
				$sql_reporte = "SELECT a.id as id_articulo, a.clave_empresa as clave, a.nombre as articulo, sum(pd.cantidad) as cantidad, sum(pd.precio_total) as precio_total
							FROM articulos a 
							LEFT JOIN pedidos_det pd on pd.id_articulo = a.id
							LEFT JOIN pedidos p on p.id = pd.id_pedido
															
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							GROUP BY a.id";
			}
			else if ($uno_todos == 2)// UNO
			{
				$sql_reporte = "SELECT a.id as id_articulo, a.clave_empresa as clave, a.nombre as articulo, sum(pd.cantidad) as cantidad, sum(pd.precio_total) as precio_total
							FROM articulos a 
							LEFT JOIN pedidos_det pd on pd.id_articulo = a.id
							LEFT JOIN pedidos p on p.id = pd.id_pedido
															
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							AND pd.id_articulo = '$valor_evaluado'
							GROUP BY a.id";
				
			}
			
			$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
			//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0){
				$valor_autorizaciones = '';
			//	echo $total_rows;
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                   {
					
					$lista_resultados[] = array(
							"value" => $row['id_articulo'], 
							"clave" => $row['clave'], 
						    "articulo" =>  $row['articulo'], 
						    "cantidad" =>  $row['cantidad'], 
						    "precio_total" =>  $row['precio_total']);
						

					
					}
					if ($tipo_reporte == 1)
					{
						 echo MostrarTabla($aplicado_a,5,$lista_resultados);
					}
					
				
				 
				
				 /* foreach($lista_resultados as $lista){
					echo $lista['clave'].' / '.$lista['articulo'].' / '.$lista['cantidad'].' / '.$lista['precio_total'].' </br>';
				}  */
			}
		}
	}
	else if ($aplicado_a == 2)
	{ // CENTRO DE COSTOS	//////////////////////////////////////////////////
		if ($detalle_totales == 1)// DETALLES 
		{ 
			// reporte de todos los centros de costos con detalles
			if ($uno_todos == 1)// TODOS
			{ 
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, u.nombre as nombre_c, u.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_departamento as id_departamento, p.con_autorizacion as autorizado, p.id as id_pedido, cc.nombre_cc as nombre_cc
								FROM centro_costos cc 
								LEFT JOIN pedidos p on p.id_cc = cc.id_cc
								LEFT JOIN pedidos_det pd on pd.id_pedido = p.id
								INNER JOIN usuarios u on u.id = p.id_usuario
																
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo; 
			}
			else if ($uno_todos == 2) // UNO
			{
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, u.nombre as nombre_c, u.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_departamento as id_departamento, p.con_autorizacion as autorizado, p.id as id_pedido, cc.nombre_cc as nombre_cc
								FROM centro_costos cc 
								LEFT JOIN pedidos p on p.id_cc = cc.id_cc
								LEFT JOIN pedidos_det pd on pd.id_pedido = p.id
								INNER JOIN usuarios u on u.id = p.id_usuario
																
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
								AND p.id_cc = '$valor_evaluado'"; 
			}
			$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
				//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0){
				$valor_autorizaciones = '';
				//echo $total_rows;
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                   {
					$recolector = '';
					if ($row['id_recolector'] == 0){ // si fue personalmente
						$recolector = 'Personalmente';
					}
					else
					{
						$recolector = Nombre($row['id_recolector']);
					}
					/* $centro_costos = '';
					if ($row['id_cc'] == 0){ // sin centro de costos
						$centro_costos = '-';
					}
					else
					{
						$centro_costos = CC_NOMBRE($row['id_cc']);
					} */
					$departamento = '';
					if ($row['id_departamento'] == 0){ // sin departamento
						$departamento = '-';
					}
					else
					{
						$departamento = DEPARTAMENTO_NOMBRE($row['id_departamento']);
					}
					$valor_autorizaciones = "";
					$autorizo = "";
					$fecha_autorizo = "";
					if ($row['autorizado'] == 1){
					$valor_autorizaciones =  verific_autorizacion($row['id_pedido']);
					$arr_autoriz = explode("_",$valor_autorizaciones);
					$autorizo = $arr_autoriz[0];
					$fecha_autorizo = $arr_autoriz[1];
					//echo '</br>'.$fecha_autorizo.' -> '.$row['articulo'].' Autorizo -> '.$autorizo;
					}
					$lista_resultados[] = array(
							"value" => $row['id_det'], 
							"clave" => $row['clave'], 
						    "articulo" =>  $row['articulo'], 
						    "fecha_pedido" => $row['fecha_pedido'], 
						    "folio" => $row['folio'], 
						    "cantidad" => $row['cantidad'], 
						    "precio_unitario" => $row['precio_unitario'], 
						    "precio_total" => $row['precio_total'], 
						    "comprador" => $row['nombre_c'].' '.$row['apellido_c'], 
						    "recolector" => $recolector, 
						    "fecha_surtido" => $row['fh_entregado'], 
						    "nombre_cc" => $row['nombre_cc'], 
						    "departamento" => $departamento, 
						    "autorizado" => $row['autorizado'], 
						    "autorizo" => $autorizo, 
						    "fecha_autorizado" => $fecha_autorizo);
					 	
				}
				echo MostrarTabla($aplicado_a,16,$lista_resultados); 
				/* foreach($lista_resultados as $lista){
					echo $lista['nombre_cc'].' / '.$lista['articulo'].' / '.$lista['precio_total'].' / '.$lista['cantidad'].' </br>';
				} */
			} // termina consulta centro costos detalle
			
		}
		else if($detalle_totales == 2)// TOTALES
		{
			// reporte de todos los centros de costos solo totales
			if ($uno_todos == 1)// TODOS
			{ 
				$sql_reporte = "SELECT cc.id_cc as id_cc,  cc.nombre_cc as centro_costo, count(p.id_cc) as cantidad, sum(p.total_pedido) as total_pedido
							FROM centro_costos cc 
								LEFT JOIN pedidos p on p.id_cc = cc.id_cc
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							GROUP BY cc.id_cc";
			}
			else if ($uno_todos == 2)// UNO
			{ 
				$sql_reporte = "SELECT cc.id_cc as id_cc,  cc.nombre_cc as centro_costo, count(p.id_cc) as cantidad, sum(p.total_pedido) as total_pedido
							FROM centro_costos cc 
								LEFT JOIN pedidos p on p.id_cc = cc.id_cc
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							AND p.id_cc = '$valor_evaluado'
							GROUP BY cc.id_cc";
			}
			$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
			//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0)
			{
				$valor_autorizaciones = '';
				//echo $total_rows;
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                {
					
					$lista_resultados[] = array(
							"value" => $row['id_cc'],
						    "centro_costo" =>  $row['centro_costo'], 
						    "cantidad" =>  $row['cantidad'], 
						    "precio_total" =>  $row['total_pedido']);
				}
				if ($tipo_reporte == 1)
				{
					echo MostrarTabla($aplicado_a,5,$lista_resultados);
				}
				else if ($tipo_reporte == 1)
				{
					
				}
				
				 
			}
		}
	}
	else if ($aplicado_a == 3)
	{ // DEPARTAMENTOS  / /////////////////////////////////////////////   /// / // // // 
		if ($detalle_totales == 1)// DETALLES 
		{ 
			// reporte de todos los centros de costos con detalles
			if ($uno_todos == 1)// TODOS
			{ 
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, u.nombre as nombre_c, u.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_cc as id_cc, p.con_autorizacion as autorizado, p.id as id_pedido, d.departamento as departamento
								FROM departamentos d 
								LEFT JOIN pedidos p on p.id_departamento = d.id_departamento
								LEFT JOIN pedidos_det pd on pd.id_pedido = p.id
								INNER JOIN usuarios u on u.id = p.id_usuario
																
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo; 
			}
			else if ($uno_todos == 2) // UNO
			{
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, u.nombre as nombre_c, u.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_cc as id_cc, p.con_autorizacion as autorizado, p.id as id_pedido, d.departamento as departamento
								FROM departamentos d 
								LEFT JOIN pedidos p on p.id_departamento = d.id_departamento
								LEFT JOIN pedidos_det pd on pd.id_pedido = p.id
								INNER JOIN usuarios u on u.id = p.id_usuario
																
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
								AND p.id_departamento = '$valor_evaluado'"; 
			}
			$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
				//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0){
				$valor_autorizaciones = '';
				//echo $total_rows;
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                   {
					$recolector = '';
					if ($row['id_recolector'] == 0){ // si fue personalmente
						$recolector = 'Personalmente';
					}
					else
					{
						$recolector = Nombre($row['id_recolector']);
					}
					$centro_costos = '';
					if ($row['id_cc'] == 0){ // sin centro de costos
						$centro_costos = '-';
					}
					else
					{
						$centro_costos = CC_NOMBRE($row['id_cc']);
					}
					/* $departamento = '';
					if ($row['id_departamento'] == 0){ // sin departamento
						$departamento = '-';
					}
					else
					{
						$departamento = DEPARTAMENTO_NOMBRE($row['id_departamento']);
					} */
					$valor_autorizaciones = "";
					$autorizo = "";
					$fecha_autorizo = "";
					if ($row['autorizado'] == 1){
					$valor_autorizaciones =  verific_autorizacion($row['id_pedido']);
					$arr_autoriz = explode("_",$valor_autorizaciones);
					$autorizo = $arr_autoriz[0];
					$fecha_autorizo = $arr_autoriz[1];
					//echo '</br>'.$fecha_autorizo.' -> '.$row['articulo'].' Autorizo -> '.$autorizo;
					}
					$lista_resultados[] = array(
							"value" => $row['id_det'], 
							"clave" => $row['clave'], 
						    "articulo" =>  $row['articulo'], 
						    "fecha_pedido" => $row['fecha_pedido'], 
						    "folio" => $row['folio'], 
						    "cantidad" => $row['cantidad'], 
						    "precio_unitario" => $row['precio_unitario'], 
						    "precio_total" => $row['precio_total'], 
						    "comprador" => $row['nombre_c'].' '.$row['apellido_c'], 
						    "recolector" => $recolector, 
						    "fecha_surtido" => $row['fh_entregado'], 
						    "nombre_cc" => $centro_costos, 
						    "departamento" => $row['departamento'], 
						    "autorizado" => $row['autorizado'], 
						    "autorizo" => $autorizo, 
						    "fecha_autorizado" => $fecha_autorizo);
					 	
				}
				echo MostrarTabla($aplicado_a,16,$lista_resultados); 
				/* foreach($lista_resultados as $lista){
					echo $lista['departamento'].' / '.$lista['folio'].' / '.$lista['articulo'].' / '.$lista['precio_total'].' / '.$lista['cantidad'].' </br>';
				} */
			} // termina consulta departamentos detalle
			
		}
		else if($detalle_totales == 2)// TOTALES
		{
			// reporte de todos los departamentos solo totales
			if ($uno_todos == 1)// TODOS
			{ 
				$sql_reporte = "SELECT d.id_departamento as id_departamento,  d.departamento as departamento, count(p.id_departamento) as cantidad, sum(p.total_pedido) as total_pedido
							FROM departamentos d 
								LEFT JOIN pedidos p on p.id_departamento = d.id_departamento
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							GROUP BY d.id_departamento";
			}
			else if ($uno_todos == 2)// UNO
			{ 
				$sql_reporte = "SELECT d.id_departamento as id_departamento,  d.departamento as departamento, count(p.id_departamento) as cantidad, sum(p.total_pedido) as total_pedido
							FROM departamentos d 
								LEFT JOIN pedidos p on p.id_departamento = d.id_departamento
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							AND p.id_departamento = '$valor_evaluado'
							GROUP BY d.id_departamento";
			}
			$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
			//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0){
				$valor_autorizaciones = '';
				//echo $total_rows;
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                   {
					
					$lista_resultados[] = array(
							"value" => $row['id_departamento'], 
						    "departamento" =>  $row['departamento'], 
						    "cantidad" =>  $row['cantidad'], 
						    "precio_total" =>  $row['total_pedido']);
					}
				 echo MostrarTabla($aplicado_a,5,$lista_resultados);
				 /* foreach($lista_resultados as $lista){
					echo $lista['clave'].' / '.$lista['cantidad'].' / '.$lista['precio_total'].' </br>';
				}  */
			}
		}
	}
	else if ($aplicado_a == 4)
	{ // USUARIOS
		if ($detalle_totales == 1)// DETALLES 
		{ 
			// reporte de todos los usuarios con detalles
			if ($uno_todos == 1)// TODOS
			{ 
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, usu.nombre as nombre_c, usu.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_cc as id_cc, p.con_autorizacion as autorizado, p.id as id_pedido, p.id_departamento as id_departamento
								FROM usuarios usu 
								LEFT JOIN pedidos p on p.id_usuario = usu.id
								LEFT JOIN pedidos_det pd on pd.id_pedido = p.id
																								
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo; 
			}
			else if ($uno_todos == 2) // UNO
			{
				$sql_reporte = "SELECT pd.clave_empresa as clave, pd.articulo as articulo, pd.id as id_det, p.fecha_pedido_oficial as fecha_pedido, p.folio as folio, pd.cantidad as cantidad, pd.precio_unitario as precio_unitario, pd.precio_total as precio_total, pd.unidad_medida as unidad_medida, usu.nombre as nombre_c, usu.apellido as apellido_c, p.id_recolector as id_recolector, p.fh_entregado as fh_entregado,  p.id_cc as id_cc, p.con_autorizacion as autorizado, p.id as id_pedido, p.id_departamento as id_departamento
								FROM usuarios usu 
								LEFT JOIN pedidos p on p.id_usuario = usu.id
								LEFT JOIN pedidos_det pd on pd.id_pedido = p.id
								
																
								WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
								AND p.id_usuario = '$valor_evaluado'"; 
			}
			$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
				//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0){
				$valor_autorizaciones = '';
				//echo $total_rows;
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                   {
					$recolector = '';
					if ($row['id_recolector'] == 0){ // si fue personalmente
						$recolector = 'Personalmente';
					}
					else
					{
						$recolector = Nombre($row['id_recolector']);
					}
					$centro_costos = '';
					if ($row['id_cc'] == 0){ // sin centro de costos
						$centro_costos = '-';
					}
					else
					{
						$centro_costos = CC_NOMBRE($row['id_cc']);
					}
					$departamento = '';
					if ($row['id_departamento'] == 0){ // sin departamento
						$departamento = '-';
					}
					else
					{
						$departamento = DEPARTAMENTO_NOMBRE($row['id_departamento']);
					}
					$valor_autorizaciones = "";
					$autorizo = "";
					$fecha_autorizo = "";
					if ($row['autorizado'] == 1){
					$valor_autorizaciones =  verific_autorizacion($row['id_pedido']);
					$arr_autoriz = explode("_",$valor_autorizaciones);
					$autorizo = $arr_autoriz[0];
					$fecha_autorizo = $arr_autoriz[1];
					//echo '</br>'.$fecha_autorizo.' -> '.$row['articulo'].' Autorizo -> '.$autorizo;
					}
					$lista_resultados[] = array(
							"value" => $row['id_det'], 
							"clave" => $row['clave'], 
						    "articulo" =>  $row['articulo'], 
						    "fecha_pedido" => $row['fecha_pedido'], 
						    "folio" => $row['folio'], 
						    "cantidad" => $row['cantidad'], 
						    "precio_unitario" => $row['precio_unitario'], 
						    "precio_total" => $row['precio_total'], 
						    "comprador" => $row['nombre_c'].' '.$row['apellido_c'], 
						    "recolector" => $recolector, 
						    "fecha_surtido" => $row['fh_entregado'], 
						    "nombre_cc" => $centro_costos, 
						    "departamento" => $departamento, 
						    "autorizado" => $row['autorizado'], 
						    "autorizo" => $autorizo, 
						    "fecha_autorizado" => $fecha_autorizo);
					 	
				}
				echo MostrarTabla($aplicado_a,16,$lista_resultados); 
				/* foreach($lista_resultados as $lista){
					echo $lista['comprador'].' / '.$lista['folio'].' / '.$lista['articulo'].' / '.$lista['precio_total'].' / '.$lista['cantidad'].' </br>';
				} */
			} // termina consulta usuarios detalle
			
		}
		else if($detalle_totales == 2)// TOTALES
		{
			// reporte de todos los usuarios solo totales
			if ($uno_todos == 1)// TODOS
			{ 
				$sql_reporte = "SELECT usu.id as id_usuario,  usu.nombre as nombre_c, usu.apellido as apellido_c, count(p.id_usuario) as cantidad, sum(p.total_pedido) as total_pedido
							FROM usuarios usu 
							LEFT JOIN pedidos p on p.id_usuario = usu.id
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							GROUP BY usu.id";
			}
			else if ($uno_todos == 2)// UNO
			{ 
				$sql_reporte = "SELECT usu.id as id_usuario,  usu.nombre as nombre_c, usu.apellido as apellido_c, count(p.id_usuario) as cantidad, sum(p.total_pedido) as total_pedido
							FROM usuarios usu 
							LEFT JOIN pedidos p on p.id_usuario = usu.id
							WHERE p.id_empresa = '$id_empresa' AND p.estatus ='3' ".$where_periodo."
							AND p.id_usuario = '$valor_evaluado'
							GROUP BY usu.id";
			}
			$resultado = mysql_query($sql_reporte, $conex) or die(mysql_error());
			//$row = mysql_fetch_assoc($resultado);
			$total_rows = mysql_num_rows($resultado);
			if ($total_rows > 0){
				$valor_autorizaciones = '';
				//echo $total_rows;
				while($row = mysql_fetch_array($resultado,MYSQL_BOTH)) 
                   {
					
					$lista_resultados[] = array(
							"value" => $row['id_usuario'], 
						    "comprador" =>  $row['nombre_c']." ".$row['apellido_c'], 
						    "cantidad" =>  $row['cantidad'], 
						    "precio_total" =>  $row['total_pedido']);
					}
					
					 echo MostrarTabla($aplicado_a,5,$lista_resultados);
				 /* foreach($lista_resultados as $lista){
					echo $lista['clave'].' / '.$lista['cantidad'].' / '.$lista['precio_total'].' </br>';
				}  */
			}
		}
	}
	
	
	
	
	?>