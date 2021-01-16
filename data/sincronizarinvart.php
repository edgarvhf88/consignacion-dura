<?php include("conexion.php");

if (isset($_POST['id_articulo'])){
	
	$id_articulo = $_POST['id_articulo'];
	$almacen_id = $_POST['almacen_id'];
	ActualizarExistenciaArticulo($id_articulo,$almacen_id);
	
}
function ActualizarExistenciaArticulo($id_articulo,$almacen_id){
	global $conex, $con_micro;

$existencia_microsip = 0;
$id_arti = 0;

$consulta_articulos = "SELECT a.id as id,
							a.id_microsip as id_microsip, 
							e.existencia_actual as existencia_actual
							FROM articulos a 
							LEFT JOIN existencias e ON e.id_articulo = a.id 
							WHERE a.id_microsip = '$id_articulo' AND e.almacen_id = '$almacen_id'";
	$resultado_articulos = mysql_query($consulta_articulos, $conex) or die(mysql_error());
	$row_articulos = mysql_fetch_assoc($resultado_articulos);
	$total_rows = mysql_num_rows($resultado_articulos);
	
	if ($total_rows > 0){
	//echo "encontrado: ".$total_rows; 
		if ($row_articulos['id_microsip'] != ""){
			
			$id_arti = $row_articulos['id'];
			//$existencia_actual = $row_articulos['existencia_actual'];
			$existencia_microsip = ExistenciaMicrosip($row_articulos['id_microsip'],$almacen_id);
			$min_max_reorden = explode("_",MinMaxReorden($row_articulos['id_microsip'],$almacen_id));
			$maximo = $min_max_reorden[0];
			$minimo = $min_max_reorden[1];
			$reorden = $min_max_reorden[2];
			$udm = UDMArticulo($row_articulos['id_microsip']);
			$precio = PrecioArticulo($row_articulos['id_microsip']);
			
			
			$update = "UPDATE existencias SET min='$minimo', max='$maximo', reorden='$reorden', existencia_actual='$existencia_microsip' WHERE id_articulo='$id_arti' and almacen_id = '$almacen_id'";
			if (mysql_query($update, $conex) or die(mysql_error()))
			{		
					$updateart = "UPDATE articulos SET precio='$precio', unidad_medida='$udm' WHERE id = $id_arti";
					if (mysql_query($updateart, $conex) or die(mysql_error()))
					{ 	
						echo '<script> console.log("Se actualizo precio");
							</script>';  
					}
				echo '<script> console.log("Se Aplico sincronizacion de inventario de Articulo");
							
							</script>';
			}
		}
	}
	else
	{	
			$query_art_dat = "SELECT * FROM articulos WHERE id_microsip = '$id_articulo'";
			$resultado_art_dat = mysql_query($query_art_dat, $conex) or die(mysql_error());
			$row_arti_dat = mysql_fetch_assoc($resultado_art_dat);
			$totalRows_art_dat = mysql_num_rows($resultado_art_dat);
			if ($totalRows_art_dat > 0){
			$id_arti = $row_arti_dat['id'];
			
			}
			
			$existencia_microsip = ExistenciaMicrosip($id_articulo,$almacen_id);
			$min_max_reorden = explode("_",MinMaxReorden($id_articulo,$almacen_id));
			$maximo = $min_max_reorden[0];
			$minimo = $min_max_reorden[1];
			$reorden = $min_max_reorden[2];
			
			$udm = UDMArticulo($id_articulo);
			$precio = PrecioArticulo($id_articulo);
		
		$insert_punto = "INSERT INTO existencias (min,max,reorden,existencia_actual,id_articulo,almacen_id)
				VALUES ('$minimo','$maximo','$reorden','$existencia_microsip','$id_arti','$almacen_id')";
							if (mysql_query($insert_punto, $conex) or die(mysql_error())){}
		
		$updateart = "UPDATE articulos SET precio='$precio', unidad_medida='$udm' WHERE id = $id_arti";
					if (mysql_query($updateart, $conex) or die(mysql_error()))
					{ 	
						
					}
	}	
}	
	
	
?>