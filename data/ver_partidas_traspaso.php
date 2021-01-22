<?php include("../data/conexion.php"); 
$id_pedido = $_POST['id_pedido_traspaso'];
$consulta_lista = "SELECT * FROM pedido_traspaso_det WHERE id_pedido = $id_pedido ";
$resultado_lista = mysql_query($consulta_lista, $conex) or die(mysql_error());
$total_rows2 = mysql_num_rows($resultado_lista);
if ($total_rows2 > 0){
	$id_articulo = "";
	
	while($row2 = mysql_fetch_array($resultado_lista,MYSQL_BOTH)) // html de articulos a mostrar
    {
		$id_articulo .= '$("#td_art_'.$row2['id_articulo'].'").addClass("bg-success");';
		
	}
	
	echo '<script>
	 $(document).ready( function () {
		$(".elementos_recibir").removeClass("bg-success");
		'.$id_articulo.'
		} );
	</script>';
}
	
	