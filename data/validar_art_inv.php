<?php include("conexion.php");

      $id_articulo = $_POST['id_articulo'];
      $id_inventario = $_POST['id_inventario'];
      $almacen_id = $_POST['almacen_id'];
		
		if ($id_articulo != ""){
			validar($id_articulo,$id_inventario,$almacen_id);
		}

	function validar($id_articulo,$id_inventario,$almacen_id){
		global $conex;
		
		$consulta_inventarios = "
					SELECT 
						i.id_usuario_creador as id_usuario_creador,
						i.cantidad_contada as cantidad_contada_total,
						c.id_conteo as id_conteo,
						c.cantidad as cantidad,
						c.existencia_momento as existencia_momento,
						c.diferencia as diferencia,
						c.estatus as estatus,
						c.id_usuario_creador as id_creador_conteo,
						c.fecha_hora_creacion as fecha_hora_conteo
					FROM inventarios_det i
					INNER JOIN inventarios_det_conteos c ON c.id_inventario_det = i.id_inventario_det
					WHERE i.id_inventario = '$id_inventario' AND i.id_articulo = '$id_articulo'";	
	$resultado = mysql_query($consulta_inventarios, $conex) or die(mysql_error());
	
	$total_rows = mysql_num_rows($resultado);
	$msj = '';
	$tabla_conteos = '';
	$clase = 'table bg-info ';
	if ($total_rows > 0 ){ //existe
		if ($total_rows == 1 ){ //si solo es uno
			$row = mysql_fetch_assoc($resultado);
			$tabla_conteos = '<table id=\"tabla_conteos\" class=\"'.$clase.'\">
						<thead>
							<tr>
								<td>
									Usuario
								</td>
								<td>
									Fecha 
								</td>
								<td>
									Cantidad 
								</td>
								<td>
									Borrar 
								</td>
								<td class=\"hidden\">
									Exist. Moment. 
								</td>
								
							</tr>
						</thead><tbody>
						<tr>
								<td>
									'.Nombre($row['id_creador_conteo']).'
								</td>
								<td>
									'.$row['fecha_hora_conteo'].'
								</td>
								<td>
									'.$row['cantidad'].'
								</td>
								<td>
									<input onclick=\"remov_conteo('.$row['id_conteo'].');\" type=\"button\" value=\"X\" class=\"btn btn-danger\" />
								</td>
								<td class=\"hidden\">
									'.$row['existencia_momento'].'
								</td>
								
							</tr></tbody></table>';
		}
		else if ($total_rows > 1 ) // si existen mas de 1 conteos muestra lista
		{
			$tabla_conteos .= '<table id=\"tabla_conteos\" class=\"'.$clase.'\">
						<thead>
							<tr>
								<td>
									Usuario
								</td>
								<td>
									Fecha 
								</td>
								<td>
									Cantidad 
								</td>
								<td>
									Borrar 
								</td>
								<td class=\"hidden\">
									Exist. Moment. 
								</td>
							</tr>
						</thead><tbody>';
			while($rows = mysql_fetch_array($resultado,MYSQL_BOTH))
			{
				
				$tabla_conteos .= '
							<tr>
								<td>
									'.Nombre($rows['id_creador_conteo']).'
								</td>
								<td>
									'.$rows['fecha_hora_conteo'].'
								</td>
								<td>
									'.$rows['cantidad'].'
								</td>
								<td>
									<input onclick=\"remov_conteo('.$rows['id_conteo'].');\" type=\"button\" value=\"X\" class=\"btn btn-danger\" />
								</td>
								<td class=\"hidden\">
									'.$rows['existencia_momento'].'
								</td>
							</tr>';
			}
			$tabla_conteos .= '</tbody></table>';
		
		}	
		
		$tabla_conteos = trim(preg_replace('/\s+/', ' ', $tabla_conteos));
		echo '<script> $("#div_msj_conteo").html("'.$tabla_conteos.'");  </script>';
		
	}else{  // no existe
		echo '<script> $("#div_msj_conteo").html("");  </script>';
	}
	
		
	}

  




?>