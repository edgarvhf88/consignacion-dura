<?php include("../data/conexion.php");

$id_empresa = $_POST['select_empresa'];
$almacen_id = $_POST['select_almacen'];
$lista_categorias = categorias_lista($id_empresa); 
if ($almacen_id=='11143')
{
	$planta ='planta_4';
}
else if ($almacen_id=='11142')
{
	$planta ='planta_3';
}
	$ruta = 'Upload/';	

	foreach ($_FILES as $key) {

		$nombre=$key["name"];
		$ruta_temporal=$key["tmp_name"];		
		
		$fecha=getdate();
		$nombre_v=$fecha["mday"]."-".$fecha["mon"]."-".$fecha["year"]."_".$fecha["hours"]."-".$fecha["minutes"]."-".$fecha["seconds"].".csv";		

		$destino=$ruta.$nombre_v;
		$explo=explode(".",$nombre);


		if($explo[1] != "csv"){
			$alert=1;
		}else{

			if(move_uploaded_file($ruta_temporal, $destino)){
				$alert=2;
			}

		}

	}

	$x=0;
	$data=array();
	$fichero=fopen($destino, "r");
	$contador =0;
	//limpio la tabla
	$insert_existencia = "DELETE FROM inventario_inicial";
	if (mysql_query($insert_existencia, $conex) or die(mysql_error())){}
	
	
	while(($datos= fgetcsv($fichero,1000)) != FALSE){
	
		$x++;
		if($x>1)
		{
			
			$data[]='("'.$datos[0].'","'.$datos[1].'","'.$datos[2].'","'.$datos[3].'","'.$datos[4].'","'.$datos[5].'","'.$datos[6].'","'.$datos[7].'","'.$datos[8].'","'.$datos[9].'","'.$datos[10].'")';
			
			
			
			$articulo_id = Articulo_Id($datos[0]);
			
			if ($articulo_id != '')
			{
				
				//uso los datos
				$existencia = $datos[7];
			if (is_numeric ($existencia))
			{
				//
			}
			else {$existencia=0;}
				
				$existe = 'S';
				//inserto
					{
						$insert_existencia = "INSERT INTO inventario_inicial (id, planta_3, existe) 
						VALUES ('$articulo_id', '$existencia', '$existe' )";
						if (mysql_query($insert_existencia, $conex) or die(mysql_error())){}
					}
								
			}
			else 
			{
				$contador ++;
				$articulo_id = $datos[0];
				//uso los datos
				$existencia = $datos[7];
				if (is_numeric ($existencia))
			{
				//
			}
			else {$existencia=0;}
				$existe = 'N';
				//inserto
					{
						$insert_existencia = "INSERT INTO inventario_inicial 	(id, ".$planta.", existe) 
						VALUES ('$articulo_id', '$existencia', '$existe')";
						if (mysql_query($insert_existencia, $conex) or die(mysql_error())){}
					}
								
			}
			
				
		}

	}

	fclose($fichero);
echo "No se encontraron ".$contador.", claves en microsip.";




?>