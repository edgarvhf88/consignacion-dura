
<?php include("conexion.php");

      $id_articulo = $_POST['id_articulo'];
      $id_almacen = $_POST['id_almacen'];
      $cantidad = $_POST['cantidad'];
      $iduser = $_POST['id_user'];
	  
	function cantidad_pedidas($id_articulo){
		global $conex;
				
		$consulta = "SELECT SUM(pd.cantidad) as cantidad
		FROM pedidos_det pd
		INNER JOIN pedidos p on p.id = pd.id_pedido
		WHERE pd.id_articulo = '$id_articulo' 
		AND p.estatus <> '0' 
		AND p.estatus <> '3' ";  // estatus diferente a abieto y surtido
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		$cantidad = 0; // la cantidad que esta en pedidos ordenados y en proceso
		
		if ($total_rows > 0)
		{ // con resultados --> actualiza el total
			$cantidad = $row['cantidad'];
		}  
		return $cantidad;
	}
	  
	  
	  if ($id_articulo != ''){
		  
		 agregar($id_articulo,$cantidad,$iduser,$id_almacen);
	  }
     function agregar($id_articulo,$cantidad,$iduser,$id_almacen){ // 
global $conex;
$clave_microsip = "";
$clave_empresa = "";
$articulo = "";
$precio_unitario = "";
$precio_total = "";
$unidad_medida = "";
$existencia_actual = 0;
$existencia_preventiva = 0;
$id_pedido = '';

		
		$consulta = "SELECT a.nombre as nombre, a.clave_microsip as clave_microsip, a.precio as precio, a.clave_empresa as clave_empresa, a.id as id, a.unidad_medida as unidad_medida, exis.existencia_actual as existencia, exis.almacen_id as almacen_id  
		FROM articulos a
		LEFT JOIN existencias exis on exis.id_articulo = a.id
		WHERE a.id = '$id_articulo' and exis.almacen_id = '$id_almacen'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		
			if ($total_rows > 0)
			{ // con resultados
				$clave_microsip = $row['clave_microsip'];
				$clave_empresa = $row['clave_empresa'];
				$articulo = $row['nombre'];
				$precio_unitario = str_replace(",","",$row['precio']);
				$precio_total = ($precio_unitario) * ($cantidad);
				$unidad_medida = $row['unidad_medida'];
				$existencia_actual = $row['existencia'];
				
			}
			//$cantidades_pedidos = cantidad_pedidas($id_articulo);
			//$existencia_preventiva = $existencia_actual - $cantidades_pedidos;
			
			/* if ($existencia_preventiva < $cantidad)  ///  
			{  // si la existencia preventiva es menor a la cantidad solicitada denegara peticion
				
				//	echo '<script> alert("La cantidad solicitada no esta disponible en este momento"); </script>';
				
			} 
			else 
			{ // de lo contrario si es igual o mayor entoces proceguira con la solicitud
			} */	$consulta2 = "SELECT * FROM pedidos WHERE id_usuario = '$iduser' AND estatus = '0' and id_sucursal = '$id_almacen' ";
				$resultado2 = mysql_query($consulta2, $conex) or die(mysql_error());
				$row2 = mysql_fetch_assoc($resultado2);
				$total_rows2 = mysql_num_rows($resultado2);
	
				if ($total_rows2 > 0)
				{ // con resultados --> actualiza el total
					$id_pedido = $row2['id'];
				}
				else
				{ // si no existe pedido abierto
					
					$id_empresa = id_empresa($iduser);
					$id_departamento = DEPARTAMENTO($iduser);
					//$folio = folio_consecutivo($id_empresa,"PED");
					$folio = "";
					
					
					$insert_pedido = "INSERT INTO pedidos (id_usuario,folio,id_empresa,estatus,id_departamento,id_sucursal)
											VALUES ('$iduser','$folio','$id_empresa','0','$id_departamento','$id_almacen')";
					if (mysql_query($insert_pedido, $conex) or die(mysql_error()))
					{
						$id_pedido =  mysql_insert_id();
				
					}
				}
				//// consulta para verificar si existe el mismo articulo en la lista y asi lo sume en lugar de agregarlo de nuevo.
				$consulta_art_list = "SELECT pd.cantidad as cantidad, pd.id as id_det
				FROM pedidos_det pd
				WHERE pd.id_articulo = '$id_articulo' 
				AND pd.id_pedido = '$id_pedido'";  // estatus diferente a abieto y surtido
				$resultado_art_list = mysql_query($consulta_art_list, $conex) or die(mysql_error());
				$row_art_list = mysql_fetch_assoc($resultado_art_list);
				$total_rows_art_list = mysql_num_rows($resultado_art_list);
				$cantidad_art_list = 0; // la cantidad que esta en pedidos ordenados y en proceso
				$id_pedido_det = 0; // la cantidad que esta en pedidos ordenados y en proceso
				if ($total_rows_art_list > 0)
				{ // si exite el articulo en la lista obtiene la cantidad y 
					$cantidad_art_list = $row_art_list['cantidad'];
					$id_pedido_det = $row_art_list['id_det'];
				}
				
				
				if ($cantidad_art_list == 0) 
				{ // si la cantidad es 0 significa que no ha sido agregado a la lista
					$insert_articulos = "INSERT INTO pedidos_det (id_pedido,id_articulo,clave_empresa,clave_microsip,articulo,cantidad,precio_unitario,precio_total,unidad_medida) VALUES ('$id_pedido','$id_articulo','$clave_empresa','$clave_microsip','$articulo','$cantidad','$precio_unitario','$precio_total','$unidad_medida')";
					if (mysql_query($insert_articulos, $conex) or die(mysql_error()))
					{
						//$id_pedido =  mysql_insert_id();
						//echo 1;
						echo '<script> $("#ventana1").modal("show"); </script>';
					
					}
				}
				else				
				{ // si la cantidad es mayor a 0 entonces editara el articulo existente y se le agregara la cantidad solicitada
					if ($id_pedido_det != 0){
						$cantidad += $cantidad_art_list;
						$precio_total = ($precio_unitario) * ($cantidad);
						/* if ($existencia_preventiva < $cantidad)  ///  
						{
							echo '<script> alert("No se puede agregar mas cantidad del articulo al pedido"); </script>';
						}	
						else 
						{} */
							$update = "UPDATE pedidos_det 
							SET cantidad='$cantidad', precio_total='$precio_total' 
							WHERE id='$id_pedido_det'";
		
							if (mysql_query($update, $conex) or die(mysql_error()))
							{
								echo '<script> $("#ventana1").modal("show"); </script>';
							}
							
					}
				}	
			
				

}

?>