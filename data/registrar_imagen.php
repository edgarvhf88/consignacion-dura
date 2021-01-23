<?php include("conexion.php"); 
				  
		if (isset($_POST['id_inventario'])){
			$id_inventario = $_POST['id_inventario'];
			$nombre_imagen = $_POST['nombre_imagen'];
			$id_usuario_subio = $_SESSION["logged_user"];
			registrar_imagen($id_inventario,$nombre_imagen,$id_usuario_subio);
		}
		if (isset($_POST['id_ped_tras'])){
			$id_ped_tras = $_POST['id_ped_tras'];
			$nombre_imagen = $_POST['nombre_imagen'];
			$id_usuario_subio = $_SESSION["logged_user"];
			registrar_imagen2($id_ped_tras,$nombre_imagen,$id_usuario_subio);
		}
			
	 
	 function registrar_imagen($id_inventario,$nombre_imagen,$id_usuario_subio)
	 { 
	 global $conex;
	 
	date_default_timezone_set('America/Mexico_City');
	$fecha_subida = date("Y-m-d H:i:s");
	$tipo_docto = "INV";
		$insert_relacion = "INSERT INTO relacion_imagenes (tipo_docto,id_docto,ruta,fecha_subida,id_usuario_subio)
		VALUES ('$tipo_docto','$id_inventario','$nombre_imagen','$fecha_subida','$id_usuario_subio')";
		if (mysql_query($insert_relacion, $conex) or die(mysql_error()))
		{
			//$id_pedido =  mysql_insert_id();
			//echo 1;
			$cosulta_imagenes = "SELECT * FROM relacion_imagenes WHERE id_docto= '$id_inventario' AND tipo_docto='INV'";
		$res_img = mysql_query($cosulta_imagenes, $conex) or die(mysql_error());
		$total_imgs = mysql_num_rows($res_img);
		$html_imagenes = '';
		if ($total_imgs > 0){ // con resultados
		$html_imagenes = '<h5>Imagenes de Inventarios Aprobados</h5>';
			while($row_img = mysql_fetch_array($res_img,MYSQL_BOTH)) 
			{
				$fecha_subida = $row_img['fecha_subida'];
				$src_mostrar = "inv_docs/imagenes/";
				$ruta = $src_mostrar.$row_img['ruta'];
				$html_imagenes .= '<div class=\"col-lg-3 col-md-3 col-sm-3 \"> <div class=\"topics-list\"> <p><img src=\"'.$ruta.'\" width=\"158\" height=\"128\" id=\"imagen_'.$row_img['id_imagen'].'\" class=\"img-thumbnail\"></p> <p><b>'.$fecha_subida.'</b></p>    </div> </div>';	
			}
		}
			echo '<script> alert("Se guardo imagen correctamente"); 
			$("#modal_subir_imagen").modal("hide");
				$("#img_registradas").html("'.$html_imagenes.'");</script>';
				
		}
	 }
	 function registrar_imagen2($id_ped_tras,$nombre_imagen,$id_usuario_subio)
	 { 
	 global $conex;
	 
	date_default_timezone_set('America/Mexico_City');
	$fecha_subida = date("Y-m-d H:i:s");
	$tipo_docto = "TRAS";
		$insert_relacion = "INSERT INTO relacion_imagenes (tipo_docto,id_docto,ruta,fecha_subida,id_usuario_subio)
		VALUES ('$tipo_docto','$id_ped_tras','$nombre_imagen','$fecha_subida','$id_usuario_subio')";
		if (mysql_query($insert_relacion, $conex) or die(mysql_error()))
		{
			//$id_pedido =  mysql_insert_id();
			//echo 1;
			$cosulta_imagenes = "SELECT * FROM relacion_imagenes WHERE id_docto= '$id_ped_tras' AND tipo_docto='TRAS'";
		$res_img = mysql_query($cosulta_imagenes, $conex) or die(mysql_error());
		$total_imgs = mysql_num_rows($res_img);
		$html_imagenes = '';
		$html_imagenes_max = '';
		if ($total_imgs > 0){ // con resultados
		$html_imagenes = '<h5>Imagen Traspaso</h5>';
			while($row_img = mysql_fetch_array($res_img,MYSQL_BOTH)) 
			{
				$fecha_subida = $row_img['fecha_subida'];
				$src_mostrar = "tras_docs/imagenes/";
				$ruta = $src_mostrar.$row_img['ruta'];
				$html_imagenes .= '<div > <div class=\"topics-list\"> <p><img src=\"'.$ruta.'\" width=\"108\" height=\"88\" id=\"imagen_'.$row_img['id_imagen'].'\" class=\"img-thumbnail imagen_tras\"></p>     </div> </div>';	
				$html_imagenes_max .= '<div > <div class=\"topics-list\"> <p><img src=\"'.$ruta.'\" width=\"600\" height=\"650\" id=\"imagenmax_'.$row_img['id_imagen'].'\" ></p>     </div> </div>';
			}
		}
			echo '<script>
			$(document).ready( function () {
			$("#modal_subir_imagen").modal("hide");
				$("#div_imagenes_tras").html("'.$html_imagenes.'");
				 $(".imagen_tras").on("click", function(){
									$("#modal_imagen_tras").modal("show");
									jQuery("#modal_imagen_tras .modal-body").html("'.$html_imagenes_max.'");
								});
			});					
			</script>';
				
		}
	 }
	 
	 
	 
	 
	 
?>