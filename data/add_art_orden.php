
<?php include("conexion.php");
	
      $id_consigna = $_POST['id_art'];
	 // $id_microsip = id_microsip_allpart($id_consigna);
	  $id_orden = $_POST['orden_id'];
      $cantidad = $_POST['unidades'];
      $iduser = $_SESSION["logged_user"];
      $precio = $_POST['precio'];
      $precio_total = $_POST['precio_total'];
      $udm = $_POST['udm'];
	  $parte=clave_consigna($id_consigna);
	  $descripcion= descripcion_empresa($id_consigna);
	  
	if ($id_consigna != ''){ 
	
		 agregar($id_consigna, $cantidad,$iduser,$precio,$precio_total,$udm, $id_orden, $parte, $descripcion);
	}
	
function agregar($id_consigna, $cantidad,$iduser,$precio,$precio_total,$udm, $id_orden, $parte, $descripcion){ // Funcion para Buscar Articulos
	global $conex;
	$almacen_id = 19;
	//obtener datos con el id_microsip
	
	$precio_unitario = $precio;
	$unidad_medida = $udm;
	$precio_total = number_format($precio_total, 2);

				//// consulta para verificar si existe el mismo articulo en la lista y asi lo sume en lugar de agregarlo de nuevo.
				$consulta_art_list = "SELECT pd.cantidad as cantidad, pd.id_oc_det as id_det
				FROM ordenes_det pd
				WHERE pd.articulo_id = '$id_consigna' 
				AND pd.id_oc = '$id_orden'";  
				$resultado_art_list = mysql_query($consulta_art_list, $conex) or die(mysql_error());
				$row_art_list = mysql_fetch_assoc($resultado_art_list);
				$total_rows_art_list = mysql_num_rows($resultado_art_list);
				$cantidad_art_list = 0; // la cantidad que esta en pedido_traspaso ordenados y en proceso
				$id_det = 0; // la cantidad que esta en pedido_traspaso ordenados y en proceso
				if ($total_rows_art_list > 0)
				{ // si exite el articulo en la lista obtiene la cantidad y 
					$cantidad_art_list = $row_art_list['cantidad'];
					$id_det = $row_art_list['id_det'];
				}
				
				
				if ($cantidad_art_list == 0) 
				{ // si la cantidad es 0 significa que no ha sido agregado a la lista
					$insert_articulos = "INSERT INTO ordenes_det (id_oc, cantidad, precio_unitario, precio_total, udm, articulo_id, numero_parte, descripcion) VALUES ('$id_orden','$cantidad','$precio_unitario','$precio_total','$unidad_medida','$id_consigna', '$parte', '$descripcion')";
					if (mysql_query($insert_articulos, $conex) or die(mysql_error()))
					{
						//$id_pedido =  mysql_insert_id();
						//echo 1;
						echo '<script> lista_oc_det(); </script>';
					
					}
				}
				else				
				{ // si la cantidad es mayor a 0 entonces editara el articulo existente y se le agregara la cantidad solicitada
					if ($id_det != 0){
						$cantidad += $cantidad_art_list;
						$precio_total = ($precio_unitario) * ($cantidad);
						
							$update = "UPDATE ordenes_det 
							SET cantidad='$cantidad', precio_total='$precio_total' 
							WHERE id_oc_det='$id_det'";
		
							if (mysql_query($update, $conex) or die(mysql_error()))
							{
								echo '<script> lista_oc_det(); </script>';
							}
						
					}
				}	
			
				

}

?>