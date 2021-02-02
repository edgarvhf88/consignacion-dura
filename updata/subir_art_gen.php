<?php include("../data/conexion.php");

$id_empresa = $_POST['select_empresa'];
$almacen_id = $_POST['select_almacen'];
$lista_categorias = categorias_lista($id_empresa); 

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
	
	while(($datos= fgetcsv($fichero,1000)) != FALSE){

		$x++;
		if($x>1)
		{
			
			$data[]='("'.$datos[0].'","'.$datos[1].'","'.$datos[2].'","'.$datos[3].'","'.$datos[4].'","'.$datos[5].'","'.$datos[6].'","'.$datos[7].'","'.$datos[8].'","'.$datos[9].'","'.$datos[10].'")';
			// del  datos[7] en adelante son Existencia,Max,Min,Reorden
			
			$articulo_id = Articulo_Id($datos[0]);
			if ($articulo_id != '')
			{
				if ($datos[7] != ""){
					$existencia = $datos[7];
				}else{
					$existencia = ExistenciaMicrosip($articulo_id,$almacen_id);
				}
				
				$arr_pr = explode("_",MinMaxReorden($articulo_id,$almacen_id));
				if ($datos[8] != ""){
					$max = $datos[8];
				}else{
					$max = $arr_pr[0];
				}
				if ($datos[9] != ""){
					$min = $datos[9];
				}else{
					$min = $arr_pr[1];
				}
				if ($datos[10] != ""){
					$reorden = $datos[10];
				}else{
					$reorden = $arr_pr[2];
				}
				
				$unidad_medida = "";
				$precio_articulo = "";
				
				if ($datos[6] == ""){
					$unidad_medida = UDMArticulo($articulo_id);
				}else {
					$unidad_medida = $datos[6];
				}
				if ($datos[4] == 0){
					$precio_articulo = PrecioArticulo($articulo_id);
				}else {
					$precio_articulo = $datos[6];
				}
				if ($datos[2] == ""){
					$nombre_articulo = NombreArticulo($articulo_id);	
				}else {
					$nombre_articulo = $datos[2];
				}
				$nombre_articulo = str_replace("\"", '&#34', $nombre_articulo); 
				$nombre_articulo = str_replace("'", '&#39', $nombre_articulo); 
				
				$sql_verif = "SELECT * FROM articulos WHERE id_microsip = '$articulo_id' AND id_empresa = '$id_empresa'";
				$res_sql_verif = mysql_query($sql_verif, $conex) or die(mysql_error());
				$num_res = mysql_num_rows($res_sql_verif);
				$row_art = mysql_fetch_assoc($res_sql_verif);
				if ($num_res > 0)
				{ // si encuentra el articulo lo edita con update
					$id_art = $row_art['id'];
					
					$sql_art_update = "UPDATE articulos SET clave_microsip='$datos[0]', clave_empresa='$datos[1]', nombre='$nombre_articulo', descripcion='$datos[3]',  precio='$precio_articulo', src_img='$datos[5]', unidad_medida='$unidad_medida' WHERE id ='$id_art'";
					if (mysql_query($sql_art_update, $conex) or die(mysql_error())){}
					 
					$sql_exis_verif = "SELECT * FROM existencias WHERE id_articulo = '$id_art' AND almacen_id = '$almacen_id'";
					$res_exis_verif = mysql_query($sql_exis_verif, $conex) or die(mysql_error());
					$num_res_exis = mysql_num_rows($res_exis_verif);
					$row_art_exis = mysql_fetch_assoc($res_exis_verif);
					if ($num_res_exis > 0)
					{
						$id_existencia = $row_art_exis['id_existencia'];
						$sql_exis_update = "UPDATE existencias SET articulo_id='$articulo_id', min='$min', max='$max', reorden='$reorden', existencia_actual='$existencia' WHERE id_existencia ='$id_existencia'";	
						if (mysql_query($sql_exis_update, $conex) or die(mysql_error())){}	
					}
					else
					{
						$insert_existencia = "INSERT INTO existencias 	(id_articulo,articulo_id,min,max,reorden,existencia_actual,almacen_id) 
					VALUES ('$id_art','$articulo_id','$min','$max','$reorden','$existencia','$almacen_id')";
						if (mysql_query($insert_existencia, $conex) or die(mysql_error())){}
					}
								
				}
				else
				{  /// inserta nuevo articulo
					
					$query = "INSERT INTO articulos 	(id_empresa,clave_microsip,clave_empresa,nombre,descripcion,precio,src_img,unidad_medida,id_microsip) 
					VALUES ('$id_empresa','$datos[0]','$datos[1]','$nombre_articulo','$datos[3]','$precio_articulo','$datos[5]','$unidad_medida','$articulo_id')";
					if (mysql_query($query, $conex) or die(mysql_error())){
						$id_articulo =  mysql_insert_id();	
						
						$insert_existencia = "INSERT INTO existencias 	(id_articulo,articulo_id,min,max,reorden,existencia_actual,almacen_id) 
					VALUES ('$id_articulo','$articulo_id','$min','$max','$reorden','$existencia','$almacen_id')";
						if (mysql_query($insert_existencia, $conex) or die(mysql_error())){}
					}
					echo 'se inserto nuevo articulo <br />';
				}
				
			}
			else
			{  // si la celda esta vacia mandar a echo
				echo $datos[0].' => No se encontro en base de datos Microsip <br />';
			}
			
	
				/* foreach ($lista_categorias as $id_categoria => $categoria) {
					$id_check = 'chkcat_'.$id_categoria;
					if (isset($_POST['chkcat_'.$id_categoria]) ) {
						$query_categorias = "INSERT INTO registros_categorias (id_categoria,id_articulo) 
								VALUES ('$id_categoria','$id_articulo')";
					$registrar_categorias = mysql_query($query_categorias, $conex) or die(mysql_error());
					} else {
						// si no esta marcado ell chek es como si no existiera 
					}	
				} */
		}

	}
//print_r($data);
	//$inserta="insert into articulos values ". implode(",", $data);
	//if (mysql_query($inserta,$conex)){
		//echo "LOS DATOS SE REGISTRARON CORRECTAMENTE";
	//}
	fclose($fichero);
echo "Se registro correctamente la lista de articulos  <a href='../updata'> regresar </a>";

?>