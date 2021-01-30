<?php include("../data/conexion.php");

	$almacen_id = $_POST['almacen_id'];
	
	$consulta_inv_inicial = "SELECT a.id as id_articulo, a.id_microsip as articulo_id, 
								ex.existencia_actual as existencia
											FROM articulos a 
											INNER JOIN existencias ex ON ex.id_articulo = a.id
											WHERE ex.almacen_id = '$almacen_id'";
	$res_articulos = mysql_query($consulta_inv_inicial, $conex) or die(mysql_error());
	$num_res_exis = mysql_num_rows($res_articulos);
	//$row_art_exis = mysql_fetch_assoc($res_articulos);
	if ($num_res_exis > 0)
	{
		while($row = mysql_fetch_array($res_articulos,MYSQL_BOTH)) // html de articulos a mostrar
        {
			
		}
	}										
											