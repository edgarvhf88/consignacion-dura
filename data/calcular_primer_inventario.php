<?php include("../data/conexion.php"); 

//id_articulo
//cantidad
//almacen
 
//primero hago la consulta
 $consulta =  "SELECT DVD.ARTICULO_ID AS ARTICULO_ID,
                DVD.UNIDADES AS UNIDADES,
                DV.ORDEN_COMPRA AS ORDEN_COMPRA,
                DV.FOLIO AS FOLIO
                FROM DOCTOS_VE DV
                INNER JOIN DOCTOS_VE_DET DVD ON DVD.DOCTO_VE_ID = DV.DOCTO_VE_ID
                WHERE DV.CLIENTE_ID = '4111' AND TIPO_DOCTO='R' AND DV.FECHA BETWEEN '25.01.2021' AND '29.01.2021'"



		$query_aplicar = $con_micro_nef->prepare($consulta);
		$query_aplicar->execute();
		$results = $query_aplicar->fetchAll(PDO::FETCH_ASSOC);
		//recorro el array y concateno la variables
		
		foreach($results as $row)
		{
			$articulo_id = $row['ARTICULO_ID'];
			$planta = $row['ORDEN_COMPRA'];
			$cantidad = $row['UNIDADES'];
			
			$existe = existe_articulo($articulo_id);
			//traerme la cantidad 
			if ($existe == 0)//no existe hago un insert
			{
				if(strpos ($planta, "3")!== false)
				{
					$consulta = "INSET INTO  inventario_inicial (id, planta_3) VALUES ($articulo_id, $cantidad)";
					$resultado = mysql_query($consulta, $conex) or die(mysql_error());
				}
				else if(strpos ($planta, "4")!== false)
				{
					$consulta = "INSET INTO  inventario_inicial (id, planta_4) VALUES ($articulo_id, $cantidad)";
					$resultado = mysql_query($consulta, $conex) or die(mysql_error());
				}
			}
			else if ($existe == 1)//existe hago un update
			{
				
				if(strpos ($planta, "3")!== false)
				{
					
				}
				else if(strpos ($planta, "4")!== false)
				{
					
				}
			}
		}

//mientras la recorro consulto si ese articulo existe en esa tabla 


function existe_articulo($articulo_id)
{
	global $database_conexion, $conex;
			
		$consulta = "SELECT * FROM inventario_inicial WHERE articulo_id = '$articulo_id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		if($total_rows>0)
		{return 1;}
		else{return 0;}
}

function existencia_articulo($articulo_id, $planta)
{
	global $database_conexion, $conex;
			
		$consulta = "SELECT ".$planta." FROM inventario_inicial WHERE articulo_id = '$articulo_id'";
		$resultado = mysql_query($consulta, $conex) or die(mysql_error());
		//$row = mysql_fetch_assoc($resultado);
		$total_rows = mysql_num_rows($resultado);
		if($total_rows>0)
		{
			if ($planta == 'planta_3')
			{
				$cantidad = $row['planta_3'];
			}
			if ($planta == 'planta_4')
			{
				$cantidad = $row['planta_4'];
			}
			
			return $cantidad;
			
		}
		
		
		
}




	
?>


