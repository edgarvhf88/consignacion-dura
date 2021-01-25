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
	$carpeta = "../tras_docs/imagenes/"; 
	$src_mostrar = "tras_docs/imagenes/"; // es diferente la ruta por que donde se muestra es en la ruta raiz
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
	move_uploaded_file($ruta_temporal,$src);
	echo '<img src="'.$src_mostrar.$nombre.'" style="max-width:500px;">
	<script> guardar_registro_imagen_tras("'.$nombre.'");</script>';
		// alert("Se guardo imagen correctamente");
	}
}
	/* if (isset($_POST['nombre_file']))
	{
		$nombre_imagen = $_POST['nombre_file']; //imagen qu subo
		$ruta_temp = $_POST['ruta_temp']; 
		$id_inventario = $_POST['id_inventario'];
		
		//subir_imagen($nombre_imagen,$ruta_temp,$id_inventario);
	}
		
function subir_imagen($nombre_imagen,$ruta_temp,$id_inventario)
{
	 	$imagen_optimizada = redimensionar_imagen($nombre_imagen,$ruta_temp,700,700);
    	imagejpeg($imagen_optimizada, "../inv_docs/imagenes/inv".$id_inventario.".jpg"); //imagen que bajo
		echo '<script> alert("Se guardo imagen correctamente"); </script>';
		
} */
   
?>