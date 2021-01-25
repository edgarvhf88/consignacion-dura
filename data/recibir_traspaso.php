<?php include("../data/conexion.php"); 
$id_pedido = $_POST['id_pedido_traspaso'];
$consulta_lista = "SELECT * FROM pedido_traspaso_det WHERE id_pedido = $id_pedido ";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$total_rows2 = mysql_num_rows($resultado_lista);
$elementos_partidas = '';

if ($total_rows2 > 0){
	
	$id_articulo = "";
	
	while($row2 = mysql_fetch_array($resultado_lista,MYSQL_BOTH)) // html de articulos a mostrar
    {
		$id_articulo .= '$("#td_art_'.$row2['id_articulo'].'").addClass("bg-success");';
		
	}
	
	$elementos_partidas = '
		$(".elementos_recibir").removeClass("bg-success");
		'.$id_articulo;
}
$cosulta_imagenes = "SELECT * FROM relacion_imagenes WHERE id_docto= '$id_pedido' AND tipo_docto='TRAS'";
		$res_img = mysql_query($cosulta_imagenes, $conex) or die(mysql_error());
		$total_imgs = mysql_num_rows($res_img);
		$html_imagenes = '';
		$html_imagenes_max = '';
		if ($total_imgs > 0){ // con resultados
		$html_imagenes = '<h5>Imagenes de traspaso</h5>';
			while($row_img = mysql_fetch_array($res_img,MYSQL_BOTH)) 
			{
				$fecha_subida = ""; //$row_img['fecha_subida'];
				$src_mostrar = "tras_docs/imagenes/";
				$ruta = $src_mostrar.$row_img['ruta'];
				$html_imagenes .= '<div > <div class=\"topics-list\"> <p><img src=\"'.$ruta.'\" width=\"108\" height=\"88\" id=\"imagen_'.$row_img['id_imagen'].'\" class=\"img-thumbnail imagen_tras\"></p>     </div> </div>';
				$html_imagenes_max .= '<div > <div class=\"topics-list\"> <p><img src=\"'.$ruta.'\" width=\"600\" height=\"650\" id=\"imagenmax_'.$row_img['id_imagen'].'\" ></p>     </div> </div>';	
			}
		}
	
	echo '<script>
		$(document).ready( function () {
		 '.$elementos_partidas.'
		 $("#div_imagenes_tras").html("'.$html_imagenes.'");
		 $(".imagen_tras").on("click", function(){
									$("#modal_imagen_tras").modal("show");
									jQuery("#modal_imagen_tras .modal-body").html("'.$html_imagenes_max.'");
								});
		});
		 </script>';