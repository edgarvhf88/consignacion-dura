<?php include("conexion.php"); 
     // error_reporting(0); 
   
				  
	  if (isset($_POST['id_inventario'])){
      $id_inventario = $_POST['id_inventario'];
  
     	lista_det_to_excel($id_inventario);
	  }	
	 
     function lista_det_to_excel($id_inventario){ 
global  $conex;
		//id_empresa, clave_microsip, clave_empresa, nombre, descripcion, precio, src_img, id_microsip, id_marca, unidad_medida
		$consulta = "SELECT 
		art.clave_microsip as clave_microsip,
		art.clave_empresa as clave_empresa,
		art.unidad_medida as unidad_medida,
		art.nombre as nombre,
		indet.id_inventario_det as id_inventario_det,
		indet.cantidad_contada as cantidad_contada,
		indet.existencia_actual as existencia_actual,
		indet.diferencia as diferencia,
		inv.folio as folio,
		inv.fecha as fecha,
		inv.fecha_hora_creacion as fecha_hora_creacion,
		alm.almacen as almacen
					FROM inventarios_det indet 
					INNER JOIN articulos art ON art.id_microsip = indet.articulo_id
					INNER JOIN inventarios inv ON inv.id_inventario = indet.id_inventario
					INNER JOIN almacenes alm ON alm.almacen_id = inv.almacen_id
					WHERE indet.id_inventario = '$id_inventario' 
					";			

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);
$almacen;
$folio;
$lista_invdet = array();
if ($total_rows > 0){ // con resultados
	while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
	{
		$almacen = $row2['almacen'];
		$folio = $row2['folio'];
		$fecha = $row2['fecha'];
		$lista_invdet[] = array("value" => $row2['id_inventario_det'], 
							   "folio" => $row2['folio'], 
							   "fecha" => $row2['fecha'], 
							   "clave_empresa" => $row2['clave_empresa'],
							   "clave_microsip" => $row2['clave_microsip'],
							   "nombre" => $row2['nombre'],
							   "unidad_medida" => $row2['unidad_medida'],
							   "existencia_actual" => $row2['existencia_actual'],
							   "cantidad_contada" => $row2['cantidad_contada'],
							   "diferencia" => $row2['diferencia']);                    							
	}	
	export_exel($lista_invdet,$almacen,$folio,$fecha);
}  
}
  function export_exel($lista_invdet,$almacen,$folio,$fecha){

	require_once 'Classes/PHPExcel.php';  
   $objPHPExcel = new PHPExcel();
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(32);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
   $objPHPExcel->
    getProperties()
        ->setCreator("Ing. Edgar Herebia")
        ->setLastModifiedBy("Ing. Edgar Herebia")
        ->setTitle("Exportacion a excel")
        ->setSubject("Lista de Articulos de inventario")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("Articulos DURA")
        ->setCategory("DURA");    
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('B1', 'FOLIO: '.$folio)
	->setCellValue('D1', 'ALMACEN: '.$almacen)
	->setCellValue('F1', 'FECHA: '.$fecha);
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A3', '#DURA')
	->setCellValue('B3', '#ALLPART')
	->setCellValue('C3', 'ARTICULO')
	->setCellValue('D3', 'UNID. MED.')
	->setCellValue('E3', 'EXISTENCIA ACTUAL')
	->setCellValue('F3', 'CANTIDAD CONTEO')
	->setCellValue('G3', 'CONSUMO');
	
   $i = 4;    

	foreach ($lista_invdet as $lista_id){
		$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A'.$i, $lista_id['clave_empresa'])
	->setCellValue('B'.$i, $lista_id['clave_microsip'])
	->setCellValue('C'.$i, $lista_id['nombre'])
	->setCellValue('D'.$i, $lista_id['unidad_medida'])
	->setCellValue('E'.$i, $lista_id['existencia_actual'])
	->setCellValue('F'.$i, $lista_id['cantidad_contada'])
	->setCellValue('G'.$i, $lista_id['diferencia']);
	$i++;
	 }
	 
		 
	$objPHPExcel->getActiveSheet()->setTitle('Lista Inventario'); 
	 

	
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Inventario_'.$almacen.'_Folio_'.$folio.'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('../inv_docs/Inventario_'.$almacen.'_Folio_'.$folio.'.xlsx');
exit;	 
	 


	 }		 
	    


?>