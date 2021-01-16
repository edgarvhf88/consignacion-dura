
<?php include("conexion.php");

      $id_microsip = $_POST['id_art'];
      $nombre_art = $_POST['nombre_art'];
      $cantidad = $_POST['unidades'];
      $iduser = $_SESSION["logged_user"];
      $precio = $_POST['precio'];
      $precio_total = $_POST['precio_total'];
      $udm = $_POST['udm'];
	  
	if ($id_microsip != ''){ 
	
		 agregar($id_microsip,$nombre_art,$cantidad,$iduser,$precio,$precio_total,$udm);
	}
     function agregar($id_microsip,$nombre_art,$cantidad,$iduser,$precio,$precio_total,$udm){ // Funcion para Buscar Articulos
global $conex;
$almacen_id = 19;
//obtener datos con el id_microsip
$clave_microsip = ClaveArticulo($id_microsip);
$articulo = NombreArticulo($id_microsip);
$precio_unitario = $precio;
$unidad_medida = $udm;
$existencia = ExistenciaMicrosip($id_microsip,$almacen_id);
$id_pedido = '';

/* Estatus
			-abierto
			-ordenado
			-proceso_surtido
			-surtido_parcial
			-surtido_completo
			-traspaso_parcial
			-traspaso_completo
			
		*/		
			
			if ($existencia < $cantidad)  ///  
			{  // si la existencia preventiva es menor a la cantidad solicitada denegara peticion
				
					echo '<script> alert("La cantidad solicitada no esta disponible en este momento"); </script>';
				
			} 
			else 
			{ // de lo contrario si es igual o mayor entoces proceguira con la solicitud
				$consulta2 = "SELECT * FROM pedido_traspaso WHERE id_usuario = '$iduser' AND estatus = '0' ";
				$resultado2 = mysql_query($consulta2, $conex) or die(mysql_error());
				$row2 = mysql_fetch_assoc($resultado2);
				$total_rows2 = mysql_num_rows($resultado2);
	
				if ($total_rows2 > 0)
				{ // con resultados --> actualiza el total
					$id_pedido = $row2['id_pedido'];
				}
				else
				{ // si no existe pedido abierto
					
					//$id_empresa = id_empresa($iduser);
					//$id_departamento = DEPARTAMENTO($iduser);
					//$folio = folio_consecutivo($id_empresa,"PED");
					$folio = "";
					$insert_pedido = "INSERT INTO pedido_traspaso (id_usuario,estatus)
											VALUES ('$iduser','0')";
					if (mysql_query($insert_pedido, $conex) or die(mysql_error()))
					{
						$id_pedido =  mysql_insert_id();
					}
				}
				//// consulta para verificar si existe el mismo articulo en la lista y asi lo sume en lugar de agregarlo de nuevo.
				$consulta_art_list = "SELECT pd.cantidad as cantidad, pd.id as id_det
				FROM pedido_traspaso_det pd
				WHERE pd.id_microsip = '$id_microsip' 
				AND pd.id_pedido = '$id_pedido'";  
				$resultado_art_list = mysql_query($consulta_art_list, $conex) or die(mysql_error());
				$row_art_list = mysql_fetch_assoc($resultado_art_list);
				$total_rows_art_list = mysql_num_rows($resultado_art_list);
				$cantidad_art_list = 0; // la cantidad que esta en pedido_traspaso ordenados y en proceso
				$id_pedido_det = 0; // la cantidad que esta en pedido_traspaso ordenados y en proceso
				if ($total_rows_art_list > 0)
				{ // si exite el articulo en la lista obtiene la cantidad y 
					$cantidad_art_list = $row_art_list['cantidad'];
					$id_pedido_det = $row_art_list['id_det'];
				}
				
				
				if ($cantidad_art_list == 0) 
				{ // si la cantidad es 0 significa que no ha sido agregado a la lista
					$insert_articulos = "INSERT INTO pedido_traspaso_det (id_pedido,id_microsip,clave_microsip,articulo,cantidad,precio_unitario,precio_total,unidad_medida) VALUES ('$id_pedido','$id_microsip','$clave_microsip','$articulo','$cantidad','$precio_unitario','$precio_total','$unidad_medida')";
					if (mysql_query($insert_articulos, $conex) or die(mysql_error()))
					{
						//$id_pedido =  mysql_insert_id();
						//echo 1;
						echo '<script> cargar_lista_pedido(); </script>';
					
					}
				}
				else				
				{ // si la cantidad es mayor a 0 entonces editara el articulo existente y se le agregara la cantidad solicitada
					if ($id_pedido_det != 0){
						$cantidad += $cantidad_art_list;
						$precio_total = ($precio_unitario) * ($cantidad);
						if ($existencia < $cantidad)  ///  
						{
							echo '<script> alert("No se puede agregar mas cantidad del articulo al pedido por que no tiene suficiente existencia"); </script>';
						}	
						else 
						{
							$update = "UPDATE pedido_traspaso_det 
							SET cantidad='$cantidad', precio_total='$precio_total' 
							WHERE id='$id_pedido_det'";
		
							if (mysql_query($update, $conex) or die(mysql_error()))
							{
								echo '<script> cargar_lista_pedido(); </script>';
							}
						}	
					}
				}	
			}
				

}

?>