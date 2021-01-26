<?php include("conexion.php");


	  if ((isset($_POST['orden_id'])) && (isset($_POST['tipo_oc']))){
				$orden_id = $_POST['orden_id'];
				$tipo_oc = $_POST['tipo_oc'];
				$fecha_orden = $_POST['fecha_orden'];
				$requisitor = $_POST['requisitor'];
				$comprador = $_POST['comprador'];
				
				actualizar($orden_id,$tipo_oc,$fecha_orden,$requisitor,$comprador);
	  } 
	 
	
     function guardar($centro_costos,$empresa_id){ // Funcion para agregar una relacion nueva
global $database_conexion, $conex;

		$consulta = "SELECT * FROM centro_costos WHERE id_empresa = '$empresa_id' and nombre_cc = '$centro_costos' ";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);

		if ($total_rows > 0)
		{ // si ya existe un centro de costos
		if ($row['nombre_cc'] == $centro_costos){
			echo '<script>
			alert("Ya existe un centro de costos con el nombre: '.$row['nombre_cc'].'");
			
			</script>';
		}
		
			
		}
		else 
		{
			///// registra el nuevo centro de costos
			$insert_cc = "INSERT INTO centro_costos (id_empresa,nombre_cc)
								VALUES ('$empresa_id','$centro_costos')";
			if (mysql_query($insert_cc, $conex) or die(mysql_error()))
			{
				//$id_cc =  mysql_insert_id();
				//echo 1;
				echo '<script>
			mostrar_cc();
			
			</script>';
		
			}
		}
}
    
function actualizar($orden_id,$tipo_oc,$fecha_orden,$requisitor,$comprador)
{ // Funcion actualizar el centro de costos
	global $database_conexion, $conex;
	date_default_timezone_set('America/Mexico_City');
	$fecha_hoy = date("Y-m-d H:i:s");	
		$calc_totales = totales_orden($orden_id);
		$calc_totales = number_format($calc_totales,2);
		
	if($tipo_oc == "C")
	{	/// Orden Cerrada -> solicitar remision inmediata y facturacion
		$update = "UPDATE ordenes SET 
								req_factura='SI',
								fecha_req_fac='$fecha_hoy', 
								tipo_oc='$tipo_oc', 
								estatus='1',
								fecha_oc='$fecha_orden',
								requisitor='$requisitor',
								subtotal='$calc_totales',
								total='$calc_totales',
								comprador='$comprador' 
								WHERE id_oc='$orden_id'";
			/// limpiar 
		if (mysql_query($update, $conex) or die(mysql_error()))
		{
			echo '<script>
		
			//mostrar_cc();
			orden_nueva();
			</script>';
			
		}
	}
	
		
		
		
}

?>