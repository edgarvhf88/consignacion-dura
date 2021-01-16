<?php

include("../data/conexion.php");

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
		if($x>1){
			
		$data[]='('.$datos[0].',"'.$datos[1].'","'.$datos[2].'","'.$datos[3].'","'.$datos[4].'","'.$datos[5].'","'.$datos[6].'",'.$datos[7].','.$datos[8].',"'.$datos[9].'")';
		
		$query = "INSERT INTO articulos (id_empresa,clave_microsip,clave_empresa,nombre,descripcion,precio,src_img,id_microsip,id_marca,unidad_medida) 
VALUES ('$datos[0]','$datos[1]','$datos[2]','$datos[3]','$datos[4]','$datos[5]','$datos[6]','$datos[7]','$datos[8]','$datos[9]')";
$registrar = mysql_query($query, $conex) or die(mysql_error());
	
		}

	}
//print_r($data);
	//$inserta="insert into articulos values ". implode(",", $data);
	//if (mysql_query($inserta,$conex)){
		//echo "LOS DATOS SE REGISTRARON CORRECTAMENTE";
	//}
	fclose($fichero);


?>