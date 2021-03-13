<?php 
session_start();
if (!isset($_SESSION["logged_user"])){
	$_SESSION["logged_user"] = '';
}

$hostname_conexion = "localhost:3306";
$database_conexion = "consigna_dura";
$username_conexion = "consigna_adminallpart";
$password_conexion = "Nacional_2021"; 

/*
$hostname_conexion = "localhost";
$database_conexion = "consignacion_dura";
$username_conexion = "admin_dura";
$password_conexion = "Allpart*2020";
*/

$conex = mysql_pconnect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database_conexion, $conex);

date_default_timezone_set('America/Mexico_City');
	$fecha = date("Y-m-d H:i:s");
function id_empresa($id_usuario) { // obtiene id de empresa **********************************
global $database_conexion, $conex;
if ($id_usuario != ""){
$query = "SELECT * FROM usuarios WHERE id = $id_usuario";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);

$id_empresa = '0';
if ($totalRows > 0){
	
$id_empresa = $row['id_empresa'];


} 
else 
{
	$id_empresa = "0";
}	
}
else 
{
	$id_empresa = "0";
}
return $id_empresa;
	
mysql_free_result($resultado);  
}

function validar_usuario($id_usuario) { // tipo de usuario
global $database_conexion, $conex;

$query = "SELECT * FROM usuarios WHERE id = $id_usuario";
$resultado = mysql_query($query, $conex) or die(mysql_error());
$row = mysql_fetch_assoc($resultado);
$totalRows = mysql_num_rows($resultado);
$tipo_usuario = '0';
if ($totalRows > 0){	
$tipo_usuario = $row['tipo_usuario'];
} 
else 
{
	$tipo_usuario = "0";
}	
return $tipo_usuario;	
mysql_free_result($resultado);  
}
	

if (isset($_GET['tipo_periodo'])){
	$tipo_usuario = validar_usuario($_SESSION["logged_user"]);
	$id_empresa = id_empresa($_SESSION["logged_user"]);
	$tipo_periodo = $_GET['tipo_periodo'];
	$tipo_reporte = $_GET['tipo_reporte'];
	$valor_periodo = $_GET['valor_periodo'];
	$valor_periodo2 = $_GET['valor_periodo2'];
	$valor_evaluado = $_GET['valor_evaluado'];
	$almacen_id = $_GET['almacen_id'];
	$where_periodo = "";
	$fecha_filtro = "";
	date_default_timezone_set('America/Mexico_City');
	$fecha_actual = date("Y-m-d H:i:s");
	$lista_resultados = array();
	/// TIPO PERIODO PARA FILTRADO POR FECHAS
	if ($tipo_periodo == 1)
	{ // si es por horas entonces se restara la cantidad del valor mandado en valor periodo
		$fecha_modif = strtotime ( '-'.$valor_periodo.' hour' , strtotime ($fecha_actual)) ;
		$fecha_modif = date('Y-m-d H:i:s', $fecha_modif );
		$fecha_filtro = $fecha_modif;
		$where_periodo = "AND p.fecha_pedido_oficial >= '$fecha_filtro'";	
	}
	else if($tipo_periodo == 2)
	{ // dias
		$fecha_modif = strtotime ( '-'.$valor_periodo.' day' , strtotime ($fecha_actual)) ;
		$fecha_modif = date('Y-m-d H:i:s', $fecha_modif );
		$fecha_filtro = $fecha_modif;
		$where_periodo = "AND p.fecha_pedido_oficial >= '$fecha_filtro'";
		//echo $fecha_modif;
	}
	else if($tipo_periodo == 3)
	{ // meses 
		$fecha_modif = strtotime ( '-'.$valor_periodo.' month' , strtotime ($fecha_actual)) ;
		$fecha_modif = date('Y-m-d H:i:s', $fecha_modif );
		$fecha_filtro = $fecha_modif;
		$where_periodo = "AND p.fecha_pedido_oficial >= '$fecha_filtro'";
		//echo $fecha_modif;
		
	}
	else if($tipo_periodo == 4)
	{
		$fecha_ini = date('Y-m-d H:i:s',  strtotime ($valor_periodo ));
		$fecha_fin = date('Y-m-d H:i:s',  strtotime ($valor_periodo2));
		$where_periodo = "AND p.fecha_pedido_oficial BETWEEN '$fecha_ini' AND '$fecha_fin'";
		//echo "Entre ".$fecha_ini." y ".$fecha_fin;
	}
	////// TERMINA VALIDACION DE PERIODO Y OBTENEMOS LA VARIABLE DE FILTRO DE FECHAS
	
	if ($tipo_reporte == 1)
	{ // reporte de material requerido
				
		$sql = "SELECT pdet.id_articulo as id_articulo, pdet.clave_empresa as clave_empresa, pdet.articulo as articulo, sum(pdet.cantidad) as cant_total, sum(pdet.precio_total) as total  
				FROM pedidos_det pdet 
				INNER JOIN pedidos p on p.id = pdet.id_pedido
				WHERE p.id_sucursal = '$almacen_id'
				".$where_periodo."
				GROUP BY pdet.id_articulo
				ORDER BY pdet.articulo ASC";
		
		$res= mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($res);
		$totalrows = mysql_num_rows($res);
		if ($totalrows > 0){
			$total = 0;
			while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
			{
			$lista_resultados[] = array(
								"value" => $row['id_articulo'], 
								"clave" => $row['clave_empresa'], 
							    "articulo" =>  $row['articulo'], 
							    "cant_total" => $row['cant_total'], 
							    "total" => number_format($row['total'],2));	
				
			$total += $row['total'];			
			}
		}
	} 
	else if($tipo_reporte == 2)
	{ // reporte de inventario
		$sql = "SELECT e.existencia_actual as existencia_sistema, e.id_articulo as id_articulo, 
				a.nombre as articulo, 
				a.clave_empresa as clave_empresa,
				a.precio as precio,
					(SELECT iidet.cantidad_contada
						FROM inventarios_det iidet
							INNER JOIN inventarios ii ON (ii.id_inventario=iidet.id_inventario)
						WHERE iidet.id_articulo = e.id_articulo 
							and ii.almacen_id = '$almacen_id' and ii.estatus = 'C'
						ORDER BY ii.fecha_hora_cierre DESC LIMIT 1) as existencia_fisica
				FROM existencias e 
				INNER JOIN articulos a on a.id = e.id_articulo
				WHERE e.almacen_id = '$almacen_id'
				";
		
		$res= mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($res);
		$totalrows = mysql_num_rows($res);
		if ($totalrows > 0){
			//$total = 0;
			$consumo = 0;
			$existencia_fisica = 0;
			while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
			{
			
				if ($row['existencia_fisica'] != ""){
					$consumo = $row['existencia_sistema'] - $row['existencia_fisica'];
					$existencia_fisica = $row['existencia_fisica'];
				}else{
					$consumo = 0;
					$existencia_fisica = $row['existencia_sistema'];
				}
			
			$lista_resultados[] = array(
								"value" => $row['id_articulo'], 
								"clave" => $row['clave_empresa'], 
							    "articulo" =>  $row['articulo'], 
							    "existencia_sistema" => $row['existencia_sistema'], 
							    "existencia_fisica" => $existencia_fisica, 
							    "total" => $consumo);	
					
			}
		}	
	}
	else if($tipo_reporte == 3)
	{ // reporte consumo
		// ya no por que en el 2 de inventario se muestra el consumo tambien.
		
	}
	else if($tipo_reporte == 4)
	{ // reporte material con falta de orden de compra
		// este ya no se ejecutara por que el 5 punto de reorden, ayuda a lo mismo mostrando el material que esta de bajo stock.
				
	}
	else if($tipo_reporte == 5)
	{ // reporte material en orden de meyor porcentaje de diferencia entre punto maximo y existencia fisica
		$sql = "SELECT e.existencia_actual as existencia_sistema,  e.id_articulo as id_articulo,
					e.max as max, 
					e.min as min, 
					e.reorden as reorden, 
					a.nombre as articulo, 
					a.clave_empresa as clave_empresa,
					a.precio as precio,
					(SELECT iidet.cantidad_contada
						FROM inventarios_det iidet
							INNER JOIN inventarios ii ON (ii.id_inventario=iidet.id_inventario)
						WHERE iidet.id_articulo = e.id_articulo 
							and ii.almacen_id = '$almacen_id' and ii.estatus = 'C'
						ORDER BY ii.fecha_hora_cierre DESC LIMIT 1) as existencia_fisica
				FROM existencias e 
				INNER JOIN articulos a on a.id = e.id_articulo
				WHERE e.almacen_id = '$almacen_id'
				";
		
		$res= mysql_query($sql, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($res);
		$totalrows = mysql_num_rows($res);
		if ($totalrows > 0){
			//$total = 0;
			$consumo = 0;
			$estatus = 0;
			$existencia_fisica = 0;
			while($row = mysql_fetch_array($res,MYSQL_BOTH)) 
			{	
				if ($row['existencia_fisica'] != ""){
					$consumo = $row['existencia_sistema'] - $row['existencia_fisica'];
					$existencia_fisica = $row['existencia_fisica'];
				}else{
					$consumo = 0;
					$existencia_fisica = $row['existencia_sistema'];
				}
				if ($row['max'] == ""){
					$estatus = "-";
				}else if ($row['max'] == 0){
					$estatus = "-";
				}else if ($row['max'] > 0){
					$estatus = $existencia_fisica / $row['max'];
					$estatus = number_format($estatus,2);
					if ($estatus >= 1){
						$estatus = str_replace(".","",$estatus);
						$estatus = $estatus."%";
					}else if ($estatus < 1){
						$estatus = str_replace("0.","",$estatus);
						$estatus = $estatus."%";
					}
					
					
				}
				
				$lista_resultados[] = array(
								"value" => $row['id_articulo'], 
								"clave" => $row['clave_empresa'], 
							    "articulo" =>  $row['articulo'], 
							    "min" => $row['min'], 
							    "reorden" => $row['reorden'], 
							    "max" => $row['max'], 
							    "existencia_fisica" => $existencia_fisica, 
							    "total" => $estatus);	
					
			}
			
		}
		
	}
	
	
	

	require_once 'Classes/PHPExcel.php';  
	$objPHPExcel = new PHPExcel();
			
	if($tipo_reporte == 1) /// Material requerido
	{
		$objPHPExcel->
			getProperties()
				->setCreator("Ing. Edgar Herebia")
				->setLastModifiedBy("Ing. Edgar Herebia")
				->setTitle("Exportacion a excel")
				->setSubject("Reporte de Articulos")
				->setDescription("Documento generado con PHPExcel")
				->setKeywords("")
				->setCategory("Reportes");    
			if ($tipo_usuario == 4){
				
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '#Part')
			->setCellValue('B1', 'Item')
			->setCellValue('C1', 'Qty.');
			
			$i = 2;    
		
			foreach ($lista_resultados as $lista){ 
				$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $lista['clave'])
			->setCellValue('B'.$i, $lista['articulo'])
			->setCellValue('C'.$i, $lista['cant_total']);
			$i++;
			}
			}else
			{
				
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '#Part')
			->setCellValue('B1', 'Item')
			->setCellValue('C1', 'Qty.')
			->setCellValue('D1', 'Total');
			
			$i = 2;    
		
			foreach ($lista_resultados as $lista){ 
				$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $lista['clave'])
			->setCellValue('B'.$i, $lista['articulo'])
			->setCellValue('C'.$i, $lista['cant_total'])
			->setCellValue('D'.$i, $lista['total']);
			$i++;
			}
			}
			
				
			$objPHPExcel->getActiveSheet()->setTitle('Requested Materials'); 
		
		
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Requested_materials.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('php://output');
			exit;
	}
	else if($tipo_reporte == 2) /// Inventarios y consumo
	{
		$objPHPExcel->
			getProperties()
				->setCreator("Ing. Edgar Herebia")
				->setLastModifiedBy("Ing. Edgar Herebia")
				->setTitle("Exportacion a excel")
				->setSubject("Reporte de Inventarios y consumo")
				->setDescription("Documento generado con PHPExcel")
				->setKeywords("")
				->setCategory("Reportes");   
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '#Part')
			->setCellValue('B1', 'Item')
			->setCellValue('C1', 'Qty. Consignment')
			->setCellValue('D1', 'Qty. Inventory')
			->setCellValue('E1', 'Qty. Consumed');
			
			$i = 2;    
		
			foreach ($lista_resultados as $lista){ 
				$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $lista['clave'])
			->setCellValue('B'.$i, $lista['articulo'])
			->setCellValue('C'.$i, $lista['existencia_sistema'])
			->setCellValue('D'.$i, $lista['existencia_fisica'])
			->setCellValue('E'.$i, $lista['total']);
			$i++;
			}
			
				
			$objPHPExcel->getActiveSheet()->setTitle('Inventory and Consume'); 
		
		
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Inventory_consume.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('php://output');
			exit;
	
	}
	else if($tipo_reporte == 3) // 
	{
		
	}
	else if($tipo_reporte == 4) /// 
	{
		
	}
	else if($tipo_reporte == 5) /// Estatus de stock
	{
		$objPHPExcel->
			getProperties()
				->setCreator("Ing. Edgar Herebia")
				->setLastModifiedBy("Ing. Edgar Herebia")
				->setTitle("Exportacion a excel")
				->setSubject("Reporte de estatus de stock")
				->setDescription("Documento generado con PHPExcel")
				->setKeywords("")
				->setCategory("Reportes");   
		
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', '#Part')
			->setCellValue('B1', 'Item')
			->setCellValue('C1', 'Min')
			->setCellValue('D1', 'Reorder')
			->setCellValue('E1', 'Max')
			->setCellValue('F1', 'Stock')
			->setCellValue('G1', 'Status');
			
			$i = 2;    
			
			foreach ($lista_resultados as $lista){ 
				$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$i, $lista['clave'])
			->setCellValue('B'.$i, $lista['articulo'])
			->setCellValue('C'.$i, $lista['min'])
			->setCellValue('D'.$i, $lista['reorden'])
			->setCellValue('E'.$i, $lista['max'])
			->setCellValue('F'.$i, $lista['existencia_fisica'])
			->setCellValue('G'.$i, $lista['total']);
			$i++;
			}
			
				
			$objPHPExcel->getActiveSheet()->setTitle('Stock status'); 
		
		
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Stock_status.xlsx"');
			header('Cache-Control: max-age=0');
			
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('php://output');
			exit;
	}	
	
}



?>