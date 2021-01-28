<?php include("conexion.php");

	if ((isset($_FILES['file'])) and ($_FILES['file'] != ""))
{		
	
	$file = $_FILES["file"];
	$nombre = $file["name"];
	$tipo = $file["type"];
	$ruta_temporal = $file["tmp_name"];
	$size = $file["size"];
	$dimenciones = getimagesize($ruta_temporal);
	$width = $dimenciones[0];
	$height = $dimenciones[1];
	$carpeta = "../spotby_img/imagenes/"; 
	$src_mostrar = "spotby_img/imagenes/"; // es diferente la ruta por que donde se muestra es en la ruta raiz
	if ($tipo != "image/jpg" && $tipo != "image/jpeg" && $tipo != "image/gif" && $tipo != "image/png")
	{
		echo "El tipo de archivo no es permitido";
	}
	else{
		
	$fecha_int = time();
	$nombre_generado = 'img_'.$fecha_int;
	$nombre = $nombre_generado.".jpeg";
	
	
	$src = $carpeta.$nombre;
	//$imagen_optimizada = redimensionar_imagen($nombre,$ruta_temporal,700,700);
	move_uploaded_file($ruta_temporal, $src);
	echo '<script>$("#nombre_imagen_spotby").val("'.$nombre.'"); </script>';
		// alert("Se guardo imagen correctamente");
	}
}
	
   
?>