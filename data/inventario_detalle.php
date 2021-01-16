<?php include("conexion.php"); 
				  
	  if (isset($_POST['id_inventario'])){
      $id_inventario = $_POST['id_inventario'];
  
     	lista_det($id_inventario);
	  }	
	 
     function lista_det($id_inventario){ 
global  $conex;
		//id_empresa, clave_microsip, clave_empresa, nombre, descripcion, precio, src_img, id_microsip, id_marca, unidad_medida
		$consulta = "SELECT 
		art.clave_microsip as clave_microsip,
		art.clave_empresa as clave_empresa,
		art.unidad_medida as unidad_medida,
		art.nombre as nombre,
		indet.id_inventario_det as id_inventario_det,
		indet.cantidad_contada as cantidad_contada,
		indet.existencia_actual as existencia_actual,
		indet.diferencia as diferencia,
		inv.folio as folio,
		inv.fecha as fecha,
		inv.cancelado as cancelado,
		inv.fecha_hora_creacion as fecha_hora_creacion
					FROM inventarios_det indet 
					INNER JOIN articulos art ON art.id = indet.id_articulo
					INNER JOIN inventarios inv ON inv.id_inventario = indet.id_inventario
					WHERE indet.id_inventario = '$id_inventario' 
					";		
        //echo $consulta;					
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
////  NOTA:: RECALCULARA EXISTENCIAS CON REGISTRO ESPEJO DE INVENTARIOS / -*/-* /-* /-*/ -/*

$resultado = mysql_query($consulta, $conex) or die(mysql_error());
//$row = mysql_fetch_assoc($resultado);
$total_rows = mysql_num_rows($resultado);

if ($total_rows > 0){ // con resultados
$estatus;
$cancelado;
$mostrar_cancel;
$folio;
$ocultar;

echo '	
<table id="tabla_inv_det" class="table table-striped table-bordered table-hover table-responsive display" >
		<thead>
			<tr class="bg-info">
				<th>#DURA</th>
				<th>#ALLPART</th>
				<th>Articulo</th>
				<th>Unid. Med.</th>
				<th>existencia</th>
				<th>Cant. Conteo</th>
				<th>Cant. Consumo</th>
			</tr>
		</thead><tbody >';
						
	while($row2 = mysql_fetch_array($resultado,MYSQL_BOTH)) // html de articulos a mostrar
	{
	echo '<tr>
	<td class="reg_indet" id="tdcd_'.$row2['id_inventario_det'].'">'.$row2['clave_empresa'].' </td>
	<td class="reg_indet" id="tdca_'.$row2['id_inventario_det'].'">'.$row2['clave_microsip'].'</td>
	<td class="reg_indet" id="tdfo_'.$row2['id_inventario_det'].'">'.$row2['nombre'].'</td>
	<td class="reg_indet" id="tdet_'.$row2['id_inventario_det'].'">'.$row2['unidad_medida'].'</td>
	<td class="reg_indet" id="tdal_'.$row2['id_inventario_det'].'">'.$row2['existencia_actual'].'</td>
	<td class="reg_indet" id="tdcl_'.$row2['id_inventario_det'].'">'.$row2['cantidad_contada'].' </td>
	<td class="reg_indet" id="tdus_'.$row2['id_inventario_det'].'">'.$row2['diferencia'].'</td>
	</tr>';                    							
		    
	$folio = $row2['folio'];		
	$cancelado = $row2['cancelado'];		
	$lista_invdet[] = array("value" => $row2['id_inventario_det'], 
							   "folio" => $row2['folio'], 
							   "fecha" => $row2['fecha'], 
							   "clave_empresa" => $row2['clave_empresa'],
							   "clave_microsip" => $row2['clave_microsip'],
							   "nombre" => $row2['nombre'],
							   "unidad_medida" => $row2['unidad_medida'],
							   "existencia_actual" => $row2['existencia_actual'],
							   "cantidad_contada" => $row2['cantidad_contada'],
							   "diferencia" => $row2['diferencia']);	                    							
	}				
	echo ' </tbody></table>';
	
  if ($cancelado == "S"){
		$ocultar = '$("#btn_cancelar_inv2").hide(); $("#btn_crear_remision_inv").hide(); $("#btnsubirimagen").hide(); $("#btnenviarinv").hide();';
		$mostrar_cancel = ' Cancelado';
		$clase_estatus = 'bg-danger';
  }
  else if($cancelado == "N")
  {	
	  $ocultar = '$("#btn_cancelar_inv2").show(); $("#btn_crear_remision_inv").show(); $("#btnsubirimagen").show(); $("#btnenviarinv").show();';
	  $mostrar_cancel = '';
	  $clase_estatus = '';
  }
 echo '<script> 
	$(document).ready(function(){
				$("#span_folioinv").html("'.$folio.$mostrar_cancel.'");
				$("#img_registradas").html("'.$html_imagenes.'");
				$("#txt_id_inventario_activo").val("'.$id_inventario.'");
				'.$ocultar.'
				
				$("#span_folioinv").prop("class","'.$clase_estatus.'");
				
				$("#tabla_inv_det").DataTable({
						"order": [[ 1, "asc" ]]
					});
				
                $(".reg_indet").on("click", function(){
                            var tr_id = $(this).attr("id")
							var arr_id = tr_id.split("_");
							var id_inventario = arr_id[1];
							
							//enviar_correo_inv(id_inventario);
							   
                });
				
			
		});
</script>';  

 
} 
else /// sin resultados
{	
$consul = "SELECT 
		inv.folio as folio,
		inv.fecha as fecha,
		inv.cancelado as cancelado,
		inv.fecha_hora_creacion as fecha_hora_creacion
					FROM inventarios inv 
					WHERE inv.id_inventario = '$id_inventario' 
					";
		$res = mysql_query($consul, $conex) or die(mysql_error());
		$row = mysql_fetch_assoc($res);
		$total_inv = mysql_num_rows($res);
$cancelado;
$ocultar;
$mostrar_cancel;
$clase_estatus;
		
if ($total_inv > 0){
	$cancelado = $row['cancelado'];
	$folio = $row['folio'];
	if ($cancelado == "S"){
		$ocultar = '$("#btn_cancelar_inv2").hide(); $("#btn_crear_remision_inv").hide(); $("#btnsubirimagen").hide(); $("#btnenviarinv").hide();';
		$mostrar_cancel = ' Cancelado';
		$clase_estatus = 'bg-danger';
	}
	else if($cancelado == "N")
	{	
		$ocultar = '$("#btn_cancelar_inv2").show(); $("#btn_crear_remision_inv").show(); $("#btnsubirimagen").show(); $("#btnenviarinv").show();';
		$mostrar_cancel = '';
		$clase_estatus = '';
	}
	
}			
						
  
 echo '<script> 
	$(document).ready(function(){
				$("#span_folioinv").html("'.$folio.$mostrar_cancel.'");
				$("#img_registradas").html("'.$html_imagenes.'");
				
				'.$ocultar.'
				
				$("#span_folioinv").prop("class","'.$clase_estatus.'");
				
				
		});
</script>';
					
	echo ' <div class="row"> 
                    <div class="col-md-12">
                        <div class="topics-list">
                            <h4><a href="#">No existen registros de inventarios.</a></h4>
                          
                        </div>
                    </div>
				</div>';		
		


}

}




?>
