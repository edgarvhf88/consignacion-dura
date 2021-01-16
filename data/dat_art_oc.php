<?php include("conexion.php"); 

		$id_articulo = $_POST['id_articulo'];
		
	  if ($id_articulo != ""){
			$consulta = "SELECT 
		a.id as id,
		a.id_empresa as id_empresa,
		a.clave_microsip as c_microsip,
		a.id_microsip as id_microsip,
		a.clave_empresa as c_empresa, 
		a.nombre as articulo, 
		a.descripcion as descip, 
		a.precio as precio, 
		a.unidad_medida as udm, 
		a.src_img as imagen, 
		exis.min as min,
		exis.max as max,
		exis.reorden as reorden,
		exis.existencia_actual as existencia
		  FROM articulos a 
		  LEFT JOIN existencias exis on exis.id_articulo = a.id 
		  WHERE a.id = '$id_articulo' ";

		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		
		$udm = $row['udm'];
		$precio = $row['precio'];
		
		echo '<script> 
				$("#txt_udm_oc").val("'.$udm.'");
				$("#txt_precio_unitario").val("'.$precio.'");
		
		</script>';
		
		
	  }
	  
	  
	  
	  
?>