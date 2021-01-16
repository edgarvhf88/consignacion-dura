<?php include("conexion.php"); 
				  
			
				  
	  if (isset($_POST['orden_id'])){
			$orden_id = $_POST['orden_id'];
			$id_articulo = $_POST['id_articulo'];
			$cantidad = $_POST['cantidad'];
			$udm = $_POST['udm'];
			$numero_parte = $_POST['numero_parte'];
			$descripcion = $_POST['descripcion'];
			$precio_unitario = $_POST['precio_unitario'];
			$precio_total = $_POST['precio_total'];
			
			
		
			add_partida($orden_id,$id_articulo,$cantidad,$udm,$numero_parte,$descripcion,$precio_unitario,$precio_total);
		}
	function add_partida($orden_id,$id_articulo,$cantidad,$udm,$numero_parte,$descripcion,$precio_unitario,$precio_total)
	{	global $conex;
		// validar si el articulo ya habia sido agregado a la lista
			$sql_busca = "SELECT COUNT(id_oc_det) as cantidad FROM ordenes_det 
			WHERE id_oc='$orden_id' AND id_articulo='$id_articulo'";
			$res = mysql_query($sql_busca, $conex) or die(mysql_error());
			$row = mysql_fetch_assoc($res);
			$total_res = mysql_num_rows($res);
			if ($total_res > 0) // si ya estaba registrado el articulo con la orden actual
			{	
				if ($row['cantidad'] > 0)
				{
				
				$msj = 'repetidamente';
				switch($row['cantidad'])
				{
					case 0:
					$msj = 'por cero ocasion';
					break;
					case 1:
					$msj = 'por segunda ocasion';
					break;
					case 2:
					$msj = 'por trecera ocasion';
					break;
					case 3:
					$msj = 'por cuarta ocasion';
					break;
				}
				echo '<script> alert("Esta agregando un numero de parte '.$msj.', si esta consinte de ello haga caso omiso de este aviso, si tiene dudas revise su lista de partidas agregadas apoyandose con el filtro dinamico"); </script>';
					
				}
			}
			
			$posicion = Posicion($orden_id);
			$insert_orden_det = "INSERT INTO ordenes_det (id_oc,id_articulo,cantidad,udm,numero_parte,descripcion,precio_unitario,precio_total,posicion) VALUES 
			('$orden_id','$id_articulo','$cantidad','$udm','$numero_parte','$descripcion','$precio_unitario','$precio_total','$posicion')";
			if (mysql_query($insert_orden_det, $conex) or die(mysql_error()))
			{
				//$id_oc =  mysql_insert_id();
				echo '<script> console.log("se agrego partida a orden"); 
				$("#txt_cantidad_oc").val("");
				$("#txt_udm_oc").val("");
				$("#txt_numero_parte").val("");
				$("#txt_descripcion_oc").val("");
				$("#txt_precio_unitario").val("");
				$("#txt_precio_total").val("");
				$("#modal_partida_oc").modal("hide");
				lista_oc_det();
				</script>';
			}
	}
		