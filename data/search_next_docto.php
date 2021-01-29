<?php include("../data/conexion.php"); 

	//varables folio y base de datos
	$tipo= $_POST['tipo'];
	
	if($tipo == "1") 
	{
		$base_datos= $_POST['base_datos'];
	$folio= $_POST['folio'];
		busca_remisiones($folio, $base_datos);
	}
	if($tipo == "2") //recepcion
	{
		$base_datos= $_POST['base_datos'];
		$folio= $_POST['folio'];
		busca_recepciones($folio, $base_datos);
	}
	 
	 if($tipo == "3") //recepcion
	{
		
		$folio= $_POST['folio'];
		busca_factura_allpart($folio);
	}
	  //busca las remisiones
	  function busca_remisiones($folio, $base_datos)
	  {
		$folio_bus=Format9digit($folio);
		
		 //selecciono la base de datos
		global $con_micro_nef;
		 //hago la consulta
		$aplicar = "SELECT
        DV2.FOLIO AS FOLIO,
		DV.ESTATUS AS ESTATUS
        FROM DOCTOS_VE DV
        LEFT JOIN DOCTOS_VE_LIGAS DVL1 ON DVL1.DOCTO_VE_FTE_ID = DV.DOCTO_VE_ID
        LEFT JOIN DOCTOS_VE DV2 ON DV2.DOCTO_VE_ID = DVL1.DOCTO_VE_DEST_ID 
        WHERE DV.FOLIO = '$folio_bus' AND DV.TIPO_DOCTO='P'";
		$query_aplicar = $con_micro_nef->prepare($aplicar);
		$query_aplicar->execute();
		$results = $query_aplicar->fetchAll(PDO::FETCH_ASSOC);
		//recorro el array y concateno la variables
		$rems="";
		foreach($results as $row)
		{
			$estatus = $row['ESTATUS'];
			if ($rems ==""){
			$rems .= str_replace(",","", number_format($row['FOLIO'],0));}
			else {
			$rems .= ",".str_replace(",","", number_format($row['FOLIO'],0));}
		}
		
		//guardo el valor en la base de datos
		if($rems != "" and $rems != "0"){
		global $conex;	
		
		$remision = "UPDATE pedido_nef SET 
		remisiones='$rems'
		WHERE folio_pedido_microsip='$folio'";
		
		if ($estatus =='S')
		{
			$remision = "UPDATE pedido_nef SET 
			remisiones='$rems',
			estatus='3'
			WHERE folio_pedido_microsip='$folio'";
		}
		
		mysql_query($remision, $conex) or die(mysql_error());
		}
		echo '<script> lista_pedidos_nef(); </script>';
		}
   
  function busca_recepciones($folio, $base_datos)
	  {
		$folio_bus=Format9digit($folio);
		
		 //selecciono la base de datos
		global $con_micro;
		 //hago la consulta
		$aplicar = "SELECT
        DV2.FOLIO AS FOLIO,
		DV.ESTATUS AS ESTATUS
        
		FROM DOCTOS_CM DV
        LEFT JOIN DOCTOS_CM_LIGAS DVL1 ON DVL1.DOCTO_CM_FTE_ID = DV.DOCTO_CM_ID
        LEFT JOIN DOCTOS_CM DV2 ON DV2.DOCTO_CM_ID = DVL1.DOCTO_CM_DEST_ID 
        WHERE DV.FOLIO = '$folio_bus' AND DV.TIPO_DOCTO='O'";
		
		$query_aplicar = $con_micro->prepare($aplicar);
		$query_aplicar->execute();
		$results = $query_aplicar->fetchAll(PDO::FETCH_ASSOC);
		//recorro el array y concateno la variables
		$recs="";
		foreach($results as $row)
		{
			$estatus = $row['ESTATUS'];
			if ($recs ==""){
			$recs .= $row['FOLIO'];}
			else {
			$recs .= ",".$row['FOLIO'];}
		}
		
		//guardo el valor en la base de datos
		if($recs != "" and $recs != "0"){
		global $conex;	
		
		$remision = "UPDATE pedido_nef SET 
		recepciones='$recs'
		WHERE orden_compra='$folio'";
		
		if ($estatus =='R')
		{
			$remision = "UPDATE pedido_nef SET 
			recepciones='$recs',
			estatus='4'
			WHERE orden_compra='$folio'";
		}
		
		mysql_query($remision, $conex) or die(mysql_error());
		}
		echo '<script> lista_pedidos_nef(); </script>';
		
	}
   
   
    function busca_factura_allpart($folio)
	  {
		$folio_bus=Format9digit($folio);
		
		 //selecciono la base de datos
		global $con_micro;
		 //hago la consulta
		$aplicar = "SELECT
        DV2.FOLIO AS FOLIO,
		DV.ESTATUS AS ESTATUS
        FROM DOCTOS_VE DV
        LEFT JOIN DOCTOS_VE_LIGAS DVL1 ON DVL1.DOCTO_VE_FTE_ID = DV.DOCTO_VE_ID
        LEFT JOIN DOCTOS_VE DV2 ON DV2.DOCTO_VE_ID = DVL1.DOCTO_VE_DEST_ID 
        WHERE DV.FOLIO = '$folio_bus' AND DV.TIPO_DOCTO='R'";
		$query_aplicar = $con_micro->prepare($aplicar);
		$query_aplicar->execute();
		$results = $query_aplicar->fetchAll(PDO::FETCH_ASSOC);
		//recorro el array y concateno la variables
		$factura="";
		foreach($results as $row)
		{
			
			//$factura = str_replace(",","", number_format($row['FOLIO'],0));
			$factura = $row['FOLIO'];
		}
		
		//guardo el valor en la base de datos
		if($factura != "" and $factura != "0"){
		global $conex;	
		
		$remision = "UPDATE ordenes SET 
		folio_factura='$factura',
		estatus = '3'
		WHERE folio_remision='$folio'";
	
		mysql_query($remision, $conex) or die(mysql_error());
		echo '<script> lista_ordenes_cxc(); </script>';
		}
		
		
		
		}
   
?>
